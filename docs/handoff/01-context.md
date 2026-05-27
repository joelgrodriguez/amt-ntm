# 01 — Context

## What this project is

**NTM** (New Tech Machinery) builds portable roof-panel and gutter rollforming machines. The website (`newtechmachinery.com`) lives in WordPress on DevKinsta locally and in production on Kinsta. The current top nav is label-driven (Machines / Profiles / Resources / Learning Center / Service & Support). Visitors get lost because nothing tells them *what to do next*.

The new IA replaces those five labels with **four action-driven labels**: Get started, Choose your machine, How to buy, Get owner support. Each one opens a two-lane mega-menu: a **browse lane** (existing catalog pages) and a **guided lane** (quizzes, calculators, comparisons).

## Where things live

```
/Users/jrodriguez/Development/Kinsta/public/newtech/        # WordPress root (DevKinsta)
└── wp-content/themes/amt-ntm/                              # Theme root
    ├── app/                                                # All theme PHP/assets
    │   ├── functions.php
    │   ├── header.php  footer.php  front-page.php  page.php  etc.
    │   ├── page-machines.php                               # /machines/ template
    │   ├── page-roof-wall-panel-machines.php
    │   ├── page-seamless-gutter-machines.php
    │   ├── page-service-hub.php
    │   ├── page-owner-resources.php
    │   ├── page-profiles.php  page-manuals.php  etc.
    │   ├── single-product.php  single-video.php  single-profile.php  etc.
    │   ├── inc/
    │   │   ├── desktop-nav.php          ← MEGA MENU DATA SOURCE
    │   │   ├── mobile-nav.php           ← MOBILE NAV DATA SOURCE
    │   │   ├── setup.php
    │   │   ├── page-templates.php
    │   │   ├── machines.php  machines-data.php  machine-product-data.php
    │   │   ├── learning-center.php
    │   │   ├── service-hub.php
    │   │   └── ...
    │   ├── templates/parts/
    │   │   ├── mega-menu.php            ← MEGA MENU RENDER
    │   │   ├── mobile-menu.php
    │   │   ├── mobile-menu-panel.php
    │   │   ├── hero-asymmetric.php
    │   │   ├── hero-category.php
    │   │   ├── value-prop-cards.php
    │   │   ├── section-header.php
    │   │   ├── faq-accordion.php
    │   │   ├── product-grid.php
    │   │   ├── card-post.php  card-product.php  card-profile.php
    │   │   ├── configurator-cta.php
    │   │   ├── final-cta.php
    │   │   └── ... (more reusable parts)
    │   ├── resources/                                      # Vite entry points
    │   │   ├── css/_app.css  components.css  utilities.css
    │   │   └── js/_app.js  modules/MobileMenu.js  etc.
    │   └── dist/                                           # Built assets
    └── ...
```

## The codebase in 30 seconds

- PHP 8.0+, `declare(strict_types=1)`, `Standard` namespace
- Vite + Tailwind CSS v4 for assets (`npm run dev` / `npm run build`)
- The mega menu is **data + template**: `desktop-nav.php` defines the `items` array, `mega-menu.php` renders it. Same pattern for mobile.
- Custom post types are real and numerous: `product`, `video`, `profile`, `manual`, `literature`, `resource`, `download`, `pricesheet`, `footprint`, `cutlist`.
- The site uses ACF for some custom field groups. WooCommerce powers products.
- Icons are SVG via the `icon()` helper. **Use existing icons.** Don't add a new icon system.

## Tools you'll use

- **WP-CLI** through the DevKinsta PHP container, e.g.:
  ```bash
  docker exec devkinsta_fpm wp --path=/www/kinsta/public/newtech post list --post_type=page --allow-root
  ```
- **Vite dev server**: `cd /Users/jrodriguez/.superset/worktrees/<your-worktree>/ && npm run dev`
- **Git worktrees**: read `AGENTS.md` / `CLAUDE.md` at repo root. You are a spawned worktree agent, not Maestro. Do not push to `dev` or `master`.

## What the user wants

1. The nav rebuild ships first.
2. The four anchor pages get built second.
3. The smaller gap pages get built last (only after anchors are reviewed).
4. **Existing URLs stay live.** No SEO regressions.
5. **Don't redesign anything.** Reuse existing components, templates, and styles.

## What the user does NOT want

- New visual components, color palettes, type scales, or animation systems
- URL changes to live, traffic-earning pages
- "Improvements" to pages you weren't asked to touch
- Force pushes, history rewrites, or merges into `dev`
