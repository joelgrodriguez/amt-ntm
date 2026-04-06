# Machines Page Sales Enhancement — Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Transform the machines page from a product catalog into a sales page by adding bottom-of-funnel content: ROI data, a real customer story, and a "which machine?" decision helper — plus revising hero/brand copy to lead with outcomes.

**Architecture:** 3 new template parts in `app/templates/pages/machines/`, 2 revised existing templates, 1 new data function in `machines-data.php`, and updated section order in `page-machines.php`. All new sections follow the existing pattern: `$content` array at top, semantic HTML, Tailwind utilities, `icon()` helper, `esc_*` escaping.

**Tech Stack:** PHP 8.0+ (WordPress classic theme), Tailwind CSS v4, Vite build pipeline.

**Key URLs (existing NTM assets to link to):**
- Profit Calculator: `/learning-center/download/portable-rollforming-profit-calculator/`
- Machine Quiz: `/roof-panel-machine-assessment-quiz/`
- ROI Article: `/learning-center/what-is-the-roi-for-a-portable-metal-roof-panel-machine/`
- Customer ROI Article: `/learning-center/ntm-customers-roi-behind-portable-standing-seam-panel-production/`

---

### Task 1: Revise Hero Copy — Outcome-Focused Headline

**Files:**
- Modify: `app/templates/pages/machines/hero.php:14-19`

**Step 1: Update the `$content` array**

Replace the existing content array with outcome-driven copy:

```php
$content = [
    'title'    => __('Make More Money on Every Metal Roof Job', 'standard'),
    'subtitle' => __('Save up to $2.25/sq ft by fabricating panels on-site with NTM portable rollformers.', 'standard'),
    'cta_text' => __('Explore the Lineup', 'standard'),
    'cta_url'  => '#lineup',
    'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
];
```

No structural HTML changes — only the content array values.

**Step 2: Verify build**

Run: `npm run build`
Expected: Clean build, no errors.

**Step 3: Commit**

```bash
git add app/templates/pages/machines/hero.php
git commit -m "Update machines hero to outcome-focused headline"
```

---

### Task 2: Revise Brand Statement — Business Case Focus

**Files:**
- Modify: `app/templates/pages/machines/brand-statement.php:14-17`

**Step 1: Update the `$content` array**

Replace with business-case messaging that mirrors the home page pain-points/value-prop flow:

```php
$content = [
    'title' => __('Stop Buying Panels. Start Making Profit.', 'standard'),
    'text'  => __("Every panel you buy from a supplier is profit you're giving away. NTM portable rollformers let you fabricate standing seam roofing and seamless gutters on-site — cutting material costs in half, winning more bids, and controlling your own schedule. Trusted by contractors in 40+ countries.", 'standard'),
];
```

No structural HTML changes.

**Step 2: Verify build**

Run: `npm run build`
Expected: Clean build.

**Step 3: Commit**

```bash
git add app/templates/pages/machines/brand-statement.php
git commit -m "Revise brand statement to lead with business case"
```

---

### Task 3: Add ROI Snapshot Section

**Files:**
- Create: `app/templates/pages/machines/roi-snapshot.php`
- Modify: `app/inc/machines-data.php` (add `get_roi_stats()` function)

**Step 1: Add data function to `machines-data.php`**

Append this function after the existing `get_journey_stats()` function (before the closing of the file):

```php
/**
 * Get ROI statistics for the ROI snapshot section.
 *
 * @return array<int, array{stat: string, label: string}>
 */
function get_roi_stats(): array {
    return [
        [
            'stat'  => '$2.25',
            'label' => 'Saved Per Sq Ft vs. Factory Panels',
        ],
        [
            'stat'  => '1–2 Yrs',
            'label' => 'Typical Machine Payback Period',
        ],
        [
            'stat'  => '1,000%',
            'label' => 'Business Growth Reported by Owners',
        ],
    ];
}
```

**Step 2: Create the ROI snapshot template**

Create `app/templates/pages/machines/roi-snapshot.php`:

```php
<?php
/**
 * Machines Page — ROI Snapshot
 *
 * Compact stat bar showing key ROI data points with link
 * to the existing profit calculator tool.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

use function Standard\MachinesData\get_roi_stats;

$content = [
    'eyebrow'  => __('Return on Investment', 'standard'),
    'title'    => __('The Numbers Speak for Themselves', 'standard'),
    'cta_text' => __('Calculate Your Profit', 'standard'),
    'cta_url'  => '/learning-center/download/portable-rollforming-profit-calculator/',
    'cta_secondary_text' => __('Read the Full ROI Breakdown', 'standard'),
    'cta_secondary_url'  => '/learning-center/what-is-the-roi-for-a-portable-metal-roof-panel-machine/',
];

$stats = get_roi_stats();
?>

<section class="section bg-slate-900" aria-labelledby="roi-snapshot-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="roi-snapshot-title" class="text-3xl font-bold text-white md:text-4xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 max-w-4xl mx-auto text-center">
            <?php foreach ($stats as $stat) : ?>
                <div class="grid gap-2">
                    <span class="text-4xl font-bold text-secondary lg:text-5xl">
                        <?php echo esc_html($stat['stat']); ?>
                    </span>
                    <span class="text-sm text-slate-400 uppercase tracking-wider">
                        <?php echo esc_html($stat['label']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-secondary">
                <?php echo esc_html($content['cta_text']); ?>
                <?php icon('calculator', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light">
                <?php echo esc_html($content['cta_secondary_text']); ?>
            </a>
        </div>

    </div>
</section>
```

**Step 3: Verify build**

Run: `npm run build`
Expected: Clean build. Check that `calculator` icon exists in `app/assets/icons/`. If not, substitute `dollar-sign` (already used on home page tools section).

**Step 4: Commit**

```bash
git add app/templates/pages/machines/roi-snapshot.php app/inc/machines-data.php
git commit -m "Add ROI snapshot section with profit calculator CTA"
```

---

### Task 4: Add Customer Story Section

**Files:**
- Create: `app/templates/pages/machines/customer-story.php`

**Step 1: Create the customer story template**

This uses real data from the NTM customer ROI article (Jim Averill / Gunnison Sheet Metal). Two-column layout: quote left, stats right.

Create `app/templates/pages/machines/customer-story.php`:

```php
<?php
/**
 * Machines Page — Customer Story
 *
 * Real customer case study with pull quote and key stats.
 * Data sourced from NTM's published ROI article.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow'    => __('Customer Story', 'standard'),
    'quote'      => __("Once I got the SSR, things really excelled. It's basically a printing press — you put coil on top, turn it on, and every foot that comes out, you're making money.", 'standard'),
    'name'       => 'Jim Averill',
    'company'    => 'Gunnison Sheet Metal',
    'machine'    => 'SSR MultiPro Jr.',
    'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
    'cta_text'   => __('Read the Full Story', 'standard'),
    'cta_url'    => '/learning-center/ntm-customers-roi-behind-portable-standing-seam-panel-production/',
];

$stats = [
    [
        'stat'  => '100+',
        'label' => __('Jobs in 3 Years', 'standard'),
    ],
    [
        'stat'  => '$200K+',
        'label' => __('Estimated Savings', 'standard'),
    ],
    [
        'stat'  => '1,000%',
        'label' => __('Business Growth', 'standard'),
    ],
];
?>

<section class="section bg-slate-50" aria-labelledby="customer-story-title">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">

            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                </div>

                <blockquote class="text-xl font-serif text-slate-800 leading-relaxed lg:text-2xl">
                    <span class="text-secondary text-3xl leading-none" aria-hidden="true">&ldquo;</span>
                    <?php echo esc_html($content['quote']); ?>
                </blockquote>

                <div>
                    <p class="font-semibold text-slate-900">
                        <?php echo esc_html($content['name']); ?>
                    </p>
                    <p class="text-sm text-slate-500">
                        <?php echo esc_html($content['company']); ?> &middot; <?php echo esc_html($content['machine']); ?>
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-6 border-t border-slate-200 pt-8">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="grid gap-1">
                            <span class="text-2xl font-bold text-slate-900 lg:text-3xl">
                                <?php echo esc_html($stat['stat']); ?>
                            </span>
                            <span class="text-xs text-slate-500 uppercase tracking-wider">
                                <?php echo esc_html($stat['label']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div>
                    <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-outline-dark">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

            <div>
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['name'] . ' — ' . $content['company']); ?>"
                    class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover"
                    loading="lazy"
                >
            </div>

        </div>
    </div>
</section>
```

**Step 2: Verify build**

Run: `npm run build`
Expected: Clean build.

**Step 3: Commit**

```bash
git add app/templates/pages/machines/customer-story.php
git commit -m "Add customer story section with real ROI data"
```

---

### Task 5: Add "Which Machine?" Decision Helper Section

**Files:**
- Create: `app/templates/pages/machines/which-machine.php`

**Step 1: Create the decision helper template**

Simple centered section placed after the comparison table to catch overwhelmed visitors.

Create `app/templates/pages/machines/which-machine.php`:

```php
<?php
/**
 * Machines Page — Which Machine Decision Helper
 *
 * Centered CTA section placed after comparison table.
 * Links to existing machine quiz and contact page.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'title'         => __('Not Sure Which Machine Is Right for You?', 'standard'),
    'text'          => __("Answer a few questions about your business and project types, and we'll recommend the best machine for your needs — or talk directly with one of our specialists.", 'standard'),
    'cta_quiz'      => __('Take the Machine Quiz', 'standard'),
    'cta_quiz_url'  => '/roof-panel-machine-assessment-quiz/',
    'cta_talk'      => __('Talk to a Specialist', 'standard'),
    'cta_talk_url'  => '/contact/',
];
?>

<section class="section-compact pattern-square-grid" aria-labelledby="which-machine-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container grid gap-8 text-center max-w-3xl mx-auto relative z-10">
        <div class="grid gap-4">
            <h2 id="which-machine-title" class="text-2xl font-bold text-slate-900 md:text-3xl lg:text-4xl">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url($content['cta_quiz_url']); ?>" class="btn btn-primary btn-lg">
                <?php icon('help-circle', ['class' => 'w-5 h-5']); ?>
                <?php echo esc_html($content['cta_quiz']); ?>
            </a>
            <a href="<?php echo esc_url($content['cta_talk_url']); ?>" class="btn btn-outline-dark btn-lg">
                <?php echo esc_html($content['cta_talk']); ?>
            </a>
        </div>
    </div>
</section>
```

**Step 2: Verify build**

Run: `npm run build`
Expected: Clean build.

**Step 3: Commit**

```bash
git add app/templates/pages/machines/which-machine.php
git commit -m "Add which-machine decision helper section"
```

---

### Task 6: Update Page Template Section Order

**Files:**
- Modify: `app/page-machines.php`

**Step 1: Replace the `<main>` content with new section order**

The new order inserts ROI snapshot after lineup grid, customer story after UNIQ spotlight, and which-machine after comparison table:

```php
<main id="primary">

    <?php get_template_part('templates/pages/machines/hero'); ?>

    <?php get_template_part('templates/pages/machines/brand-statement'); ?>

    <?php get_template_part('templates/pages/machines/lineup-grid'); ?>

    <?php get_template_part('templates/pages/machines/roi-snapshot'); ?>

    <?php get_template_part('templates/pages/machines/faq-accordion'); ?>

    <?php get_template_part('templates/pages/machines/ssq3-spotlight'); ?>

    <?php get_template_part('templates/pages/machines/uniq-spotlight'); ?>

    <?php get_template_part('templates/pages/machines/customer-story'); ?>

    <?php get_template_part('templates/pages/machines/differentiators'); ?>

    <?php get_template_part('templates/pages/machines/journey'); ?>

    <?php get_template_part('templates/pages/machines/comparison-table'); ?>

    <?php get_template_part('templates/pages/machines/which-machine'); ?>

    <?php get_template_part('templates/pages/machines/final-cta'); ?>

</main>
```

**Step 2: Verify build**

Run: `npm run build`
Expected: Clean build.

**Step 3: Commit**

```bash
git add app/page-machines.php
git commit -m "Reorder machines page sections with sales content"
```

---

### Task 7: Final Verification

**Step 1: Full production build**

Run: `npm run build`
Expected: Clean build with no warnings.

**Step 2: Check icon availability**

Verify these icons exist (used in new sections):
- `calculator` — used in ROI snapshot. If missing, replace with `dollar-sign`.
- `help-circle` — used in which-machine. Already confirmed on home page tools section.
- `arrow-right` — used throughout. Confirmed.

Run: `ls app/assets/icons/ | grep -E "calculator|help-circle|dollar-sign"`

If `calculator` is missing, edit `roi-snapshot.php` and replace `'calculator'` with `'dollar-sign'`.

**Step 3: Verify no duplicate section IDs**

Check that these IDs are unique across the page:
- `roi-snapshot-title`
- `customer-story-title`
- `which-machine-title`

Run: `grep -r 'id="roi-snapshot\|id="customer-story\|id="which-machine' app/templates/pages/machines/`
Expected: Each ID appears exactly once.
