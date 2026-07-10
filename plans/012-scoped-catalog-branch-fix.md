# Plan 012: Make CPT archives render the scoped-catalog sidebar (not the blog-category dashboard)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving on. If any
> STOP condition occurs, stop and report — do not improvise. Your reviewer
> maintains `plans/README.md`.
>
> **Environment check (FIRST)**: `git rev-parse --short HEAD` + `ls plans/`.
> If `plans/012-scoped-catalog-branch-fix.md` is missing or HEAD is a master
> release commit, run `git switch -C advisor/012-scoped-catalog-fix dev`. If
> your worktree directory vanishes and cwd falls back to the shared main
> checkout, do NOT work there — `git worktree add "$TMPDIR/wt-012" -B advisor/012-scoped-catalog-fix dev`.
>
> **Drift check**: `git diff --stat 8cc4afc..HEAD -- app/archive.php` → expect empty.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug
- **Planned at**: commit `8cc4afc`, 2026-07-10

## Why this matters

`/learning-center/manual/` and `/learning-center/profile/` — the catalog entry pages every "View All Manuals/Profiles" link targets — render the generic Learning-Center dashboard sidebar instead of the scoped catalog sidebar. The dashboard sidebar's "Filter by Category" options are **blog** categories (Testimonials, Industry News…) that no manual/profile carries, so nearly every filter click from the catalog front door lands on an empty-results page (verified live 2026-07-10). The scoped sidebar — with the real manual/profile categories and machine tags — already works perfectly; it just never fires on the bare archive URL because the branch condition only accepts a `?post_type=` GET parameter and ignores the `is_post_type_archive()` case computed four lines earlier.

## Current state

`app/archive.php:28-48`:

```php
$requested_types = get_request_values(get_post_type_filter_keys(), 'post_type');
$archive_type = '';
if (is_post_type_archive()) {
    $archive_query_type = get_query_var('post_type') ?: 'post';
    $archive_type = is_array($archive_query_type)
        ? sanitize_key((string) reset($archive_query_type))
        : sanitize_key((string) $archive_query_type);
}
$active_type = count($requested_types) === 1 ? $requested_types[0] : $archive_type;
$type_options = get_post_type_filter_options();
$active_type_label = $active_type !== '' && isset($type_options[$active_type])
    ? $type_options[$active_type]
    : '';
$is_scoped_catalog = count($requested_types) === 1 && in_array($active_type, ['profile', 'manual'], true);
```

`$active_type` already resolves correctly on bare CPT archives (falls back to `$archive_type`, verified: category links on `/learning-center/manual/` carry `?post_type=manual` today, proving `$active_type === 'manual'` there). Only the `$is_scoped_catalog` line re-imposes the GET-param requirement.

Verified behavior matrix (live, 2026-07-10): bare `/learning-center/manual/` → dashboard sidebar with blog categories (WRONG); `/learning-center/manual/?post_type=manual` → scoped sidebar with `manuals/roof-wall-panel-machine-manuals`, `manuals/gutter-machine-manuals` etc. (RIGHT); the scoped pipeline itself (category+type, tag+type, pagination) verified working.

## Commands you will need

| Purpose | Command | Expected |
|---|---|---|
| PHP lint | `php -l app/archive.php` | `No syntax errors detected` |

(No node_modules in worktrees; never run npm. Live curl verification happens post-merge on the served checkout — the reviewer runs it.)

## Scope

**In scope**: `app/archive.php` (the `$is_scoped_catalog` assignment only).
**Out of scope**: everything else — the query wiring, the sidebar templates, `$active_type` computation (already correct), `inc/search.php`, `inc/learning-center/`.

## Git workflow

- Branch: `advisor/012-scoped-catalog-fix`
- One commit, subject like: `Render scoped catalog sidebar on bare profile/manual archives`
- Do NOT push.

## Steps

### Step 1: Fix the branch condition

Change line 41 from:

```php
$is_scoped_catalog = count($requested_types) === 1 && in_array($active_type, ['profile', 'manual'], true);
```

to:

```php
// Scoped whenever the active type is a catalog CPT — whether that came from a
// ?post_type= filter link OR from landing on the CPT archive itself. The old
// GET-only check made the bare /learning-center/manual|profile/ entry pages
// render the blog-category dashboard sidebar, whose every option was a dead
// combo for catalog content.
$is_scoped_catalog = in_array($active_type, ['profile', 'manual'], true);
```

**Verify**: `php -l app/archive.php` → clean. `grep -n 'is_scoped_catalog =' app/archive.php` → one line, no `count(` in it. `grep -c 'requested_types' app/archive.php` → unchanged count minus the one removed usage is fine; just confirm `$requested_types` is still used at line 28 and nothing else broke (`php -l` covers syntax).

## Test plan

No PHP harness. Reviewer post-merge: curl `/learning-center/manual/` — sidebar category hrefs must be `learning-center/category/manuals/...` (real manual categories), not `testimonials`/`industry-news`; same for `/learning-center/profile/` (expect `profiles/...` categories); tag/category archives with `?post_type=` keep working; a plain blog category page (no post_type) still renders the dashboard sidebar.

## Done criteria

- [ ] `php -l app/archive.php` exits 0
- [ ] `$is_scoped_catalog` no longer requires a GET param
- [ ] `git status --porcelain` shows only `app/archive.php`

## STOP conditions

- The excerpt doesn't match live code (drift).
- You find `$active_type` is used before line 36 in a way the new condition would change — report instead of adapting.

## Maintenance notes

- If more catalog CPTs get scoped treatment (video? literature), extend the `in_array` list — the sidebar sections (archive.php:110-128) currently hardcode profile/manual back-links, so those need the same touch then.
