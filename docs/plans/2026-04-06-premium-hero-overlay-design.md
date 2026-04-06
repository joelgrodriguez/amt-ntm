# Premium Hero Overlay System — Design Spec

**Date:** 2026-04-06
**Branch:** `feat/premium-hero-overlays`
**Status:** Approved for implementation

## Problem

The current hero overlay across the site uses a flat 45% dark blanket (`bg-slate-950/45`) combined with a tiling PNG dot texture at 40% opacity. This produces a uniform, heavy filter over hero images that:

- Kills the photography underneath — the image can't breathe
- The repeating texture reads as noise rather than intentional design
- Looks template-grade rather than premium/professional
- Doesn't adapt to image composition — same treatment regardless of content

## Decision

Replace all hero overlays site-wide with a **cinematic vignette system** inspired by premium automotive brands (Rivian, Porsche). The core idea: a radial spotlight preserves the image subject while naturally darkening the edges where text needs contrast. No tiling textures. Minimal, purposeful overlays only.

## Design: Cinematic Vignette Overlay System

### Core Overlay Stack (3 layers, applied to all heroes)

**Layer 1 — Radial Vignette**
```css
background: radial-gradient(
  ellipse at 55% 45%,
  transparent 20%,
  rgba(2, 6, 23, 0.15) 45%,
  rgba(2, 6, 23, 0.5) 75%,
  rgba(2, 6, 23, 0.75) 100%
);
```
- Center of the ellipse is offset slightly right and up (55% 45%) — a single default for all heroes, no per-image tuning
- Transparent core preserves the image subject at full quality
- Edges darken progressively for natural depth

**Layer 2 — Bottom Floor-Fade**
```css
background: linear-gradient(
  to top,
  rgba(2, 6, 23, 0.7) 0%,
  rgba(2, 6, 23, 0.3) 30%,
  transparent 60%
);
```
- Multi-stop eased gradient concentrates darkness where text sits (bottom)
- Transparent above 60% so the vignette handles the upper portion alone

**Layer 3 — SVG Fractal Noise Grain**
```css
.hero-overlay__grain {
  position: absolute;
  inset: 0;
  pointer-events: none;
  opacity: 0.06;
  mix-blend-mode: overlay;
  filter: url(#hero-grain-filter);
}
```
SVG filter definition (injected once in footer):
```html
<svg style="position:absolute;width:0;height:0" aria-hidden="true">
  <filter id="hero-grain-filter">
    <feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/>
    <feColorMatrix type="saturate" values="0"/>
  </filter>
</svg>
```
- 6% opacity fractal noise breaks gradient banding
- `mix-blend-mode: overlay` integrates naturally
- Resolution-independent, tiny file size vs PNG texture
- Applied to gradient areas, not directly on photography

### Text Readability Safety Net

Keep `text-shadow: 0 2px 4px rgba(0,0,0,0.5)` on all hero text containers as a subtle backup. Invisible when vignette provides enough contrast, catches edge cases with bright images.

### CSS Component

New file: `app/resources/css/components/hero-overlay.css`

```css
/**
 * Hero Overlay — Cinematic Vignette
 *
 * Premium overlay system for all hero sections.
 * Radial vignette + bottom fade + SVG grain.
 *
 * Usage:
 *   <div class="hero-overlay"></div>
 *   <div class="hero-overlay__grain"></div>
 *
 * Variants:
 *   .hero-overlay--asymmetric-left   Directional fade for left-positioned text
 *   .hero-overlay--asymmetric-right  Directional fade for right-positioned text
 */

.hero-overlay {
  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;
  background:
    /* Bottom floor-fade */
    linear-gradient(
      to top,
      rgba(2, 6, 23, 0.7) 0%,
      rgba(2, 6, 23, 0.3) 30%,
      transparent 60%
    ),
    /* Radial vignette */
    radial-gradient(
      ellipse at 55% 45%,
      transparent 20%,
      rgba(2, 6, 23, 0.15) 45%,
      rgba(2, 6, 23, 0.5) 75%,
      rgba(2, 6, 23, 0.75) 100%
    );
}

/* SVG grain texture */
.hero-overlay__grain {
  position: absolute;
  inset: 0;
  z-index: 2;
  pointer-events: none;
  opacity: 0.06;
  mix-blend-mode: overlay;
  filter: url(#hero-grain-filter);
}
```

### Three Variants

#### 1. Standard (product hero, front-page slider, machines page)

Used when text is bottom-aligned (left or center). The default `.hero-overlay` with no modifier.

**Applies to:**
- `app/templates/woo/product/parts/hero.php` — product page hero
- `app/templates/parts/front-page/hero-slide.php` — front page slider slides
- `app/templates/pages/machines/hero.php` — machines listing hero

#### 2. Asymmetric (category pages)

Used when text occupies one side with a split composition. Replaces the current hard clip-path wedge with a **soft directional gradient** that harmonizes with the vignette.

**Modifiers:** `.hero-overlay--asymmetric-left` and `.hero-overlay--asymmetric-right`

```css
/* Asymmetric: text on the left */
.hero-overlay--asymmetric-left {
  background:
    /* Directional: dark left, transparent right */
    linear-gradient(
      to right,
      rgba(2, 6, 23, 0.8) 0%,
      rgba(2, 6, 23, 0.6) 30%,
      rgba(2, 6, 23, 0.2) 55%,
      transparent 75%
    ),
    /* Subtle bottom anchor */
    linear-gradient(
      to top,
      rgba(2, 6, 23, 0.4) 0%,
      transparent 40%
    ),
    /* Vignette for depth */
    radial-gradient(
      ellipse at 70% 45%,
      transparent 25%,
      rgba(2, 6, 23, 0.2) 50%,
      rgba(2, 6, 23, 0.5) 80%,
      rgba(2, 6, 23, 0.65) 100%
    );
}

/* Asymmetric: text on the right */
.hero-overlay--asymmetric-right {
  background:
    /* Directional: dark right, transparent left */
    linear-gradient(
      to left,
      rgba(2, 6, 23, 0.8) 0%,
      rgba(2, 6, 23, 0.6) 30%,
      rgba(2, 6, 23, 0.2) 55%,
      transparent 75%
    ),
    /* Subtle bottom anchor */
    linear-gradient(
      to top,
      rgba(2, 6, 23, 0.4) 0%,
      transparent 40%
    ),
    /* Vignette for depth */
    radial-gradient(
      ellipse at 30% 45%,
      transparent 25%,
      rgba(2, 6, 23, 0.2) 50%,
      rgba(2, 6, 23, 0.5) 80%,
      rgba(2, 6, 23, 0.65) 100%
    );
}
```

**Mobile behavior:** On mobile (< lg), asymmetric heroes stack vertically. The overlay falls back to the standard vignette (no directional bias) since text fills the full width.

```css
@media (max-width: 1023px) {
  .hero-overlay--asymmetric-left,
  .hero-overlay--asymmetric-right {
    background:
      linear-gradient(
        to top,
        rgba(2, 6, 23, 0.75) 0%,
        rgba(2, 6, 23, 0.5) 30%,
        rgba(2, 6, 23, 0.3) 60%,
        rgba(2, 6, 23, 0.15) 100%
      );
  }
}
```

**Applies to:**
- `app/templates/parts/hero-asymmetric.php` — shared asymmetric hero (gutter, roof-wall pages)

#### 3. Centered (template-hero-video)

The template-hero-video uses a dark solid background (`bg-slate-900`) with a square grid pattern and an embedded video — not a full-bleed image hero. This one gets a lighter treatment since the background is already controlled:

- Keep `bg-slate-900` as the base
- Replace the square grid pattern overlays with the SVG grain only (subtle texture on the dark background)
- No vignette needed — the dark background IS the contrast

**Applies to:**
- `app/templates/template-hero-video.php`

## Files Changed

### Modified
| File | Change |
|------|--------|
| `app/templates/woo/product/parts/hero.php` | Replace `bg-slate-950/45` + `pattern-png-texture` div with `.hero-overlay` + `.hero-overlay__grain` |
| `app/templates/parts/front-page/hero-slide.php` | Replace `.hero-slider__overlay` + `pattern-png-texture` div with `.hero-overlay` + `.hero-overlay__grain` |
| `app/templates/pages/machines/hero.php` | Replace `bg-slate-950/45` + `pattern-png-texture` div with `.hero-overlay` + `.hero-overlay__grain` |
| `app/templates/parts/hero-asymmetric.php` | Remove clip-path wedge, mobile overlay, gradient bleed, and `pattern-png-texture`; add `.hero-overlay--asymmetric-{left,right}` + `.hero-overlay__grain` |
| `app/templates/template-hero-video.php` | Remove `pattern-square-grid__overlay` divs; add `.hero-overlay__grain` |
| `app/resources/css/front-page/hero-slider.css` | Remove `.hero-slider__overlay` rule (replaced by `.hero-overlay`) |
| `app/resources/css/components/patterns.css` | Remove `.pattern-png-texture` class |
| `app/footer.php` | Add inline SVG filter definition for `#hero-grain-filter` |

### Created
| File | Purpose |
|------|---------|
| `app/resources/css/components/hero-overlay.css` | New cinematic vignette overlay component |

### Removed (references only — not deleting pattern classes used elsewhere)
| Item | Reason |
|------|--------|
| `.pattern-png-texture` CSS class | No longer referenced by any template |
| `pattern-png-texture` div in all hero templates | Replaced by SVG grain |
| `.hero-slider__overlay` CSS rule | Replaced by `.hero-overlay` |
| Clip-path wedge divs in `hero-asymmetric.php` | Replaced by soft directional gradient |
| Mobile solid overlay in `hero-asymmetric.php` | Replaced by asymmetric mobile fallback |
| Gradient bleed div in `hero-asymmetric.php` | No longer needed — directional gradient handles the transition |

### Not Deleted (still used elsewhere)
| Item | Reason |
|------|--------|
| `app/assets/images/hero-bg-pattern-bg.png` | May be referenced elsewhere; leave for now |
| `.pattern-dot-matrix` CSS class | Unused in heroes but available for other sections |
| `.pattern-square-grid` CSS classes | Still used in non-hero contexts |
| `.pattern-dot-grid` CSS class | Used in accessory pages |

## Z-Index Stacking (unchanged)

```
z-index: 0  = background image
z-index: 1  = background video (when both exist)
z-index: 2  = .hero-overlay + .hero-overlay__grain
z-index: 3+ = content (text, buttons)
z-index: 10 = interactive elements (nav, progress bar)
```

## Accessibility

- WCAG AA contrast maintained: the vignette + floor-fade provides sufficient contrast for white text at the bottom/edges where text is positioned
- `text-shadow` on hero text provides additional contrast safety
- `pointer-events: none` on all overlay layers
- `aria-hidden="true"` on the SVG filter definition
- SVG grain is decorative only, no impact on screen readers
- `prefers-reduced-motion` is not affected — overlays are static, not animated

## Out of Scope

- Changing hero dimensions, typography, or layout structure
- Per-image focal point tuning (decided against — single default for all)
- Animated grain or parallax overlay effects
- Glassmorphism or blur effects
- Changes to non-hero patterns (dot-grid, square-grid used in other sections)
