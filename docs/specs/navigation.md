# Behavior spec: navigation

<!-- admiral:auto — appended on land, newest first. Read top-down for current behavior. -->

## Client-reported: the desktop mega menu looks broken on standard laptop screens (14", Windows scaling). Reproduced with Playwright at 1280×620, 1024×550, and 1440×750. Three confirmed root causes, all in the theme's desktop header/mega-menu layer:

1. **Panel folds mid-card and scrolls internally.** `.mega-panel` (`app/resources/css/layout/mega-menu.css`) is `height: auto; max-height: calc(100vh - 4rem)`. On short viewports the Choose Your Machine panel content (~890px) exceeds the cap (~790px), so the second card row clips at the fold and the content area scrolls. Decision (Joel): the `tabbed-machines` panel should extend **full-height to the viewport bottom** on screens where content doesn't fit, with content scrolling inside. Short flyout panels (`flyout-groups`) keep the current content-height behavior.
2. **Horizontal scrollbar from classic scrollbars.** Measured `.mega-panel__content` `scrollWidth 1020 > clientWidth 1008` — when the vertical scrollbar appears, its width isn't reserved, so a horizontal scrollbar spawns. Invisible on macOS overlay scrollbars; ugly and always-visible on Windows (that's the scrollbar in the client's screenshot). Fix with `scrollbar-gutter: stable` on the scrolling areas (`.mega-panel__content`, `.mega-panel__sidebar`) and make sure nothing in the grid hard-blocks shrink (`minmax(0, 1fr)` columns / `min-width: 0` where needed).
3. **Header CTA wraps at lg widths.** "Talk To A Specialist" (utility rail in `app/header.php`) wraps to two lines below ~1150px (measured height 58px at 1024). Add `whitespace-nowrap` and create room at `lg` by tightening nav trigger padding / rail gap (`px-5`, `gap-10`) with `lg:`→`xl:` steps. Header must fit at 1024px with zero overflow — the desktop header shows from `lg:` (1024) up.

Also at 1024 the in-card "Build & Quote" buttons wrap to two lines (3-col `.mega-product-grid` gives ~229px cards). Prefer dropping the machines grid to 2 columns below `xl` over letting CTAs wrap — with the full-height scrolling panel the extra row height is fine. Use `100dvh` (with `100vh` fallback) for the panel height math.

Files: `app/resources/css/layout/mega-menu.css`, `app/header.php`, possibly `app/resources/css/woo/product-card.css` and `app/templates/parts/mega-menu.php` (panel-type modifier class).

Design rules: mobile-first Tailwind (`lg:`/`xl:` up, never `max-*:`); Tailwind-first, custom CSS only where utilities can't express it (the mega-menu.css layer already exists for this); read `.agents/skills/spacing-system.md` before layout changes. — #89
*Landed 2026-07-13 · type: bugfix*

- Choose Your Machine panel extends to the viewport bottom on laptop screens (no mid-card fold, no dead gap below short tabs on tall screens)
- No horizontal scrollbar in the panel with classic (Windows-style) scrollbars; verify by forcing non-overlay scrollbars or asserting `scrollWidth <= clientWidth` on `.mega-panel__content` after opening
- 'Talk To A Specialist' never wraps; desktop header fits without overflow at 1024, 1280, 1440px
- Card "Build & Quote" CTAs don't wrap to two lines at 1024–1280px
- Flyout panels (New to Rollforming?, How To Buy, Get Owner Support) still size to content and look unchanged at 1440px+
- npm run build passes

**Why:**

(fill on land)
