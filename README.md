# AMT NTM

WordPress classic theme for New Tech Machinery (`amt-ntm`). The repo root holds
build tooling; the actual theme WordPress activates is the `app/` subdirectory
(`amt-ntm/app`).

## Requirements

- PHP 8.1+
- WordPress 6.0+
- Node.js 18+

## Build Commands

```bash
npm install       # Install build dependencies
npm run dev       # Vite dev server with HMR
npm run build     # Production build to app/dist/
```

## Directory Layout

```
amt-ntm/
├── app/                    # The WordPress theme (activate amt-ntm/app)
│   ├── inc/                # PHP includes (vite, setup, nav, fonts, icons, seo)
│   ├── templates/parts/    # Shared template parts
│   ├── templates/pages/    # Page-specific template parts
│   ├── templates/woo/      # WooCommerce template overrides
│   ├── data/machines/      # Per-machine content, specs, and pricing (git-owned)
│   ├── resources/js/       # JS entry (_app.js) and modules
│   ├── resources/css/      # CSS entry (_app.css), Tailwind v4 layers
│   └── dist/               # Built assets (gitignored)
├── scripts/                # DB capture, content export, release tooling
├── db/                     # Replayable DB state (redirects.json)
├── vite.config.js          # Vite configuration
└── package.json            # Build dependencies and scripts
```

## Asset Pipeline

Production loads hashed assets through `app/dist/.vite/manifest.json`. In
development, `npm run dev` writes the dev-server URL to `app/.vite-dev-server`
and PHP switches to HMR assets via `is_vite_dev()`.

## Navigation

Navigation is hardcoded PHP data in `app/inc/desktop-nav.php` and
`app/inc/mobile-nav.php`. WordPress nav menus are deliberately not registered —
edit those files to change menus.

## Fonts

Self-hosted Noto fonts (latin subsets), preloaded by `app/inc/fonts.php`.
No third-party font CDN.

## Local Development

The site runs in DevKinsta with the WordPress root at
`/Users/jrodriguez/Development/Kinsta/public/newtech`. Run WP-CLI through the
DevKinsta PHP container with the `php8.3` pin (the container's default CLI PHP
is 7.4 and fatals on this theme's `match()` syntax):

```bash
docker exec devkinsta_fpm php8.3 /usr/local/bin/wp --path=/www/kinsta/public/newtech option get home --allow-root
```

## Database State

The theme is git-controlled; the database is not — a fresh production pull
wipes every local DB change. Capture DB-side changes as replayable scripts in
`scripts/db/` (plus `db/redirects.json` for redirects) and reapply them with
`npm run db:apply`.

## Release

`dev` is the integration branch; feature branches merge into it. `npm run
release:master` publishes `master` with dev-only tooling stripped (the strip
list lives in `scripts/release/dev-tooling-paths.txt`). Set
`RELEASE_TARGET_BASE_URL` to the Kinsta staging base URL; the release gate
requires a prior DevKinsta Files + Database push so required uploads are present.
