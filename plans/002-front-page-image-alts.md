# Plan 002: Give product-card and hero images real alt text (and fix the markup-in-alt bug)

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**:
> `git diff --stat c5d139b..HEAD -- app/templates/parts/card-product.php app/templates/parts/front-page/hero-slide.php app/inc/machines.php`
> If any in-scope file changed since this plan was written, compare the
> "Current state" excerpts against the live code before proceeding; on a
> mismatch, treat it as a STOP condition.

## Status

- **Priority**: P1
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: bug (SEO / a11y)
- **Planned at**: commit `c5d139b`, 2026-07-21

## Why this matters

36 of the 53 images on the rendered homepage have `alt=""` — including every
machine product image (SSQ3, SSH, MACH II, BG7, …). These are the product
catalog, not decoration: empty alts throw away image-search visibility and
entity signals, and screen readers skip the only visual identification of each
machine. Separately, hero slide 2 has a real defect: its alt attribute contains
escaped markup — `alt="Do More&lt;br class="hidden lg:inline"&gt; in Less
Time"` — because the slide headline (which legitimately embeds a responsive
`<br>`) is passed raw as the image alt. Screen readers read that markup aloud.

## Current state

- `app/templates/parts/card-product.php` — canonical machine card used on the
  front-page Explore strip, /machines grid, accessory carousels, mega menu,
  and mobile menu. The image renders with a hardcoded empty alt
  (second argument of `responsive_image`) at lines 89–91:

  ```php
  <?php \Standard\Images\responsive_image($image, '', 'product-card', [
      'class' => 'card-product__image',
  ]); ?>
  ```

  Above it, the card has `$title` (machine name, trademark glyphs already
  stripped) and `$category_label` variables in scope (assigned around lines
  52–54).

- `app/inc/images.php:51` — helper signature (do not modify this file):

  ```php
  function responsive_image(string $url, string $alt = '', string $size = 'large', array $attrs = []): void {
  ```

- `app/templates/parts/front-page/hero-slide.php` — `$title` is assigned at
  line 25 (`$title = $machine['title'] ?? '';`) and passed as the alt at
  lines ~62–66:

  ```php
  <?php \Standard\Images\responsive_image($background_image, $title, 'full', [
      'class'         => 'hero__media',
      'loading'       => 'eager',
      'fetchpriority' => 'high',
  ]); ?>
  ```

  There is a **second** `responsive_image` call further down in the same file
  inside the deferred `<template class="hero-slide__media-template">` block
  (media parked for JS hydration) — both call sites must be fixed. Find them
  with `grep -n 'responsive_image' app/templates/parts/front-page/hero-slide.php`.

- `app/inc/machines.php` — hero slide data. Slide titles are display headlines
  and one intentionally contains markup (line 70):

  ```php
  'title'     => __('Do More<br class="hidden lg:inline"> in Less Time', 'standard'),
  ```

  The slides are built in the `$machine_slides` array (keys `ssq3-multipro`,
  `mach-ii-combo-gutter`) plus a hand-rolled `upgrades` slide appended after
  the loop. The loop maps `$meta` fields into each `$slides[]` entry.

- Repo convention for descriptive alts — follow the pattern in
  `app/templates/parts/front-page/three-step-plan.php` (lines ~30–50), which
  stores `image_alt` / `alt` strings alongside image paths in the content
  array, e.g. `'image_alt' => __('Jim and his family with their NTM SSQ
  portable rollformer', 'standard')`.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP syntax | `php -l <file>` | `No syntax errors` |
| Lint | `npm run lint:php` | exit 0 |
| Build (CSS/JS unchanged but sanity) | `npm run build` | exit 0 |
| Template tests | `npm run test:page-templates` | exit 0 |

**Rendered-page verification caveat**: DevKinsta serves the *main checkout*
at `/Users/jrodriguez/Development/kinsta/public/newtech`, not git worktrees.
If you are working in a worktree, `curl https://newtech.local/` will NOT show
your theme changes — use the static `grep` verifications below and defer
rendered checks to post-merge QA. Only curl-verify if your branch is checked
out in the served checkout.

## Scope

**In scope** (the only files you should modify):
- `app/templates/parts/card-product.php`
- `app/templates/parts/front-page/hero-slide.php`
- `app/inc/machines.php`

**Out of scope** (do NOT touch):
- `app/inc/images.php` — the helper already accepts an alt; no signature change.
- `fallback_image()` usage — the no-image placeholder is correctly
  `aria-hidden="true"` with no alt; leave it.
- Alt text anywhere else on the site (single-machine pages, learning center,
  etc.) — homepage surfaces only, via these three files.
- The visible slide headline rendering (`hero-slide.php` h2/`hero__title`) —
  the embedded `<br>` there is intentional responsive typography.

## Git workflow

- Branch off `dev`: `joelgrodriguez/002-front-page-image-alts`
- One commit per step or one combined commit; short imperative subject, e.g.
  "Add descriptive alt text to product cards and hero slides".
- Do NOT push or merge unless the operator instructed it.

## Steps

### Step 1: Product card alt

In `app/templates/parts/card-product.php`, replace the empty-string alt with a
descriptive one built from data already in scope. Target shape:

```php
<?php
$image_alt = $title;
if ($title !== '' && $category_label !== '') {
    $image_alt = $title . ' — ' . $category_label;
}
?>
<?php \Standard\Images\responsive_image($image, $image_alt, 'product-card', [
    'class' => 'card-product__image',
]); ?>
```

Place the `$image_alt` assignment with the other derived variables near the
top of the file (after `$category_label` is assigned), not inline in the
markup, matching the file's existing style. Result example:
`alt="SSQ3 MultiPro — Roof & Wall Panel Machines"`. (Yes, this partially
duplicates the visible card title for screen readers; the category suffix adds
information the image otherwise loses in image search. This tradeoff is
deliberate — do not switch back to `alt=""`.)

**Verify**: `php -l app/templates/parts/card-product.php` → no syntax errors.
`grep -n "responsive_image(\$image, ''" app/templates/parts/card-product.php`
→ no matches.

### Step 2: Per-slide hero alts in `app/inc/machines.php`

Add an `image_alt` field to each entry in `$machine_slides` and to the
hand-rolled `upgrades` slide, and carry it through the `$slides[]` mapping
(add `'image_alt' => $meta['image_alt'] ?? '',` in the loop, and a literal on
the upgrades slide). Use these strings (they describe the actual photos):

- `ssq3-multipro` (photo `ssq3-machine-rear-panel-exit.jpg`):
  `__('NTM SSQ3 MultiPro portable rollforming machine forming a standing seam metal roof panel', 'standard')`
- `mach-ii-combo-gutter` (photo `ntm-mach2-gutter-install-abel-002.jpg`):
  `__('Contractor running seamless gutter from an NTM MACH II portable gutter machine on a job site', 'standard')`
- `upgrades` (photo `Jim-adjusting-his-machine-scaled.jpg`):
  `__('NTM owner adjusting his portable rollforming machine', 'standard')`

**Verify**: `php -l app/inc/machines.php` → no syntax errors.
`grep -c 'image_alt' app/inc/machines.php` → at least 4 (3 data entries + the
loop mapping).

### Step 3: Use the alt in `hero-slide.php` (both call sites)

At **both** `responsive_image` call sites in
`app/templates/parts/front-page/hero-slide.php`, replace the `$title` alt
argument with:

```php
$image_alt = $machine['image_alt'] ?? '';
if ($image_alt === '') {
    $image_alt = wp_strip_all_tags($title);
}
```

(assign once near the top with the other slide variables, use `$image_alt` in
both calls). The `wp_strip_all_tags` fallback guarantees no future slide can
leak markup into an alt attribute even if its `image_alt` is missing. Also
apply the same treatment to the `<video>` block's `poster` sibling only if it
renders an `<img>` — it doesn't; posters have no alt. Leave video markup alone.

**Verify**: `php -l app/templates/parts/front-page/hero-slide.php` → clean.
`grep -n 'responsive_image(\$background_image, \$title' app/templates/parts/front-page/hero-slide.php`
→ no matches (both sites now use `$image_alt`).

### Step 4: Lint, build, template tests

**Verify**: `npm run lint:php` → exit 0. `npm run build` → exit 0.
`npm run test:page-templates` → exit 0.

### Step 5 (served checkout only): Rendered check

If (and only if) your branch is checked out in the served main checkout:

```bash
curl -sk https://newtech.local/ | grep -o '<img[^>]*alt="[^"]*&lt;[^"]*"' | wc -l   # expect 0
curl -sk https://newtech.local/ | grep -c 'alt="[^"]*MultiPro[^"]*"'               # expect >= 3
```

Otherwise mark this step "deferred to post-merge QA" in your report.

## Test plan

No new automated tests: this is template markup with no logic branch worth a
unit test beyond the existing `test:page-templates` smoke suite, which must
stay green. The grep gates in Steps 1–3 are the regression checks; the
`wp_strip_all_tags` fallback is the structural fix that prevents the bug class
from recurring.

## Done criteria

- [ ] `grep -rn "responsive_image(\$image, ''" app/templates/parts/card-product.php` → no matches
- [ ] `app/inc/machines.php` defines `image_alt` for all 3 hero slides and maps it into `$slides[]`
- [ ] Both `responsive_image` calls in `hero-slide.php` use the stripped/curated alt
- [ ] `npm run lint:php`, `npm run build`, `npm run test:page-templates` all exit 0
- [ ] No files outside the in-scope list modified (`git status`)
- [ ] `plans/README.md` status row updated

## STOP conditions

Stop and report back (do not improvise) if:

- `card-product.php` no longer contains the empty-alt `responsive_image` call
  shown in "Current state" (someone already fixed it).
- `hero-slide.php` has more than two `responsive_image` call sites (the file
  was restructured; the plan's mapping may not apply).
- `npm run test:page-templates` fails in a way that names files outside the
  in-scope list.

## Maintenance notes

- Any future hero slide added to `$machine_slides` should ship an `image_alt`;
  the `wp_strip_all_tags` fallback keeps it safe but generic if omitted.
- Reviewer should scrutinize: alt strings describe the *photo*, not the
  marketing headline; card alt uses the glyph-stripped `$title` (™/® already
  removed upstream, so alts stay clean).
- Deferred (out of scope here): empty alts on non-homepage surfaces — audit
  separately if image-search traffic becomes a priority.
