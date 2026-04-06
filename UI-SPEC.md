# UI Design System Specification

This document defines the visual language, design tokens, component patterns, and coding conventions used across NTM digital properties. It is derived from the Standard Theme codebase and draws heavy inspiration from IBM's Carbon Design System. Any new project built for this company must match this aesthetic exactly.

---

## 1. Design Philosophy

The visual style is industrial, clean, and confident. Think Carbon Design System adapted for a manufacturing/B2B brand. Key principles:

- **Zero border-radius.** Every element — buttons, cards, badges, inputs, images — uses sharp corners. `border-radius: 0` globally. No rounded anything.
- **Grid-based precision.** Layouts use CSS Grid with consistent gap values. No freeform floating.
- **Restrained motion.** Animations are subtle (150–300ms), functional, and always respect `prefers-reduced-motion`.
- **Typographic hierarchy through weight and size**, not decorative elements. IBM Plex font family throughout.
- **High contrast, minimal color.** Mostly slate grays with two brand accent colors. Color is used sparingly and intentionally.

---

## 2. Design Tokens

### 2.1 Colors

```
Primary:        #0078C2   (IBM-style blue — links, primary buttons, focus rings)
Secondary:      #F7951D   (Orange — eyebrows, accent dividers, CTAs, testimonial dots)
Accent:         #B91C1C   (Red — sparingly, for destructive/warning states)

Dark:           #18181b   (Near-black — dark backgrounds, footer)
Dark Medium:    #3f3f46   (Dark gray)
Medium:         #71717a   (Mid gray — secondary text)
Light Medium:   #d4d4d8   (Light gray — borders, dividers)
Light:          #fafafa   (Off-white — section backgrounds)
White:          #ffffff   (Cards, page backgrounds)
Border:         #e4e4e7   (Default border color for all elements)
```

The Tailwind `slate` palette is used as the primary neutral scale. Specific usages:

- `slate-900` — primary headings, body text
- `slate-700` — nav links, secondary text
- `slate-600` — body copy, descriptions, subtitles
- `slate-500` — meta text, timestamps, captions
- `slate-400` — footer links (dark bg), placeholder text
- `slate-200` — borders, dividers on light bg
- `slate-100` — hover backgrounds, alternate section bg
- `slate-50`  — light section backgrounds

### 2.2 Typography

Font stack (loaded via Bunny Fonts CDN):

```
Sans:   "IBM Plex Sans", ui-sans-serif, system-ui, sans-serif
Serif:  "IBM Plex Serif", ui-serif, Georgia, serif
Mono:   "IBM Plex Mono", ui-monospace, monospace
```

**Usage rules:**

- `font-sans` — all body text, headings, buttons, UI elements
- `font-mono` — category labels, post type indicators, site name in header, small metadata labels
- `font-serif` — reserved for editorial/long-form content if needed (not currently used in UI)

**Type scale (rem):**

```
xs:   0.75rem   (12px)  — badges, fine print
sm:   0.875rem  (14px)  — nav links, button-sm, accordion triggers, footer
base: 1rem      (16px)  — body copy
lg:   1.125rem  (18px)  — subtitles, pain point descriptions
xl:   1.25rem   (20px)  — card titles (desktop), step titles
2xl:  1.5rem    (24px)  — testimonial text (mobile)
3xl:  1.875rem  (30px)  — section titles (mobile)
4xl:  2.25rem   (36px)  — section titles (tablet)
5xl:  3rem      (48px)  — section titles (desktop)
```

**Heading pattern:** Section titles use responsive sizing: `text-3xl md:text-4xl lg:text-5xl`, always `font-bold text-slate-900`.

### 2.3 Spacing & Layout

```
Content max-width:  720px   (prose, single-column content)
Wide max-width:     1440px  (page container)
Border-radius:      0       (global — no rounded corners anywhere)
```

**Section spacing (responsive vertical padding):**

```
.section:          py-16 md:py-20 lg:py-24
.section-compact:  py-12 md:py-16
```

**Container:** Centered with `mx-auto px-4 lg:px-0`, max-width 1440px. On desktop, the container has side borders (`border-x border-slate-200`) for a contained, structured feel.

### 2.4 Animation Timing

```
Fast:     150ms   (button hover, link transitions)
Base:     200ms   (page transitions, mobile menu)
Slow:     300ms   (scroll reveals, accordion expand/collapse)
Ease Out: cubic-bezier(0.33, 1, 0.68, 1)
Ease I/O: cubic-bezier(0.65, 0, 0.35, 1)
```

---

## 3. Component Library

### 3.1 Buttons

Sized per Carbon Design System height tokens:

```
btn-sm:  h-8   (32px)  px-3   min-w-24   text-sm
btn-md:  h-10  (40px)  px-3.5 min-w-28
btn-lg:  h-12  (48px)  px-4   min-w-32   (default)
btn-xl:  h-16  (64px)  px-5   min-w-40
```

All buttons use `inline-flex items-center justify-center gap-2 font-medium`. No border-radius.

**Hover effect:** Background-size sweep from right to left using a two-stop linear-gradient. The gradient goes from the hover-state color (50%) to the resting color (50%), and `background-size: 200% 100%` with `background-position` transition creates a sweep effect.

**Variants:**

| Class | Resting | Hover | Use Case |
|---|---|---|---|
| `btn-primary` | Solid primary blue, white text | Darkens (color-mix 85% with black) | Primary CTAs |
| `btn-secondary` | Solid orange, white text | Darkens | Secondary CTAs |
| `btn-light` | White, dark text | Light gray (#f1f5f9) | Light backgrounds |
| `btn-outline-dark` | Transparent, dark border | Fills dark, text goes white | Cards, secondary actions |
| `btn-outline-light` | Transparent, white/60 border | Fills white, text goes dark | Dark backgrounds |
| `btn-ghost` | Transparent, no border | Light gray bg | Tertiary actions, minimal UI |

### 3.2 Cards

**Base card:** `bg-white border transition-all duration-200 hover:shadow-lg hover:-translate-y-1`. Sharp corners. Subtle lift on hover.

**Post card (content card):** Three-row grid layout: image → content → footer CTA bar.

- Image: full-width, contained in a bordered block
- Content: padding, category label (`font-mono text-xs uppercase tracking-wide text-slate-500`), title (`font-semibold text-slate-900`)
- Footer: border-top bar with icon + CTA text (`font-mono text-xs text-slate-500`), arrow icon on right, hover bg-slate-50

**Product card:** Horizontal on tablet+, vertical on mobile. Image with `aspect-ratio: 4/3`, `object-contain`, subtle scale on hover. Badge in top-left corner (`text-xs font-semibold uppercase tracking-wide text-white bg-slate-800`).

### 3.3 Section Pattern

Every page section follows this structure:

```
section.section > div.container > div.section-content
  └── div.section-header (or .section-header-left)
        ├── p.section-eyebrow
        ├── div.section-divider (or .section-divider-center)
        ├── h2.section-title
        └── p.section-subtitle (or .section-subtitle-centered)
  └── [section body content]
```

**Eyebrow:** `text-sm font-semibold uppercase tracking-wider text-secondary` — always orange.

**Divider:** `w-12 h-1 bg-secondary` — a short horizontal accent bar in orange, placed between eyebrow and title.

**Background alternation:** Sections alternate between `bg-white`, `bg-slate-50`, `bg-slate-100`, and `bg-slate-900` (dark). Use `.section-content` grid with `gap-12 lg:gap-16` to separate header from body.

### 3.4 Navigation

**Header:** Fixed height `h-12` (48px). White background, bottom border. Contains: mobile toggle (left, `w-12 h-12` with border-right), logo + site name, desktop nav, search icon (right).

- Logo text: `font-mono font-bold text-sm text-slate-600`
- Nav links: `text-sm px-4 text-slate-700 hover:bg-slate-100`
- Mega menu: Full-width dropdown below header, 4-column grid, white bg, top/bottom borders
- Menu link arrows: Inline SVG masks on `::after` pseudo-elements (Carbon-style directional arrows)

**Mobile menu:** Slides in from left (`translateX(-100%)` → `translateX(0)`), full viewport below header.

**Scroll behavior:** Header hides on scroll-down (`translateY(-100%)`), shows on scroll-up. Becomes `position: fixed` when sticky.

### 3.5 Accordion (Carbon-Style)

- Items separated by `border-t border-slate-200`, last item gets `border-b`
- Trigger: full-width button, `text-sm font-semibold text-slate-900`, chevron-down icon on right
- Trigger hover: `bg-slate-100`
- Expanded content: `max-h` transition (300ms ease-in-out), left border accent (`border-l-2 border-primary`) on answer text
- Single-open behavior: only one item expanded at a time
- Chevron rotates 180° when expanded

### 3.6 Badges

`inline-block px-3 py-1 text-sm font-medium capitalize border text-slate-700 bg-slate-100 hover:bg-slate-200`. No border-radius.

### 3.7 Links

**Animated underline:** `link-animated` class. Uses `::after` pseudo-element: `h-px bg-current`, `w-0` → `w-full` on hover with 200ms transition.

**Standard links:** `text-primary` with `hover:text-blue-700 hover:underline`.

### 3.8 Icon Buttons

`inline-flex items-center justify-center`, `hover:scale-110 active:scale-95`. Focus ring on `:focus-visible`.

### 3.9 Testimonials / Social Proof

Dark section (`bg-slate-900`). Centered layout, max-width constrained. Quote icon in `text-secondary`. Quote text in white, responsive sizing (`text-xl md:text-2xl lg:text-3xl font-medium`). Attribution: name in white, role/company in `text-slate-300 text-sm`. Navigation dots: `h-3` pills, active dot is wider (`w-8`) in `bg-secondary`, inactive is `w-3 bg-slate-600`.

### 3.10 Background Patterns

**Dot grid:** `radial-gradient(circle, slate-200 1px, transparent 1px)` at 16px intervals. Applied via `::before` pseudo-element with `pointer-events-none`.

**Square grid:** Two overlapping linear-gradients (horizontal + vertical lines, `slate-300 1px`) at 32px intervals. Fades from corners using CSS `mask-image` gradients. Variants: default (light), `--dark` (slate-500 lines), `--primary` (white/15 lines for blue bg).

**Fade overlays:** `gradient-fade-bottom` uses `mask-image: linear-gradient(to bottom, black 0%, black 70%, transparent 100%)` to fade patterns at the bottom.

---

## 4. Accessibility

- **Focus visible:** `outline-2 outline-offset-2 outline-primary` on all interactive elements
- **Skip to content:** Screen-reader-only link that becomes visible on focus, positioned top-left with `bg-primary text-white`
- **Reduced motion:** All animations wrapped in `@media (prefers-reduced-motion: no-preference)` or have `motion-reduce:` overrides. Scroll reveals show immediately. Sliders stop auto-advancing.
- **ARIA patterns:** Sections use `aria-labelledby` pointing to their `h2` id. Accordion triggers use `aria-expanded`. Mobile menu toggle uses `aria-expanded` and `aria-controls`.
- **Color contrast:** All text/background combinations must meet WCAG AA. Light text on dark bg uses `text-white` or `text-slate-300` (never lighter than slate-400 on slate-900).

---

## 5. Template Coding Conventions

### 5.1 Data-Driven Templates

All template content MUST be defined in arrays at the top of the file, then rendered dynamically below. Never hardcode text inline with HTML.

**Pattern:**

```php
<?php
declare(strict_types=1);

// All content in arrays at the top
$content = [
    'eyebrow' => __('Section Label', 'textdomain'),
    'title'   => __('Section Heading', 'textdomain'),
    'text'    => __('Description text.', 'textdomain'),
    'cta_text' => __('Call to Action', 'textdomain'),
    'cta_url'  => '/target-page/',
];

$items = [
    [
        'title' => __('Item One', 'textdomain'),
        'text'  => __('Description.', 'textdomain'),
    ],
    [
        'title' => __('Item Two', 'textdomain'),
        'text'  => __('Description.', 'textdomain'),
    ],
];
?>

<!-- HTML rendering below — loops over data, no hardcoded strings -->
<section class="section" aria-labelledby="unique-id">
    <div class="container section-content">
        <div class="section-header">
            <p class="section-eyebrow"><?php echo esc_html($content['eyebrow']); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="unique-id" class="section-title"><?php echo esc_html($content['title']); ?></h2>
            <p class="section-subtitle-centered"><?php echo esc_html($content['text']); ?></p>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <?php foreach ($items as $item) : ?>
                <div class="bg-white border p-8">
                    <h3 class="text-xl font-semibold text-slate-900"><?php echo esc_html($item['title']); ?></h3>
                    <p class="text-slate-600"><?php echo esc_html($item['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

### 5.2 Template Documentation

Every template file MUST have a docblock at the top:

```php
<?php
/**
 * [Template Name] — [Brief Description]
 *
 * [Longer description of layout, columns, behavior.]
 *
 * @package [Package]
 *
 * @usage [Parent template] ([filename])
 * @see [related CSS file or JS module]
 */
```

### 5.3 Config Arrays for Variant Logic

When a component has type-based behavior, define a config map:

```php
$type_config = [
    'post'     => ['icon' => 'file-text', 'cta' => __('Read Article', 'textdomain')],
    'video'    => ['icon' => 'play',      'cta' => __('Watch Video', 'textdomain')],
    'download' => ['icon' => 'download',  'cta' => __('View Download', 'textdomain')],
];

$config = $type_config[$current_type] ?? ['icon' => 'link', 'cta' => __('View', 'textdomain')];
```

### 5.4 CSS Architecture

- Use Tailwind CSS v4 with `@theme` for design tokens
- Component styles go in `@layer components` — use `@apply` for Tailwind utilities
- BEM-like naming for multi-part components (e.g., `.card-product`, `.card-product__image`, `.card-product__title`)
- Every CSS file gets a file-level docblock describing its purpose
- Responsive breakpoints: `md: 768px`, `lg: 1024px`
- Prefer CSS Grid (`grid gap-X`) over flexbox for layout. Use flexbox for inline alignment (`flex items-center gap-X`).

### 5.5 JavaScript Conventions

- Vanilla JS only, no frameworks. ES modules with named exports.
- Each module exports an `init` function and optionally a `cleanup` function for HMR.
- Use `IntersectionObserver` for scroll-based effects.
- All DOM queries at module scope, event listeners added in `init()`.
- Always check `prefers-reduced-motion` before enabling animations.
- JSDoc on all exported functions.

---

## 6. Dark Section Treatment

When a section uses a dark background (`bg-slate-900` or `bg-dark`):

- Text: `text-white` for headings, `text-slate-300` for body, `text-slate-400` for meta
- Buttons: Use `btn-outline-light` or `btn-secondary`
- Borders: `border-slate-700`
- Pattern overlays: Use `--dark` or `--primary` variants
- Icons: `text-secondary` (orange) for accent, `text-slate-300` for decorative

---

## 7. Common Two-Column Layout

Many sections use a two-column split:

```
grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center
```

- **Content side:** Section header (eyebrow → divider → title → subtitle), list of items with icons, CTA buttons
- **Media side:** Full-width image with optional caption below (`text-center text-sm text-slate-600`)
- On mobile: stacks vertically with `gap-12`
- Use `order-1`/`order-2` classes to control visual order vs. DOM order

---

## 8. Footer Pattern

- Dark background: `bg-slate-900 text-slate-300 border-t border-slate-700`
- Multi-column grid on desktop (5 columns), CSS-only accordions on mobile
- Section titles: `text-white font-semibold text-sm uppercase tracking-wider`
- Links: `text-sm text-slate-400 hover:text-white`
- Bottom bar: border-top separator, three-column flex with legal links (left), copyright (center), social icons (right)
- Social icons: `w-5 h-5 text-slate-400 hover:text-white`
- "New" badges on footer links: `text-[10px] font-bold uppercase tracking-wider bg-primary text-white px-1.5 py-0.5`

---

## 9. Form Styling

Forms follow the industrial aesthetic:

- Inputs: No border-radius, `border border-slate-200`, padding that matches button heights
- Form containers: `bg-slate-50 border border-slate-200 p-8 lg:p-10`
- Labels: `text-sm font-semibold text-slate-900`
- Focus: `outline-2 outline-primary outline-offset-2`

---

## 10. Iconography

SVG icons loaded inline. Common icons include: `menu`, `x`, `search`, `chevron-down`, `chevron-up`, `arrow-right`, `external-link`, `mail`, `phone`, `check`, `quote`, `play`, `download`, `file-text`, `user`, `folder`, plus social icons (`facebook`, `twitter`, `linkedin`, `instagram`).

Icons are sized with Tailwind width/height classes (e.g., `w-5 h-5`). Color inherits from parent or set explicitly. Navigation arrows use CSS `mask-image` with inline SVG data URIs for the Carbon-style directional arrow pattern.

---

## Summary Checklist for New Projects

When building a new portal or application in this design system, verify:

- [ ] IBM Plex Sans/Mono loaded, applied globally
- [ ] `border-radius: 0` set globally — no rounded corners on any element
- [ ] Color palette matches tokens exactly (primary blue, secondary orange, slate neutrals)
- [ ] Buttons match Carbon height tokens (32/40/48/64px) with sweep hover effect
- [ ] Section pattern used: eyebrow (orange, uppercase) → divider bar → title → subtitle
- [ ] All content in data arrays, rendered via loops — no hardcoded strings in HTML
- [ ] Every template file has a docblock
- [ ] Cards have sharp corners, subtle hover lift, border styling
- [ ] Dark sections use correct text/border color mapping
- [ ] Animations respect `prefers-reduced-motion`
- [ ] Focus states visible on all interactive elements
- [ ] Container max-width 1440px with side borders on desktop
