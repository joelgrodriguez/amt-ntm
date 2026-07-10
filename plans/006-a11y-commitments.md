# Plan 006: Keep the theme's own accessibility commitments (tap targets, contrast, table semantics, reduced motion)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. Your reviewer maintains `plans/README.md` — do
> not edit it.
>
> **Drift check (run first)**: `git diff --stat 91b252b..HEAD -- app/resources/css/components/buttons.css app/header.php app/templates/parts/comparison-table.php app/templates/parts/video-section.php app/resources/css/animations.css DESIGN.md`
> On any change since this SHA, compare the excerpts below; on mismatch, STOP.

## Status

- **Priority**: P1
- **Effort**: M
- **Risk**: MED (visual changes on shared components — reviewer does a visual pass post-merge)
- **Depends on**: none
- **Category**: a11y
- **Planned at**: commit `91b252b`, 2026-07-10

## Why this matters

PRODUCT.md ("Accessibility & Inclusion") commits to: WCAG AA across the site; "Tap targets generous on mobile; CTAs are not 32px buttons"; `prefers-reduced-motion` "enforced, not optional"; real semantic structure everywhere. The audience skews 50+, reading phones in sunlight. Four places where the code breaks those written commitments:

1. `.btn-sm` is exactly 32px tall (`h-8`) and is used on real CTAs (product cards, comparison table, configurator/financing CTAs).
2. `blue-400` (`#5A7691`) on `blue-900` (`#0A1322`) computes **3.93:1** — below the 4.5:1 AA floor for normal text. `video-section.php:68` sets it as a section's base text color on machine pages.
3. The comparison table has no `scope` attributes and uses `<td>` for row headers — spec comparison is a core decision surface.
4. Reduced-motion enforcement is per-component (animations.css covers only `.reveal*`/`.mobile-menu`/`.page-transition`/`.stagger`; transitions.css has its own guards) with no global backstop — DESIGN.md §9 says "Enforced, not optional."

## Current state

- `app/resources/css/components/buttons.css:10-30`:

```css
@layer components {
  .btn {
    @apply inline-flex items-center justify-center gap-2 font-mono font-medium;
    @apply focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500;
    @apply h-12 px-6 min-w-32 text-body;
    transition: background-color 200ms ease-out, color 200ms ease-out, border-color 200ms ease-out;
  }

  .btn-sm {
    @apply h-8 px-4 min-w-24 text-caption;
  }
```

- `app/header.php:146` (desktop utility rail Contact CTA — `px-3 py-1` ≈ 30px tall):

```php
<a href="<?php echo esc_url($contact['url']); ?>" class="inline-flex items-center px-3 py-1 font-sans text-sm font-medium text-blue-700 border border-blue-500 hover:bg-blue-50 transition-colors no-underline">
```

- `app/templates/parts/comparison-table.php` — thead `<th>` cells at lines ~67 and ~77 have no `scope`; body row label cells at line ~93 are `<td>`:

```php
<th class="bg-blue-800 text-white py-4 px-4 text-left font-medium text-base border-r border-blue-700 sticky left-0 z-10 ...">
    <?php esc_html_e('Machine', 'standard'); ?>
</th>
...
<td class="py-3 px-4 font-medium text-blue-800 border-r border-blue-200 sticky left-0 bg-white z-10 ...">
    <?php echo esc_html($label); ?>
</td>
```

- `app/templates/parts/video-section.php:68`:

```php
<section class="bg-blue-900 text-blue-400" aria-labelledby="<?php echo esc_attr($args['section_id'] . '-title'); ?>">
```

(This part renders on machine product pages via `single-machine.php:64` / `single-machine-default.php:161` and on `single-video.php:28`.)

- `app/resources/css/animations.css:168-195` — the only global-file reduced-motion block; neutralizes `.reveal*`, `.mobile-menu`, `.page-transition`, `.stagger > *` and nothing else.
- `DESIGN.md:35` — token table row: `| Blue 400 | #5A7691 | --color-blue-400 | Meta text, captions, timestamps, footer links on dark backgrounds. |`
- Contrast math (verified): `#5A7691` on `#0A1322` = 3.93:1 (AA fail for normal text); on white = 4.74:1 (pass); `#9BB1C7` (blue-300) on `#0A1322` = ~8.4:1 (pass). The footer already uses `text-blue-300` on `bg-blue-900` (`app/footer.php:72`) — that is the pattern to match.
- Conventions: Tailwind-first (utilities in templates; custom CSS only where Tailwind can't express it). Before editing, read `.agents/skills/typography-system.md` and `.agents/skills/spacing-system.md` if present in your worktree.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP lint | `php -l <file>` per touched PHP file | `No syntax errors detected` |

(Worktree has no `node_modules`; do NOT run npm. The reviewer builds and does the visual pass post-merge.)

## Scope

**In scope**:
- `app/resources/css/components/buttons.css`
- `app/header.php` (the Contact anchor only)
- `app/templates/parts/comparison-table.php`
- `app/templates/parts/video-section.php` (line 68 class change only)
- `app/resources/css/animations.css` (append backstop)
- `DESIGN.md` (token-table usage note only)

**Out of scope** (do NOT touch):
- `app/theme.json` — do NOT change token hex values; 108 `text-blue-400` usages exist and most sit on light backgrounds where 4.74:1 passes.
- `app/resources/css/mobile-menu.css`, `layout/mega-menu.css` — their blue-400 uses sit on light panels (verified).
- `.btn`, `.btn-md`, `.btn-xl` sizes; the desktop search trigger in header.php.
- Every other `text-blue-400` template usage — the dark-surface audit beyond video-section is deferred (see Maintenance notes).

## Git workflow

- Branch: `advisor/006-a11y-commitments`
- Commit per step or one commit, subject like: `Meet stated a11y commitments: tap targets, dark contrast, table semantics, motion backstop`
- Do NOT push.

## Steps

### Step 1: 44px tap targets for `.btn-sm` on touch devices

In `buttons.css`, after the `.btn-sm` rule (inside `@layer components`), add:

```css
  /* PRODUCT.md: "CTAs are not 32px buttons." Keep the compact desktop look;
     guarantee a 44px target where the pointer is a finger. */
  @media (pointer: coarse) {
    .btn-sm {
      @apply h-11;
    }
  }
```

**Verify**: `grep -n 'pointer: coarse' app/resources/css/components/buttons.css` → one match after the `.btn-sm` block.

### Step 2: Bump the header Contact CTA

In `app/header.php:146`, change `px-3 py-1` to `px-4 py-2` (30px → ~38px tall; it is a desktop-rail control, mobile uses the mobile menu). Leave every other class intact.

**Verify**: `php -l app/header.php` → clean; `grep -n 'px-4 py-2 font-sans text-sm' app/header.php` → one match.

### Step 3: Comparison-table semantics

In `comparison-table.php`:
- Add `scope="col"` to BOTH thead `<th>` variants (the sticky "Machine" header and the per-machine loop header).
- Change the body row-label cell from `<td class="py-3 px-4 font-medium text-blue-800 ...">` to `<th scope="row" class="py-3 px-4 font-medium text-blue-800 text-left ...">` — same class list plus `text-left` (th defaults to center+bold; font-medium already set), and change its closing tag.

**Verify**: `php -l app/templates/parts/comparison-table.php` → clean. `grep -c 'scope="col"' app/templates/parts/comparison-table.php` → `2`; `grep -c 'scope="row"' ...` → `1`; the row-label `</td>` became `</th>`.

### Step 4: Fix the dark-section base text color

In `video-section.php:68`, change `text-blue-400` to `text-blue-300` (matches the footer's dark-surface pattern).

**Verify**: `php -l app/templates/parts/video-section.php` → clean; `grep -c 'text-blue-400' app/templates/parts/video-section.php` → `0`.

### Step 5: Global reduced-motion backstop

Append to the END of `animations.css` (after the existing reduced-motion block, keeping it — component rules remain the precise layer):

```css
/* Backstop: DESIGN.md §9 says reduced motion is enforced, not optional.
   Near-zero durations (not `none`) so JS waiting on transitionend/animationend
   still gets its events. Component-level rules above remain the fine-grained
   layer; this catches anything they miss. */
@media (prefers-reduced-motion: reduce) {
  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    transition-delay: 0ms !important;
    scroll-behavior: auto !important;
  }
}
```

**Verify**: `tail -20 app/resources/css/animations.css` shows the backstop; `grep -c 'prefers-reduced-motion' app/resources/css/animations.css` → `2`.

### Step 6: Update the DESIGN.md token rule

In `DESIGN.md` line 35, amend the Blue 400 usage cell to:

```
Meta text, captions, timestamps on LIGHT backgrounds (4.74:1 on white). On dark surfaces (blue-800/900) use blue-300 for any normal-size text — blue-400 computes 3.93:1 there and fails AA; reserve it for large text or decorative elements.
```

**Verify**: `grep -n '3.93:1' DESIGN.md` → one match on the Blue 400 row.

## Test plan

No automated a11y harness. Gates are the greps/lints above. Reviewer post-merge: build; visual pass on a machine page (comparison table renders identically, video section text is lighter), mobile emulation confirms 44px buttons; VoiceOver/axe spot-check on the comparison table.

## Done criteria

- [ ] All six step verifications pass
- [ ] All touched PHP files pass `php -l`
- [ ] `git status --porcelain` shows only the six in-scope files

## STOP conditions

- `comparison-table.php` structure differs from the excerpts (e.g. the `<td>` row label isn't where described).
- The `@media (pointer: coarse)` `@apply` fails any build the reviewer runs — the fallback is plain CSS `min-height: 2.75rem;` (note this in your report if you use it — but you cannot run the build; write the `@apply` form and flag it for the reviewer).
- You find yourself wanting to edit `theme.json` or more than the one `text-blue-400` instance — stop; that audit is explicitly deferred.

## Maintenance notes

- Deferred follow-up: a full dark-surface `text-blue-400` audit (108 usages; same-file `bg-blue-800/900` co-occurrence is the search heuristic). The DESIGN.md rule from Step 6 is the standard new work must meet.
- The reduced-motion backstop uses near-zero durations precisely so `HeroSlider.js` / `MegaMenu.js` `transitionend` waits still fire; if a future module hangs under reduced motion, check whether it listens for `animationend` on an element whose animation was `none`d by a component rule, not this backstop.
- Captions/transcripts for content-bearing videos (PRODUCT.md commitment) are a content/QA task in Wistia/YouTube settings, not theme code — flagged here so it isn't lost.
