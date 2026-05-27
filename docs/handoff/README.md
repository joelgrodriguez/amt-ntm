# NTM IA Rebuild — Agent Handoff

You are implementing an information-architecture change in the `amt-ntm` WordPress theme. The new top-level navigation replaces five label-driven mega-menus (Machines, Profiles, Resources, Learning Center, Service & Support) with **four action-driven labels**: Get started, Choose your machine, How to buy, Get owner support.

This is mostly **logic and organization work**. It is not a redesign. Do not introduce new visual components, new CSS systems, new design tokens, new color palettes, or new typography. Use what already exists in the theme.

## Scope, sharply defined

What you ARE shipping in this pass:

1. **The new mega menu** (desktop + mobile) wired to the new four-action structure
2. **A scaffolded PHP page template** for each new landing page — file exists, registered, basic structure, but body content is a clear placeholder ("Coming soon" / "WIP")
3. **The homepage hero CTA swap** to point at the four action paths
4. **Active-state highlighting** working across the new structure

What you are NOT shipping in this pass:

- ❌ **No real content on the new pages.** Each new page should just say something like "🚧 In development — placeholder for [Page name]". A short heading + a paragraph is enough. Do NOT write hero copy, value-prop card text, FAQ entries, or any production content yet.
- ❌ **No Gutenberg blocks for the new pages.** Build them as classic PHP page templates (e.g. `page-start-here.php`), assignable via the WordPress page admin → Page Attributes → Template dropdown. Do not author content in the block editor.
- ❌ **No new visual components, design tokens, or CSS files.**

The point of this pass is: the nav works, every link goes somewhere real, and there's a placeholder file ready for content to be added later in a follow-up.

---

## The copy-paste prompt (start here)

> You're working in the `amt-ntm` WordPress theme on the `amt-ntm` repo. We need to rebuild the site's information architecture and scaffold placeholder templates for the new landing pages. **This pass is nav + nav flow + empty templates only. Do not author real page content yet.**
>
> Read `docs/handoff/README.md` first, then `01` through `07` in order. The full visual rationale lives in `docs/presentations/navigation-flow/` — open `index.html` in a browser if you need to see what the new mega menus should look like.
>
> **What "done" means for this pass:**
> 1. New four-action mega menu (desktop + mobile) renders and every link goes somewhere real
> 2. Each new page exists in WordPress with a clear `🚧 In development — [Page name]` placeholder
> 3. Each new page uses a custom PHP template file (e.g. `app/page-start-here.php`), NOT Gutenberg blocks
> 4. Homepage hero CTAs point at the four action paths
> 5. Active-state highlighting works for the new structure
>
> **What "done" does NOT mean for this pass:**
> - Writing hero copy, value-prop card text, FAQ entries, or any production content on the new pages
> - Building Gutenberg block layouts for any new page
> - Designing anything new (no new CSS files, design tokens, colors, or components)
>
> Each new page should be a PHP template file with `Template Name:` in the header so it's assignable in the WP page admin. The template body can be as simple as a header, the page title, and a `🚧 In development` note. Production content gets added in a later pass.
>
> Work on a new branch off `dev`. Follow the worktree agent rules in `AGENTS.md` / `CLAUDE.md`: create a Superset task, move it to In Progress before editing, commit clearly, move to In Review when done, and **do not merge into `dev`**. Maestro lands it.
>
> **Stop and ask the user before:**
> - Introducing any new visual component, CSS file, or design token
> - Changing any URL of an existing published page (slug changes break SEO)
> - Deleting any existing PHP page template
> - Touching the homepage hero design (the configurator demotion is a logic change, not a redesign)
> - Renaming or moving an existing post type or taxonomy
> - Writing more than placeholder content on a new page
>
> **Scope ground truth:**
> - The site lives at `/Users/jrodriguez/Development/Kinsta/public/newtech/` (DevKinsta).
> - All theme code lives at `wp-content/themes/amt-ntm/app/`.
> - The mega menu data source is `app/inc/desktop-nav.php` (`Standard\Nav\get_desktop_nav()`).
> - The mega menu render template is `app/templates/parts/mega-menu.php`.
> - The mobile menu is `app/templates/parts/mobile-menu.php` and `mobile-menu-panel.php` (plus `app/inc/mobile-nav.php`).
> - Existing custom page templates (`app/page-machines.php`, `app/page-service-hub.php`, etc.) are the pattern to follow.
> - 778 published items already exist across 12 post types — the inventory CSVs are in `docs/handoff/inventory/`.
>
> Build in this order:
> 1. **Mega menu rebuild** (Phase 1 in `04-pages-to-build.md`) — desktop + mobile
> 2. **Placeholder templates** for the 9 new pages — create the PHP files, register `Template Name:`, create the WP pages, assign the templates
> 3. **Wire mega menu links** to the new placeholder pages
> 4. **Homepage hero CTA swap**
> 5. **Active-state verification** across representative URLs
>
> When all of that works end-to-end and every mega-menu link lands on either an existing page or a placeholder, you're done.

---

## Read these files in order

| File | Purpose |
|---|---|
| `01-context.md` | What this project is, where the code lives, what tools to use |
| `02-the-change.md` | The IA decision in one place: four labels, two lanes per flyout |
| `03-mega-menu-spec.md` | Exact menu structure, link by link, with every URL |
| `04-pages-to-build.md` | New pages to create — purpose, template, content blocks |
| `05-pages-to-reuse.md` | Existing URLs the agent must NOT touch |
| `06-aesthetic-rules.md` | What is and isn't in scope for design |
| `07-verification.md` | How you prove your work is correct before handing back |
| `inventory/*.csv` | The crawled WP database — every published item across 12 post types |

## Visual reference (read with a browser, not as text)

`docs/presentations/navigation-flow/` is a stakeholder-facing deck. It is your **rationale and visual source of truth**, not your implementation spec. Open it in a browser:

- `index.html` — overview of the four action paths
- `megamenu.html` — what each flyout should literally look like
- `sitemap-flow.html` — the new tree
- `content-gap.html` — inventory + gap analysis

The implementation spec is the markdown files. If the deck and the markdown ever disagree, **the markdown wins** — flag the conflict to the user.
