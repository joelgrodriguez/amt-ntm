# Plan 003: Demote mega-menu and mobile-menu panel titles from H2 to non-headings

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**:
> `git diff --stat c5d139b..HEAD -- app/templates/parts/mega-menu.php app/templates/parts/mobile-menu-panel.php`
> If either file changed since this plan was written, compare the "Current
> state" excerpts against the live code before proceeding; on a mismatch,
> treat it as a STOP condition.

## Status

- **Priority**: P2
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: tech-debt (SEO / document outline)
- **Planned at**: commit `c5d139b`, 2026-07-21

## Why this matters

The desktop mega-menu and the mobile menu panels render their panel titles as
`<h2>` elements. Because both menus are server-rendered on every page, every
page's document outline starts with nav noise — on the homepage: "New to
Rollforming?", "How To Buy", "Get Support" (desktop) plus "New to
Rollforming?", "Choose Your Machine", "How To Buy", "Get Support" (mobile) —
before any real content H2. Crawlers and LLM extractors use the heading
outline to understand page structure; seven duplicate nav headings ahead of
the content muddy it on every page of the site. Nav panel titles are labels,
not section headings.

## Current state

- `app/templates/parts/mega-menu.php:218` — desktop panel intro title:

  ```php
  <h2 class="px-5 mb-3 font-sans font-medium text-heading-sm text-blue-900"><?php echo esc_html($intro['title']); ?></h2>
  ```

  No `id` attribute; nothing references it via ARIA. Rendered once per mega
  panel (3 panels on current nav data).

- `app/templates/parts/mobile-menu-panel.php:33-38` — the panel `<section>`
  labels itself via the heading's id:

  ```php
  <section class="mobile-menu__panel" data-panel="<?php echo esc_attr($slug); ?>" aria-hidden="true" aria-labelledby="mobile-menu-title-<?php echo esc_attr($slug); ?>">
  ```
  ```php
  <h2 id="mobile-menu-title-<?php echo esc_attr($slug); ?>" class="mobile-menu__panel-title">
  ```

  The `aria-labelledby` → `id` link must survive the change (`aria-labelledby`
  may reference any element; it does not need to be a heading).

- Nav data (labels like "New to Rollforming?") lives in
  `app/inc/desktop-nav.php` — do not touch it; only the rendering tags change.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP syntax | `php -l <file>` | `No syntax errors` |
| Lint | `npm run lint:php` | exit 0 |
| Build | `npm run build` | exit 0 |
| Template tests | `npm run test:page-templates` | exit 0 |

**Rendered-page caveat**: DevKinsta serves the main checkout, not worktrees.
From a worktree, use only the static grep verifications; the curl check in
Step 3 applies only when the branch is checked out in
`/Users/jrodriguez/Development/kinsta/public/newtech`.

## Scope

**In scope** (the only files you should modify):
- `app/templates/parts/mega-menu.php`
- `app/templates/parts/mobile-menu-panel.php`

**Out of scope** (do NOT touch):
- `app/inc/desktop-nav.php` / `app/inc/mobile-nav.php` — nav data, not markup.
- Section headings inside page content (`templates/parts/front-page/*`) — the
  H2s there are correct.
- `app/templates/parts/search-modal.php` and footer headings — separate
  surfaces, out of this plan's evidence base.
- Any CSS file, unless Step 2's selector check proves an element-qualified
  selector exists (then the minimal selector edit is allowed and must be
  reported).

## Git workflow

- Branch off `dev`: `joelgrodriguez/003-demote-nav-headings`
- One commit; short imperative subject, e.g. "Demote nav panel titles from h2".
- Do NOT push or merge unless the operator instructed it.

## Steps

### Step 1: Swap the tags

- In `mega-menu.php:218`, change `<h2 …>` / `</h2>` to `<p …>` / `</p>`,
  keeping the class list byte-identical.
- In `mobile-menu-panel.php:38`, change `<h2 …>` / `</h2>` to `<p …>` / `</p>`,
  keeping BOTH the `id="mobile-menu-title-…"` and the class untouched (the
  section's `aria-labelledby` depends on the id).

**Verify**:
`grep -n '<h2' app/templates/parts/mega-menu.php app/templates/parts/mobile-menu-panel.php`
→ no matches. `php -l` on both files → clean.

### Step 2: Check for element-qualified CSS/JS selectors

```bash
grep -rn 'h2\.mobile-menu__panel-title\|mobile-menu__panel-title' app/assets/ | grep -v dist
grep -rn "querySelector.*h2" app/assets/scripts/ 2>/dev/null | grep -i 'menu'
```

**Verify**: any styling for `.mobile-menu__panel-title` is class-only (no `h2`
element qualifier) and no menu JS queries `h2`. If a qualified selector
exists, update just that selector to the class-only form and note it in your
report; if menu JS structurally depends on `h2`, STOP.

### Step 3: Build, lint, tests (+ rendered check when possible)

**Verify**: `npm run lint:php`, `npm run build`,
`npm run test:page-templates` → all exit 0.

Served-checkout only:
```bash
curl -sk https://newtech.local/ | grep -c '<h2[^>]*>New to Rollforming'   # expect 0
```
Also visually confirm (or defer to post-merge QA): mobile menu panels and
desktop mega panels look unchanged — the swap must be invisible.

## Test plan

No new automated tests — markup-only change with grep gates above as the
regression check. `test:page-templates` must stay green.

## Done criteria

- [ ] No `<h2>` remains in either in-scope file
- [ ] `mobile-menu-panel.php` keeps `id="mobile-menu-title-<slug>"` on the replacement element
- [ ] `npm run lint:php`, `npm run build`, `npm run test:page-templates` exit 0
- [ ] No files outside the in-scope list modified (`git status`) — except a
      single CSS selector de-qualification if Step 2 required it (reported)
- [ ] `plans/README.md` status row updated

## STOP conditions

Stop and report back (do not improvise) if:

- The excerpts in "Current state" don't match the live files.
- Menu JavaScript selects panels or titles by `h2` element (behavioral
  dependency, not just styling).
- The visual appearance of either menu changes after the swap (a `p`-element
  default style is bleeding in — do not start adding CSS overrides; report).

## Maintenance notes

- Future nav panels should label themselves with `<p>` + `aria-labelledby`
  (or `aria-label`), never headings — consider this the convention going
  forward.
- Reviewer should scrutinize: the class strings and the `id` attribute
  survived byte-identically; only tag names changed.
