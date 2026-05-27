# 02 — The Change

## Scope of this pass

**This pass = nav + nav flow + empty templates.** You're shipping the new mega menu structure and a set of PHP page templates with placeholder bodies. You are NOT writing real page content. That happens in a later pass.

Concretely:

- Mega menu rebuilt with the 4 new action labels (desktop + mobile)
- Each new page exists in WordPress with a custom PHP template assigned
- Each new page body is a clear `🚧 In development — [Page name]` placeholder, not production content
- Templates are **classic PHP files** (not Gutenberg block patterns)
- Every mega-menu link lands somewhere real (existing page or placeholder)

## The decision in one paragraph

Replace the current label-driven top navigation (Machines / Profiles / Resources / Learning Center / Service & Support) with **four action-driven labels**: **Get started**, **Choose your machine**, **How to buy**, **Get owner support**. Each label opens a mega-menu flyout with three groups. **Top-level labels are not clickable** — only items inside the flyout are. Each flyout has a **browse lane** (existing catalog pages, kept loud) and a **guided lane** (quizzes, calculators, comparisons).

## Why we're doing it

The current site is organized like a catalog. Visitors arrive with a job to do — "see if this fits my business," "find the right machine," "buy the thing," "get support" — but the nav makes them figure out the org chart instead. The new IA is the job-to-be-done expressed as labels.

## The five rules of the new nav

1. **Four top-level labels.** Get started, Choose your machine, How to buy, Get owner support.
2. **Top labels are pure labels, not links.** Hovering opens the flyout. Tapping (mobile) opens the panel. There's no `/get-started/` page that the top label clicks through to.
3. **Every flyout has two equal lanes.** A browse lane (existing catalog/category pages) and a guided lane (quiz, calculator, or playlist). Browsers and unsure visitors get equal treatment.
4. **Anchor item per group.** The first item in each group is the "loudest" — visually larger and color-tinted — and is the de-facto entry point for that group.
5. **Existing URLs stay alive.** `/machines/`, `/roof-wall-panel-machines/`, `/seamless-gutter-machines/`, `/profiles/`, every product page, every Learning Center post — they all keep their URLs. They get reached *through* the new flyouts, not replaced by them.

## Section colors (already in the deck CSS, reuse the tokens)

These colors already exist in the theme as design tokens / Tailwind utilities. Don't invent new ones.

- **Get started** — green
- **Choose your machine** — blue
- **How to buy** — orange
- **Get owner support** — aqua / teal

If the theme doesn't already expose these four accent colors, ask the user before adding any.

## What the old top nav had (for reference)

From `app/inc/desktop-nav.php`, current `get_desktop_nav()['items']`:

1. `mega` · id `machines` · label "Machines" · type `tabbed-products`
2. `mega` · id `profiles` · label "Profiles" · type `tabbed-profiles`
3. `link` · label "Resources"
4. `mega` · id `learning-center` · label "Learning Center" · type `tabbed-content`
5. `link` · label "Service & Support"

Plus a utility rail: "Service & Repair", "Build & Finance", "Contact".

> **Note:** the current `mega-menu.php` has temporary code that only renders the `machines` panel. The other panels are defined in data but filtered out at render. Your rebuild replaces the entire `items` array — that temporary filter no longer matters.

## What the new top nav looks like

5 mega panels become 4. Three of them (Get started, How to buy, Get owner support) are new in label and structure. The fourth (Choose your machine) replaces what was "Machines". Everything from the old Profiles / Resources / Learning Center / Service & Support panels moves *into* one of the four new flyouts. Utility rail stays minor — just "Talk to a specialist" as the persistent CTA on the right side of the header.

The exact tree, link by link, is in **`03-mega-menu-spec.md`**.

## Homepage hero (logic-only change)

The current homepage routes into the configurator from the hero. The new homepage should route into the four action paths. **This is a logic change, not a design change** — use the existing hero component, just swap the four CTAs to the four action labels and demote the configurator to a secondary CTA further down the page.

If the hero component doesn't support four CTAs cleanly, stop and ask before changing its layout.
