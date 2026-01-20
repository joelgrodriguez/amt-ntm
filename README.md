# Standard Press

Modern WordPress theme starter with Vite and Tailwind CSS v4.

## Requirements

- PHP 8.0+
- WordPress 6.0+
- Node.js 18+

## Quick Start

```bash
# Install dependencies
npm install

# Development (with HMR)
npm run dev

# Production build
npm run build
```

## Features

- **Vite** - Fast builds with Hot Module Replacement
- **Tailwind CSS v4** - CSS-first configuration with design tokens
- **Auto-detected dev server** - No manual IP configuration needed
- **Bunny Fonts** - GDPR-compliant IBM Plex font family
- **SVG icon system** - Cached icon loader with sanitization
- **Strict PHP** - Typed, namespaced code throughout

## Directory Structure

```
standard-press/
├── app/                    # WordPress theme (symlinked as standard-theme)
│   ├── inc/               # PHP includes
│   │   ├── vite.php       # Asset loading (dev/production)
│   │   ├── setup.php      # Theme supports, menus
│   │   ├── sidebars.php   # Widget areas
│   │   ├── fonts.php      # Bunny Fonts
│   │   ├── icons.php      # SVG icon loader
│   │   └── walkers/       # Nav menu walkers
│   ├── resources/         # Source assets
│   │   ├── css/          # Tailwind CSS files
│   │   └── js/           # JavaScript modules
│   ├── assets/icons/      # SVG icons
│   ├── templates/parts/   # Template parts
│   └── dist/              # Built assets (gitignored)
├── vite.config.js         # Vite configuration
├── package.json           # Node dependencies
└── CLAUDE.md              # AI assistant instructions
```

## Development

### Vite Dev Server

The dev server URL is auto-detected. When you run `npm run dev`:

1. Vite detects your local network IP
2. Writes the URL to `app/.vite-dev-server`
3. PHP reads this file to load assets with HMR

Works automatically with Docker, DevKinsta, or localhost.

### CSS Architecture

Tailwind v4 CSS-first configuration:

- `theme.css` - Design tokens (`@theme` directive)
- `base.css` - Element defaults
- `components.css` - Reusable patterns
- `utilities.css` - Custom utilities
- `animations.css` - Transitions and animations

### Icons

SVG icons in `app/assets/icons/`. Usage:

```php
<?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
```

### Navigation Menus

Three registered menus:
- `primary` - Desktop header navigation
- `mobile` - Mobile menu
- `footer` - Footer links

## Production

```bash
npm run build
```

Outputs hashed assets to `app/dist/` with a manifest for cache-busting.

## License

MIT
