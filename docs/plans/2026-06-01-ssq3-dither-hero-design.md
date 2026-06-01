# SSQ3 Dithered Canvas Hero — Test Design

**Date:** 2026-06-01
**Branch:** `effects`
**Status:** Approved — ready for implementation plan
**Type:** UI experiment (frontend, homepage)

## Goal

A/B-test replacing the homepage slider with a single static-but-alive `<canvas>`
hero that promotes the SSQ3 MultiPro flagship. The current slider
(`templates/parts/front-page/hero-slider.php`) stays fully intact behind a flag;
the experiment is reversible by deleting one `add_filter` call.

This is a **test**, not a committed redesign. It ships **off** by default — the
homepage is unchanged until the flag is flipped.

## Why

- Sliders underperform: users rarely wait for slide 2, and the carousel chrome
  competes with the message.
- A single striking hero focused on the flagship (SSQ3) is a stronger bet than a
  rotating set, and matches the stated intent ("promoting the ssq3").
- The brand already leans engineering/technical (mono eyebrows, blueprint
  section, existing `hero-overlay__grain` layer), so a dithered halftone
  treatment reads as *precision*, not gimmick.

## Constraint carried over from the slider

Per `front-page.php`, the hero is also the **routing surface** — it replaced the
old "category-doors" section. The replacement hero must still route people
somewhere. Decision: **SSQ3 flagship focus** — primary CTA to the SSQ3 product
page, secondary CTA to the full lineup. Single clear message.

## The Effect

A `<canvas>` samples one SSQ3 photo once (to an offscreen buffer), then renders
it as a **halftone dot field** in brand blues.

- **Assemble on load:** dots scatter → resolve into the machine (it "develops").
- **Idle:** slow, low-amplitude ripple so it's never visually dead.
- **Cursor (desktop only):** dots near the pointer displace gently — capped
  parallax, not a physics toy.
- **Red spark:** a single accent pulse near the primary CTA — the *only* red on
  screen, honoring DESIGN.md §2.4 (red is a pinpoint accent).

Rendering rules:

- Dot radius is driven by sampled pixel brightness (dark pixels → larger dots).
- Color ramp: `--color-blue-900 (#0A1322)` → `--color-blue-300 (#9BB1C7)`.
- `requestAnimationFrame` loop that **pauses when off-screen**
  (IntersectionObserver) and **when the tab is hidden** (`visibilitychange`).
- Device-pixel-ratio capped at 2 so retina/phones don't over-render.

## Content / Routing (SSQ3 focus)

- **H1** (real `<h1>`, replaces the slider's `sr-only` H1):
  "16 Panel Profiles. One Machine."
- **Mono kicker:** `NTM // FLAGSHIP`
- **Subtitle:** the SSQ3 slogan ("The future of portable roll forming." or the
  hero subtitle from `app/data/machines/ssq3-multipro.php`).
- **Primary CTA** → SSQ3 product page ("Explore the SSQ3 →").
- **Secondary CTA** → all machines ("See the lineup").
- Text sits in a normal DOM layer over the canvas — selectable, accessible,
  SEO-real. The canvas is decorative (`aria-hidden="true"`).

## Performance & Fallbacks (non-negotiable for a homepage hero)

- **LCP-safe:** the SSQ3 photo renders as a normal `<img>` (or CSS background)
  *underneath* the canvas and is preloaded `fetchpriority="high"`. The canvas
  fades in over it once sampling completes. If JS never runs, the user sees a
  clean SSQ3 photo + text + CTAs. Nothing breaks.
- **`prefers-reduced-motion`:** skip assemble + ripple; render the resolved dot
  field once, statically. (Matches existing `ScrollReveal.js` behavior.)
- **Mobile:** larger dot spacing (fewer dots), assemble-then-idle-still (or very
  low ripple), no cursor parallax (`pointer: fine` only).

## The Flag

One switch in `front-page.php`:

```php
$use_dither_hero = (bool) apply_filters('ntm_dither_hero', false);
get_template_part(
    'templates/parts/front-page/' . ($use_dither_hero ? 'hero-dither' : 'hero-slider')
);
```

- Flip via a one-line `add_filter('ntm_dither_hero', '__return_true')`.
- Live preview without committing the flag: support `?dither_hero=1` query param
  (only when the param is present; never overrides an explicit `true`).
- Slider code is untouched. Reverting the experiment = remove the filter.

## New Files

- `app/templates/parts/front-page/hero-dither.php` — markup: preloaded `<img>`
  base layer + `<canvas>` + content/CTA layer.
- `app/resources/js/modules/DitherHero.js` — the canvas engine. Exports
  `initDitherHero()`, registered in `app/resources/js/_app.js`. Guarded:
  no-op if the hero element is absent (every other page).
- Hero styles: Tailwind-first in the markup. Custom CSS only where Tailwind
  can't express it — canvas layering/positioning and the red-spark `@keyframes`
  — added to `components.css` (or `animations.css` for the keyframe).

## Explicitly NOT Doing (scope guard)

- Not touching `hero-slider.php`, `hero-slide.php`, `get_hero_slides()`, or any
  other homepage section.
- No new dependencies — vanilla `<canvas>`, ~4–6kb of JS.
- No DB / WordPress-state changes, so no `scripts/db/` capture is required.

## Decisions Made (change on request)

- **Source photo:** reuse the existing SSQ3 manual-controller image already in
  the data (`/uploads/2026/05/ntm-ssq3-manual-controller-050.jpg`). No new
  uploads.
- **Default flag state:** OFF. Homepage unchanged until flipped.

## Verification Note

Per the worktree convention: DevKinsta serves the main checkout, not worktrees.
Browser QA / build verification of the rendered page happens on the main
checkout (post-merge) or via `?dither_hero=1`. From this worktree the work is
limited to building the files, linting, and a production `npm run build` to
confirm the bundle compiles. Visual sign-off is deferred.

## Risks

- **Canvas perf on low-end mobile** — mitigated by dot-spacing scale, DPR cap,
  off-screen/hidden-tab pause, and reduced-motion static render.
- **LCP regression** — mitigated by the base `<img>` carrying the LCP, canvas
  layered on top (decorative).
- **"Too loud" for a premium industrial brand** — mitigated by monochrome-blue
  palette + single red spark; no full-color motion.
