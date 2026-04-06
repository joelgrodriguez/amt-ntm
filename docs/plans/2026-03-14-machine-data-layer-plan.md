# Machine Product Data Layer — Implementation Plan

> **For agentic workers:** REQUIRED: Use superpowers:subagent-driven-development (if subagents available) or superpowers:executing-plans to implement this plan. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Create per-machine PHP data files for all 10 machines, refactor the loader, update all template parts to read from the data, add JSON-LD schema generation, and add an FAQ template part.

**Architecture:** Individual PHP data files in `app/data/machines/` return structured arrays. A refactored `machine-product-data.php` loads them by slug. Templates read nested keys conditionally. A new `machine-schema.php` generates Product + FAQPage JSON-LD.

**Tech Stack:** PHP 8.0+, WordPress/WooCommerce, Tailwind CSS v4

**Spec:** `docs/plans/2026-03-14-machine-data-layer-design.md`

**Important:** The loader refactor changes the data shape from flat keys (`hero_headline`) to nested keys (`hero.headline`). The loader and ALL templates must be updated in the same chunk to avoid breakage. Chunk 1 handles data files + loader + all templates together.

---

## File Structure

### New files

| File | Responsibility |
|---|---|
| `app/data/machines/ssq3-multipro.php` | SSQ3 MultiPro machine data |
| `app/data/machines/ssq-ii-multipro.php` | SSQ II MultiPro machine data |
| `app/data/machines/ssh-multipro.php` | SSH MultiPro machine data |
| `app/data/machines/ssr-multipro-jr.php` | SSR MultiPro Jr. machine data |
| `app/data/machines/5vc-5v-crimp.php` | 5VC 5V Crimp machine data |
| `app/data/machines/wav-wall-panel.php` | WAV Wall Panel machine data |
| `app/data/machines/mach-ii-5-gutter.php` | MACH II 5" Gutter machine data |
| `app/data/machines/mach-ii-6-gutter.php` | MACH II 6" Gutter machine data |
| `app/data/machines/mach-ii-combo-gutter.php` | MACH II Combo Gutter machine data |
| `app/data/machines/bg7-box-gutter.php` | BG7 Box Gutter machine data |
| `app/inc/machine-schema.php` | JSON-LD schema generator |
| `app/templates/woo/product/parts/faq.php` | FAQ accordion template |

### Modified files

| File | Change |
|---|---|
| `app/inc/machine-product-data.php` | Refactor loader to read from data files |
| `app/functions.php` | Add `machine-schema.php` to includes |
| `app/templates/woo/product/single-machine.php` | Add FAQ part, swap schema, add `use` import |
| `app/templates/woo/product/parts/hero.php` | Read from `$machine['hero']` |
| `app/templates/woo/product/parts/stats-bar.php` | Read from `$machine['stats']` |
| `app/templates/woo/product/parts/cta-finance.php` | Read from `$machine['finance']` |
| `app/templates/woo/product/parts/machine-breakdown.php` | Read from `$machine['breakdown']` |
| `app/templates/woo/product/parts/blueprint.php` | Read from `$machine['specs']['dimensions']` |
| `app/templates/woo/product/parts/gallery.php` | Read from `$machine['gallery']` |
| `app/templates/woo/product/parts/profile-selector.php` | Query profiles by `$machine['profiles']['tag_slugs']` |
| `app/templates/woo/product/parts/social-proof.php` | Read from `$machine['testimonials']` |
| `app/templates/woo/product/parts/comparison.php` | Read from `$machine['comparison']` |
| `app/templates/woo/product/parts/accessories.php` | Query by `$machine['accessories']['product_tag']` |
| `app/templates/woo/product/parts/specs-accordion.php` | Read from `$machine['specs']` |
| `app/templates/woo/product/parts/resources.php` | Read from `$machine['resources']` |
| `app/templates/woo/product/parts/configurator-finance.php` | Read from `$machine['finance']` |

### Unchanged files (no machine data reads)

These template parts exist in `single-machine.php` but do not read from `$machine` data keys. They use only `$product` (WooCommerce) or are purely structural. No changes needed:

- `app/templates/woo/product/parts/cta-configurator.php` — reads only `$product` for configurator URL
- `app/templates/woo/product/parts/final-cta.php` — reads only `$product` for CTA URLs
- `app/templates/woo/product/parts/sticky-cta.php` — reads only `$product->get_name()` and `get_price_html()`

---

## Chunk 1: Data Layer + Loader + SSQ3 Data File

### Task 1: Create SSQ3 MultiPro data file

**Files:**
- Create: `app/data/machines/ssq3-multipro.php`

- [ ] **Step 1: Create directory and file**

```bash
mkdir -p app/data/machines
```

Create `app/data/machines/ssq3-multipro.php` — the flagship machine with the most complete data. All other machines follow the exact same array shape. See the full file content in the spec's Data Structure section. Key data points:

- Hero: "Produce 16 Panel Profiles On-Site. One Machine."
- Stats: 16 profiles, 25 min changeover, 75 ft/min, $2.25/sq ft savings
- Finance: $121K – $137K range
- Breakdown: 4 subsystems (Forming, Frame, Power Pack, Brain)
- Blueprint SVG: 'ssq3-machine'
- Profiles tag: `['ssq-ii-multipro-roof-panel-machine']` (temporary, shares with SSQ II)
- Accessories tag: `'SSQII'` (temporary)
- Compare with: `['ssq-ii-multipro', 'ssh-multipro']`
- Full specs: 17 standard features, dimensions (14'4" x 5'2" x 4'3", 2,830 lbs), trailer (18'11" x 7'2½" x 6'3", 5,090 lbs), 5 speed options, 4 materials, 6 power options, 8 add-on weights, warranty + patent
- Resources: SSQ3 manual + brochure URLs
- FAQ: 5 machine-specific questions
- Schema: low $121,000 / high $137,000, InStock

The complete code for this file was provided in the previous version of this plan. Use the same SSQ3 data array — it is the reference implementation.

- [ ] **Step 2: Commit**

```bash
git add app/data/machines/ssq3-multipro.php
git commit -m "feat: add SSQ3 MultiPro machine data file"
```

---

### Task 2: Refactor machine-product-data.php

**Files:**
- Modify: `app/inc/machine-product-data.php`

- [ ] **Step 1: Replace entire file with new loader**

```php
<?php
/**
 * Machine Product Landing Page Data — Loader
 *
 * Maps WooCommerce product slugs to per-machine data files.
 * Each machine's content lives in app/data/machines/{key}.php.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineProductData;

/**
 * Get landing page data for a specific machine by slug.
 *
 * @param string $slug WooCommerce product slug.
 * @return array|null Machine data or null if not found.
 */
function get_machine_product_data(string $slug): ?array {
    $key = resolve_machine_key($slug);

    if ($key !== null) {
        $file = get_template_directory() . '/data/machines/' . $key . '.php';
        if (file_exists($file)) {
            return include $file;
        }
    }

    return get_default_machine_data();
}

/**
 * Resolve a WooCommerce product slug to a machine data file key.
 *
 * Tries exact match first, then longest-prefix match.
 *
 * @param string $slug WooCommerce product slug.
 * @return string|null Data file key, or null if no match.
 */
function resolve_machine_key(string $slug): ?string {
    $keys = get_machine_data_keys();

    // Exact match
    if (in_array($slug, $keys, true)) {
        return $slug;
    }

    // Longest prefix match (e.g., 'ssq3-multipro-roof-panel-machine' → 'ssq3-multipro')
    usort($keys, fn($a, $b) => strlen($b) - strlen($a));
    foreach ($keys as $key) {
        if (str_starts_with($slug, $key)) {
            return $key;
        }
    }

    return null;
}

/**
 * Get all known machine data file keys.
 *
 * @return string[]
 */
function get_machine_data_keys(): array {
    return [
        'ssq3-multipro',
        'ssq-ii-multipro',
        'ssh-multipro',
        'ssr-multipro-jr',
        '5vc-5v-crimp',
        'wav-wall-panel',
        'mach-ii-5-gutter',
        'mach-ii-6-gutter',
        'mach-ii-combo-gutter',
        'bg7-box-gutter',
    ];
}

/**
 * Default skeleton data for machines without dedicated data files.
 *
 * @return array
 */
function get_default_machine_data(): array {
    return [
        'hero'         => null,
        'stats'        => [],
        'finance'      => null,
        'breakdown'    => [],
        'blueprint'    => null,
        'gallery'      => null,
        'profiles'     => null,
        'accessories'  => null,
        'testimonials' => [],
        'comparison'   => null,
        'specs'        => null,
        'resources'    => null,
        'faq'          => [],
        'schema'       => null,
    ];
}
```

- [ ] **Step 2: Commit**

```bash
git add app/inc/machine-product-data.php
git commit -m "refactor: load machine data from individual files in app/data/machines/"
```

---

## Chunk 2: Update All Templates

**Critical:** All templates must be updated before any page loads with the new data shape. Complete this entire chunk before testing.

### Task 3: Update hero.php

**Files:**
- Modify: `app/templates/woo/product/parts/hero.php`

- [ ] **Step 1: Replace hero.php**

```php
<?php
/**
 * Machine Product — Hero Section
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;

if (!$product) {
    return;
}

$hero     = $machine['hero'] ?? null;
$headline = $hero['headline'] ?? $product->get_name();
$subtitle = $hero['subtitle'] ?? $product->get_short_description();
$image    = $hero['image'] ?? '';
$video    = $hero['video'] ?? null;
$finance  = $machine['finance'] ?? null;
$price    = $finance['price_range'] ?? $product->get_price_html();
?>

<section id="machine-hero" class="relative min-h-[70vh] flex items-end overflow-hidden bg-slate-800" aria-labelledby="machine-hero-title">
    <?php if ($image) : ?>
        <img src="<?php echo esc_url($image); ?>"
             alt="<?php echo esc_attr($product->get_name()); ?>"
             class="absolute inset-0 w-full h-full object-cover" />
    <?php endif; ?>
    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-transparent"></div>

    <div class="container relative z-10 pb-16 pt-32 grid gap-6 max-w-3xl">
        <h1 id="machine-hero-title" class="text-4xl font-bold text-white md:text-5xl lg:text-6xl">
            <?php echo esc_html($headline); ?>
        </h1>
        <p class="text-lg text-slate-200 md:text-xl"><?php echo esc_html($subtitle); ?></p>
        <?php if ($price) : ?>
            <p class="text-sm text-slate-300 uppercase tracking-wider">Starting at <span class="text-white font-semibold"><?php echo wp_kses_post($price); ?></span></p>
        <?php endif; ?>
        <div class="flex gap-4 mt-2">
            <a href="/configurator/<?php echo esc_attr($product->get_slug()); ?>/" class="btn btn-primary">Build & Quote</a>
            <a href="#machine-breakdown" class="btn btn-outline-light">Explore</a>
        </div>
    </div>
</section>
```

---

### Task 4: Update stats-bar.php

**Files:**
- Modify: `app/templates/woo/product/parts/stats-bar.php`

- [ ] **Step 1: Replace stats-bar.php**

```php
<?php
/**
 * Machine Product — Stats Bar
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$stats   = $machine['stats'] ?? [];

if (empty($stats)) {
    return;
}
?>

<section class="bg-slate-900 py-10" aria-label="Key specifications">
    <div class="container">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center">
            <?php foreach ($stats as $stat) : ?>
                <div class="grid gap-1">
                    <span class="text-3xl font-bold text-white lg:text-4xl"><?php echo esc_html($stat['value']); ?></span>
                    <span class="text-sm text-slate-400 uppercase tracking-wider"><?php echo esc_html($stat['label']); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
```

---

### Task 5: Update cta-finance.php

**Files:**
- Modify: `app/templates/woo/product/parts/cta-finance.php`

- [ ] **Step 1: Replace cta-finance.php**

```php
<?php
/**
 * Machine Product — CTA Strip: Financing
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;
$finance = $machine['finance'] ?? null;

if (!$finance) {
    return;
}

$monthly = $finance['monthly_price'] ?? null;
$range   = $finance['price_range'] ?? null;
$note    = $finance['note'] ?? null;
$label   = $monthly ? sprintf(__('As low as %s', 'standard'), $monthly) : ($range ? sprintf(__('Starting at %s', 'standard'), $range) : null);

if (!$label) {
    return;
}
?>

<div class="bg-secondary py-6">
    <div class="container flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <p class="text-white font-bold text-lg"><?php echo esc_html($label); ?></p>
            <?php if ($note) : ?>
                <p class="text-white/80 text-sm hidden md:block"><?php echo esc_html($note); ?></p>
            <?php else : ?>
                <p class="text-white/80 text-sm hidden md:block"><?php esc_html_e('Flexible financing — lease-to-own, seasonal plans, quick approval', 'standard'); ?></p>
            <?php endif; ?>
        </div>
        <a href="/machines/leasing-financing/" class="btn btn-sm bg-white text-secondary hover:bg-slate-100 shrink-0"><?php esc_html_e('Explore Financing', 'standard'); ?></a>
    </div>
</div>
```

---

### Task 6: Update machine-breakdown.php

**Files:**
- Modify: `app/templates/woo/product/parts/machine-breakdown.php`

- [ ] **Step 1: Replace machine-breakdown.php**

```php
<?php
/**
 * Machine Product — Machine Breakdown
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine    = $args['machine'] ?? null;
$subsystems = $machine['breakdown'] ?? [];

if (empty($subsystems)) {
    return;
}
?>

<section id="machine-breakdown" class="section" aria-labelledby="breakdown-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Inside the Machine', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="breakdown-title" class="section-title"><?php esc_html_e('Built to Perform', 'standard'); ?></h2>
        </div>

        <?php foreach ($subsystems as $idx => $sub) :
            $is_reversed = $idx % 2 !== 0;
            $image       = $sub['image'] ?? '';
        ?>
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-16 items-center <?php echo $is_reversed ? 'lg:[&>*:first-child]:order-2' : ''; ?>">
                <div class="bg-slate-100 aspect-video flex items-center justify-center">
                    <?php if ($image) : ?>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($sub['title']); ?>" class="w-full h-full object-cover" />
                    <?php else : ?>
                        <span class="text-slate-400 text-sm font-mono"><?php echo esc_html($sub['title']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="grid gap-4">
                    <p class="text-sm font-semibold uppercase tracking-wider text-secondary"><?php echo esc_html($sub['title']); ?></p>
                    <h3 class="text-2xl font-bold text-slate-900 lg:text-3xl"><?php echo esc_html($sub['headline']); ?></h3>
                    <p class="text-slate-600"><?php echo esc_html($sub['copy']); ?></p>
                    <?php if (!empty($sub['specs'])) : ?>
                        <ul class="grid gap-2 mt-2">
                            <?php foreach ($sub['specs'] as $spec) : ?>
                                <li class="flex items-start gap-2 text-sm text-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-secondary mt-1.5 shrink-0"></span>
                                    <?php echo esc_html($spec); ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</section>
```

---

### Task 7: Update blueprint.php

**Files:**
- Modify: `app/templates/woo/product/parts/blueprint.php`

- [ ] **Step 1: Replace blueprint.php**

Reads dimensions from `$machine['specs']['dimensions']` (canonical source). Uses `$machine['blueprint']['svg']` for the visual asset.

```php
<?php
/**
 * Machine Product — Blueprint / Footprint
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$specs   = $machine['specs'] ?? null;
$dims    = $specs['dimensions'] ?? null;

if (!$dims) {
    return;
}

$m       = $dims['machine'] ?? [];
$trailer = $dims['on_trailer'] ?? [];
$svg     = $machine['blueprint']['svg'] ?? '';

// Build machine dimension display pairs (label => value)
$machine_dims = [];
if (!empty($m['length']))         $machine_dims[__('Length', 'standard')]    = $m['length'];
if (!empty($m['width']))          $machine_dims[__('Width', 'standard')]     = $m['width'];
if (!empty($m['height']))         $machine_dims[__('Height', 'standard')]    = $m['height'];
if (!empty($m['weight']))         $machine_dims[__('Weight', 'standard')]    = $m['weight'];
if (!empty($m['length_slitter'])) $machine_dims[__('w/ Slitter', 'standard')] = $m['length_slitter'];
if (!empty($m['height_no_rack'])) $machine_dims[__('No Rack', 'standard')]  = $m['height_no_rack'];

// Build trailer dimension display pairs
$trailer_dims = [];
if (!empty($trailer['length'])) $trailer_dims[__('Length', 'standard')] = $trailer['length'];
if (!empty($trailer['width']))  $trailer_dims[__('Width', 'standard')]  = $trailer['width'];
if (!empty($trailer['height'])) $trailer_dims[__('Height', 'standard')] = $trailer['height'];
if (!empty($trailer['weight'])) $trailer_dims[__('Weight', 'standard')] = $trailer['weight'];

$cols = count($machine_dims);
?>

<section class="bg-slate-950 section" aria-labelledby="blueprint-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-emerald-400"><?php esc_html_e('Engineering Specs', 'standard'); ?></p>
            <h2 id="blueprint-title" class="text-3xl font-bold text-white md:text-4xl"><?php esc_html_e('Machine Footprint', 'standard'); ?></h2>
        </div>

        <div class="border border-slate-700 aspect-[16/7] flex items-center justify-center mx-auto max-w-4xl">
            <span class="text-slate-500 text-sm font-mono">[Blueprint SVG — <?php echo esc_html($svg); ?>]</span>
        </div>

        <?php if (!empty($machine_dims)) : ?>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-<?php echo esc_attr((string) $cols); ?> gap-6 max-w-4xl mx-auto">
                <?php foreach ($machine_dims as $label => $value) : ?>
                    <div class="text-center">
                        <span class="block text-lg font-bold text-white font-mono"><?php echo esc_html($value); ?></span>
                        <span class="block text-xs text-slate-500 uppercase tracking-wider mt-1"><?php echo esc_html($label); ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($trailer_dims)) : ?>
            <div class="border-t border-slate-800 pt-8">
                <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider text-center mb-6"><?php esc_html_e('On Trailer', 'standard'); ?></p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-3xl mx-auto">
                    <?php foreach ($trailer_dims as $label => $value) : ?>
                        <div class="text-center">
                            <span class="block text-lg font-bold text-white font-mono"><?php echo esc_html($value); ?></span>
                            <span class="block text-xs text-slate-500 uppercase tracking-wider mt-1"><?php echo esc_html($label); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</section>
```

---

### Task 8: Update gallery.php

**Files:**
- Modify: `app/templates/woo/product/parts/gallery.php`

- [ ] **Step 1: Replace gallery.php**

```php
<?php
/**
 * Machine Product — Gallery / Product Rotator
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$gallery = $machine['gallery'] ?? null;
$images  = $gallery['images'] ?? [];
$rotator = $gallery['rotator'] ?? [];

if (empty($images) && empty($rotator)) {
    return;
}
?>

<section class="section" aria-labelledby="gallery-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('360° View', 'standard'); ?></p>
            <h2 id="gallery-title" class="section-title"><?php esc_html_e('See Every Angle', 'standard'); ?></h2>
        </div>

        <div class="bg-slate-100 aspect-video max-w-4xl mx-auto flex items-center justify-center">
            <?php if (!empty($images[0])) : ?>
                <img src="<?php echo esc_url($images[0]); ?>" alt="<?php echo esc_attr($args['product']->get_name() ?? ''); ?>" class="w-full h-full object-contain" />
            <?php else : ?>
                <span class="text-slate-400 text-sm font-mono">[Product rotator / multi-angle gallery]</span>
            <?php endif; ?>
        </div>

        <?php if (!empty($images)) : ?>
            <div class="flex justify-center gap-3 max-w-4xl mx-auto">
                <?php foreach (array_slice($images, 0, 5) as $img) : ?>
                    <div class="w-16 h-16 bg-slate-200 flex items-center justify-center overflow-hidden">
                        <img src="<?php echo esc_url($img); ?>" alt="" class="w-full h-full object-cover" />
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
```

---

### Task 9: Update profile-selector.php

**Files:**
- Modify: `app/templates/woo/product/parts/profile-selector.php`

- [ ] **Step 1: Replace profile-selector.php**

```php
<?php
/**
 * Machine Product — Profile Selector
 *
 * Queries profiles by post_tag association from machine data.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine      = $args['machine'] ?? null;
$profile_data = $machine['profiles'] ?? null;

if (empty($profile_data['tag_slugs'])) {
    return;
}

$profiles = get_posts([
    'post_type'      => 'profile',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'tax_query'      => [[
        'taxonomy' => 'post_tag',
        'field'    => 'slug',
        'terms'    => $profile_data['tag_slugs'],
    ]],
]);

if (empty($profiles)) {
    return;
}
?>

<section class="section" aria-labelledby="profiles-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Panel Profiles', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="profiles-title" class="section-title"><?php esc_html_e('Available Profiles', 'standard'); ?></h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($profiles as $profile) :
                $thumb = get_the_post_thumbnail_url($profile->ID, 'medium');
                $link  = get_permalink($profile->ID);
            ?>
                <a href="<?php echo esc_url($link); ?>" class="border border-slate-200 bg-white p-4 text-center grid gap-2 hover:border-slate-400 transition-colors">
                    <div class="bg-slate-50 aspect-square flex items-center justify-center">
                        <?php if ($thumb) : ?>
                            <img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr($profile->post_title); ?>" class="w-full h-full object-contain p-2" />
                        <?php else : ?>
                            <span class="text-slate-400 text-xs font-mono"><?php echo esc_html($profile->post_title); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900"><?php echo esc_html($profile->post_title); ?></h3>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

---

### Task 10: Update social-proof.php

**Files:**
- Modify: `app/templates/woo/product/parts/social-proof.php`

- [ ] **Step 1: Replace social-proof.php**

```php
<?php
/**
 * Machine Product — Social Proof
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine      = $args['machine'] ?? null;
$testimonials = $machine['testimonials'] ?? [];

if (empty($testimonials)) {
    return;
}
?>

<section class="section bg-slate-900" aria-labelledby="social-proof-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary"><?php esc_html_e('Customer Stories', 'standard'); ?></p>
            <h2 id="social-proof-title" class="text-3xl font-bold text-white md:text-4xl"><?php esc_html_e('Trusted by Contractors Nationwide', 'standard'); ?></h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <?php foreach ($testimonials as $t) : ?>
                <blockquote class="border border-slate-700 p-6 grid gap-4">
                    <p class="text-slate-300 italic">"<?php echo esc_html($t['quote']); ?>"</p>
                    <footer class="text-sm text-slate-400">
                        <strong class="text-white"><?php echo esc_html($t['name']); ?></strong><?php
                        if (!empty($t['company'])) echo ', ' . esc_html($t['company']);
                        if (!empty($t['location'])) echo ', ' . esc_html($t['location']);
                    ?></footer>
                </blockquote>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

---

### Task 11: Update comparison.php

**Files:**
- Modify: `app/templates/woo/product/parts/comparison.php`

- [ ] **Step 1: Replace comparison.php**

Loads comparison machine data via `get_machine_product_data()`. Reads `hero.headline` for name, `finance.price_range` for price, and `comparison.best_for` for use case.

```php
<?php
/**
 * Machine Product — Machine Comparison
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

use function Standard\MachineProductData\get_machine_product_data;

$product    = $args['product'] ?? null;
$machine    = $args['machine'] ?? null;
$comparison = $machine['comparison'] ?? null;

if (!$product || empty($comparison['compare_with'])) {
    return;
}

$current_name  = $machine['hero']['headline'] ?? $product->get_name();
$current_price = $machine['finance']['price_range'] ?? '';
$current_best  = $comparison['best_for'] ?? '';
?>

<section class="section bg-slate-50" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Compare', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="comparison-title" class="section-title"><?php esc_html_e('Which Machine Is Right for You?', 'standard'); ?></h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <!-- Current machine -->
            <div class="border-2 border-secondary bg-white p-6 grid gap-3 text-center relative">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-secondary text-white text-xs font-semibold px-3 py-1 uppercase tracking-wider"><?php esc_html_e("You're Viewing", 'standard'); ?></span>
                <div class="bg-slate-100 aspect-square flex items-center justify-center mt-4">
                    <?php echo $product->get_image('woocommerce_thumbnail', ['class' => 'w-full h-full object-contain p-2']); ?>
                </div>
                <h3 class="text-lg font-bold text-slate-900"><?php echo esc_html($product->get_name()); ?></h3>
                <?php if ($current_best) : ?>
                    <p class="text-sm text-slate-500"><?php echo esc_html(sprintf(__('Best for: %s', 'standard'), $current_best)); ?></p>
                <?php endif; ?>
                <?php if ($current_price) : ?>
                    <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($current_price); ?></span>
                <?php endif; ?>
            </div>

            <!-- Comparison machines -->
            <?php foreach ($comparison['compare_with'] as $slug) :
                $comp = get_machine_product_data($slug);
                if (!$comp) continue;
                $comp_name  = $comp['hero']['headline'] ?? $slug;
                $comp_price = $comp['finance']['price_range'] ?? '';
                $comp_best  = $comp['comparison']['best_for'] ?? '';
            ?>
                <div class="border border-slate-200 bg-white p-6 grid gap-3 text-center">
                    <div class="bg-slate-100 aspect-square flex items-center justify-center">
                        <span class="text-slate-400 text-xs font-mono"><?php echo esc_html($comp_name); ?></span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900"><?php echo esc_html($comp_name); ?></h3>
                    <?php if ($comp_best) : ?>
                        <p class="text-sm text-slate-500"><?php echo esc_html(sprintf(__('Best for: %s', 'standard'), $comp_best)); ?></p>
                    <?php endif; ?>
                    <?php if ($comp_price) : ?>
                        <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($comp_price); ?></span>
                    <?php endif; ?>
                    <a href="/machines/<?php echo esc_attr($slug); ?>/" class="btn btn-sm btn-outline-dark mx-auto"><?php esc_html_e('Explore', 'standard'); ?></a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

---

### Task 12: Update accessories.php

**Files:**
- Modify: `app/templates/woo/product/parts/accessories.php`

- [ ] **Step 1: Replace accessories.php**

```php
<?php
/**
 * Machine Product — Accessories & Equipment
 *
 * Queries WooCommerce products by product_tag from machine data.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine  = $args['machine'] ?? null;
$acc_data = $machine['accessories'] ?? null;

if (empty($acc_data['product_tag'])) {
    return;
}

$accessories = wc_get_products([
    'tag'    => [$acc_data['product_tag']],
    'limit'  => 8,
    'status' => 'publish',
]);

if (empty($accessories)) {
    return;
}
?>

<section class="section" aria-labelledby="accessories-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Accessories', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="accessories-title" class="section-title"><?php esc_html_e('Complete Your Setup', 'standard'); ?></h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($accessories as $acc) : /** @var \WC_Product $acc */ ?>
                <a href="<?php echo esc_url($acc->get_permalink()); ?>" class="border border-slate-200 bg-white p-6 text-center grid gap-2 hover:border-slate-400 transition-colors">
                    <div class="bg-slate-100 aspect-square mb-2 flex items-center justify-center">
                        <?php echo $acc->get_image('woocommerce_thumbnail', ['class' => 'w-full h-full object-contain p-2']); ?>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900"><?php echo esc_html($acc->get_name()); ?></h3>
                    <?php if ($acc->get_short_description()) : ?>
                        <p class="text-xs text-slate-500"><?php echo wp_kses_post(wp_trim_words($acc->get_short_description(), 10)); ?></p>
                    <?php endif; ?>
                    <?php if ($acc->get_price_html()) : ?>
                        <span class="text-sm font-semibold text-slate-700"><?php echo wp_kses_post($acc->get_price_html()); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

---

### Task 13: Update specs-accordion.php

**Files:**
- Modify: `app/templates/woo/product/parts/specs-accordion.php`

- [ ] **Step 1: Replace specs-accordion.php**

Renders 8 accordion sections: Standard Features, Machine Dimensions, Performance, Materials, Coil, Power Options, Add-On Weights, Warranty & Patents.

```php
<?php
/**
 * Machine Product — Specifications Accordion
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$specs   = $machine['specs'] ?? null;

if (!$specs) {
    return;
}

// Build accordion sections dynamically from available data
$sections = [];

if (!empty($specs['standard_features'])) {
    $sections['Standard Features'] = $specs['standard_features'];
}
if (!empty($specs['dimensions'])) {
    $sections['Machine Dimensions'] = $specs['dimensions'];
}
if (!empty($specs['performance'])) {
    $sections['Performance Specs'] = $specs['performance'];
}
if (!empty($specs['materials'])) {
    $sections['Materials Formed'] = $specs['materials'];
}
if (!empty($specs['coil'])) {
    $sections['Coil Specifications'] = $specs['coil'];
}
if (!empty($specs['power_options'])) {
    $sections['Power Options'] = $specs['power_options'];
}
if (!empty($specs['add_on_weights'])) {
    $sections['Add-On Weights'] = $specs['add_on_weights'];
}
if (!empty($specs['warranty'])) {
    $sections['Warranty & Patents'] = $specs['warranty'];
}

if (empty($sections)) {
    return;
}

$resources = $machine['resources'] ?? null;
?>

<section class="section" aria-labelledby="specs-title">
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('Technical Specifications', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="specs-title" class="section-title"><?php esc_html_e('Full Details', 'standard'); ?></h2>
        </div>

        <div class="max-w-4xl grid gap-0">
            <?php foreach ($sections as $title => $data) : ?>
                <details class="border border-slate-200 -mt-px group">
                    <summary class="px-6 py-4 cursor-pointer flex items-center justify-between bg-white hover:bg-slate-50 transition-colors font-semibold text-slate-900">
                        <?php echo esc_html($title); ?>
                        <span class="text-slate-400 transition-transform group-open:rotate-180">&#9660;</span>
                    </summary>
                    <div class="px-6 py-6 border-t border-slate-200 text-sm text-slate-600">
                        <?php
                        switch ($title) {
                            case 'Standard Features':
                                echo '<ul class="grid gap-2">';
                                foreach ($data as $feature) {
                                    echo '<li class="flex items-start gap-2"><span class="w-1.5 h-1.5 rounded-full bg-secondary mt-1.5 shrink-0"></span>' . esc_html($feature) . '</li>';
                                }
                                echo '</ul>';
                                break;

                            case 'Machine Dimensions':
                                $m = $data['machine'] ?? [];
                                $t = $data['on_trailer'] ?? [];
                                if (!empty($m)) {
                                    echo '<h4 class="font-semibold text-slate-900 mb-2">' . esc_html__('Base Machine', 'standard') . '</h4>';
                                    echo '<dl class="grid grid-cols-2 gap-x-8 gap-y-2 mb-4">';
                                    foreach ($m as $key => $val) {
                                        $label = ucwords(str_replace('_', ' ', $key));
                                        echo '<dt class="text-slate-500">' . esc_html($label) . '</dt><dd class="font-mono">' . esc_html($val) . '</dd>';
                                    }
                                    echo '</dl>';
                                }
                                if (!empty($t)) {
                                    echo '<h4 class="font-semibold text-slate-900 mb-2">' . esc_html__('On Trailer', 'standard') . '</h4>';
                                    echo '<dl class="grid grid-cols-2 gap-x-8 gap-y-2">';
                                    foreach ($t as $key => $val) {
                                        $label = ucwords(str_replace('_', ' ', $key));
                                        echo '<dt class="text-slate-500">' . esc_html($label) . '</dt><dd class="font-mono">' . esc_html($val) . '</dd>';
                                    }
                                    echo '</dl>';
                                }
                                break;

                            case 'Performance Specs':
                                $shear = $data['shear'] ?? [];
                                $drive = $data['drive'] ?? [];
                                $speed = $data['speed'] ?? [];
                                if (!empty($shear)) {
                                    echo '<h4 class="font-semibold text-slate-900 mb-2">' . esc_html__('Shear', 'standard') . ': ' . esc_html($shear['type']) . '</h4>';
                                    echo '<ul class="grid gap-1 mb-4">';
                                    foreach ($shear['details'] as $d) echo '<li>' . esc_html($d) . '</li>';
                                    echo '</ul>';
                                }
                                if (!empty($drive)) {
                                    echo '<h4 class="font-semibold text-slate-900 mb-2">' . esc_html__('Drive', 'standard') . ': ' . esc_html($drive['type']) . '</h4>';
                                    echo '<ul class="grid gap-1 mb-4">';
                                    foreach ($drive['details'] as $d) echo '<li>' . esc_html($d) . '</li>';
                                    echo '</ul>';
                                }
                                if (!empty($speed)) {
                                    echo '<h4 class="font-semibold text-slate-900 mb-2">' . esc_html__('Speed', 'standard') . '</h4>';
                                    echo '<dl class="grid grid-cols-2 gap-x-8 gap-y-2">';
                                    foreach ($speed as $s) {
                                        echo '<dt class="text-slate-500">' . esc_html($s['source']) . '</dt><dd class="font-mono">' . esc_html($s['value']) . '</dd>';
                                    }
                                    echo '</dl>';
                                }
                                break;

                            case 'Materials Formed':
                                echo '<div class="grid gap-3">';
                                foreach ($data as $mat) {
                                    echo '<div>';
                                    echo '<h4 class="font-semibold text-slate-900">' . esc_html($mat['type']) . '</h4>';
                                    echo '<p class="font-mono">' . esc_html($mat['gauge']) . '</p>';
                                    if (!empty($mat['note'])) echo '<p class="text-slate-500 text-xs mt-1">' . esc_html($mat['note']) . '</p>';
                                    echo '</div>';
                                }
                                echo '</div>';
                                break;

                            case 'Coil Specifications':
                                echo '<dl class="grid grid-cols-2 gap-x-8 gap-y-2">';
                                if (!empty($data['widths']))              echo '<dt class="text-slate-500">' . esc_html__('Coil Widths', 'standard') . '</dt><dd class="font-mono">' . esc_html($data['widths']) . '</dd>';
                                if (!empty($data['finished_widths']))     echo '<dt class="text-slate-500">' . esc_html__('Finished Panel Widths', 'standard') . '</dt><dd class="font-mono">' . esc_html($data['finished_widths']) . '</dd>';
                                if (!empty($data['max_diameter_rack']))   echo '<dt class="text-slate-500">' . esc_html__('Max Coil Diameter (Rack)', 'standard') . '</dt><dd class="font-mono">' . esc_html($data['max_diameter_rack']) . '</dd>';
                                if (!empty($data['max_diameter_decoil'])) echo '<dt class="text-slate-500">' . esc_html__('Max Coil Diameter (Decoiler)', 'standard') . '</dt><dd class="font-mono">' . esc_html($data['max_diameter_decoil']) . '</dd>';
                                if (!empty($data['max_weight_reel']))     echo '<dt class="text-slate-500">' . esc_html__('Max Coil Weight (Reel)', 'standard') . '</dt><dd class="font-mono">' . esc_html($data['max_weight_reel']) . '</dd>';
                                if (!empty($data['max_weight_cradle']))   echo '<dt class="text-slate-500">' . esc_html__('Max Coil Weight (Cradle)', 'standard') . '</dt><dd class="font-mono">' . esc_html($data['max_weight_cradle']) . '</dd>';
                                echo '</dl>';
                                break;

                            case 'Power Options':
                                echo '<ul class="grid gap-2">';
                                foreach ($data as $option) echo '<li class="font-mono">' . esc_html($option) . '</li>';
                                echo '</ul>';
                                break;

                            case 'Add-On Weights':
                                echo '<dl class="grid grid-cols-2 gap-x-8 gap-y-2">';
                                foreach ($data as $item) {
                                    echo '<dt class="text-slate-500">' . esc_html($item['item']) . '</dt><dd class="font-mono">' . esc_html($item['weight']) . '</dd>';
                                }
                                echo '</dl>';
                                break;

                            case 'Warranty & Patents':
                                if (!empty($data['description'])) echo '<p class="mb-3">' . esc_html($data['description']) . '</p>';
                                if (!empty($data['patents'])) {
                                    echo '<p class="text-xs text-slate-500">';
                                    foreach ($data['patents'] as $patent) echo esc_html($patent) . '<br>';
                                    echo '</p>';
                                }
                                break;
                        }
                        ?>
                    </div>
                </details>
            <?php endforeach; ?>

            <?php if (!empty($resources['brochure'])) : ?>
                <div class="mt-4">
                    <a href="<?php echo esc_url($resources['brochure']); ?>" class="btn btn-sm btn-outline-dark"><?php esc_html_e('Download Full Spec Sheet', 'standard'); ?></a>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>
```

---

### Task 14: Update resources.php

**Files:**
- Modify: `app/templates/woo/product/parts/resources.php`

- [ ] **Step 1: Replace resources.php**

```php
<?php
/**
 * Machine Product — Resources & Support
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine   = $args['machine'] ?? null;
$resources = $machine['resources'] ?? null;

if (!$resources) {
    return;
}

$cards = [];
if (!empty($resources['manual'])) {
    $cards[] = ['label' => __('Machine Manual', 'standard'), 'url' => $resources['manual'], 'action' => __('View Manual', 'standard')];
}
if (!empty($resources['brochure'])) {
    $cards[] = ['label' => __('Product Brochure', 'standard'), 'url' => $resources['brochure'], 'action' => __('View Brochure', 'standard')];
}
if (!empty($resources['service_training_url'])) {
    $cards[] = ['label' => __('Service & Training', 'standard'), 'url' => $resources['service_training_url'], 'action' => __('Learn More', 'standard')];
}

if (empty($cards)) {
    return;
}
?>

<section class="section bg-slate-50" aria-labelledby="resources-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Resources', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="resources-title" class="section-title"><?php esc_html_e('Downloads & Support', 'standard'); ?></h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <?php foreach ($cards as $card) : ?>
                <a href="<?php echo esc_url($card['url']); ?>" class="border border-slate-200 bg-white p-6 text-center grid gap-2 hover:border-slate-400 transition-colors">
                    <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($card['label']); ?></span>
                    <span class="text-xs text-slate-500"><?php echo esc_html($card['action']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

---

### Task 15: Update configurator-finance.php

**Files:**
- Modify: `app/templates/woo/product/parts/configurator-finance.php`

- [ ] **Step 1: Replace configurator-finance.php**

```php
<?php
/**
 * Machine Product — Configurator & Financing
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? null;
$finance = $machine['finance'] ?? null;

if (!$product) {
    return;
}

$slug          = $product->get_slug();
$monthly_price = $finance['monthly_price'] ?? null;
$price_range   = $finance['price_range'] ?? null;
?>

<section class="section bg-slate-900" aria-labelledby="config-finance-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary"><?php esc_html_e('Get Started', 'standard'); ?></p>
            <h2 id="config-finance-title" class="text-3xl font-bold text-white md:text-4xl"><?php esc_html_e('Build It. Finance It. Own It.', 'standard'); ?></h2>
            <p class="text-slate-400 max-w-2xl mx-auto"><?php esc_html_e('Configure your machine with the exact options you need, then explore flexible financing to make it happen.', 'standard'); ?></p>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 max-w-5xl mx-auto">

            <!-- Configurator -->
            <div class="border border-slate-700 bg-slate-800 p-8 grid gap-6">
                <div class="bg-slate-700 aspect-video flex items-center justify-center rounded">
                    <span class="text-slate-400 text-sm font-mono">[Configurator preview]</span>
                </div>
                <div class="grid gap-3">
                    <h3 class="text-xl font-bold text-white"><?php esc_html_e('Build Your Machine', 'standard'); ?></h3>
                    <p class="text-sm text-slate-400"><?php esc_html_e('Choose your profiles, power pack, control system, and accessories. Get an instant quote or send your build to a specialist.', 'standard'); ?></p>
                    <ul class="grid gap-2 text-sm text-slate-300">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span><?php esc_html_e('Select profiles & tooling', 'standard'); ?></li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span><?php esc_html_e('Choose power pack & controls', 'standard'); ?></li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-secondary shrink-0"></span><?php esc_html_e('Add accessories & trailer', 'standard'); ?></li>
                    </ul>
                </div>
                <a href="/configurator/<?php echo esc_attr($slug); ?>/" class="btn btn-primary w-full"><?php esc_html_e('Open Configurator', 'standard'); ?></a>
            </div>

            <!-- Financing -->
            <div class="border border-slate-700 bg-slate-800 p-8 grid gap-6">
                <div class="grid gap-2 text-center py-6">
                    <?php if ($monthly_price) : ?>
                        <p class="text-sm text-slate-400 uppercase tracking-wider"><?php esc_html_e('Payments as low as', 'standard'); ?></p>
                        <p class="text-5xl font-bold text-white font-mono"><?php echo esc_html($monthly_price); ?></p>
                    <?php elseif ($price_range) : ?>
                        <p class="text-sm text-slate-400 uppercase tracking-wider"><?php esc_html_e('Starting at', 'standard'); ?></p>
                        <p class="text-5xl font-bold text-white font-mono"><?php echo esc_html($price_range); ?></p>
                    <?php endif; ?>
                </div>
                <div class="grid gap-3">
                    <h3 class="text-xl font-bold text-white"><?php esc_html_e('Flexible Financing', 'standard'); ?></h3>
                    <p class="text-sm text-slate-400"><?php esc_html_e('Most contractors pay off their machine within the first year from increased revenue. We make it easy to get started.', 'standard'); ?></p>
                    <ul class="grid gap-2 text-sm text-slate-300">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span><?php esc_html_e('Lease-to-own options', 'standard'); ?></li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span><?php esc_html_e('Seasonal payment plans', 'standard'); ?></li>
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span><?php esc_html_e('No-commitment quote in 24 hrs', 'standard'); ?></li>
                    </ul>
                </div>
                <a href="/machines/leasing-financing/" class="btn btn-outline-light w-full"><?php esc_html_e('Explore Financing', 'standard'); ?></a>
            </div>

        </div>

        <p class="text-center text-sm text-slate-500"><?php printf(wp_kses_post(__('Or <a href="%s" class="text-white underline">talk to a specialist</a> to discuss your specific needs.', 'standard')), esc_url('/contact/')); ?></p>

    </div>
</section>
```

---

### Task 16: Commit all template updates

- [ ] **Step 1: Stage and commit all template changes**

```bash
git add app/templates/woo/product/parts/hero.php \
       app/templates/woo/product/parts/stats-bar.php \
       app/templates/woo/product/parts/cta-finance.php \
       app/templates/woo/product/parts/machine-breakdown.php \
       app/templates/woo/product/parts/blueprint.php \
       app/templates/woo/product/parts/gallery.php \
       app/templates/woo/product/parts/profile-selector.php \
       app/templates/woo/product/parts/social-proof.php \
       app/templates/woo/product/parts/comparison.php \
       app/templates/woo/product/parts/accessories.php \
       app/templates/woo/product/parts/specs-accordion.php \
       app/templates/woo/product/parts/resources.php \
       app/templates/woo/product/parts/configurator-finance.php
git commit -m "feat: wire all machine product templates to data layer"
```

---

## Chunk 3: Remaining Data Files

### Task 17: Create all 9 remaining machine data files

**Files:**
- Create: `app/data/machines/ssq-ii-multipro.php`
- Create: `app/data/machines/ssh-multipro.php`
- Create: `app/data/machines/ssr-multipro-jr.php`
- Create: `app/data/machines/5vc-5v-crimp.php`
- Create: `app/data/machines/wav-wall-panel.php`
- Create: `app/data/machines/mach-ii-5-gutter.php`
- Create: `app/data/machines/mach-ii-6-gutter.php`
- Create: `app/data/machines/mach-ii-combo-gutter.php`
- Create: `app/data/machines/bg7-box-gutter.php`

Each file MUST follow the exact same array shape as `ssq3-multipro.php`. The data for each machine was fetched from the NTM website during brainstorming and is available in the conversation history. Key data per machine below.

**Implementation note:** These 9 files can be created in parallel by subagents. Each is independent. The subagent should reference the SSQ3 file as the template and populate with the correct machine data.

- [ ] **Step 1: Create `ssq-ii-multipro.php`**

Key data — SSQ II MultiPro:
- Hero: "16 Quick-Change Profiles. One Proven Machine."
- Stats: 16 profiles, 45 min changeover, 75 ft/min max, $115K–$130K
- Finance: price_range '$115K – $130K'
- Dimensions: same as SSQ3 (14'4" x 5'2" x 4'3", 2,830 lbs)
- Same speed specs, materials, coil, power options as SSQ3
- Standard features: 11 items (no QWIKSwap, no cover windows, no RFID, no strobe, no LEDs)
- Profiles tag: `['ssq-ii-multipro-roof-panel-machine']`
- Accessories tag: `'SSQII'`
- Compare: `['ssq3-multipro', 'ssh-multipro']`
- Schema: low 115000, high 130000
- Resources: SSQ II manual + brochure URLs from NTM

- [ ] **Step 2: Create `ssh-multipro.php`**

Key data — SSH MultiPro:
- Hero: "7 Profiles. Hydraulic Power. Residential Precision."
- Stats: 7 profiles, 60 ft/min, hydraulic shear, PLC included
- Finance: price_range '$69,200+'
- Dimensions: machine 12'10" x 4'10½" x 4'3", 2,360 lbs. Trailer 18'11" x 7'2½" x 6'3", 4,620 lbs
- 8 polyurethane drive rollers, hydraulic drive & shear
- Speed: single entry 60 ft/min
- Materials: painted steel 28-24ga, aluminum, copper, terne coat stainless
- Profiles tag: `['ssh-multipro-roof-panel-machine']`
- Accessories tag: `'SSH'`
- Compare: `['ssq-ii-multipro', 'ssr-multipro-jr']`
- Schema: low 69200, availability InStock

- [ ] **Step 3: Create `ssr-multipro-jr.php`**

Key data — SSR MultiPro Jr.:
- Hero: "Affordable Entry Into Portable Rollforming."
- Stats: 7 profiles, 30 ft/min, electric, $43,400+
- Finance: price_range '$43,400+'
- Dimensions: machine 12'6" x 3'10" x 4'3", 1,715 lbs. Trailer 18'11" x 7'2½" x 6'3", 3,975 lbs
- 8 polyurethane drive rollers, electric drive, manual shear
- EZE-Change profile roller system
- Motor: 1.5 HP, 120V, 60 Hz, 1PH, 18 amp
- Profiles tag: `['ssr-multipro-roof-panel-machine']`
- Accessories tag: `'SSR'`
- Compare: `['ssh-multipro', '5vc-5v-crimp']`
- Schema: low 43400, availability InStock

- [ ] **Step 4: Create `5vc-5v-crimp.php`**

Key data — 5VC 5V Crimp:
- Hero: "The Portable Solution to Your 5V Crimp Needs."
- Stats: 3 profiles, 60 ft/min, hydraulic, $70,800+
- Finance: price_range '$70,800+'
- Dimensions: machine 10'5" x 4'10½" x 4'3", 2,200 lbs. Trailer 18'11" x 7'2½" x 6'3", 4,460 lbs
- Polyurethane drive rollers, hydraulic drive & shear
- 3 profiles: 5VC-210P, 5VC-240P, 5VC-245P
- Materials: painted steel 30-24ga, aluminum, copper
- Profiles tag: `['5vc-5v-crimp-roof-panel-machine']`
- Accessories tag: `'5VC'`
- Compare: `['ssh-multipro', 'ssr-multipro-jr']`
- Schema: low 70800, availability InStock

- [ ] **Step 5: Create `wav-wall-panel.php`**

Key data — WAV Wall Panel:
- Hero: "The Industry's Only Portable WAV Profile Machine."
- Stats: 3 profiles, 150 ft/min electric, UNIQ standard, $232,000+
- Finance: price_range '$232,000+'
- Dimensions: machine 22'8" x 5'1" x 4'5", 5,000 lbs. Trailer 27'10" x 7'4" x 6'7", 8,700 lbs
- 25 polyurethane drive rollers, hydraulic drive & shear, VFD
- UNIQ Automatic Control System standard
- Speed: electric 150 ft/min, gas 75 ft/min
- Materials: painted steel 22-24ga Grade 50, aluminum .032"-.040" (16" profile only)
- Profiles tag: `['wav-wall-panel-machine']`
- Accessories tag: `'WAV'`
- Compare: `['ssq3-multipro', 'ssq-ii-multipro']`
- Schema: low 232000, availability InStock

- [ ] **Step 6: Create `mach-ii-5-gutter.php`**

Key data — MACH II 5" Gutter:
- Hero: "The Original Polyurethane Drive System. Since 1994."
- Stats: 50 ft/min, 1,000 lbs, 30+ years, $10,600+
- Finance: price_range '$10,600+'
- Dimensions: machine 8' x 2' x 4', 1,000 lbs. No trailer dims.
- Polyurethane drive rollers, stainless steel forming rollers, manual shear
- Motor: 3/4 HP, 110 VAC, 60 Hz, 1 Phase, 11 AMPs
- Materials: painted steel 30-24ga, aluminum .019"-.032", copper
- Coil: 11¾"–12⅜" width, max 30" OD, max 1,000 lbs reel, 400 lbs cradle
- 3 profiles: MG5-BB5, MG5-HK5, MG5-SB5
- Profiles tag: `['mach-ii-5-gutter-machine']`
- Accessories tag: `'MACHII'`
- Compare: `['mach-ii-6-gutter', 'mach-ii-combo-gutter']`
- Schema: low 10600, availability InStock

- [ ] **Step 7: Create `mach-ii-6-gutter.php`**

Key data — MACH II 6" Gutter:
- Hero: "6\" K-Style Gutters. The Industry Standard."
- Stats: 50 ft/min, 1,250 lbs, 30+ years, $12,300+
- Finance: price_range '$12,300+'
- Dimensions: machine 10' x 2' x 4', 1,250 lbs
- Same standard features as 5" model
- Material width: 15" (380mm)
- 3 profiles: MG6-SB6, MG6-HK6, MG6-BB6
- Profiles tag: `['mach-ii-6-gutter-machine']`
- Accessories tag: `'MACHII'`
- Compare: `['mach-ii-5-gutter', 'mach-ii-combo-gutter']`
- Schema: low 12300, availability InStock

- [ ] **Step 8: Create `mach-ii-combo-gutter.php`**

Key data — MACH II 5"/6" Combo:
- Hero: "5\" and 6\" K-Style in One Machine."
- Stats: 50 ft/min, 1,350 lbs, 2 sizes, $15,500+
- Finance: price_range '$15,500+'
- Dimensions: machine 10' x 2' x 4', 1,350 lbs
- Extra shear included for combo
- Both 5" and 6" material widths
- All 5" + 6" profiles (5 total)
- Profiles tag: `['mach-ii-5-gutter-machine', 'mach-ii-5-6-gutter-machine', 'mach-ii-6-gutter-machine']`
- Accessories tag: `'MACHII'`
- Compare: `['mach-ii-5-gutter', 'mach-ii-6-gutter']`
- Schema: low 15500, availability InStock

- [ ] **Step 9: Create `bg7-box-gutter.php`**

Key data — BG7 Box Gutter:
- Hero: "Two Profiles. One Commercial-Grade Machine."
- Stats: 60 ft/min, 2,600 lbs, 2 profiles, $69,200+
- Finance: price_range '$69,200+'
- Dimensions: machine 17'10" x 4'10½" x 4'3", 2,600 lbs. Trailer 21' x 7' x 6'3", 4,800 lbs
- Hydraulic drive & shear, polyurethane drive rollers
- Quick-Change Power-Pack (gas or electric)
- Materials: Grade 50 steel 26-22ga or aluminum .040"
- Coil width: 20" (508mm)
- 2 profiles: BG7-HK7, BG7-SB7
- Profiles tag: `['bg7-box-gutter-machine']`
- Accessories tag: `'BG7'`
- Compare: `['mach-ii-combo-gutter', 'mach-ii-6-gutter']`
- Schema: low 69200, availability InStock

- [ ] **Step 10: Commit all 9 data files**

```bash
git add app/data/machines/ssq-ii-multipro.php \
       app/data/machines/ssh-multipro.php \
       app/data/machines/ssr-multipro-jr.php \
       app/data/machines/5vc-5v-crimp.php \
       app/data/machines/wav-wall-panel.php \
       app/data/machines/mach-ii-5-gutter.php \
       app/data/machines/mach-ii-6-gutter.php \
       app/data/machines/mach-ii-combo-gutter.php \
       app/data/machines/bg7-box-gutter.php
git commit -m "feat: add data files for remaining 9 machines"
```

---

## Chunk 4: FAQ Template + Schema + Final Integration

### Task 18: Create FAQ template part

**Files:**
- Create: `app/templates/woo/product/parts/faq.php`

- [ ] **Step 1: Create faq.php**

```php
<?php
/**
 * Machine Product — FAQ Accordion
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$faqs    = $machine['faq'] ?? [];

if (empty($faqs)) {
    return;
}
?>

<section class="section bg-white" aria-labelledby="faq-title">
    <div class="container section-content max-w-3xl">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('FAQ', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="faq-title" class="section-title"><?php esc_html_e('Frequently Asked Questions', 'standard'); ?></h2>
        </div>

        <div class="grid gap-0 border-t border-slate-200">
            <?php foreach ($faqs as $i => $faq) : ?>
                <details class="group border-b border-slate-200" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary class="flex items-center justify-between gap-4 py-5 cursor-pointer list-none text-left font-semibold text-slate-900 hover:text-primary transition-colors">
                        <span><?php echo esc_html($faq['question']); ?></span>
                        <span class="shrink-0 text-slate-400 group-open:rotate-45 transition-transform text-xl leading-none">+</span>
                    </summary>
                    <div class="pb-5 text-slate-600 leading-relaxed">
                        <?php echo wp_kses_post($faq['answer']); ?>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

---

### Task 19: Create machine-schema.php

**Files:**
- Create: `app/inc/machine-schema.php`
- Modify: `app/functions.php`

- [ ] **Step 1: Create `app/inc/machine-schema.php`**

```php
<?php
/**
 * Machine Product JSON-LD Schema Generator
 *
 * Generates Product + FAQPage structured data for machine product pages.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineSchema;

/**
 * Render Product + FAQPage JSON-LD schema for a machine product.
 *
 * @param \WC_Product $product  WooCommerce product object.
 * @param array       $machine  Machine data array.
 */
function render_machine_schema(\WC_Product $product, array $machine): void {
    $product_schema = build_product_schema($product, $machine);
    if ($product_schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($product_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }

    $faq_schema = build_faq_schema($machine);
    if ($faq_schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}

/**
 * @param \WC_Product $product
 * @param array       $machine
 * @return array|null
 */
function build_product_schema(\WC_Product $product, array $machine): ?array {
    $overrides = $machine['schema'] ?? [];
    $hero      = $machine['hero'] ?? [];
    $specs     = $machine['specs'] ?? [];

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => $hero['subtitle'] ?? $product->get_short_description(),
        'url'         => get_permalink($product->get_id()),
        'image'       => wp_get_attachment_url($product->get_image_id()) ?: ($hero['image'] ?? ''),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => $overrides['brand'] ?? 'New Tech Machinery',
        ],
        'manufacturer' => [
            '@type' => 'Organization',
            'name'  => $overrides['manufacturer'] ?? 'New Tech Machinery',
        ],
    ];

    if (!empty($overrides['category'])) {
        $schema['category'] = $overrides['category'];
    }

    // Offers (AggregateOffer for price ranges, Offer for single price)
    if (!empty($overrides['low_price'])) {
        $offer = [
            '@type'         => 'AggregateOffer',
            'priceCurrency' => 'USD',
            'lowPrice'      => $overrides['low_price'],
        ];
        if (!empty($overrides['high_price'])) {
            $offer['highPrice'] = $overrides['high_price'];
        }
        if (!empty($overrides['availability'])) {
            $offer['availability'] = 'https://schema.org/' . $overrides['availability'];
        }
        $schema['offers'] = $offer;
    }

    // Additional properties from specs
    $properties = build_additional_properties($specs, $machine);
    if (!empty($properties)) {
        $schema['additionalProperty'] = $properties;
    }

    return $schema;
}

/**
 * @param array $specs
 * @param array $machine
 * @return array
 */
function build_additional_properties(array $specs, array $machine): array {
    $props = [];

    // Dimensions
    $dims = $specs['dimensions']['machine'] ?? [];
    if (!empty($dims['weight'])) {
        $props[] = pv('Weight', $dims['weight']);
    }
    if (!empty($dims['length'])) {
        $props[] = pv('Length', $dims['length']);
    }

    // Performance
    $perf = $specs['performance'] ?? [];
    if (!empty($perf['shear']['type'])) {
        $props[] = pv('Shear Type', $perf['shear']['type']);
    }
    if (!empty($perf['drive']['type'])) {
        $props[] = pv('Drive Type', $perf['drive']['type']);
    }
    if (!empty($perf['speed'][0]['value'])) {
        $props[] = pv('Max Speed', $perf['speed'][0]['value']);
    }

    // Stats (key marketing numbers)
    foreach (($machine['stats'] ?? []) as $stat) {
        $props[] = pv($stat['label'], $stat['value']);
    }

    // Materials
    foreach (($specs['materials'] ?? []) as $mat) {
        $props[] = pv('Material: ' . $mat['type'], $mat['gauge']);
    }

    // Warranty
    if (!empty($specs['warranty']['description'])) {
        $props[] = pv('Warranty', $specs['warranty']['description']);
    }

    return $props;
}

/**
 * Build a PropertyValue entry.
 *
 * @param string $name
 * @param string $value
 * @return array
 */
function pv(string $name, string $value): array {
    return [
        '@type' => 'PropertyValue',
        'name'  => $name,
        'value' => $value,
    ];
}

/**
 * @param array $machine
 * @return array|null
 */
function build_faq_schema(array $machine): ?array {
    $faqs = $machine['faq'] ?? [];
    if (empty($faqs)) {
        return null;
    }

    $entities = [];
    foreach ($faqs as $faq) {
        $entities[] = [
            '@type' => 'Question',
            'name'  => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $faq['answer'],
            ],
        ];
    }

    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];
}
```

- [ ] **Step 2: Add to functions.php includes**

In `app/functions.php`, add after line `'inc/machine-product-data.php',`:

```php
    'inc/machine-schema.php',
```

- [ ] **Step 3: Commit**

```bash
git add app/inc/machine-schema.php app/functions.php
git commit -m "feat: add Product + FAQPage JSON-LD schema generator"
```

---

### Task 20: Update single-machine.php

**Files:**
- Modify: `app/templates/woo/product/single-machine.php`

- [ ] **Step 1: Add `use function` import at top of file**

Add this line after the existing `use function` statement at line 14:

```php
use function Standard\MachineSchema\render_machine_schema;
```

- [ ] **Step 2: Add FAQ template part**

Add between the `resources` and `configurator-finance` parts (after line 61):

```php
    <?php get_template_part('templates/woo/product/parts/faq', null, compact('machine')); ?>
```

- [ ] **Step 3: Replace schema call**

Replace lines 78-79:

```php
// Old:
WC()->structured_data->generate_product_data($product);
do_action('woocommerce_after_single_product');

// New:
render_machine_schema($product, $machine);
do_action('woocommerce_after_single_product');
```

- [ ] **Step 4: Commit**

```bash
git add app/templates/woo/product/single-machine.php app/templates/woo/product/parts/faq.php
git commit -m "feat: add FAQ section and JSON-LD schema to machine product pages"
```

---

### Task 21: Final verification

- [ ] **Step 1: Verify SSQ3 page renders fully**

Open the SSQ3 MultiPro product page. Check:
- Hero shows "Produce 16 Panel Profiles On-Site. One Machine." with background image
- Stats bar shows 4 metrics (16, 25 min, 75 ft/min, $2.25/sq ft)
- Finance CTA shows "Starting at $121K – $137K"
- Breakdown shows 4 subsystems with spec bullets
- Blueprint shows dimensions from specs (6 data points + 4 trailer)
- Specs accordion has 8 sections with real data (Standard Features, Dimensions, Performance, Materials, Coil, Power, Add-On Weights, Warranty)
- FAQ accordion renders with 5 questions
- Resources shows 3 cards with real URLs
- Sections with empty data (gallery, testimonials) are hidden

- [ ] **Step 2: Verify JSON-LD in page source**

View page source, search for `application/ld+json`. Verify:
- Product schema with name, offers (low/high price), additionalProperty array
- FAQPage schema with all 5 questions

- [ ] **Step 3: Spot-check a gutter machine**

Open the MACH II 5" page. Verify different data renders: different dimensions, different specs shape (no length_slitter, has max_weight_reel), different profiles.
