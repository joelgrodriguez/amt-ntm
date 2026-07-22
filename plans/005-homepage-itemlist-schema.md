# Plan 005: Emit an ItemList JSON-LD of the machine lineup on the front page

> **Executor instructions**: Follow this plan step by step. Run every
> verification command and confirm the expected result before moving to the
> next step. If anything in the "STOP conditions" section occurs, stop and
> report — do not improvise. When done, update the status row for this plan
> in `plans/README.md` — unless a reviewer dispatched you and told you they
> maintain the index.
>
> **Drift check (run first)**:
> `git diff --stat c5d139b..HEAD -- app/inc/machine-schema.php app/inc/setup.php app/front-page.php`
> If `machine-schema.php` changed since this plan was written, compare the
> "Current state" excerpts against the live code before proceeding; on a
> mismatch, treat it as a STOP condition.

## Status

- **Priority**: P3
- **Effort**: S
- **Risk**: LOW
- **Depends on**: none
- **Category**: direction (SEO / entity graph)
- **Planned at**: commit `c5d139b`, 2026-07-21

## Why this matters

The homepage visually showcases the machine lineup but tells crawlers nothing
structured about it — Yoast's homepage graph is WebPage/Organization only. An
`ItemList` node naming each machine and linking its product URL gives search
engines and LLMs a machine-readable "this manufacturer's product line is X, Y,
Z" statement and strengthens the entity graph connecting the brand to its
products.

**Critical constraint**: the machine product pages already emit full `Product`
nodes via `app/inc/machine-schema.php`, which is deliberately the *sole*
Product emitter (verified 2026-07-10 — see that file's header comment). The
homepage ItemList must therefore contain **URL-reference `ListItem`s only —
no embedded `Product` nodes** — so no duplicate Product schema ever exists for
the same machine.

## Current state

- `app/inc/machine-schema.php` — namespace `Standard\MachineSchema`. Defines
  `SCHEMA_JSON_FLAGS` (line 24) and echoes JSON-LD like:

  ```php
  echo '<script type="application/ld+json">' . wp_json_encode($product_schema, SCHEMA_JSON_FLAGS) . '</script>' . "\n";
  ```

  It currently exposes `render_machine_schema()` called from the
  single-machine template. It has no front-page logic and no `add_action`
  calls of its own.

- The front-page "Explore All Machines" section
  (`app/templates/parts/front-page/explore-machines.php`) sources its cards
  from the Woo catalog helpers (top of file):

  ```php
  use function Standard\Woo\Catalog\get_product_categories;
  use function Standard\Woo\Catalog\get_products_by_category;
  ```

  The ItemList should be built from the same helpers so the schema always
  matches what the page shows. Inspect `app/inc/woo/` for these functions'
  signatures and the shape of a product entry (each card receives `title`,
  `explore_url`, etc. — see the `@param` docblock in
  `app/templates/parts/card-product.php:22-33`).

- Sitewide `wp_head` hook precedent: `app/inc/seo.php:31` uses
  `add_action('wp_head', __NAMESPACE__ . '\\render_head_tags', 5);` — but
  note that file's renderers no-op when Yoast is active. Your new hook must
  NOT be behind such a guard: it supplements Yoast rather than replacing it
  (same reasoning as the header comment in `machine-schema.php`).

- The retired product categories excluded from sitemaps (do not include their
  machines): `roof-wall-panel-machines`, `gutter-machines`,
  `accessories-add-on-equipment` are *legacy slugs* listed in
  `app/inc/seo.php:25-29` — the catalog helpers already serve current
  categories, so simply build from what `get_product_categories()` returns.

## Commands you will need

| Purpose | Command | Expected on success |
|---|---|---|
| PHP syntax | `php -l app/inc/machine-schema.php` | `No syntax errors` |
| Lint | `npm run lint:php` | exit 0 |
| Template tests | `npm run test:page-templates` | exit 0 |

**Rendered-page caveat**: DevKinsta serves the main checkout, not worktrees;
rendered checks (Step 3) only work if your branch is checked out there —
otherwise defer to post-merge QA.

## Scope

**In scope**:
- `app/inc/machine-schema.php` (add one function + one `add_action`)

**Out of scope** (do NOT touch):
- `app/inc/seo.php` — fallback-only file; the ItemList must not live behind
  its Yoast guard.
- The existing `render_machine_schema()` / Product / FAQ builders in
  `machine-schema.php`.
- Woo catalog helpers — consume, don't modify.
- Any template file.

## Git workflow

- Branch off `dev`: `joelgrodriguez/005-homepage-itemlist-schema`
- One commit; short imperative subject, e.g. "Emit machine lineup ItemList on
  front page".
- Do NOT push or merge unless the operator instructed it.

## Steps

### Step 1: Add the builder + hook in `app/inc/machine-schema.php`

Append (same namespace, same style as the existing builders):

```php
add_action('wp_head', __NAMESPACE__ . '\\render_front_page_machine_list', 6);

/**
 * ItemList of the machine lineup, front page only.
 *
 * URL-reference ListItems ONLY — full Product nodes live exclusively on the
 * single-machine pages (this file's render_machine_schema()); embedding them
 * here would duplicate Product schema across URLs.
 */
function render_front_page_machine_list(): void {
    if (!is_front_page()) {
        return;
    }
    // Build $elements from Standard\Woo\Catalog helpers: for each machine
    // category, for each product: ['@type' => 'ListItem', 'position' => $i,
    // 'name' => <product title>, 'url' => <product permalink>].
    // Skip accessories; machines only. Bail (render nothing) if the catalog
    // returns no products — an empty ItemList is worse than none.
    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'New Tech Machinery portable rollforming machines',
        'itemListElement' => $elements,
    ];
    echo '<script type="application/ld+json">' . wp_json_encode($schema, SCHEMA_JSON_FLAGS) . '</script>' . "\n";
}
```

Implementation notes:
- Resolve the exact helper signatures from `app/inc/woo/` (recon found
  `get_product_categories()` / `get_products_by_category()` used by
  `explore-machines.php` — mirror that file's usage, including how it derives
  each card's `explore_url`).
- `position` is 1-based and contiguous across the whole list.
- Absolute URLs (the card data's `explore_url` — confirm it is absolute; if
  relative, wrap in `home_url()`).
- Strip trademark glyphs from names the same way `card-product.php:52` does
  (`str_replace(["\u{2122}", "\u{00AE}"], '', $title)`).

**Verify**: `php -l app/inc/machine-schema.php` → clean.

### Step 2: Lint + tests

**Verify**: `npm run lint:php` → exit 0. `npm run test:page-templates` →
exit 0.

### Step 3 (served checkout only): Rendered check

```bash
curl -sk https://newtech.local/ | grep -c '"@type":"ItemList"'      # expect 1
curl -sk https://newtech.local/ | grep -c '"@type":"Product"'       # expect 0  ← no Product on homepage
curl -sk https://newtech.local/machines/ | grep -c '"@type":"ItemList"'  # expect 0 ← front page only
```

Extract the block and validate at https://validator.schema.org/ → ItemList
with N ListItems, zero errors. From a worktree, defer to post-merge QA.

## Test plan

- `npm run test:page-templates` stays green.
- Step 3's three greps are the behavioral contract: exactly one ItemList,
  zero Product nodes on the homepage, nothing leaking to other pages.

## Done criteria

- [ ] `render_front_page_machine_list()` exists in `Standard\MachineSchema`, hooked to `wp_head`, gated on `is_front_page()`
- [ ] ListItems carry only `position` / `name` / `url` — no embedded Product nodes
- [ ] Machines only (no accessories); names are ™/®-free; URLs absolute
- [ ] Empty catalog renders no script tag at all
- [ ] `npm run lint:php` and `npm run test:page-templates` exit 0
- [ ] No files outside the in-scope list modified (`git status`)
- [ ] `plans/README.md` status row updated

## STOP conditions

Stop and report back (do not improvise) if:

- The Woo catalog helpers named in "Current state" don't exist or return a
  shape with no title/url you can map (report the actual shape).
- The homepage already renders an `ItemList` from another source (Yoast
  settings drift or a plugin) — duplicate ItemLists must not ship.
- Calling the catalog helpers at `wp_head` time errors because WooCommerce
  isn't loaded yet — report rather than moving the hook later than priority 6
  or caching workarounds.

## Maintenance notes

- New machines added to the Woo catalog appear in the list automatically —
  no per-machine maintenance.
- If a "featured machines only" curation is ever wanted instead of the full
  lineup, change the data source, not the schema shape.
- Reviewer should scrutinize: the no-Product-on-homepage invariant (grep in
  Step 3), and that the hook doesn't fire on `/machines/` or category landing
  pages (`is_front_page()` only).
