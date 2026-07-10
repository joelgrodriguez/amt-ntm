# Plan 015: Render filter counts where they're accurate; stop shipping misleading ones

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving on. If any
> STOP condition occurs, stop and report. Your reviewer maintains `plans/README.md`.
>
> **Environment check (FIRST)**: `git rev-parse --short HEAD` + `ls plans/`.
> If `plans/015-render-filter-counts.md` is missing or HEAD is a master release
> commit, run `git switch -C advisor/015-filter-counts dev`. If your worktree
> vanishes and cwd falls back to the shared main checkout, do NOT work there —
> `git worktree add "$TMPDIR/wt-015" -B advisor/015-filter-counts dev`.
>
> **Drift check**: `git diff --stat 8cc4afc..HEAD -- app/templates/parts/filter-sidebar.php app/inc/filters.php` → expect empty.

## Status

- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none (complements 012/013)
- **Category**: bug / dx
- **Planned at**: commit `8cc4afc`, 2026-07-10

## Why this matters

Every filter-option record carries a `count`, `.filter-option-count` CSS exists, and service-hub even runs cached count queries per machine — but `filter-sidebar.php` never renders a count element, so all of it is dead weight. Meanwhile the counts that ARE cheap to show fall into two classes: **accurate** (service-hub's computed per-machine/type counts) and **misleading** (`$term->count` on link groups is the GLOBAL term count across all post types — on a manual-scoped sidebar it would claim e.g. "SSQ3 (12)" when only 1 manual matches). This plan renders counts in the sidebar and makes the link-group builder stop supplying the misleading global numbers, so what appears is only what's true.

## Current state

- `app/templates/parts/filter-sidebar.php:120-143` — the option markup renders only `filter-option-label`; no count element. Both branches (link `<a>` and `<label>`+input) end with:

```php
<span class="filter-option-label"><?php echo esc_html($label); ?></span>
```

Option records already carry `count` (`?int`) — `$option['count']` is read nowhere in the template today. The loop already extracts `$value/$label/$active/$url`; add `$count = isset($option['count']) ? (int) $option['count'] : null;` alongside.

- `app/inc/filters.php:131-172` `build_term_link_group()` — populates `'count' => (int) $term->count` (line ~158): the global term count, wrong for scoped contexts. `build_choice_group()` (lines 60-90) passes through a caller-supplied `$counts` map — service-hub supplies real computed counts there (`inc/service-hub.php:474-489`); the Learning-Center groups pass `[]` (no counts). Leave `build_choice_group` and `build_term_choice_group` as they are.
- `app/resources/css/components/filters.css:170-178` — `.filter-option-count` styles exist (verified).

## Commands you will need

| Purpose | Command | Expected |
|---|---|---|
| PHP lint | `php -l <file>` per touched file | `No syntax errors detected` |

(No npm in worktrees; reviewer builds and does the visual pass post-merge.)

## Scope

**In scope**:
- `app/templates/parts/filter-sidebar.php` (render the count span in both option branches)
- `app/inc/filters.php` (`build_term_link_group` only — null the count)

**Out of scope**:
- `build_choice_group` / `build_term_choice_group` (callers control accuracy there).
- `inc/service-hub.php` (its counts are accurate and will now display — that's the point).
- `filters.css` (styles exist).
- Computing scoped per-combo counts anywhere — explicitly deferred.

## Git workflow

- Branch: `advisor/015-filter-counts`
- One commit, subject like: `Render filter counts where accurate; drop global term counts from link groups`
- Do NOT push.

## Steps

### Step 1: Render the count in filter-sidebar.php

In BOTH option branches (the link `<a>` and the `<label>`), after the `filter-option-label` span, add:

```php
<?php if ($count !== null) : ?>
    <span class="filter-option-count"><?php echo esc_html((string) $count); ?></span>
<?php endif; ?>
```

with `$count` extracted at the top of the option loop next to `$value`/`$label`:

```php
$count = isset($option['count']) && $option['count'] !== null ? (int) $option['count'] : null;
```

**Verify**: `php -l app/templates/parts/filter-sidebar.php` → clean. `grep -c 'filter-option-count' app/templates/parts/filter-sidebar.php` → `2`.

### Step 2: Null the count in build_term_link_group

In `app/inc/filters.php` `build_term_link_group()`, change `'count' => (int) $term->count,` to:

```php
// Term ->count is the GLOBAL count across all post types — misleading in the
// scoped contexts this builder serves (e.g. a manual-only catalog). Suppress;
// callers with accurate numbers use build_choice_group's $counts instead.
'count'  => null,
```

**Verify**: `php -l app/inc/filters.php` → clean. `grep -n 'term->count' app/inc/filters.php` → remaining matches only inside `build_term_choice_group` (which is out of scope), none in `build_term_link_group`.

## Test plan

Reviewer post-merge: service-hub page (`/service-hub/` or its search) shows counts next to machine options (e.g. "SSQ3 MultiPro  14"); archive/single link sidebars show NO counts; search sidebar unchanged (no counts — LC groups pass none). Lint gates above are the machine-checkable part.

## Done criteria

- [ ] Both files pass `php -l`
- [ ] Count span rendered in both branches, only when `count !== null`
- [ ] `build_term_link_group` supplies `null` counts
- [ ] `git status --porcelain` shows only the two in-scope files

## STOP conditions

- The option-loop structure in filter-sidebar.php differs from the excerpt (drift).
- You find `.filter-option-count` styles absent from filters.css (they were verified present — absence means drift).

## Maintenance notes

- Future work (recorded, not in scope): scoped per-combo counts for the catalog sidebars (needs cached intersection queries — the service-hub pattern at `inc/service-hub.php:263-302` is the precedent); zero-count suppression on the LC dashboard's type×category links falls out naturally once such counts exist.
