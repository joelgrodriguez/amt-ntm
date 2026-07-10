# Plan 014: Sync the active filter state into whichever sidebar copy is visible (mobile drawer fix)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving on. If any
> STOP condition occurs, stop and report. Your reviewer maintains `plans/README.md`.
>
> **Environment check (FIRST)**: `git rev-parse --short HEAD` + `ls plans/`.
> If `plans/014-filter-drawer-checked-sync.md` is missing or HEAD is a master
> release commit, run `git switch -C advisor/014-filter-drawer-sync dev`. If
> your worktree vanishes and cwd falls back to the shared main checkout, do NOT
> work there — `git worktree add "$TMPDIR/wt-014" -B advisor/014-filter-drawer-sync dev`.
>
> **Drift check**: `git diff --stat 8cc4afc..HEAD -- app/resources/js/_app.js app/templates/parts/filter-sidebar.php` → expect empty.

## Status

- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug / a11y
- **Planned at**: commit `8cc4afc`, 2026-07-10

## Why this matters

`filter-sidebar.php` renders its body twice — a mobile `<details class="filter-drawer">` and a desktop `<aside>` rail — with identical radio `name`s bound to the same form. HTML radio-group semantics allow only one checked radio per name+form, and when the server marks BOTH copies `checked`, the browser keeps only the LAST in DOM order (the rail). Verified live: on `/?s=gutter` with an active filter, 2 of 12 same-name radios carry the `checked` attribute, and the drawer copy loses the live checked state. Net effect on a phone: a user applies a filter, the page reloads, and the drawer shows their filter **unchecked** — "it didn't take." The fix is a small JS module that, on load and on breakpoint change, re-asserts the server-intended state (`defaultChecked`, i.e. the `checked` attribute) onto the copy that is currently visible.

## Current state

- `app/templates/parts/filter-sidebar.php:194-210` — drawer (`<details class="filter-drawer">`) rendered first, rail (`<aside class="hidden lg:block ...">`) second. Inputs (line ~131-141) carry `name`, `value`, optional `form="<id>"`, and `<?php checked($active); ?>`.
- Visibility split: `.filter-drawer` is `lg:hidden` (`filters.css:283-285`); the rail aside is `hidden lg:block`. Tailwind's `lg` breakpoint = `64rem` (1024px).
- `app/resources/js/_app.js` — `initApp()` calls ~15 initializers on `DOMContentLoaded` (module pattern: each module exports an `init*` function that early-returns when its DOM is absent; several also export a cleanup). Read the file and match its import/registration style exactly.
- No existing JS touches filters (verified by grep).
- Form-mode sidebar pages (where this matters): search results, Learning Center landing, service-hub, service-search. Link-mode sidebars (archives/singles) have no inputs — the module must no-op there.

## Commands you will need

| Purpose | Command | Expected |
|---|---|---|
| JS syntax | `node --check app/resources/js/modules/FilterDrawerSync.js` | exit 0 |
| JS syntax | `node --check app/resources/js/_app.js` | exit 0 |

(No npm install/build in worktrees — the reviewer builds post-merge.)

## Scope

**In scope**:
- `app/resources/js/modules/FilterDrawerSync.js` (create)
- `app/resources/js/_app.js` (import + one init call)

**Out of scope**:
- `filter-sidebar.php` and all PHP — no markup/name changes (renaming inputs would break the form contract).
- Any other JS module.

## Git workflow

- Branch: `advisor/014-filter-drawer-sync`
- One commit, subject like: `Sync active filter state to the visible sidebar copy`
- Do NOT push.

## Steps

### Step 1: Create the module

`app/resources/js/modules/FilterDrawerSync.js`, shape (match the repo's existing module idioms — header comment, early return, cleanup):

```js
/**
 * The filter sidebar renders twice (mobile drawer + desktop rail) with
 * identical radio names in one form. Browsers keep only the LAST
 * server-checked radio per group — the rail — so the mobile drawer shows
 * active filters as unchecked. Re-assert the server state (defaultChecked)
 * onto whichever copy is visible, on load and when the breakpoint flips.
 */

const LG = '(min-width: 64rem)';

let mql = null;
let handler = null;

function sync() {
  const visibleScope = mql.matches
    ? 'aside .filter-sidebar'
    : '.filter-drawer-body';

  document.querySelectorAll(`${visibleScope} input[type="radio"], ${visibleScope} input[type="checkbox"]`)
    .forEach((input) => {
      if (input.defaultChecked && !input.checked) {
        input.checked = true;
      }
    });
}

export function initFilterDrawerSync() {
  cleanup();

  if (!document.querySelector('.filter-drawer-body input')) {
    return; // link-mode sidebars or no sidebar at all
  }

  mql = window.matchMedia(LG);
  handler = () => sync();
  mql.addEventListener('change', handler);
  sync();

  return cleanup;
}

function cleanup() {
  if (mql && handler) {
    mql.removeEventListener('change', handler);
  }
  mql = null;
  handler = null;
}
```

Notes baked into the design (keep them): setting `checked = true` on the visible copy's radio automatically unchecks the hidden twin (same group) — that is the point; `defaultChecked` reads the server-rendered `checked` attribute, which survives on both copies; checkboxes are included for future-proofing (re-asserting `defaultChecked` on them is a no-op today since no checkbox consumers exist).

**Verify**: `node --check app/resources/js/modules/FilterDrawerSync.js` → exit 0.

### Step 2: Register in _app.js

Read `app/resources/js/_app.js`; add the import alongside the existing module imports and call `initFilterDrawerSync()` inside `initApp()` with the other initializers (order does not matter for this module; place it near SearchModal/menu inits).

**Verify**: `node --check app/resources/js/_app.js` → exit 0; `grep -c 'FilterDrawerSync' app/resources/js/_app.js` → `2` (import + call).

## Test plan

Reviewer post-merge: build, then on the served checkout load `/?s=gutter&lc_category=testimonials` (an option that exists in the radio list) at mobile width — the drawer's Testimonials radio must render checked; at desktop width the rail's must. Automated gates here are the two `node --check`s + greps.

## Done criteria

- [ ] Both `node --check`s exit 0
- [ ] `grep -c 'FilterDrawerSync' app/resources/js/_app.js` → 2
- [ ] `git status --porcelain` shows only the two in-scope files

## STOP conditions

- `_app.js` structure differs materially from "imports + initApp() calling init functions" (adapt registration only if the file's own idiom is obvious; otherwise stop).
- You find an existing module already syncing filter inputs (there isn't one per audit — if one appeared, stop).

## Maintenance notes

- If the `lg` breakpoint token ever changes in the design system, `LG` here must follow (it mirrors `filters.css`'s `lg:` split).
- If a consumer ever adds checkbox groups with counts of checked-per-copy diverging by user interaction pre-submit, this module's load-time sync is still correct (it only re-asserts server state, never user changes).
