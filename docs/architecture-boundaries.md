# Theme Architecture Boundaries

This theme uses a simple functional architecture on purpose. The goal is low-complexity code that is easy to trace in WordPress, not heavy abstraction.

## Layer Rules

`app/functions.php`

- Bootstrap only.
- Load modules, define theme constants, and stop there.
- Do not put product logic, rendering logic, or large hook callbacks here.

`app/inc/setup.php`, `app/inc/vite.php`, `app/inc/woo/*`

- WordPress and WooCommerce integration layer.
- Register hooks, supports, menus, template routing, and asset loading.
- Keep these files focused on framework wiring.

`app/inc/*.php` domain modules

- Own one theme concern each.
- Good examples in the current codebase:
  - `app/inc/video.php`: normalize and render safe video embeds.
  - `app/inc/machine-product-data.php`: resolve product slug to machine data.
  - `app/inc/machine-schema.php`: build and render machine JSON-LD.
- Public APIs should stay small and explicit.

`app/data/machines/*.php`

- Static machine content only.
- These files are content payloads, not logic containers.

`app/templates/*.php` and `app/templates/parts/*.php`

- Templates compose sections and render markup.
- Template parts should stay presentational.
- Avoid embedding business rules or JavaScript behavior directly in template files.

`app/resources/js/modules/*.js`

- Own interactive frontend behavior.
- If a PHP template needs a click handler, scrolling behavior, or stateful UI logic, move it here.

## Current Project Assessment

Good boundaries:

- `app/functions.php`
- `app/inc/setup.php`
- `app/inc/vite.php`
- `app/inc/video.php`
- `app/inc/machine-product-data.php`
- `app/inc/machine-schema.php`

Borderline areas to watch:

- `app/inc/woo/catalog.php`
  - It currently queries WooCommerce, formats card view data, and provides sample fallback data.
  - This is still workable, but it mixes data access and view-model shaping.
- Large array-returning modules in general
  - They are pragmatic, but the cost is looser contracts around array keys.

Refactors applied in this branch:

- Grid class helpers moved out of `app/inc/machines-data.php` into `app/inc/grid.php`.
  - Reason: layout logic should not live in a content data module.
- Carousel behavior moved out of `app/templates/woo/product/single-machine.php` into `app/resources/js/modules/CarouselNav.js`.
  - Reason: interactive behavior belongs in the frontend JS layer, not inline in PHP templates.

## Practical Rules For New Code

Use these rules when adding or reviewing code:

1. A file should have one main reason to change.
2. Keep WordPress hooks in integration files, not in templates.
3. Keep content/data modules free of CSS class calculations and markup concerns.
4. Keep templates focused on composition and rendering.
5. Put interactive browser behavior in JS modules.
6. Prefer small function-based modules over introducing classes unless state or polymorphism actually demands it.
7. If a helper returns arrays used across templates, keep the array shape stable and documented in the module docblock.

## Why This Is Better

This structure keeps the theme readable for WordPress work:

- bootstrap is easy to find
- data modules are easier to test mentally
- templates stay readable
- frontend behavior is easier to debug

Simple version: a good boundary reduces the number of places you have to touch when one type of change comes in.
