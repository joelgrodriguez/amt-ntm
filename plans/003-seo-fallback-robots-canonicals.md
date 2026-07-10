# Plan 003: Make the fallback SEO layer emit robots noindex + correct paged canonicals

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- app/inc/seo.php`
> On any change to the file since this SHA, compare the excerpts below against
> the live code; on a mismatch, STOP.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: seo
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

`app/inc/seo.php` is the safety net that runs ONLY when no SEO plugin is active (Yoast is expected on prod, but the fallback exists precisely for the misconfiguration case). Two gaps in the net: (1) it never emits a robots directive, so internal search results (`/?s=…`) are fully indexable — classic index bloat; (2) `canonical_url()` returns the page-1 URL for every paginated archive, telling crawlers to drop `/page/2/`+ entirely. Both fixes are cheap and only fire when no SEO plugin is active, so they cannot conflict with Yoast.

## Current state

- `app/inc/seo.php` — namespace `Standard\Seo`, `declare(strict_types=1)`. Registration at line 26: `add_action('wp_head', __NAMESPACE__ . '\\render_head_tags', 5);`. Guard pattern used throughout:

```php
function seo_plugin_active(): bool
{
    return defined('WPSEO_VERSION')      // Yoast SEO
        || defined('RANK_MATH_VERSION')  // Rank Math
        || defined('SEOPRESS_VERSION');  // SEOPress
}
```

```php
function render_head_tags(): void
{
    if (seo_plugin_active()) {
        return;
    }
    ...
```

- `canonical_url()` at lines 109-134 — the archive branches return unpaginated links:

```php
function canonical_url(): string
{
    if (is_singular()) {
        return (string) wp_get_canonical_url();
    }

    if (is_front_page()) {
        return home_url('/');
    }

    if (is_category() || is_tag() || is_tax()) {
        $link = get_term_link(get_queried_object());
        if (is_string($link)) {
            return $link;
        }
    }

    if (is_post_type_archive()) {
        $link = get_post_type_archive_link((string) get_query_var('post_type'));
        if (is_string($link)) {
            return $link;
        }
    }

    return '';
}
```

- There is no `wp_robots` filter and no `noindex` anywhere in `app/` (verified by grep at plan time).
- Permalinks are pretty (`/learning-center/%postname%/`), so paged archive URLs take the `/page/N/` form.
- Convention: WordPress-core-first — prefer the `wp_robots` filter (core since 5.7) over echoing a raw `<meta name="robots">`.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint | `php -l app/inc/seo.php` | `No syntax errors detected` |

(Worktree has no `node_modules`; do not run npm commands.)

## Scope

**In scope**:
- `app/inc/seo.php`

**Out of scope** (do NOT touch):
- `app/search.php`, `app/searchform.php` — templates are fine; robots is a head concern.
- `app/inc/breadcrumbs.php`, `app/inc/machine-schema.php`.
- Any Yoast-active behavior — every new branch must early-return when `seo_plugin_active()`.

## Git workflow

- Branch: `advisor/003-seo-fallback-robots-canonicals`
- One commit, subject like: `Noindex search and self-canonicalize paged archives in SEO fallback`
- Do NOT push.

## Steps

### Step 1: Register a `wp_robots` filter for internal search

Below the existing `add_action('wp_head', ...)` registration (line 26), add:

```php
add_filter('wp_robots', __NAMESPACE__ . '\\robots_directives');
```

And add the function (place it after `seo_plugin_active()`, matching the file's top-down ordering):

```php
/**
 * Fallback robots: keep internal search results out of the index.
 * No-op whenever a dedicated SEO plugin owns the head.
 *
 * @param array<string, bool|string> $robots
 * @return array<string, bool|string>
 */
function robots_directives(array $robots): array
{
    if (seo_plugin_active()) {
        return $robots;
    }

    if (is_search()) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
    }

    return $robots;
}
```

**Verify**: `php -l app/inc/seo.php` → no syntax errors; `grep -n "wp_robots" app/inc/seo.php` → one `add_filter` line + the function.

### Step 2: Self-canonicalize paginated archives

In `canonical_url()`, after each archive `$link` is resolved (the `is_category()||is_tag()||is_tax()` branch and the `is_post_type_archive()` branch), append the paged segment before returning. Implement once with a small helper to avoid duplicating logic:

```php
/**
 * Append /page/N/ to an archive base URL when the query is paginated,
 * so page 2+ self-canonicalizes instead of pointing at page 1.
 */
function paged_url(string $base): string
{
    $paged = (int) get_query_var('paged');
    if ($paged > 1) {
        return trailingslashit($base) . 'page/' . $paged . '/';
    }
    return $base;
}
```

Change both archive branches to `return paged_url($link);`. Leave the `is_singular()` and `is_front_page()` branches untouched (`wp_get_canonical_url()` already handles paged singular content).

**Verify**: `php -l app/inc/seo.php` → clean. `grep -n 'paged_url' app/inc/seo.php` → three matches (definition + two call sites).

## Test plan

No PHP test harness in this repo. Reviewer's post-merge smoke (Yoast is active locally, so the fallback is dormant — the reviewer verifies via a one-off `wp eval` that calls the functions with the main query manipulated, or simply by code review + lint; note this in your report). The machine-checkable gates are the greps and lint above.

## Done criteria

- [ ] `php -l app/inc/seo.php` exits 0
- [ ] `wp_robots` filter registered; sets `noindex,follow` only for `is_search()` and only when no SEO plugin is active
- [ ] Both archive canonical branches route through `paged_url()`
- [ ] `is_singular()` / `is_front_page()` branches unchanged
- [ ] `git status --porcelain` shows only `app/inc/seo.php` modified

## STOP conditions

- `canonical_url()` body differs from the excerpt (drift).
- You are tempted to also noindex paginated archives — do NOT; the decision is self-canonical + indexable (Yoast's default posture). If you believe otherwise, stop and report.

## Maintenance notes

- If a `noindex` need appears for other views (attachment pages, empty terms), extend `robots_directives()` — one function owns fallback robots.
- If permalinks ever change away from pretty URLs, `paged_url()` needs the query-arg form (`?paged=N`); watch for that in review.
