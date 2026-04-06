# Machine Product Data Layer — Design Spec

**Date:** 2026-03-14
**Status:** Draft
**Scope:** Per-machine PHP data files, hybrid profile/accessory querying, structured JSON-LD schema

---

## Problem

The machine product landing page template (`single-machine.php`) needs rich, SEO-optimized content for each of the 10 machines. Currently only the SSQ3 MultiPro has a partial data entry in `machine-product-data.php`; all other machines fall back to an empty skeleton.

The data includes marketing copy, technical specs, profile associations, accessory associations, resources, and FAQ content. This data must:

1. Power the landing page template sections (hero, stats, breakdown, specs, etc.)
2. Generate rich JSON-LD schema (Product with `additionalProperty` specs + FAQPage)
3. Be maintainable and eventually migratable to ACF field groups

Profiles and accessories are existing WordPress post types with `post_tag` / `product_tag` associations to machines, so the data layer should leverage those rather than duplicating content.

---

## Architecture

### Data Files

Each machine gets its own PHP file in `app/data/machines/`. This is a new directory alongside `inc/`, `templates/`, `assets/`, and `resources/`. The `data/` directory is for static content arrays that are separate from logic (`inc/`) and presentation (`templates/`).

```
app/data/machines/
├── ssq3-multipro.php
├── ssq-ii-multipro.php
├── ssh-multipro.php
├── ssr-multipro-jr.php
├── 5vc-5v-crimp.php
├── wav-wall-panel.php
├── mach-ii-5-gutter.php
├── mach-ii-6-gutter.php
├── mach-ii-combo-gutter.php
└── bg7-box-gutter.php
```

Each file follows this pattern:

```php
<?php
declare(strict_types=1);
// Machine data: SSQ3 MultiPro
return [
    'hero' => [ ... ],
    'stats' => [ ... ],
    // ...
];
```

### Loader (Refactored `machine-product-data.php`)

The existing `get_machine_product_data(string $slug)` function is refactored to:

1. Map the WooCommerce product slug to a data file key (using the existing prefix-match logic)
2. Load the corresponding file from `app/data/machines/`
3. Return the array, or the default skeleton if no file exists

```php
function get_machine_product_data(string $slug): ?array {
    $map = get_machine_slug_map();
    $key = resolve_machine_key($slug, $map);
    $file = get_template_directory() . '/data/machines/' . $key . '.php';

    if (file_exists($file)) {
        return include $file;
    }

    return get_default_machine_data();
}
```

The slug map handles WooCommerce's verbose slugs (e.g., `ssq3-multipro-roof-panel-machine` → `ssq3-multipro`).

### Schema Generator (`app/inc/machine-schema.php`)

New file in namespace `Standard\MachineSchema`. Must be added to the `$theme_includes` array in `functions.php`.

```php
namespace Standard\MachineSchema;

/**
 * Render Product + FAQPage JSON-LD schema for a machine product.
 *
 * @param \WC_Product $product  WooCommerce product object.
 * @param array       $machine  Machine data array from get_machine_product_data().
 */
function render_machine_schema(\WC_Product $product, array $machine): void { ... }
```

Generates:
- **Product** schema with `additionalProperty` entries (PropertyValue) from structured specs
- **FAQPage** schema from the `faq` array
- Replaces the generic `WC()->structured_data->generate_product_data()` call in `single-machine.php`

---

## Data Structure

Each machine data file returns an array with these top-level keys. All sections are optional — `null` or `[]` means the template skips that section.

### `hero`

```php
'hero' => [
    'headline' => string,      // Primary H1 text
    'subtitle' => string,      // Supporting tagline
    'image'    => string,      // Hero background image URL
    'video'    => ?string,     // Optional hero video URL (mp4)
],
```

### `stats`

```php
'stats' => [
    ['value' => '16', 'label' => 'Panel Profiles'],
    ['value' => '25 min', 'label' => 'Tooling Changeover'],
    // ... 4 items
],
```

### `finance`

Powers both `cta-finance.php` (thin strip CTA) and `configurator-finance.php` (deep section). Included in default skeleton as `null`.

```php
'finance' => [
    'monthly_price' => ?string,  // e.g., '$1,850/mo' — used in cta-finance.php
    'price_range'   => ?string,  // e.g., '$121K – $137K' — display-formatted for templates
    'note'          => ?string,  // e.g., 'Depending on profile; notching not included'
],
```

### `breakdown`

```php
'breakdown' => [
    [
        'id'       => string,   // CSS anchor ID
        'title'    => string,   // Section label (e.g., 'The Forming System')
        'headline' => string,   // Marketing headline
        'copy'     => string,   // Description paragraph
        'specs'    => string[], // Bullet points
        'image'    => string,   // Contextual photo URL
    ],
    // ... typically 4 subsystems
],
```

### `blueprint`

References dimensions from the canonical `specs.dimensions` to avoid duplication. The blueprint template reads from `$machine['specs']['dimensions']` for the data and uses `blueprint.svg` only for the visual asset.

```php
'blueprint' => [
    'svg' => string,  // SVG asset name for icon() helper
],
```

### `gallery`

```php
'gallery' => [
    'images'  => string[],  // Multi-angle photo URLs
    'rotator' => string[],  // 24-36 frame 360° sequence URLs
],
```

### `profiles` (Hybrid — tag-based query)

```php
'profiles' => [
    'tag_slugs' => string[],  // post_tag slugs to query profile CPT
    // e.g., ['ssq-ii-multipro-roof-panel-machine']
],
```

The template uses these slugs to query the `profile` post type:

```php
$profiles = get_posts([
    'post_type'      => 'profile',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'tax_query'      => [[
        'taxonomy' => 'post_tag',
        'field'    => 'slug',
        'terms'    => $machine['profiles']['tag_slugs'],
    ]],
]);
```

### `accessories` (Hybrid — tag-based query)

```php
'accessories' => [
    'product_tag' => string,  // WooCommerce product_tag term slug
    // NOTE: These are stored as WordPress term slugs (lowercase).
    // The old theme used display names like 'SSQII' but wc_get_products()
    // expects slugs. Verify actual term slugs in WP database during
    // implementation and adjust if needed. Likely: 'ssqii', 'ssh', etc.
],
```

The template uses this tag to query WooCommerce products:

```php
$accessories = wc_get_products([
    'tag'    => [$machine['accessories']['product_tag']],
    'limit'  => 8,
    'status' => 'publish',
]);
```

### `testimonials`

```php
'testimonials' => [
    [
        'name'     => string,
        'company'  => string,
        'location' => ?string,  // e.g., 'Denver, CO'
        'quote'    => string,
        'image'    => ?string,
    ],
    // ... typically 3
],
```

### `comparison`

```php
'comparison' => [
    'compare_with' => string[],  // Machine data file keys (e.g., ['ssq-ii-multipro', 'ssh-multipro'])
    'best_for'     => string,    // Use case summary
],
```

The comparison template loads data for each machine via `get_machine_product_data()`. The data file keys are valid arguments to this function since the loader does exact-match first (before prefix-match), so `'ssq-ii-multipro'` matches the `ssq-ii-multipro` key directly.

### `specs` (Fully Structured — Canonical Source for Dimensions)

This is the single source of truth for all technical specifications. The `blueprint` template reads dimensions from `specs.dimensions` rather than maintaining a separate copy.

```php
'specs' => [
    'standard_features' => string[],  // Bullet list of standard features

    'dimensions' => [
        'machine' => [
            'length'         => string,
            'length_slitter' => ?string,  // Only for machines with angled slitter
            'width'          => string,
            'height'         => string,
            'height_no_rack' => ?string,
            'weight'         => string,
        ],
        'on_trailer' => [
            'length' => string,
            'width'  => string,
            'height' => string,
            'weight' => string,
        ],
    ],

    'performance' => [
        'shear' => [
            'type'     => string,  // 'Hydraulic' | 'Manual'
            'details'  => string[],
        ],
        'drive' => [
            'type'    => string,
            'details' => string[],
        ],
        'speed' => [
            // Array of source/value pairs
            ['source' => string, 'value' => string],
            // e.g., ['source' => 'Gas Engine', 'value' => '75 ft./min (23m/min)']
        ],
    ],

    'materials' => [
        [
            'type'      => string,   // 'Painted Steel', 'Painted Aluminum', 'Copper', etc.
            'gauge'     => string,   // e.g., '28 ga. to 22 ga. (0.4mm to 0.8mm)'
            'note'      => ?string,  // e.g., '50 ksi maximum for 24 to 22 ga.'
        ],
    ],

    'coil' => [
        'widths'              => string,
        'finished_widths'     => string,
        'max_diameter_rack'   => string,
        'max_diameter_decoil' => string,
        'max_weight_reel'     => ?string,   // Gutter machines only
        'max_weight_cradle'   => ?string,   // Gutter machines only
    ],

    'power_options' => [
        // Each as a formatted string: '7.5 HP, 220V, 60 Hz, 3PH, 18 amps'
        string,
    ],

    'add_on_weights' => [
        ['item' => string, 'weight' => string],
        // e.g., ['item' => 'Trailer', 'weight' => '2,260 lbs. (1,020 kg)']
    ],

    'warranty' => [
        'description' => string,
        'patents'     => string[],
    ],
],
```

### `resources`

Maps to the three-card template layout: Machine Manual, Product Brochure, Service & Training.

```php
'resources' => [
    'manual'   => ?string,  // URL to machine manual page
    'brochure' => ?string,  // URL to product brochure page
    'service_training_url' => string,  // defaults to '/service-training'
],
```

### `faq`

Machine-specific FAQ items. Rendered visually as an accordion section on the page AND used for FAQPage JSON-LD schema. An FAQ template part will be added to `single-machine.php` to render these visually.

```php
'faq' => [
    ['question' => string, 'answer' => string],
],
```

### `schema`

Overrides for JSON-LD generation. `price_range` here is the canonical numeric source for schema markup. `finance.price_range` is the display-formatted version for templates (e.g., "$121K – $137K" vs structured low/high for schema).

```php
'schema' => [
    'low_price'    => ?string,   // e.g., '121000' — numeric for schema.org
    'high_price'   => ?string,   // e.g., '137000' — numeric for schema.org
    'availability' => ?string,   // e.g., 'InStock', 'PreOrder'
    'brand'        => 'New Tech Machinery',
    'manufacturer' => 'New Tech Machinery',
    'category'     => string,    // 'Roof & Wall Panel Machines' | 'Seamless Gutter Machines'
],
```

---

## Migration: Key Renames

The current `machine-product-data.php` uses flat keys. The new structure nests them. All template parts must be updated simultaneously during implementation.

| Old Key (flat) | New Key (nested) | Notes |
|---|---|---|
| `hero_headline` | `hero.headline` | |
| `hero_subtitle` | `hero.subtitle` | |
| `hero_image` | `hero.image` | |
| `hero_video` | `hero.video` | |
| `stats` | `stats` | Same shape |
| `breakdown` | `breakdown` | Same shape |
| `blueprint_svg` | `blueprint.svg` | |
| `blueprint_dimensions` | `specs.dimensions.machine` | Moved to canonical location |
| `blueprint_trailer` | `specs.dimensions.on_trailer` | Moved to canonical location |
| `gallery_images` | `gallery.images` | |
| `rotator_images` | `gallery.rotator` | |
| `profiles` | `profiles.tag_slugs` | Was empty array, now tag-based |
| `testimonials` | `testimonials` | Added `location` field |
| `compare_with` | `comparison.compare_with` | |
| `best_for` | `comparison.best_for` | |
| `featured_accessories` | `accessories.product_tag` | Was slug array, now tag string |
| `resources.manual` | `resources.manual` | Same, now URL string |
| `resources.brochure` | `resources.brochure` | Same, now URL string |
| _(new)_ | `finance` | New section |
| _(new)_ | `specs` | New structured specs |
| _(new)_ | `faq` | New section + template part |
| _(new)_ | `schema` | New section |

---

## Default Skeleton

The `get_default_machine_data()` function is updated to match the new structure:

```php
function get_default_machine_data(): array {
    return [
        'hero'        => null,
        'stats'       => [],
        'finance'     => null,
        'breakdown'   => [],
        'blueprint'   => null,
        'gallery'     => null,
        'profiles'    => null,
        'accessories' => null,
        'testimonials' => [],
        'comparison'  => null,
        'specs'       => null,
        'resources'   => null,
        'faq'         => [],
        'schema'      => null,
    ];
}
```

---

## Tag Mappings

### Accessory Tags (`product_tag`)

> **Note:** The old theme used display names (e.g., `SSQII`). During implementation, verify actual `product_tag` term slugs in the WP database. WooCommerce's `wc_get_products(['tag' => ...])` expects slugs. If the term slug is `ssqii` (lowercase), use that.

| Machine Data Key | Accessory Tag (verify slug) |
|---|---|
| `ssq3-multipro` | `SSQII` (temporary, will change) |
| `ssq-ii-multipro` | `SSQII` |
| `ssh-multipro` | `SSH` |
| `ssr-multipro-jr` | `SSR` |
| `5vc-5v-crimp` | `5VC` |
| `wav-wall-panel` | `WAV` |
| `mach-ii-5-gutter` | `MACHII` |
| `mach-ii-6-gutter` | `MACHII` |
| `mach-ii-combo-gutter` | `MACHII` |
| `bg7-box-gutter` | `BG7` |

### Profile Tags (`post_tag`)

| Machine Data Key | Profile Tag Slug(s) |
|---|---|
| `ssq3-multipro` | `ssq-ii-multipro-roof-panel-machine` (temporary, will change) |
| `ssq-ii-multipro` | `ssq-ii-multipro-roof-panel-machine` |
| `ssh-multipro` | `ssh-multipro-roof-panel-machine` |
| `ssr-multipro-jr` | `ssr-multipro-roof-panel-machine` |
| `5vc-5v-crimp` | `5vc-5v-crimp-roof-panel-machine` |
| `wav-wall-panel` | `wav-wall-panel-machine` |
| `mach-ii-5-gutter` | `mach-ii-5-gutter-machine` |
| `mach-ii-6-gutter` | `mach-ii-6-gutter-machine` |
| `mach-ii-combo-gutter` | `mach-ii-5-gutter-machine`, `mach-ii-5-6-gutter-machine`, `mach-ii-6-gutter-machine` |
| `bg7-box-gutter` | `bg7-box-gutter-machine` |

---

## JSON-LD Schema Generation

### Product Schema

Generated from the structured `specs` and `schema` keys:

```json
{
  "@context": "https://schema.org",
  "@type": "Product",
  "name": "SSQ3™ MultiPro Roof and Wall Panel Machine",
  "description": "...",
  "brand": { "@type": "Brand", "name": "New Tech Machinery" },
  "manufacturer": { "@type": "Organization", "name": "New Tech Machinery" },
  "category": "Roof & Wall Panel Machines",
  "offers": {
    "@type": "AggregateOffer",
    "priceCurrency": "USD",
    "lowPrice": "121000",
    "highPrice": "137000",
    "availability": "https://schema.org/InStock"
  },
  "additionalProperty": [
    { "@type": "PropertyValue", "name": "Panel Profiles", "value": "16" },
    { "@type": "PropertyValue", "name": "Max Speed (Gas)", "value": "75 ft./min (23m/min)" },
    { "@type": "PropertyValue", "name": "Weight", "value": "2,830 lbs (1,280 kg)" },
    { "@type": "PropertyValue", "name": "Shear Type", "value": "Hydraulic" }
  ]
}
```

### FAQPage Schema

Generated from the `faq` array:

```json
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "How long does it take to get a roof panel machine after ordering?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Lead times vary by model..."
      }
    }
  ]
}
```

---

## Template Integration

### Conditional Section Rendering

Each template part already receives `$machine` via `compact()`. Sections check for data presence:

```php
// In stats-bar.php
if (empty($machine['stats'])) return;

// In profile-selector.php — now queries by tag
if (empty($machine['profiles']['tag_slugs'])) return;
$profiles = get_posts([...tax_query...]);
if (empty($profiles)) return;
```

### New FAQ Template Part

Add `app/templates/woo/product/parts/faq.php` — renders FAQ items as an accordion. Added to `single-machine.php` between `resources` and `configurator-finance`.

### Schema Output

In `single-machine.php`, replace:

```php
WC()->structured_data->generate_product_data($product);
```

With:

```php
use function Standard\MachineSchema\render_machine_schema;
render_machine_schema($product, $machine);
```

### Files to Update

| File | Change |
|---|---|
| `app/inc/machine-product-data.php` | Refactor loader, update default skeleton |
| `app/inc/machine-schema.php` | New file (namespace `Standard\MachineSchema`) |
| `app/functions.php` | Add `machine-schema.php` to `$theme_includes` |
| `app/templates/woo/product/single-machine.php` | Add FAQ part, swap schema call |
| `app/templates/woo/product/parts/*.php` | Update all parts to read nested keys |
| `app/templates/woo/product/parts/faq.php` | New template part |
| `app/data/machines/*.php` | 10 new data files |

---

## ACF Migration Path

Each top-level array key maps to a future ACF field group:

| Data Key | ACF Field Group | ACF Field Type |
|---|---|---|
| `hero` | Machine Hero | Group (text, image, url) |
| `stats` | Machine Stats | Repeater (text, text) |
| `breakdown` | Machine Breakdown | Repeater (group) |
| `specs` | Machine Specifications | Group (nested groups) |
| `profiles.tag_slugs` | Machine Profiles | Taxonomy (post_tag) |
| `accessories.product_tag` | Machine Accessories | Taxonomy (product_tag) |
| `faq` | Machine FAQ | Repeater (text, textarea) |

When ready to migrate:

1. Create ACF field groups matching the structure above
2. In `machine-product-data.php`, add an ACF check before loading the file:
   ```php
   if (function_exists('get_field') && get_field('hero', $product_id)) {
       return build_machine_data_from_acf($product_id);
   }
   return include $file; // fallback to PHP file
   ```
3. Templates remain unchanged — they consume the same array shape regardless of source

---

## Machines & Data Sources

All machine data is sourced from the NTM product pages. The 10 machines:

### Roof & Wall Panel Machines (6)

1. **SSQ3 MultiPro** — `ssq3-multipro.php` — Flagship, 16 profiles, $121K–$137K
2. **SSQ II MultiPro** — `ssq-ii-multipro.php` — 16 profiles, $115K–$130K
3. **SSH MultiPro** — `ssh-multipro.php` — 7 profiles, $69,200+
4. **SSR MultiPro Jr.** — `ssr-multipro-jr.php` — 7 profiles, $43,400+
5. **5V Crimp** — `5vc-5v-crimp.php` — Exposed fastener, $70,800+
6. **WAV** — `wav-wall-panel.php` — Commercial wall panels, $232,000+

### Seamless Gutter Machines (4)

7. **MACH II 5"** — `mach-ii-5-gutter.php` — $10,600+
8. **MACH II 6"** — `mach-ii-6-gutter.php` — $12,300+
9. **MACH II 5"/6" Combo** — `mach-ii-combo-gutter.php` — $15,500+
10. **BG7 Box Gutter** — `bg7-box-gutter.php` — $69,200+
