# Plan 006: Harden the category landing pages to absorb the 301 ranking equity

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**:
> `git diff --stat bc5ecf2..HEAD -- app/templates/parts/hero-category.php app/templates/pages/roof-wall/hero.php app/templates/pages/gutter/hero.php`
> If any in-scope file changed since this plan was written, compare the
> "Current state" excerpts against the live code before proceeding; on a
> mismatch, treat it as a STOP condition.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none (pairs with 007, which monitors the outcome)
- **Category**: SEO (on-page)
- **Planned at**: commit `bc5ecf2`, 2026-07-21

## Why this matters

The Woo product-category URLs `/product-category/roof-wall-panel-machines/`
and `/product-category/gutter-machines/` already 301-redirect to the marketing
landing pages `/roof-wall-panel-machines/` and `/seamless-gutter-machines/`
(redirects live on prod, dated ~2026-07-02, captured in `db/redirects.json`).
Those product-category URLs are the historical ranking winners — GSC (last 90
days) shows them at **position 1–4** for the money terms: "metal roof panel
machine" (1.1), "metal panel machine" (1.1), "standing seam machine" (2.9),
"roof panel machine" (4.6). Google is mid-transition: a 301 transfers ranking
equity to the target over several weeks, but only lands well if the target is
**at least as relevant** as the source.

Right now the landing pages undercut themselves: their only `<h1>` is a
`sr-only` render of the page's `post_title`, which is **brand-first** —
"NewTech Machinery Roof & Wall Panel Machines" (also "NewTech" as one word)
and "New Tech Machinery Seamless Gutter Machines". The head term is buried
behind the brand, and the `<h1>` is the single strongest on-page relevance
signal. This plan leads the `<h1>` with the head term so the landing pages
deserve the rankings now flowing to them. (Title tags were already fixed in
db 054; this is the H1 counterpart.)

## Current state

- `app/templates/parts/hero-category.php` — shared category hero. The H1 is
  rendered here from `get_the_title()` (lines ~77–81):

  ```php
  $page_title = function_exists('get_the_title') ? get_the_title() : '';
  if ($page_title !== '') :
  ?>
      <h1 class="sr-only"><?php echo esc_html($page_title); ?></h1>
  <?php endif; ?>
  ```

  The visible marketing headline is a separate `<h2>` fed by
  `$content['title']` (line ~95). The kicker (`$content['kicker']`, line ~49/89)
  already carries the head term (e.g. "ROOF & WALL PANEL MACHINES") but a
  kicker `<p>` is a weak signal next to the `<h1>`.

- `app/templates/pages/roof-wall/hero.php` — data wrapper; calls
  `hero-category` with content. Relevant keys:
  ```php
  'kicker'   => __('ROOF & WALL PANEL MACHINES', 'standard'),
  'title'    => __('Fabricate Panels On-Site. Cut Lead Times by 75%.', 'standard'),
  'subtitle' => __('Portable rollformers that produce standing seam, flush wall, and board & batten panels right on the jobsite.', 'standard'),
  ```
  The visible H2 ("Fabricate Panels On-Site…") is benefit-led and does NOT
  contain the head term. Keep it — it converts. The fix is the H1, not the H2.

- `app/templates/pages/gutter/hero.php` — same shape:
  ```php
  'kicker'   => __('SEAMLESS GUTTER MACHINES', 'standard'),
  'title'    => __('Seamless Gutters. Fabricated On-Site. Ready for Install.', 'standard'),
  ```

- The landing pages self-canonical (verified) and no theme code links to the
  old `/product-category/` URLs (verified). So the only on-page weakness worth
  fixing here is the brand-first H1.

- Convention: content passed into `hero-category` is a flat array of
  translatable strings (see both hero.php wrappers). Add the new key the same
  way, wrapped in `__()`.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP syntax | `php -l <file>` | `No syntax errors` |
| Lint | `npm run lint:php` | exit 0 |
| Build | `npm run build` | exit 0 |
| Template tests | `npm run test:page-templates` | exit 0 |

**Rendered-page caveat**: DevKinsta serves the *main checkout*, not git
worktrees. If you are in a worktree, verify statically (grep) and defer the
`curl` checks to post-merge QA in the served checkout. If your branch is
checked out in `/Users/jrodriguez/Development/kinsta/public/newtech`, the
`curl https://newtech.local/...` checks work directly.

## Scope

**In scope**:
- `app/templates/parts/hero-category.php` (add optional `h1` content slot)
- `app/templates/pages/roof-wall/hero.php` (pass explicit `h1`)
- `app/templates/pages/gutter/hero.php` (pass explicit `h1`)

**Out of scope** (do NOT touch):
- The visible hero `<h2>` (`content['title']`) — it is conversion copy; leave it.
- The page `post_title` / page slugs / breadcrumbs — changing post_title would
  ripple into nav and breadcrumbs; the H1 override avoids that.
- Yoast title tags — already handled by db 054.
- `db/redirects.json` and the redirects themselves — that is plan 007.
- Any other page that uses `hero-category` (machines index, etc.) — they must
  keep falling back to `get_the_title()` unchanged.

## Git workflow

- Branch off `dev`: `joelgrodriguez/006-category-h1`
- One commit; short imperative subject, e.g. "Lead category hero H1 with the
  head term".
- Do NOT push or merge unless the operator instructed it.

## Steps

### Step 1: Add an optional `h1` slot to `hero-category.php`

Replace the H1 block so an explicit `h1` wins, falling back to the existing
`get_the_title()` behavior (so every other caller is unchanged):

```php
$h1 = $content['h1'] ?? (function_exists('get_the_title') ? get_the_title() : '');
if ($h1 !== '') :
?>
    <h1 class="sr-only"><?php echo esc_html($h1); ?></h1>
<?php endif; ?>
```

**Verify**: `php -l app/templates/parts/hero-category.php` → no errors.
`grep -n "content\['h1'\]" app/templates/parts/hero-category.php` → one match.

### Step 2: Pass a head-term-led H1 from each landing page

- `app/templates/pages/roof-wall/hero.php`: add to the `content` array
  ```php
  'h1' => __('Roof & Wall Panel Machines', 'standard'),
  ```
- `app/templates/pages/gutter/hero.php`: add
  ```php
  'h1' => __('Seamless Gutter Machines', 'standard'),
  ```

Head term leads; no brand in the H1 (the brand is in the title tag and logo).

**Verify**: `php -l` on both files → no errors.
`grep -rn "'h1'" app/templates/pages/roof-wall/hero.php app/templates/pages/gutter/hero.php`
→ one match each.

### Step 3: Confirm the head term also appears in visible body copy

The H1 is `sr-only`; Google also weighs visible text. Open the brand-statement
and value-prop partials for each page and confirm the exact head term
("roof panel machine" / "roof & wall panel machines" and "seamless gutter
machine(s)") appears in at least one visible heading or sentence:

```bash
grep -rin 'panel machine\|roof & wall panel' app/templates/pages/roof-wall/
grep -rin 'gutter machine' app/templates/pages/gutter/
```

If the term is absent from all visible copy on a page, STOP and report (adding
body copy is a content decision for the operator, not an improvised edit). If
it is present, no change needed — note where it appears in your report.

### Step 4: Build, lint, tests

**Verify**: `npm run lint:php`, `npm run build`, `npm run test:page-templates`
→ all exit 0.

### Step 5 (served checkout only): Rendered H1 check

```bash
curl -sk https://newtech.local/roof-wall-panel-machines/ | perl -0ne 'print "$1\n" while /<h1[^>]*>(.*?)<\/h1>/gs'
# expect: Roof & Wall Panel Machines   (NOT "NewTech Machinery ...")
curl -sk https://newtech.local/seamless-gutter-machines/ | perl -0ne 'print "$1\n" while /<h1[^>]*>(.*?)<\/h1>/gs'
# expect: Seamless Gutter Machines
```

Also confirm exactly one `<h1>` per page (the sr-only one) — no second H1
appeared. From a worktree, defer this step to post-merge QA.

## Test plan

Markup change with no logic branch worth a unit test; `test:page-templates`
must stay green, and the grep/curl gates above are the regression checks. The
fallback-to-`get_the_title()` path is what protects every other `hero-category`
caller — Step 1's grep confirms it is preserved.

## Done criteria

- [ ] `hero-category.php` uses `$content['h1']` with a `get_the_title()` fallback
- [ ] Both landing hero wrappers pass a head-term-led `h1` (no brand)
- [ ] Head term confirmed present in visible copy on both pages (Step 3)
- [ ] `npm run lint:php`, `build`, `test:page-templates` exit 0
- [ ] Rendered H1 leads with the head term on both pages (or deferred to QA)
- [ ] No files outside the in-scope list modified (`git status`)
- [ ] `plans/README.md` status row updated

## STOP conditions

Stop and report back (do not improvise) if:

- The `hero-category.php` H1 block no longer matches the "Current state" excerpt.
- Adding the `h1` slot would change the H1 on any OTHER page that uses
  `hero-category` (it must not — the fallback preserves them; verify by
  checking which pages set `h1` vs rely on the fallback).
- The head term is absent from all visible copy on a landing page (Step 3) —
  that needs a content decision, not an improvised paragraph.
- More than one `<h1>` renders on a landing page after the change.

## Maintenance notes

- Any new page using `hero-category` gets the `get_the_title()` H1 by default;
  pass `h1` only when the post_title is brand-first or otherwise weak.
- This plan is time-sensitive: it strengthens the 301 targets *while* Google
  is transferring equity (see plan 007). Landing it sooner captures more of
  the transfer; landing it after the transition settles still helps but
  recovers less.
- Reviewer should scrutinize: the H1 is still present (not accidentally
  removed), still `sr-only`, still single per page, and the fallback path is
  intact for non-landing callers.
