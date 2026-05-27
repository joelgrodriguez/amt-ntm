# 06 — Aesthetic Rules

## The one-liner

This is an **information architecture** project, not a redesign. Use what already exists in the theme. If you find yourself opening a new CSS file or sketching a new component, **stop and ask**.

## Scope reminder for this pass

This pass ships **nav + nav flow + empty template scaffolds**. You are not writing page content. You are not designing page layouts. New pages get a placeholder body and nothing more. See `04-pages-to-build.md` for the exact placeholder template.

If you find yourself reaching for `hero-asymmetric.php`, `value-prop-cards.php`, `faq-accordion.php`, or any other content-building template part to fill out a new page — **stop**. That's the next pass, not this one.

## In scope

✅ Restructuring the data in `app/inc/desktop-nav.php` and `app/inc/mobile-nav.php`
✅ Editing `app/templates/parts/mega-menu.php` to handle a new `type` (e.g. `flyout-groups`)
✅ Creating new PHP page templates with the **placeholder body** described in `04-pages-to-build.md` — header, page title, "🚧 In development" marker, footer. Nothing more.
✅ Creating the matching WordPress pages via WP-CLI and assigning the templates
✅ Swapping the four CTAs on the homepage hero (logic, not layout)
✅ Adding `current_paths` arrays in `desktop-nav.php` so active-state highlighting works for the new structure
✅ Using minimal Tailwind utility classes in the placeholder templates

## Out of scope

❌ **Writing real content on the new pages.** No hero copy, no value-prop card text, no FAQ entries, no body copy beyond "🚧 In development".
❌ **Building Gutenberg blocks or block patterns** for any new page. New pages are classic PHP templates only.
❌ **Pulling in content-building template parts** on the new pages: `hero-asymmetric.php`, `value-prop-cards.php`, `faq-accordion.php`, `comparison-table.php`, etc. Those are for the next pass.
❌ New color palettes, accent colors, or color tokens
❌ New typography, fonts, or type scales
❌ New CSS files (don't add `app/resources/css/megamenu-v2.css` or similar)
❌ New design tokens in `theme.css`
❌ New JavaScript modules (the existing `MobileMenu.js` should still work — if it doesn't, ask before refactoring)
❌ New icons (use existing `icon()` helper with existing SVGs in `app/assets/icons/`)
❌ New animation systems or motion patterns
❌ Redesigning any existing template part
❌ Redesigning the homepage hero layout (CTA swap = logic, layout change = design)
❌ Adding any third-party UI library
❌ Adding new fonts via `app/inc/fonts.php`

## Tailwind utility usage

Tailwind utility classes are fine — the project is Tailwind-first per `CLAUDE.md`. Use them in the new PHP templates. **Don't write new component classes** in `components.css` unless you ask first.

## Color conventions for the four sections

The deck uses these accent colors for the four sections:

- Get started → green
- Choose your machine → blue
- How to buy → orange
- Get owner support → aqua / teal

Before using them in the new mega menu, check whether the theme already exposes these as design tokens (look in `app/resources/css/theme.css`). If yes, reuse them. If no, **ask the user** before adding new tokens.

## Mobile

The mobile nav already exists (`app/templates/parts/mobile-menu.php`, `mobile-menu-panel.php`, JS module `MobileMenu.js`). Restructure its data, **don't redesign its UI**. If the existing mobile menu can't visually represent the new four-section structure without significant changes, stop and ask.

## When in doubt

Ask the user. The cost of asking is 5 minutes. The cost of designing something the user didn't want and the team has to revert is hours.

---

## What "logic vs aesthetics" means in this project

| Task | In scope this pass? |
|---|---|
| Renaming a top-level mega menu label | **Yes** ✅ |
| Reordering links within a flyout | **Yes** ✅ |
| Changing what URL a link points to | **Yes** ✅ |
| Creating a new PHP page template with a placeholder body | **Yes** ✅ |
| Creating a WP page and assigning a template via WP-CLI | **Yes** ✅ |
| Swapping which CTA the homepage hero shows | **Yes** ✅ |
| Updating `current_paths` for active-state highlighting | **Yes** ✅ |
| Writing real content for `/start-here/` (or any new page) | **No** ❌ — next pass |
| Building hero / value-prop / FAQ blocks on a new page | **No** ❌ — next pass |
| Using Gutenberg blocks for any new page content | **No** ❌ — templates only |
| Adding a new accent color to the mega menu | **No** ❌ — ask first |
| Adjusting padding or spacing of the mega menu container | **No** ❌ — ask first |
| Designing a new hero variant for the new landing pages | **No** ❌ — ask first |
| Changing animation timings | **No** ❌ — ask first |
| Introducing a new SVG icon | **No** ❌ — ask first |
| Changing the typography scale | **No** ❌ — ask first |

The agent should be able to ship the entire pass without writing a single line of CSS or a single paragraph of real page copy.
