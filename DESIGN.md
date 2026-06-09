# New Tech Machinery — Style Reference
> Engineered Showroom. Industrial precision presented with premium restraint — a spec sheet that thinks it's an art book.

**Theme:** light

The design treats rollforming machines the way Tesla treats vehicles and Toyota treats the Tundra: full-bleed product imagery, generous negative space, and a minimal interface that gets out of the way. Our buyers are spending truck-and-vehicle money on our equipment, and the site signals that. The visual language is industrial-premium — sharp corners, monospaced labeling, blue-tinted neutrals — never rugged, never dirty. Color is restrained and disciplined: a single brand blue carries the system, a single red ignites action, and every "neutral" carries a low-saturation blue tint so the brand is felt without being announced.

---

## 1. Design Philosophy

- **Zero border-radius, everywhere.** Buttons, cards, badges, inputs, images — every corner is sharp. This is the Cybertruck instinct, not the Model Y. It is non-negotiable and it is what differentiates us from every other industrial-catalog site in the category.
- **No shadows.** Depth is established through color contrast, borders, and spacing — never drop shadows. Shadows read as consumer-software; their absence reads as engineered.
- **Two fonts. Two weights. That's it.** Noto Sans Mono (400, 500) and Noto Sans (400, 500). No 600/700/800 sneaking in. Restriction is the premium signal.
- **Mono is the editorial voice.** Section titles, eyebrows, labels, badges, button text, model numbers, navigation, metadata — anything that *identifies, labels, or commands* is monospace. Sans is reserved for body copy and large hero/display headlines that are *read*, not scanned.
- **60 / 30 / 10 color discipline.** 60% light blue-tinted neutrals + white. 30% deep blue-tinted darks + true black for product imagery contrast. 10% saturated color — and that 10% is mostly primary blue, with red used as a pinpoint accent.
- **Blue-tinted neutrals.** Every "gray" in the system is actually a desaturated blue derived from `#0078C2`. Backgrounds, text, borders, and dark surfaces all carry an unnoticeable but present brand hue. Pure neutrals are forbidden except for `#ffffff` and `#000000` in specific roles.
- **Restrained motion.** 150–300ms transitions, subtle reveals, `prefers-reduced-motion` respected. Motion supports the product; it never performs.
- **Imagery is the hero.** Product photography is full-bleed, sharp, cinematic, and color-graded clean. UI exists to support the machine, not compete with it.

---

## 2. Tokens — Colors

The brand color is **`#0078C2`**. Every neutral in the system is derived from it — desaturated blues that span near-white to near-black. This creates an unnoticeable but present brand "feeling" across every surface.

### 2.1 Brand-tinted neutral scale

| Name | Value | Token | Role |
|------|-------|-------|------|
| Blue 50 | `#F4F8FB` | `--color-blue-50` | Page backgrounds, alternate section backgrounds. Replaces all uses of slate-50/gray-50. |
| Blue 100 | `#E4ECF3` | `--color-blue-100` | Subtle hover backgrounds, dividers on light surfaces. |
| Blue 200 | `#C8D6E4` | `--color-blue-200` | Default borders, dividers on light bg, inactive UI element borders. |
| Blue 300 | `#9BB1C7` | `--color-blue-300` | Disabled text, placeholder text, tertiary UI details. |
| Blue 400 | `#5A7691` | `--color-blue-400` | Meta text, captions, timestamps, footer links on dark backgrounds. |
| Blue 500 | `#0078C2` | `--color-blue-500` | **Primary brand.** Links, primary CTAs, focus rings, active states, key iconography. The 10% accent. |
| Blue 600 | `#3F5870` | `--color-blue-600` | Body copy on light backgrounds, secondary text, sub-headings. |
| Blue 700 | `#26384B` | `--color-blue-700` | Secondary headings, technical/spec text on light backgrounds, secondary CTA borders. |
| Blue 800 | `#142235` | `--color-blue-800` | Dark surfaces, footer background, dark section backgrounds. |
| Blue 900 | `#0A1322` | `--color-blue-900` | **Primary headings on light backgrounds.** Also: deepest dark backgrounds, hero overlays, full-bleed dark sections. |

### 2.2 Pure neutrals

| Name | Value | Token | Role |
|------|-------|-------|------|
| Pure White | `#FFFFFF` | `--color-white` | Card surfaces, contrast surfaces, text on dark/colored buttons. |
| Pure Black | `#000000` | `--color-black` | SVG icon fills, image overlays, photographic captions. Reserved — not a UI background color. |

### 2.3 Accent

| Name | Value | Token | Role |
|------|-------|-------|------|
| Red | `#CD1018` | `--color-red` | **Pinpoint accent only.** Section divider bars, eyebrow accent dots, single high-emphasis CTA fills, active/selected indicators on key controls. |

### 2.4 The 10% rule

Saturated color = `--color-blue-500` + `--color-red`. Together they account for ~10% of any given screen. The discipline:

- **`--color-blue-500`** carries the system: links, primary CTAs, focus rings, key icons, active states.
- **`--color-red`** is reserved for moments that need to *ignite* — a single hero CTA fill, an accent divider bar, an eyebrow dot, a "selected" or "configure" emphasis.
- Never use either color for body copy, large background fills, decorative shapes, or hover states on non-interactive elements.

### 2.5 Quick color reference

- **Page background:** `--color-white` or `--color-blue-50`
- **Dark section background:** `--color-blue-800` or `--color-blue-900`
- **Heading text (light bg):** `--color-blue-900` (deep navy, premium and grounded)
- **Secondary heading / technical text (light bg):** `--color-blue-700`
- **Body text (light bg):** `--color-blue-600`
- **Heading text (dark bg):** `--color-white`
- **Body text (dark bg):** `--color-blue-200`
- **Border (default):** `--color-blue-200`
- **Primary CTA:** `--color-blue-500` background, `--color-white` text
- **Secondary CTA:** transparent background, `--color-blue-700` border + text
- **High-emphasis CTA / accent divider / eyebrow dot:** `--color-red`

---

## 3. Tokens — Typography

Two fonts. Two weights. No exceptions.

### 3.1 Noto Sans Mono — Editorial voice · `--font-mono`
- **Substitute:** `ui-monospace, SFMono-Regular, Menlo, monospace`
- **Weights:** 400, 500
- **Role:** Section titles, eyebrows, labels, badges, button text, navigation, model numbers, metadata, captions, technical specs, product identifiers. Anything that *identifies, labels, or commands*. The voice of an engineered machine.

### 3.2 Noto Sans — Reading voice · `--font-sans`
- **Substitute:** `ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif`
- **Weights:** 400, 500
- **Role:** Body copy, paragraphs, long-form content, large hero/display headlines that are *read* rather than scanned. The voice of communication.

### 3.3 Type scale

Compact, confident, premium. Sized in pixels for precision.

| Role | Size | Line height | Default font | Token |
|------|------|-------------|--------------|-------|
| caption | 11px | 1.2 | Mono | `--text-caption` |
| body | 16px | 1.5 | Sans | `--text-body` |
| heading-sm | 20px | 1.4 | Mono | `--text-heading-sm` |
| heading | 28px | 1.29 | Mono | `--text-heading` |
| heading-lg | 40px | 1.2 | Sans | `--text-heading-lg` |
| display | 48px | 1.17 | Sans | `--text-display` |
| hero (lg+) | 60–72px | 0.95 | Sans | `--text-6xl` / `--text-7xl` |

The `hero` row documents the front-page slider's responsive sizing, which exceeds the standard display scale at lg+ breakpoints. The slider is the one surface where a marquee scale is permitted; everywhere else, `--text-display` is the ceiling.

### 3.4 Font/weight pairing rules

- **Eyebrows** (small text above a heading): Mono 500, uppercase, tracked, `--color-red` or `--color-blue-500`. 11px.
- **Section titles** (the label of a section): Mono 500. 20–28px.
- **Hero / display headlines** (the read line): Sans 400 or 500. 40–48px. Tight leading.
- **Body copy:** Sans 400. 16px. 1.5 leading.
- **Buttons / nav / labels / badges:** Mono 500. 14px or smaller.
- **Model numbers, specs, metadata:** Mono 400 or 500. Sized to context.

Never use weights outside 400 / 500. Never italicize. Never decorate.

---

## 4. Tokens — Spacing & Shape

**Base unit:** 4px. **Density:** compact-to-comfortable.

### 4.1 Spacing scale

| Name | Value | Token |
|------|-------|-------|
| 4 | 4px | `--spacing-4` |
| 8 | 8px | `--spacing-8` |
| 16 | 16px | `--spacing-16` |
| 24 | 24px | `--spacing-24` |
| 32 | 32px | `--spacing-32` |
| 40 | 40px | `--spacing-40` |
| 48 | 48px | `--spacing-48` |
| 64 | 64px | `--spacing-64` |
| 96 | 96px | `--spacing-96` |

### 4.2 Border radius

**Zero. Everywhere. Always.**

| Element | Value |
|---------|-------|
| buttons | 0 |
| cards | 0 |
| inputs | 0 |
| modals | 0 |
| images | 0 |
| badges | 0 |

### 4.3 Shadows

**None.** Shadows are forbidden on UI elements. Depth comes from color contrast, borders, and spacing. The only acceptable "depth" effect is a 1px border in `--color-blue-200` or a background color shift.

### 4.4 Layout defaults

- **Card padding:** 24–32px
- **Section vertical padding:** 64px (md), 80px (lg), 96px (xl)
- **Element gap:** 8–16px
- **Container max-width:** Use full-bleed sections; constrain inner content with a max-width container as needed.

---

## 5. Components

### 5.1 Primary CTA Button
**Role:** Highest-priority single action — "Configure," "Request Quote," "Order Now."

Solid `--color-blue-500` background. `--color-white` text in Mono 500, 14px. Zero border-radius. Padding: 12px top/bottom, 24px left/right. Hover: shift background to a 10% darkened blue. Focus: 2px outline `--color-blue-500` at 2px offset.

For a single moment of maximum emphasis (one per page, ideally), the primary CTA may use `--color-red` as the background fill (the `.btn-emphasis` variant). Use sparingly. **A red CTA must either be the one deliberate ignition on the page or sit beside a secondary CTA in a 2-up row — never the lone CTA in an ordinary section.** A solo CTA in a content section is `.btn-primary` (blue). (2026-06-09: audited and corrected three category-page CTAs that were lone red.)

### 5.2 Secondary Ghost Button
**Role:** Secondary action — "Learn More," "View Specs."

Transparent background. 1px border `--color-blue-700`. `--color-blue-700` text in Mono 500, 14px. Zero border-radius. Padding: 12px top/bottom, 24px left/right. Hover: background fills `--color-blue-700`, text becomes `--color-white`.

### 5.3 Tertiary / Inverted Button (on dark)
**Role:** Action sitting on a dark section.

Solid `--color-white` background. `--color-blue-800` text in Mono 500. Zero radius. Same padding as Primary.

### 5.4 Eyebrow
**Role:** Small label above a heading identifying the section type or model line.

Mono 500, 11px, uppercase, letter-spacing `0.1em`, color `--color-blue-500` (the default — blue carries the system). `--color-red` only when the eyebrow is the page's deliberate accent moment, not a per-section reflex. **No leading dot on section eyebrows** (2026-06-09: removed the inconsistent per-instance dots). The square dot is reserved for the hero/brand-voice eyebrow only, where it's part of the marquee chrome.

### 5.5 Section Title
**Role:** The label of a content section.

Sans 500, tracking-tight, 20px (sm) / 28px (md) / 40px (lg), color `--color-blue-900`. No decoration. (The early spec called for mono 500 in `--color-blue-700`; the as-built ships sans at responsive display sizes and the deeper blue-900 for premium gravity. Mono remains the voice for eyebrows, labels, badges, buttons, navigation, and metadata per §3.)

### 5.6 Hero / Display Headline
**Role:** The hero line of a full-bleed product section.

Sans 400 or 500, 40–48px, color `--color-blue-900` (light bg) or `--color-white` (dark bg / over imagery). Tight leading (1.17–1.2). Read this; don't scan it.

**Editorial exception (Learning Center).** Long-form editorial surfaces (`home.php`, `single.php`, and similar blog/article templates) use **Mono 500** for the H1 instead of sans. The mono display ties article surfaces to the same technical voice that runs on labels, navigation, and metadata, and visually separates editorial pages from product hero sections. Same scale (`text-heading` → `text-display`), same `--color-blue-900`, same tight leading. Mono never appears on product-page hero headlines.

### 5.7 Header Navigation Link
**Role:** Top-level site navigation.

Mono 500, 14px, color `--color-blue-700`. No background, no border. Hover: color shifts to `--color-blue-500`. Active page: `--color-blue-500` with a 2px bottom border in `--color-red` *or* `--color-blue-500`.

### 5.8 Footer Navigation Link
**Role:** Tertiary footer links.

Mono 400, 12px, color `--color-blue-400`. Hover: `--color-white` on dark footer.

### 5.9 Card
**Role:** Content surface — machine card, article card, spec card.

Background `--color-white`. 1px border `--color-blue-200`. Zero border-radius. Padding 24px. No shadow. Hover: border shifts to `--color-blue-500`, optional 1px translation upward (-1px transform). Image inside: zero radius, sharp crop.

### 5.10 Product Hero Section
**Role:** Full-screen container showcasing a single machine.

Full-bleed background image (cinematic product photography). Centered or left-aligned stack: eyebrow (mono red), display headline (sans, white or onyx-blue depending on image), supporting subhead in body text, and a Primary + Secondary CTA pair with 16px gap. No overlays, no gradients unless legibility absolutely requires a subtle bottom-up dark gradient.

### 5.11 Spec Card / Technical Block
**Role:** Lists model specs, dimensions, capabilities.

`--color-blue-50` or `--color-white` background. 1px border `--color-blue-200`. Zero radius. Mono 500 for labels (11–14px, color `--color-blue-400`). Mono 500 for values (14–20px, color `--color-blue-700`). Grid layout, 2–4 columns. No shadow, no decoration.

### 5.12 Badge
**Role:** Status, category, or identifier — e.g., "NEW," "FLAGSHIP."

Mono 500, 11px, uppercase. `--color-blue-800` background, `--color-white` text. Zero radius. Padding: 4px 8px. For high-emphasis states, swap background to `--color-red`.

### 5.13 Form Input
**Role:** Text field, select, textarea.

`--color-white` background. 1px border `--color-blue-200`. Zero radius. Sans 400, 14px, color `--color-blue-700`. Padding 12px 16px. Focus: border `--color-blue-500`, 2px outline `--color-blue-500` at 2px offset. No shadow.

---

## 6. Imagery

The visual language depends on cinematic, high-fidelity machine photography. Every image is a hero shot:

- **Full-bleed** wherever possible. The machine is the subject; the viewport is the frame.
- **Sharp focus, clean color grading.** Natural and confident — never over-saturated, never warm-and-rugged. Cool, clean, slightly desaturated reads premium.
- **Real environments, idealized.** A finished machine on a polished shop floor; a panel being formed in clean light. Avoid mud, clutter, overt "industrial" tropes. Industrial-premium, not industrial-gritty.
- **Consistent crop language.** Either full-bleed environmental shots or perfectly centered/silhouetted product shots. Don't mix in lifestyle photography that breaks the showroom feel.
- **Motion in stills.** Subjects feel ready to operate — the calm before the run, not the chaos of the run.
- **Light image wells.** When a product photo (transparent or `object-contain`) sits in its own well, the well is `--color-blue-50` — a clean studio plate, never a dark fill. Dark navy behind a product shot reads as a night photo; the light tint keeps the showroom feel. (2026-06-09: standardized after the MACH II Family light wells were held up as the reference.)

---

## 7. Layout

- **Vertical scrolling presentation.** A page is a series of full-screen or near-full-screen blocks. Each block tells one thing.
- **Full-bleed by default.** Edge-to-edge sections; constrain inner text with a max-width when needed.
- **Centered or hard-left stacks.** Hero text + CTAs centered over imagery, or hard-left aligned to a grid line. Never freeform.
- **Thin, minimal header.** Non-imposing, technical, mono-labeled. Preserve maximum screen real estate for product visuals.
- **No visible page container.** The viewport is the frame.
- **Section transitions are seamless.** No decorative dividers; rely on color shifts (`--color-white` → `--color-blue-50` → `--color-blue-900`) to delineate sections — *or* a single hairline structural border (see §8).

---

## 8. Structural Borders

Borders are the system's primary depth tool. With shadows banned and radii at zero, hairline borders are how we communicate structure, hierarchy, and precision. Used correctly, they read as **technical drawing / blueprint / spec sheet** — the visual subtext of an engineered showroom.

### 8.1 The single rule

Every structural border is `1px solid var(--color-blue-200)` on light surfaces, or `1px solid var(--color-blue-700)` on dark surfaces (`--color-blue-800` / `--color-blue-900` backgrounds). One weight, one color per context. No exceptions.

### 8.2 Where borders belong

- **Section dividers.** A single full-bleed horizontal hairline marking the boundary between two sections.
- **Column dividers within a section.** A full-height vertical hairline between major content columns in a multi-column layout.
- **Card containment.** Cards, spec blocks, form inputs.
- **Active state indicators.** A 2px bottom border under the active nav item or selected tab — this is the only place a 2px border is permitted, and only in `--color-blue-500` or `--color-red`.

### 8.3 Where borders do not belong

- Around individual buttons (Secondary Ghost is the exception — its border is its identity).
- Around headings, paragraphs, or text blocks.
- As decoration — corner brackets, double lines, dashed strokes, gradients on borders.
- On every card in a grid when the grid itself already has dividers (don't double-fence).

### 8.4 Intersections are intentional

When borders meet, let them meet cleanly. A vertical column divider crossing a horizontal section divider produces a visible 1px × 1px intersection — embrace it. This is the blueprint feel. Do not fade, round, gap, or hide intersections.

### 8.5 The signature pattern

A page may use one or two of these per view, sparingly:

- **Full-bleed horizontal section divider.** `1px solid var(--color-blue-200)` running edge-to-edge of the viewport between sections.
- **Full-height column divider.** `1px solid var(--color-blue-200)` running the full height of a section, dividing 2 or 3 content columns.
- **Cross.** A vertical full-height divider intersecting horizontal dividers above and below — clean intersections, no styling.

### 8.6 Asymmetry over symmetry

A border on only one edge of a content block (e.g., a left-edge accent line on a quote, or a bottom-only divider under a section title) reads more confident and more engineered than a fully boxed-in container. Boxing every element = bureaucratic. Selective lines = architectural.

### 8.7 Light and dark contexts

| Context | Border color |
|---|---|
| On `--color-white` | `--color-blue-200` |
| On `--color-blue-50` | `--color-blue-200` |
| On `--color-blue-800` | `--color-blue-700` |
| On `--color-blue-900` | `--color-blue-800` |

The principle: borders are exactly one step lighter (on dark) or one step darker (on light) than the surface they sit on. Just visible, never loud.

### 8.8 Hover and active borders

- **Hover on a card or interactive surface:** border shifts from `--color-blue-200` to `--color-blue-500`. The shift is the entire hover affordance — no other change required.
- **Active nav / selected tab:** 2px bottom border in `--color-blue-500` or `--color-red`. This is the only permitted use of a 2px border.
- **Focus on an input:** border shifts to `--color-blue-500` plus a 2px outline at 2px offset (already specified in §5.13).

### 8.9 Never combine borders with…

- Drop shadows (banned globally).
- Border-radius (banned globally).
- Multiple weights in the same component.
- Decorative effects (dashes, dots, doubles, gradients).

---

## 9. Motion

- **Timing:** `--duration-fast` 150ms · `--duration-base` 200ms · `--duration-slow` 300ms.
- **Easing (shared):** `cubic-bezier(0.22, 1, 0.36, 1)` — both layers use the same curve so motion feels unified.
- **Hover:** Color shift, border shift, or 1px translation only. No scale-on-hover for cards (icon-buttons may use 1.05 sparingly).
- **`prefers-reduced-motion`:** All non-essential motion is disabled. Enforced, not optional.

### Motion System — two layers

The site uses two coordinated motion layers. They share the same easing curve and duration tokens, so the overall feel is consistent.

#### Layer A — in-place micro-interactions (transitions.dev)

Lives in `transitions.css`. The canonical source for anything that reacts to user input: accordions, dropdowns, modals, icon swaps, card resize, text/icon state swap, success flash, error shake, hover lifts, page slides, notification badges, number pop.

**Rule:** do not give a component a custom `transition` property for something Layer A already covers. Tune the semantic token instead.

#### Layer B — scroll entrances (animations.css + ScrollReveal.js)

Content arriving as you scroll into view. IntersectionObserver fires once per element; once visible, the class is not removed. Travel: 8–16px. Never bounce, never spring.

#### Shared vocabulary

| Token | Value |
|---|---|
| `--ease-out` | `cubic-bezier(0.22, 1, 0.36, 1)` |
| `--duration-fast` | 150ms |
| `--duration-base` | 200ms |
| `--duration-slow` | 300ms |

Travel distance: 8–16px. Both layers settle on the same ease.

#### Reveal variants

| `data-reveal` value | CSS class | Use |
|---|---|---|
| `fade` (default) | `reveal` | Default; prose, section intros, standalone blocks |
| `stagger` | `stagger` (on container) | Grids, lists, sequences — children cascade at 50ms increments |
| `image` | `reveal-image` | Product photography; starts at scale(1.04) — **requires `overflow-hidden` on the element** |
| `rule` | `reveal-rule` | Hairline / blueprint dividers; scales a 1px rule in from the left |
| `left` | `reveal-left` | Directional entry; use sparingly |
| `right` | `reveal-right` | Directional entry; use sparingly |
| `scale` | `reveal-scale` | Cards (the service-hub machine-card effect) |

#### How to apply

Add `data-reveal="<value>"` to the element. JS (`ScrollReveal.js`) maps the attribute to the corresponding CSS class on init. You can also add the class directly and skip the attribute.

```html
<!-- attribute form (JS resolves to class) -->
<h2 data-reveal="fade">Section title</h2>

<!-- class form (no JS dependency) -->
<div class="stagger">…</div>
```

#### The cardinal rule

**One reveal variant per section type, chosen to fit what it reveals.** Deliberately leaving sections unrevealed is correct when motion would fight existing behavior (carousels, accordions, interactive widgets) or obscure important content.

- **Never reveal-gate the hero, LCP element, or primary CTAs.** Users should not wait for the most important content.
- **Never use `display: none`.** Reveals work by offsetting an already-visible default state (opacity + transform). Reduced-motion, no-JS, bfcache restore, and headless crawlers all show content immediately without any class toggle.
- Don't apply the same fade variant to every section — that flattens the hierarchy. Use `stagger` for grids, `image` for photography, `fade` for prose intros, `scale` for cards.

#### prefers-reduced-motion

Enforced in both layers. Under `prefers-reduced-motion: reduce`, all scroll-reveal classes resolve instantly (opacity 1, transform none, transition none). No travel. No delay.

#### No new JS dependency

Vanilla `IntersectionObserver` + CSS only. No external library.

---

## 10. Do's and Don'ts

### Do
- Lead with full-bleed, high-quality machine photography in every major section.
- Use Mono for labels, eyebrows, buttons, navigation, specs, model numbers, badges.
- Use Sans for body copy and large display headlines.
- Maintain the 60/30/10 color rule. Saturated color is rare and intentional.
- Use `--color-blue-500` for primary CTAs and links. Reserve `--color-red` for a single high-emphasis moment per page.
- Use blue-tinted neutrals (`--color-blue-50` through `--color-blue-900`) for every "gray" surface. Pure gray is forbidden.
- Keep zero border-radius on every element, always.
- Use 1px hairline structural borders (`--color-blue-200` on light, `--color-blue-700` on dark) to communicate structure and hierarchy.
- Let borders intersect cleanly — full-bleed horizontals crossing full-height verticals is a signature move.
- Keep UI chrome minimal so the machine remains the hero.

### Don't
- Don't use border-radius. Ever. Anywhere.
- Don't use drop shadows. Use borders or color contrast instead.
- Don't use border weights other than 1px (2px is allowed only on active-state indicators).
- Don't use decorative borders — no dashes, dots, doubles, gradients, or corner brackets.
- Don't box in every element. Asymmetric, selective borders read as engineered; full boxing reads as bureaucratic.
- Don't use font weights outside 400 and 500.
- Don't use Mono for body copy paragraphs.
- Don't use `--color-blue-500` for body text or large background fills.
- Don't use `--color-red` for body text, headings, or decorative shapes — only for single-moment accents.
- Don't introduce a third font, a third color family, or a third weight.
- Don't use pure-neutral grays (`#cccccc`, `slate-*`, `gray-*`). Use the blue-tinted scale.
- Don't use rugged/industrial photography clichés (mud, sparks, grime, harsh orange light).
- Don't use uppercase on hero/display headlines. Uppercase belongs to mono labels and eyebrows only.
- Don't use serif fonts, expressive display fonts, or italics.
- Don't use gradients on UI. A subtle image-overlay gradient for legibility is the only exception.

---

## 11. Agent Prompt Guide

When generating new components or pages with an LLM, anchor every prompt in these tokens. Pre-tested prompts:

### 11.1 Quick token reference
- **Heading text:** `--color-blue-900` (or `--color-white` on dark)
- **Secondary / technical text:** `--color-blue-700`
- **Body text:** `--color-blue-600` (or `--color-blue-200` on dark)
- **Background (light):** `--color-white` or `--color-blue-50`
- **Background (dark):** `--color-blue-800` or `--color-blue-900`
- **Primary CTA:** `--color-blue-500` bg, `--color-white` text, Mono 500
- **Secondary CTA:** transparent bg, `--color-blue-700` border + text, Mono 500
- **Accent / single-emphasis:** `--color-red`
- **Border:** `--color-blue-200`
- **Mono font:** `--font-mono` (Noto Sans Mono, weights 400/500)
- **Sans font:** `--font-sans` (Noto Sans, weights 400/500)
- **All radii:** 0
- **All shadows:** none

### 11.2 Example component prompts

1. **Machine Hero Section.** "Full-bleed hero section with a high-resolution photograph of an SSQ II MultiPro rollformer on a clean shop floor. Overlay a left-aligned stack at 96px from bottom-left edge: a small eyebrow 'MODEL · SSQ II MULTIPRO' in Noto Sans Mono 500, 11px, uppercase, color `--color-red`, preceded by a 4px square red dot. Below it, a display headline 'Precision Rollforming, Built to Order' in Noto Sans 500, 48px, color `--color-white`, line-height 1.17. Below that, a 14px body line in Noto Sans 400, color `--color-blue-200`. Below that, a row of two buttons with 16px gap: a primary CTA 'Configure' with `--color-blue-500` background, `--color-white` text, Noto Sans Mono 500 14px, zero radius, 12px × 24px padding; and a secondary ghost 'View Specs' with transparent background, 1px `--color-white` border, `--color-white` text, same font and padding."

2. **Spec Card.** "Build a spec card with `--color-white` background, 1px `--color-blue-200` border, zero border-radius, no shadow, 32px padding. Inside, a section eyebrow 'SPECIFICATIONS' in Noto Sans Mono 500, 11px, uppercase, color `--color-red`. Below it, a 4-column CSS grid with 24px gap. Each cell contains a label in Noto Sans Mono 500, 11px, uppercase, color `--color-blue-400`, and a value below it in Noto Sans Mono 500, 20px, color `--color-blue-700`."

3. **Primary Button.** "Generate a primary button. `--color-blue-500` background, `--color-white` text. Font: Noto Sans Mono, 500 weight, 14px. Zero border-radius. Padding 12px top/bottom, 24px left/right. Hover state: background darkens 10%. Focus state: 2px `--color-blue-500` outline at 2px offset."

4. **Section with eyebrow and headline.** "Section with `--color-blue-50` background and 96px vertical padding. Centered max-width 960px container. Inside, top to bottom with 16px gaps: an eyebrow 'INNOVATION · ROLLFORMING' in Noto Sans Mono 500, 11px, uppercase, color `--color-red`; a section heading 'Engineered for the Job Site' in Noto Sans Mono 500, 28px, color `--color-blue-700`; a body paragraph in Noto Sans 400, 14px, color `--color-blue-600`, line-height 1.43, max-width 640px."

---

## 12. Similar Brands

- **Tesla** — Single accent color used as ignition, achromatic neutrals (we replace with blue-tinted neutrals), full-bleed product imagery.
- **Toyota Tundra / Cybertruck** — Industrial-premium, hard angles, sharp corners, technical typography. The reference for our visual posture.
- **Apple** — Restraint as premium signal, two-weight typography discipline, product-as-hero.
- **Linear / Stripe / Vercel** — Brand-tinted neutral scales, premium-tech-product feel.
- **Sonos** — Pristine product shots on clean achromatic-ish backgrounds, minimal functional UI.

---

## 13. Quick Start

### 13.1 CSS custom properties

```css
:root {
  /* Brand-tinted neutral scale */
  --color-blue-50:  #F4F8FB;
  --color-blue-100: #E4ECF3;
  --color-blue-200: #C8D6E4;
  --color-blue-300: #9BB1C7;
  --color-blue-400: #5A7691;
  --color-blue-500: #0078C2; /* primary brand */
  --color-blue-600: #3F5870;
  --color-blue-700: #26384B;
  --color-blue-800: #142235;
  --color-blue-900: #0A1322;

  /* Pure neutrals */
  --color-white: #FFFFFF;
  --color-black: #000000;

  /* Accent */
  --color-red: #CD1018;

  /* Typography — families */
  --font-sans: 'Noto Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --font-mono: 'Noto Sans Mono', ui-monospace, SFMono-Regular, Menlo, monospace;

  /* Typography — scale */
  --text-caption:    11px;  --leading-caption:    1.2;
  --text-body:       14px;  --leading-body:       1.43;
  --text-heading-sm: 20px;  --leading-heading-sm: 1.4;
  --text-heading:    28px;  --leading-heading:    1.29;
  --text-heading-lg: 40px;  --leading-heading-lg: 1.2;
  --text-display:    48px;  --leading-display:    1.17;

  /* Typography — weights */
  --font-weight-regular: 400;
  --font-weight-medium:  500;

  /* Spacing */
  --spacing-4:  4px;
  --spacing-8:  8px;
  --spacing-16: 16px;
  --spacing-24: 24px;
  --spacing-32: 32px;
  --spacing-40: 40px;
  --spacing-48: 48px;
  --spacing-64: 64px;
  --spacing-96: 96px;

  /* Layout */
  --card-padding: 24px;
  --section-padding-y: 96px;
  --element-gap: 16px;

  /* Border radius — all zero */
  --radius: 0;
}
```

### 13.2 Tailwind v4 `@theme`

```css
@theme {
  /* Brand-tinted neutral scale */
  --color-blue-50:  #F4F8FB;
  --color-blue-100: #E4ECF3;
  --color-blue-200: #C8D6E4;
  --color-blue-300: #9BB1C7;
  --color-blue-400: #5A7691;
  --color-blue-500: #0078C2;
  --color-blue-600: #3F5870;
  --color-blue-700: #26384B;
  --color-blue-800: #142235;
  --color-blue-900: #0A1322;

  --color-white: #FFFFFF;
  --color-black: #000000;
  --color-red:   #CD1018;

  /* Typography */
  --font-sans: 'Noto Sans', ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  --font-mono: 'Noto Sans Mono', ui-monospace, SFMono-Regular, Menlo, monospace;

  --text-caption:    11px;
  --text-body:       14px;
  --text-heading-sm: 20px;
  --text-heading:    28px;
  --text-heading-lg: 40px;
  --text-display:    48px;

  --leading-caption:    1.2;
  --leading-body:       1.43;
  --leading-heading-sm: 1.4;
  --leading-heading:    1.29;
  --leading-heading-lg: 1.2;
  --leading-display:    1.17;

  /* Spacing */
  --spacing-4:  4px;
  --spacing-8:  8px;
  --spacing-16: 16px;
  --spacing-24: 24px;
  --spacing-32: 32px;
  --spacing-40: 40px;
  --spacing-48: 48px;
  --spacing-64: 64px;
  --spacing-96: 96px;

  /* Border radius — all zero */
  --radius-sm: 0;
  --radius-md: 0;
  --radius-lg: 0;
  --radius-xl: 0;
  --radius-2xl: 0;
  --radius-3xl: 0;
  --radius-full: 0;

  /* Shadows — none */
  --shadow-sm: none;
  --shadow:    none;
  --shadow-md: none;
  --shadow-lg: none;
  --shadow-xl: none;
}
```
