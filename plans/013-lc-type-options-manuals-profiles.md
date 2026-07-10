# Plan 013: Add Manuals + Profiles to the Learning-Center type filter options

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving on. If any
> STOP condition occurs, stop and report. Your reviewer maintains `plans/README.md`.
>
> **Environment check (FIRST)**: `git rev-parse --short HEAD` + `ls plans/`.
> If `plans/013-lc-type-options-manuals-profiles.md` is missing or HEAD is a
> master release commit, run `git switch -C advisor/013-lc-type-options dev`.
> If your worktree vanishes and cwd falls back to the shared main checkout, do
> NOT work there — `git worktree add "$TMPDIR/wt-013" -B advisor/013-lc-type-options dev`.
>
> **Drift check**: `git diff --stat 8cc4afc..HEAD -- app/inc/learning-center/filters.php` → expect empty.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug
- **Planned at**: commit `8cc4afc`, 2026-07-10

## Why this matters

Search results include manuals and profiles (they're in the searchable-types list, and `?lc_type=manual` correctly narrows — verified live), but the sidebar's "Resource Type" radio group can't select them: `Standard\LearningCenter\get_type_filter_options()` hardcodes only post/video/resource/download/literature. Three user-visible symptoms from this one gap: (1) users can't filter search to the two most valuable owner-library types; (2) with `lc_type=manual` active via URL, the sidebar checks "All resources" instead (active value has no matching option — verified live); (3) no removal chip renders for those type filters (chip label lookup fails). The sibling function `Standard\Search\get_post_type_filter_options()` (`inc/search.php:112-125`) already labels both (`'profile' => 'Profiles'`, `'manual' => 'Manuals'`) — this plan brings the LC list into line.

## Current state

`app/inc/learning-center/filters.php:61-73`:

```php
function get_type_filter_options(bool $include_all = true, string $all_label = ''): array {
    $options = $include_all
        ? ['' => $all_label !== '' ? $all_label : \__('All resources', 'standard')]
        : [];

    return $options + [
        'post'       => \__('Articles', 'standard'),
        'video'      => \__('Videos', 'standard'),
        'resource'   => \__('Resources', 'standard'),
        'download'   => \__('Downloads', 'standard'),
        'literature' => \__('Literature', 'standard'),
    ];
}
```

Consumers that pick the change up automatically (do NOT edit them): search sidebar radios and chips (via `get_filter_groups`, same file), home.php LC landing, `archive.php:153` dashboard "Resource Type" links (each new option gets a link to the CPT archive via `get_post_type_archive_link` — both archives exist and resolve), `learning-center/results.php` label lookups. The query reader (`Standard\Search\get_requested_post_types`) already honors both values — verified live.

## Commands you will need

| Purpose | Command | Expected |
|---|---|---|
| PHP lint | `php -l app/inc/learning-center/filters.php` | `No syntax errors detected` |

(No npm in worktrees. Live verification post-merge by reviewer.)

## Scope

**In scope**: `app/inc/learning-center/filters.php` — the `get_type_filter_options()` return array only.
**Out of scope**: `inc/search.php` (already correct), `get_filter_groups`, `get_category_filter_options`, any consumer template.

## Git workflow

- Branch: `advisor/013-lc-type-options`
- One commit, subject like: `Add Manuals and Profiles to Learning Center type filters`
- Do NOT push.

## Steps

### Step 1: Extend the options map

Insert `manual` and `profile` after `video`, matching the sibling list's labels:

```php
    return $options + [
        'post'       => \__('Articles', 'standard'),
        'video'      => \__('Videos', 'standard'),
        'manual'     => \__('Manuals', 'standard'),
        'profile'    => \__('Profiles', 'standard'),
        'resource'   => \__('Resources', 'standard'),
        'download'   => \__('Downloads', 'standard'),
        'literature' => \__('Literature', 'standard'),
    ];
```

**Verify**: `php -l app/inc/learning-center/filters.php` → clean. `grep -n "'manual'" app/inc/learning-center/filters.php` → one match inside `get_type_filter_options`. `grep -n "'profile'" ...` → one match, same function.

## Test plan

Reviewer post-merge on the served checkout: `/?s=gutter` sidebar shows Manuals + Profiles radios; `/?s=gutter&lc_type=manual` checks the Manuals radio (not "All resources") and renders a removable "Manuals" chip; the LC dashboard Resource Type group links to `/learning-center/manual/` and `/learning-center/profile/`.

## Done criteria

- [ ] `php -l` exits 0
- [ ] Options map contains manual + profile with the sibling labels
- [ ] `git status --porcelain` shows only the one file

## STOP conditions

- The function body differs from the excerpt (drift).
- You find a doc comment or config explicitly stating manuals/profiles were excluded from LC type filters on purpose — stop and report the evidence instead of overriding a decision.

## Maintenance notes

- The two type-label lists (`LearningCenter\get_type_filter_options` and `Search\get_post_type_filter_options`) are now behaviorally aligned but still duplicated; consolidating them is a reasonable future cleanup, deliberately out of scope here.
