# Typography System Quick Reference

Use this guide every time you write or edit type in PHP, CSS, or class lists.

## Philosophy: Tech / Spec-Sheet

Two type families. **Sans is the default. Mono is a semantic marker.**

- `--font-sans` (Noto Sans) — prose, headlines, UI, anything readable
- `--font-mono` (Noto Sans Mono) — labels, eyebrows, model numbers, units, captions, footnotes — *anything that says "this is data"*

The brand is industrial / engineered / B2B premium. Mono earns its keep by tagging the technical surface area: capacities, gauges, SKUs, eyebrows above sections. Sans does everything else. This is the same pattern used by Linear, Vercel, Stripe, Resend.

**If you can't justify why mono belongs there, use sans.**

## The Two Jobs Mono Does

Mono is allowed *only* in these roles:

| Role | Examples |
|------|----------|
| Eyebrow / section label | `SECTION 02 / FORMING`, `LATEST ARTICLES` |
| Tab / nav meta label | Mega-menu sidebar eyebrow, breadcrumbs, "Previous / Next" |
| Product / model code | `MACH II 5.5`, `SSQ II 19`, `XCD 2,8-4/35-100E` |
| Spec value | `24" / 610mm`, `22 ga`, `$199/mo`, gauge / speed / capacity numbers |
| Caption / metadata | Image captions, footnotes, "Posted on …", footer column headers |
| Tag / badge / chip | Category tags on cards, status badges |
| Eyebrow-style CTA | "View All →", small linkbacks above grids |

If the use case isn't on this list, it's sans.

## What Goes In Sans (Everything Else)

- Body copy, paragraphs, descriptions
- All headlines and headings (h1–h6) when they're *content*, not display brand voice
- Card titles (post cards, product cards, profile cards)
- Buttons (`.btn`, all sizes)
- Nav links, mega-menu triggers, mobile menu items
- Form labels, table cells, list items
- Image-placeholder text ("Machine image", "No image")

**Hero / display headlines are the only sans headings that *may* opt into mono** — and only when the page is a brand-voice page (front page hero, campaign template). Article titles, section h2s, card titles → sans.

## Discipline Rules

These are what separate the system from a vibes-driven mess:

1. **Mono never runs longer than ~6 words.** It's slow to read. Long mono = bad.
2. **Mono is never a body paragraph.** Ever. Not in articles, not in CTAs, not in product descriptions.
3. **One eyebrow per section, max.** Not every block needs a label.
4. **Numbers in prose stay in sans.** Numbers in spec contexts go mono. ("Founded in 1991" = sans. "22 ga / 0.76mm" = mono.)
5. **One weight per font.**
   - Sans: `font-normal` (400) for body, `font-medium` (500) for UI/titles, `font-semibold` (600) for headlines, `font-bold` (700) for display hero only.
   - Mono: `font-medium` (500) only. Never bold mono. Never light mono.
6. **Tracking is the secret.**
   - Display sans (`text-3xl`+) → `tracking-tight`
   - Mono eyebrows (`text-xs`, `text-[11px]`) → `tracking-widest`
   - Mid mono → default tracking, no opinion needed
7. **Case is part of the identity.** Mono eyebrows + tags + meta → uppercase. Mono spec values + model codes + captions → mixed case (don't shout numbers).

## Token Aliases (semantic names)

In `tokens.css`:

```css
--font-sans: "Noto Sans", ui-sans-serif, system-ui, sans-serif;
--font-mono: "Noto Sans Mono", ui-monospace, monospace;

/* Semantic aliases — use these in role-specific CSS */
--font-display: var(--font-sans);  /* Headlines, page titles, hero */
--font-body:    var(--font-sans);  /* Paragraphs, UI, defaults */
--font-label:   var(--font-mono);  /* Eyebrows, tags, captions, specs */
```

Use the semantic alias in CSS that defines a role (`.section-eyebrow`, `.spec-value`). Use the raw `--font-sans` / `--font-mono` only where you're tuning a one-off rendering.

## Class Cheat Sheet

| Want | Class combo |
|------|-------------|
| Section eyebrow | `font-mono text-xs font-medium uppercase tracking-widest text-blue-400` |
| Tag / category chip | `font-mono text-[11px] font-medium uppercase tracking-widest text-blue-500` |
| Spec value | `font-mono text-base font-medium text-blue-900` |
| Model code | `font-mono text-sm font-medium text-blue-700` |
| Caption under image | `font-mono text-xs text-blue-400` |
| "View All →" linkback | `font-mono text-xs font-medium text-blue-500 uppercase tracking-wider` |
| Hero display headline | `font-sans text-5xl lg:text-6xl font-semibold tracking-tight text-blue-900` |
| Page H1 | `font-sans text-4xl lg:text-5xl font-semibold tracking-tight` |
| Section H2 | `font-sans text-2xl lg:text-3xl font-semibold tracking-tight` |
| Card title | `font-sans text-lg font-medium text-blue-900` |
| Body | (no class needed — base sets `font-sans` 400 on `<body>`) |
| Button label | `font-sans font-medium` (already in `.btn`) |

(`font-sans` is rarely needed in markup since `<body>` defaults to it. Strip redundant `font-sans` classes when found.)

## Common Migrations (when sweeping)

| Currently | Should be | Reason |
|-----------|-----------|--------|
| `font-mono` on card title (post / product / profile) | sans (drop class, or `font-medium`) | Card title is content, not display brand voice |
| `font-mono` on `.btn` | sans (already in `.btn`) | Buttons are UI, not labels |
| `font-mono` on placeholder text ("Machine image") | sans, drop the class | Utility text, not branded |
| `font-mono` on mid-page H3 inside content | sans, `font-medium` | Content hierarchy, not display |
| `font-mono` on prose H1 / H2 (article titles, page heads) | sans, `font-semibold tracking-tight` | Headlines are content, not brand voice |
| `font-mono` on "View All", linkbacks, "Previous Next" | **stay mono**, uppercase, tracked | Meta navigation = label role |
| `font-mono` on eyebrows / category tags | **stay mono**, uppercase, `tracking-widest` | Eyebrow is the canonical mono role |
| `font-mono` on spec values, model codes, prices | **stay mono** | Spec / data = the whole point of mono |

## Anti-Patterns

- ❌ Mono on a paragraph longer than one short line
- ❌ Mono `font-bold` (use `font-medium`)
- ❌ Mono lowercase eyebrow (eyebrows are uppercase + tracked, always)
- ❌ Sans uppercase eyebrow (give that role to mono — it's what mono is for)
- ❌ Multiple eyebrows stacked in one section
- ❌ Adding `font-sans` redundantly when body already inherits it
- ❌ Mixing `font-mono` and `font-sans` mid-sentence to "stylize a number" — let typography rules carry that, not inline switches

## Quick Decision Tree

```
Is the text…
├─ a model number, SKU, capacity, gauge, speed, dimension, price? → MONO
├─ an uppercase tracked eyebrow / tag / breadcrumb / "View All"?  → MONO
├─ a caption, footnote, image-overlay meta?                       → MONO
├─ a hero / display headline on a brand-voice page?               → sans + tracking-tight (mono only if explicitly a brand campaign)
└─ everything else (body, headings, cards, UI, buttons, nav)      → SANS
```
