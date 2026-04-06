# Machines Page Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Build a `/machines` landing page showcasing NTM's full product lineup with SSQ3 flagship spotlight, UNIQ technology section, machine grid, comparison table, and CTAs.

**Architecture:** Follows the existing front-page pattern — `page-machines.php` orchestrates template parts via `get_template_part()`. Machine data is hardcoded in `app/inc/machines-data.php`. Template parts live in `app/templates/pages/machines/`.

**Tech Stack:** PHP 8.0+, Tailwind CSS v4, Vite, existing theme component classes

**Design Doc:** `docs/plans/2026-03-03-machines-page-design.md`

**Reference Data:** `docs/ntm-machines.md`

---

### Task 1: Create the data layer — `app/inc/machines-data.php`

**Files:**
- Create: `app/inc/machines-data.php`
- Modify: `app/functions.php` (add to includes array)

**Step 1: Create `app/inc/machines-data.php`**

This file provides all machine data from the research brief as structured PHP arrays. It uses the `Standard\MachinesData` namespace (distinct from the existing `Standard\Machines` namespace in `inc/machines.php`).

```php
<?php
/**
 * Machines Page Data
 *
 * Hardcoded machine data for the /machines landing page.
 * Sourced from docs/ntm-machines.md research brief.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachinesData;

/**
 * Get all machines organized by category.
 *
 * @return array<string, array{label: string, machines: array}>
 */
function get_machine_categories(): array {
    $uploads_url = 'https://newtechmachinery.com/wp-content/uploads';

    return [
        'roof-wall' => [
            'label' => 'Roof & Wall Panel Machines',
            'machines' => [
                [
                    'slug'    => 'ssq3-multipro',
                    'name'    => 'SSQ3™ MultiPro',
                    'tagline' => 'The future of portable roll forming.',
                    'image'   => $uploads_url . '/2026/01/Screenshot-2026-01-07-at-9.37.43-AM.png',
                    'url'     => '/machines/ssq3-multipro/',
                    'badge'   => 'New — Flagship',
                    'specs'   => [
                        'profiles' => '16',
                        'speed'    => 'High-speed',
                        'power'    => 'Gas/Electric (QCPP)',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Commercial + Residential',
                    ],
                ],
                [
                    'slug'    => 'ssq-ii-multipro',
                    'name'    => 'SSQ II™ MultiPro',
                    'tagline' => 'Versatility meets precision.',
                    'image'   => $uploads_url . '/2025/12/starting-SSQ-on-job-site-1024x576-1.jpg',
                    'url'     => '/machines/ssq-ii-multipro/',
                    'badge'   => '',
                    'specs'   => [
                        'profiles' => '16',
                        'speed'    => '~75 FPM',
                        'power'    => 'Gas/Electric (QCPP)',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Commercial + Residential',
                    ],
                ],
                [
                    'slug'    => 'ssh-multipro',
                    'name'    => 'SSH™ MultiPro',
                    'tagline' => 'Built for standing seam perfection.',
                    'image'   => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                    'url'     => '/machines/ssh-multipro/',
                    'badge'   => '',
                    'specs'   => [
                        'profiles' => '7',
                        'speed'    => '~60 FPM',
                        'power'    => 'Gas/Electric (QCPP)',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Residential + Light Commercial',
                    ],
                ],
                [
                    'slug'    => 'ssr-multipro-jr',
                    'name'    => 'SSR™ MultiPro Jr.',
                    'tagline' => 'Compact power, professional results.',
                    'image'   => $uploads_url . '/2023/05/5V-on-site.jpg',
                    'url'     => '/machines/ssr-multipro-jr/',
                    'badge'   => '',
                    'specs'   => [
                        'profiles' => 'Multiple',
                        'speed'    => '~30 FPM',
                        'power'    => 'Electric only',
                        'shear'    => 'Manual',
                        'best_for' => 'Entry-level / Residential',
                    ],
                ],
                [
                    'slug'    => '5vc-5v-crimp',
                    'name'    => '5V Crimp',
                    'tagline' => 'Classic profiles, modern efficiency.',
                    'image'   => $uploads_url . '/2023/05/5V-on-site.jpg',
                    'url'     => '/machines/5vc-5v-crimp/',
                    'badge'   => '',
                    'specs'   => [
                        'profiles' => '5V Crimp',
                        'speed'    => '—',
                        'power'    => '—',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Exposed fastener roofing',
                    ],
                ],
            ],
        ],
        'wall' => [
            'label' => 'Wall Panel Machines',
            'machines' => [
                [
                    'slug'    => 'wav-wall-panel',
                    'name'    => 'WAV™',
                    'tagline' => 'Purpose-built for wall panel production at scale.',
                    'image'   => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                    'url'     => '/machines/wav-wall-panel/',
                    'badge'   => '',
                    'specs'   => [
                        'profiles' => '4',
                        'speed'    => '—',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Heavy commercial/industrial walls',
                    ],
                ],
            ],
        ],
        'gutter' => [
            'label' => 'Seamless Gutter Machines',
            'machines' => [
                [
                    'slug'    => 'mach-ii-gutter',
                    'name'    => 'MACH II™',
                    'tagline' => 'The industry standard for K-style gutter production.',
                    'image'   => $uploads_url . '/2024/07/20240612_NTM_CS-Rain-Gutters-Interview_V1.00_03_30_06.Still002.jpg',
                    'url'     => '/machines/mach-ii/',
                    'badge'   => '',
                    'specs'   => [
                        'profiles' => 'K-style gutter',
                        'speed'    => '~50 FPM',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Seamless gutter production',
                    ],
                ],
            ],
        ],
    ];
}

/**
 * Get all machines as a flat array.
 *
 * @return array
 */
function get_all_machines(): array {
    $all = [];
    foreach (get_machine_categories() as $category) {
        foreach ($category['machines'] as $machine) {
            $all[] = $machine;
        }
    }
    return $all;
}

/**
 * Get SSQ3 feature improvements for the spotlight section.
 *
 * @return array<int, array{title: string, text: string}>
 */
function get_ssq3_features(): array {
    return [
        [
            'title' => 'Sealed Drive Gear Covers',
            'text'  => 'Inspection windows protect components against dust and debris.',
        ],
        [
            'title' => 'RFID Cover Sensors',
            'text'  => 'Controller identifies exactly which cover is open.',
        ],
        [
            'title' => 'Shear Warning Strobe',
            'text'  => 'Line-of-sight safety cue before and during shear cycles.',
        ],
        [
            'title' => 'Interior LED Lighting',
            'text'  => '8 strategically placed LEDs — work even when machine is off, LOTO-friendly.',
        ],
        [
            'title' => 'Improved Safety Guarding',
            'text'  => 'Bottom guarding limits hand access. Slug funnel controls scrap for cleaner floors.',
        ],
        [
            'title' => 'Simplified Maintenance',
            'text'  => 'Better visibility, faster adjustments, simpler service overall.',
        ],
    ];
}

/**
 * Get UNIQ control system features for the technology spotlight.
 *
 * @return array<int, array{title: string, text: string}>
 */
function get_uniq_features(): array {
    return [
        [
            'title' => 'Touchscreen Interface',
            'text'  => 'Batch and length control with intuitive touch operation.',
        ],
        [
            'title' => 'Cut List Upload',
            'text'  => 'Upload cut lists directly — integrated with AppliCad software.',
        ],
        [
            'title' => 'Built-in Troubleshooting',
            'text'  => 'Error messages with help pages and videos. Operators diagnose and fix problems independently.',
        ],
        [
            'title' => 'CE-Compliant Safety',
            'text'  => 'Controls start drive, notching, and shear functions with safety compliance.',
        ],
    ];
}

/**
 * Get key differentiators for the 3-card section.
 *
 * @return array<int, array{icon: string, title: string, text: string}>
 */
function get_differentiators(): array {
    return [
        [
            'icon'  => 'settings',
            'title' => 'On-Site Fabrication',
            'text'  => 'Eliminate factory delays, transportation costs, and panel damage risks. Produce exactly what you need, where you need it.',
        ],
        [
            'icon'  => 'trending-up',
            'title' => 'Up to 16 Profiles',
            'text'  => 'Standing seam, flush wall, and board & batten siding — maximum versatility from a single machine.',
        ],
        [
            'icon'  => 'link',
            'title' => 'Retrofit-Friendly',
            'text'  => 'New innovations can often be retrofitted to existing NTM equipment, protecting your investment.',
        ],
    ];
}
```

**Step 2: Register the include in `app/functions.php`**

Add `'inc/machines-data.php'` to the `$theme_includes` array, after `'inc/products.php'`:

```php
$theme_includes = [
    'inc/vite.php',
    'inc/setup.php',
    'inc/sidebars.php',
    'inc/fonts.php',
    'inc/icons.php',
    'inc/related-posts.php',
    'inc/machines.php',
    'inc/products.php',
    'inc/machines-data.php',
    'inc/walkers/class-pagination.php',
    'inc/walkers/class-mobile-nav-walker.php',
    'inc/walkers/class-primary-nav-walker.php',
];
```

**Step 3: Add Tailwind source directive for new template directory**

In `app/resources/css/_app.css`, add a source line for the new `templates/pages/` directory so Tailwind scans those PHP files for classes:

```css
@source "../../../templates/pages/**/*.php";
```

Add it after the existing `@source "../../../templates/parts/**/*.php";` line.

**Step 4: Commit**

```bash
git add app/inc/machines-data.php app/functions.php app/resources/css/_app.css
git commit -m "feat(machines): add hardcoded machine data layer and Tailwind source directive"
```

---

### Task 2: Create page template and directory structure

**Files:**
- Create: `app/page-machines.php`
- Create: `app/templates/pages/machines/` (directory)

**Step 1: Create the directory**

```bash
mkdir -p app/templates/pages/machines
```

**Step 2: Create `app/page-machines.php`**

This is the orchestrator, following the same pattern as `front-page.php`.

```php
<?php
/**
 * Template Name: Machines
 *
 * Landing page showcasing NTM's full product lineup.
 * Inspired by Toyota's trucks landing page layout.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/machines/hero'); ?>

    <?php get_template_part('templates/pages/machines/brand-statement'); ?>

    <?php get_template_part('templates/pages/machines/lineup-grid'); ?>

    <?php get_template_part('templates/pages/machines/ssq3-spotlight'); ?>

    <?php get_template_part('templates/pages/machines/image-break'); ?>

    <?php get_template_part('templates/pages/machines/uniq-spotlight'); ?>

    <?php get_template_part('templates/pages/machines/differentiators'); ?>

    <?php get_template_part('templates/pages/machines/comparison-table'); ?>

    <?php get_template_part('templates/pages/machines/final-cta'); ?>

</main>

<?php
get_footer();
```

**Step 3: Commit**

```bash
git add app/page-machines.php app/templates/pages/machines/
git commit -m "feat(machines): add page template and directory structure"
```

---

### Task 3: Hero banner section

**Files:**
- Create: `app/templates/pages/machines/hero.php`

**Step 1: Create `app/templates/pages/machines/hero.php`**

Full-width hero with dark overlay, headline, subtext, and scroll CTA. Pattern follows `hero-slider.php` but as a static hero.

```php
<?php
/**
 * Machines Page — Hero Banner
 *
 * Full-width hero image with headline overlay.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'title'    => __("The World's Finest Portable Rollforming Equipment", 'standard'),
    'subtitle' => __('Machines built for the metal construction industry since 1991.', 'standard'),
    'cta_text' => __('Explore the Lineup', 'standard'),
    'cta_url'  => '#lineup',
    'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
];
?>

<section class="relative min-h-[60vh] lg:min-h-[70vh] flex items-center justify-center overflow-hidden" aria-labelledby="machines-hero-title">
    <img
        src="<?php echo esc_url($content['image']); ?>"
        alt=""
        class="absolute inset-0 w-full h-full object-cover"
        fetchpriority="high"
    >
    <div class="absolute inset-0 bg-slate-950/60"></div>

    <div class="relative z-10 container text-center grid gap-6 py-20">
        <h1 id="machines-hero-title" class="text-3xl font-bold text-white md:text-5xl lg:text-6xl max-w-4xl mx-auto">
            <?php echo esc_html($content['title']); ?>
        </h1>
        <p class="text-lg text-slate-200 md:text-xl max-w-2xl mx-auto">
            <?php echo esc_html($content['subtitle']); ?>
        </p>
        <div>
            <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-secondary btn-lg">
                <?php echo esc_html($content['cta_text']); ?>
                <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>
    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/hero.php
git commit -m "feat(machines): add hero banner section"
```

---

### Task 4: Brand statement section

**Files:**
- Create: `app/templates/pages/machines/brand-statement.php`

**Step 1: Create `app/templates/pages/machines/brand-statement.php`**

Centered text block — mirrors Toyota's "Strength Without Limits" section.

```php
<?php
/**
 * Machines Page — Brand Statement
 *
 * Centered brand value proposition text block.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'title' => __('Built for the Jobsite', 'standard'),
    'text'  => __('New Tech Machinery manufactures portable rollforming machines that let contractors fabricate metal panels on-site or in-plant — eliminating factory delays, transportation costs, and damage risks. From standing seam roofing to seamless gutters, NTM machines are trusted in 40+ countries across all seven continents.', 'standard'),
];
?>

<section class="section" aria-labelledby="brand-statement-title">
    <div class="container grid gap-6 text-center max-w-3xl mx-auto">
        <div class="section-divider-center"></div>
        <h2 id="brand-statement-title" class="text-3xl font-bold text-slate-900 md:text-4xl">
            <?php echo esc_html($content['title']); ?>
        </h2>
        <p class="text-lg text-slate-600 leading-relaxed">
            <?php echo esc_html($content['text']); ?>
        </p>
    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/brand-statement.php
git commit -m "feat(machines): add brand statement section"
```

---

### Task 5: Machine lineup grid section

**Files:**
- Create: `app/templates/pages/machines/lineup-grid.php`

**Step 1: Create `app/templates/pages/machines/lineup-grid.php`**

Responsive grid of all machines grouped by category. Uses the data from `machines-data.php`.

```php
<?php
/**
 * Machines Page — Lineup Grid
 *
 * Responsive grid of all machines grouped by category.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_machine_categories;

$content = [
    'eyebrow' => __('Our Machines', 'standard'),
    'title'   => __('Machines for Every Project', 'standard'),
];

$categories = get_machine_categories();
?>

<section id="lineup" class="section bg-slate-50" aria-labelledby="lineup-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="lineup-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid gap-12">
            <?php foreach ($categories as $category) : ?>
                <div class="grid gap-6">
                    <h3 class="text-lg font-semibold text-slate-500 uppercase tracking-wider">
                        <?php echo esc_html($category['label']); ?>
                    </h3>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($category['machines'] as $machine) : ?>
                            <a href="<?php echo esc_url($machine['url']); ?>" class="card group grid grid-rows-[auto_1fr_auto] no-underline">
                                <div class="relative bg-slate-100 overflow-hidden">
                                    <img
                                        src="<?php echo esc_url($machine['image']); ?>"
                                        alt="<?php echo esc_attr($machine['name']); ?>"
                                        class="w-full aspect-4/3 object-cover transition-transform duration-300 group-hover:scale-105"
                                        loading="lazy"
                                    >
                                    <?php if (!empty($machine['badge'])) : ?>
                                        <span class="absolute top-3 left-3 px-2 py-1 text-xs font-semibold uppercase tracking-wide text-white bg-secondary">
                                            <?php echo esc_html($machine['badge']); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="p-5 grid gap-2 content-start">
                                    <h4 class="text-xl font-bold text-slate-900">
                                        <?php echo esc_html($machine['name']); ?>
                                    </h4>
                                    <p class="text-sm text-slate-600">
                                        <?php echo esc_html($machine['tagline']); ?>
                                    </p>
                                </div>
                                <div class="px-5 pb-5">
                                    <span class="inline-flex items-center gap-1 text-sm font-medium text-primary">
                                        <?php esc_html_e('Learn More', 'standard'); ?>
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                    </span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/lineup-grid.php
git commit -m "feat(machines): add machine lineup grid section"
```

---

### Task 6: SSQ3 spotlight section

**Files:**
- Create: `app/templates/pages/machines/ssq3-spotlight.php`

**Step 1: Create `app/templates/pages/machines/ssq3-spotlight.php`**

Split layout featuring the SSQ3 flagship. Follows the pattern of `value-prop.php` (two-column, image + content).

```php
<?php
/**
 * Machines Page — SSQ3 Spotlight
 *
 * Feature spotlight for the SSQ3 flagship machine.
 * Two-column split: image left, features right.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_ssq3_features;

$content = [
    'eyebrow'  => __('New — Flagship', 'standard'),
    'title'    => __('SSQ3™ MultiPro Roof Panel Machine', 'standard'),
    'subtitle' => __("NTM's latest and most advanced portable rollformer, building on the proven SSQ II platform. Up to 16 panel profiles with high-speed hydraulic drive and advanced touchscreen controls.", 'standard'),
    'image'    => 'https://newtechmachinery.com/wp-content/uploads/2026/01/Screenshot-2026-01-07-at-9.37.43-AM.png',
    'cta_text' => __('Learn More About SSQ3', 'standard'),
    'cta_url'  => '/machines/ssq3-multipro/',
];

$features = get_ssq3_features();
?>

<section class="section" aria-labelledby="ssq3-spotlight-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div class="order-2 lg:order-1">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['title']); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                >
            </div>

            <div class="order-1 lg:order-2 grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="ssq3-spotlight-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle">
                        <?php echo esc_html($content['subtitle']); ?>
                    </p>
                </div>

                <ul class="space-y-4">
                    <?php foreach ($features as $feature) : ?>
                        <li class="flex gap-4">
                            <span class="shrink-0 mt-1">
                                <?php icon('check', ['class' => 'w-4 h-4 text-green-600']); ?>
                            </span>
                            <div>
                                <h3 class="font-semibold text-slate-900 mb-0.5">
                                    <?php echo esc_html($feature['title']); ?>
                                </h3>
                                <p class="text-sm text-slate-600">
                                    <?php echo esc_html($feature['text']); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div>
                    <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-primary">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/ssq3-spotlight.php
git commit -m "feat(machines): add SSQ3 flagship spotlight section"
```

---

### Task 7: Full-bleed image break

**Files:**
- Create: `app/templates/pages/machines/image-break.php`

**Step 1: Create `app/templates/pages/machines/image-break.php`**

Simple full-width image section for visual breathing room.

```php
<?php
/**
 * Machines Page — Image Break
 *
 * Full-bleed lifestyle/jobsite photo for visual breathing room.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'image' => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
    'alt'   => __('NTM rollforming machine on a jobsite rooftop', 'standard'),
];
?>

<section class="relative" aria-label="<?php echo esc_attr($content['alt']); ?>">
    <img
        src="<?php echo esc_url($content['image']); ?>"
        alt="<?php echo esc_attr($content['alt']); ?>"
        class="w-full h-64 md:h-80 lg:h-96 object-cover"
        loading="lazy"
    >
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/image-break.php
git commit -m "feat(machines): add full-bleed image break section"
```

---

### Task 8: UNIQ technology spotlight section

**Files:**
- Create: `app/templates/pages/machines/uniq-spotlight.php`

**Step 1: Create `app/templates/pages/machines/uniq-spotlight.php`**

Dark background technology section — mirrors Toyota's i-FORCE MAX layout.

```php
<?php
/**
 * Machines Page — UNIQ Technology Spotlight
 *
 * Dark background section showcasing the UNIQ Automatic Control System.
 * Mirrors Toyota's i-FORCE MAX technology section.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_uniq_features;

$content = [
    'eyebrow'      => __('Technology', 'standard'),
    'title'        => __('UNIQ® Automatic Control System', 'standard'),
    'subtitle'     => __("NTM's most advanced programmable controller — designed to improve automation, safety, and the operator experience.", 'standard'),
    'availability' => __('Standard on WAV · Optional on SSQ II & SSQ3', 'standard'),
];

$features = get_uniq_features();
?>

<section class="section bg-slate-900" aria-labelledby="uniq-spotlight-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="uniq-spotlight-title" class="text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-slate-400 max-w-2xl mx-auto">
                <?php echo esc_html($content['subtitle']); ?>
            </p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-4 max-w-5xl mx-auto">
            <?php foreach ($features as $feature) : ?>
                <div class="grid gap-3 content-start text-center">
                    <h3 class="text-lg font-semibold text-white">
                        <?php echo esc_html($feature['title']); ?>
                    </h3>
                    <p class="text-sm text-slate-400">
                        <?php echo esc_html($feature['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <p class="text-center text-sm font-mono uppercase tracking-wider text-slate-500">
            <?php echo esc_html($content['availability']); ?>
        </p>

    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/uniq-spotlight.php
git commit -m "feat(machines): add UNIQ technology spotlight section"
```

---

### Task 9: Key differentiators section

**Files:**
- Create: `app/templates/pages/machines/differentiators.php`

**Step 1: Create `app/templates/pages/machines/differentiators.php`**

3-card grid highlighting NTM's key selling points. Uses existing icons.

```php
<?php
/**
 * Machines Page — Key Differentiators
 *
 * Three-card grid highlighting NTM's key selling points.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_differentiators;

$differentiators = get_differentiators();
?>

<section class="section bg-slate-50" aria-labelledby="differentiators-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php esc_html_e('Why NTM', 'standard'); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="differentiators-title" class="section-title">
                <?php esc_html_e('The NTM Difference', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-8 md:grid-cols-3">
            <?php foreach ($differentiators as $item) : ?>
                <div class="grid gap-4 content-start text-center p-8 bg-white border border-slate-200">
                    <div class="flex justify-center">
                        <?php icon($item['icon'], ['class' => 'w-8 h-8 text-primary']); ?>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900">
                        <?php echo esc_html($item['title']); ?>
                    </h3>
                    <p class="text-slate-600">
                        <?php echo esc_html($item['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/differentiators.php
git commit -m "feat(machines): add key differentiators section"
```

---

### Task 10: Comparison table section

**Files:**
- Create: `app/templates/pages/machines/comparison-table.php`

**Step 1: Create `app/templates/pages/machines/comparison-table.php`**

Responsive specs comparison table with all machines. SSQ3 row highlighted.

```php
<?php
/**
 * Machines Page — Comparison Table
 *
 * Responsive machine specs comparison table.
 * Horizontal scroll on mobile, full table on desktop.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_all_machines;

$content = [
    'eyebrow' => __('Compare', 'standard'),
    'title'   => __('Machine Comparison', 'standard'),
];

$machines = get_all_machines();

$columns = [
    'name'     => __('Machine', 'standard'),
    'profiles' => __('Profiles', 'standard'),
    'speed'    => __('Speed', 'standard'),
    'power'    => __('Power', 'standard'),
    'shear'    => __('Shear', 'standard'),
    'best_for' => __('Best For', 'standard'),
];
?>

<section class="section" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="comparison-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="overflow-x-auto -mx-4 px-4">
            <table class="w-full text-left text-sm min-w-[700px]">
                <thead>
                    <tr class="border-b-2 border-slate-300">
                        <?php foreach ($columns as $col) : ?>
                            <th class="py-3 px-4 font-semibold text-slate-900 whitespace-nowrap">
                                <?php echo esc_html($col); ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($machines as $machine) : ?>
                        <?php $is_flagship = !empty($machine['badge']); ?>
                        <tr class="border-b border-slate-200 <?php echo $is_flagship ? 'bg-primary/5 font-medium' : ''; ?>">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <a href="<?php echo esc_url($machine['url']); ?>" class="text-primary font-semibold hover:underline">
                                    <?php echo esc_html($machine['name']); ?>
                                </a>
                                <?php if ($is_flagship) : ?>
                                    <span class="ml-2 px-1.5 py-0.5 text-xs font-semibold uppercase bg-secondary text-white">
                                        <?php esc_html_e('New', 'standard'); ?>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['profiles']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['speed']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['power']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['shear']); ?>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                <?php echo esc_html($machine['specs']['best_for']); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/comparison-table.php
git commit -m "feat(machines): add machine comparison table section"
```

---

### Task 11: Final CTA section

**Files:**
- Create: `app/templates/pages/machines/final-cta.php`

**Step 1: Create `app/templates/pages/machines/final-cta.php`**

Follows the same pattern as the front-page `final-cta.php`.

```php
<?php
/**
 * Machines Page — Final CTA
 *
 * Closing call-to-action section.
 * Dark background for visual weight before footer.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'title'             => __('Ready to Roll?', 'standard'),
    'text'              => __("Whether you're expanding your business or buying your first machine, we're here to help you find the right fit.", 'standard'),
    'cta_primary'       => __('Talk to a Specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('Build & Finance', 'standard'),
    'cta_secondary_url' => '/build-finance/',
];
?>

<section class="section bg-slate-900" aria-labelledby="machines-final-cta-title">
    <div class="container grid gap-8 lg:gap-10 text-center">

        <div class="grid gap-4">
            <h2 id="machines-final-cta-title" class="text-3xl font-bold text-white md:text-4xl lg:text-5xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-slate-300 max-w-2xl mx-auto">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url($content['cta_primary_url']); ?>" class="btn btn-secondary btn-lg">
                <?php echo esc_html($content['cta_primary']); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light btn-lg">
                <?php echo esc_html($content['cta_secondary']); ?>
            </a>
        </div>

    </div>
</section>
```

**Step 2: Commit**

```bash
git add app/templates/pages/machines/final-cta.php
git commit -m "feat(machines): add final CTA section"
```

---

### Task 12: Verify the build

**Step 1: Run the Vite build to ensure no CSS/asset issues**

```bash
npm run build
```

Expected: Build completes successfully with no errors.

**Step 2: Verify all files exist**

```bash
ls -la app/page-machines.php app/inc/machines-data.php app/templates/pages/machines/
```

Expected: All 11 files present (page template + data layer + 9 section templates).

**Step 3: Commit build output if needed**

```bash
git add app/dist/
git commit -m "build: update production assets for machines page"
```
