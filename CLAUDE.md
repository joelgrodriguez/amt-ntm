# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Build Commands

```bash
npm run dev      # Start Vite dev server with HMR (port 5173)
npm run build    # Production build to app/dist/
npm run preview  # Preview production build
```

## Architecture

This is a WordPress classic theme using Vite + Tailwind CSS v4 for modern asset bundling.

### Directory Structure

The repository is split into two parts:
- **Root**: Build tooling (`package.json`, `vite.config.js`, `node_modules/`)
- **`app/`**: WordPress theme files (PHP, assets, templates)

A symlink `standard-theme` → `standard-press/app` allows WordPress to load the theme.

### Asset Pipeline

**Entry points:**
- `app/resources/js/_app.js` - Main JS entry, imports CSS and initializes modules
- `app/resources/css/_app.css` - Main CSS entry, imports Tailwind and theme styles

**Vite integration:**
- Development: Assets served from Vite dev server with HMR
- Production: Vite manifest (`app/dist/.vite/manifest.json`) maps entry points to hashed output files
- `app/functions.php` handles both modes via `is_vite_dev()` detection

**CSS architecture (Tailwind v4 CSS-first):**
- `app/resources/css/theme.css` - Design tokens via `@theme` (colors, fonts, spacing)
- `app/resources/css/base.css` - Base element styles
- `app/resources/css/components.css` - Reusable component classes
- `app/resources/css/utilities.css` - Custom utility classes
- `app/resources/css/animations.css` - Animation definitions
- `app/safelist.txt` - WordPress block classes preserved in production builds

### PHP Structure

All PHP uses `declare(strict_types=1)` and the `Standard` namespace.

**Includes loaded via `app/functions.php`:**
- `app/inc/vite.php` - Vite integration (dev server detection, asset loading)
- `app/inc/setup.php` - Theme supports, nav menus (primary, mobile, footer)
- `app/inc/sidebars.php` - Widget areas
- `app/inc/fonts.php` - Bunny Fonts (IBM Plex family)
- `app/inc/icons.php` - SVG icon loader with caching
- `app/inc/walkers/` - Custom nav menu walkers

**Template hierarchy:**
- Standard WordPress templates: `app/header.php`, `app/footer.php`, `app/index.php`, `app/single.php`, `app/page.php`, `app/search.php`, `app/404.php`
- Template parts in `app/templates/parts/`

### JavaScript Modules

Located in `app/resources/js/modules/`:
- `MobileMenu.js` - Full-width mobile menu with header state management
- `ScrollReveal.js` - Scroll-based reveal animations

### Icons

SVG icons in `app/assets/icons/`. Use the `icon()` helper in templates:
```php
<?php icon('arrow--right', ['class' => 'w-5 h-5']); ?>
```

## Development Notes

- Theme requires PHP 8.0+ and WordPress 6.0+
- Nav menus: `primary` (desktop), `mobile`, `footer`

### Vite Dev Server Setup

The Vite dev server URL is **auto-detected**. When you run `npm run dev`:

1. Vite automatically detects your local network IP
2. Writes the URL to `app/.vite-dev-server`
3. PHP reads this file to load assets from the dev server

No manual configuration needed. Works automatically with Docker/DevKinsta or localhost.
