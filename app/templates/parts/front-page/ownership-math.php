<?php
/**
 * Ownership Math Section Template Part
 *
 * Full-bleed dark spec-sheet block that replaces pain-points + value-prop +
 * three-step-plan with one ownership-math surface. Three mono numeric tiles
 * with hairline vertical dividers (DESIGN.md §8.5 signature pattern).
 *
 * PRODUCT.md principles:
 * #1 The machine is the hero, the UI is the frame. Tiles are numbers, not stories.
 * #3 Spec-sheet, not catalog. Mono carries the value.
 * #6 Educate the newcomer without slowing the pro. Numbers do both at once.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 *
 * TODO(real-numbers): the three tile values + "View the math" links need
 * real sourced data from sales/finance before this ships to production.
 * Current values are placeholder ranges plausible for the rollforming
 * category. Wire to actual ROI numbers + supporting case-study URLs.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Ownership Math', 'standard'),
    'title'   => __('The Numbers That Earn the Investment', 'standard'),
    'text'    => __('Three numbers describe what changes when the panels you sell are panels you rolled yourself. No webinar, no plan, just arithmetic.', 'standard'),
];

// TODO(real-numbers): replace each tile's value, unit, and proof link
// with sourced data before production launch.
$tiles = [
    [
        'eyebrow' => __('Margin Recapture', 'standard'),
        'value'   => '35',
        'unit'    => '%',
        'body'    => __('Margin per square that stays with you instead of the panel supplier, on a typical standing-seam job.', 'standard'),
        'proof_label' => __('View the math', 'standard'),
        'proof_url'   => '#', // TODO: replace with case-study or calculator URL
    ],
    [
        'eyebrow' => __('Time to Payoff', 'standard'),
        'value'   => '14',
        'unit'    => 'mo',
        'body'    => __('Typical break-even for a mid-volume contractor running an SSQ-class machine, financed at standard terms.', 'standard'),
        'proof_label' => __('View the math', 'standard'),
        'proof_url'   => '#', // TODO: replace with payoff-calculator URL
    ],
    [
        'eyebrow' => __('Jobs per Day', 'standard'),
        'value'   => '+6',
        'unit'    => '/day',
        'body'    => __('Production lift when crews stop waiting on panel deliveries and produce coil-to-roof on the truck.', 'standard'),
        'proof_label' => __('View the math', 'standard'),
        'proof_url'   => '#', // TODO: replace with throughput case study
    ],
];
?>

<section class="section bg-blue-900 text-white" aria-labelledby="ownership-math-title">
    <div class="container grid gap-12 lg:gap-16">

        <div class="section-header-left max-w-3xl">
            <p class="section-eyebrow text-red">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="ownership-math-title" class="section-title text-white">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle text-blue-200">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 md:divide-x md:divide-blue-700">
            <?php foreach ($tiles as $i => $tile) : ?>
                <article class="grid gap-6 py-10 md:py-0 md:px-10 first:md:pl-0 last:md:pr-0 border-t md:border-t-0 border-blue-700 first:border-t-0">
                    <p class="font-mono text-caption font-medium uppercase tracking-wider text-blue-300">
                        <?php echo esc_html($tile['eyebrow']); ?>
                    </p>

                    <div class="flex items-baseline gap-2 font-mono text-white">
                        <span class="text-6xl font-medium leading-none lg:text-7xl">
                            <?php echo esc_html($tile['value']); ?>
                        </span>
                        <span class="text-2xl font-medium uppercase tracking-wider text-blue-300">
                            <?php echo esc_html($tile['unit']); ?>
                        </span>
                    </div>

                    <p class="text-body text-blue-200 leading-relaxed max-w-sm">
                        <?php echo esc_html($tile['body']); ?>
                    </p>

                    <a
                        href="<?php echo esc_url($tile['proof_url']); ?>"
                        class="inline-flex items-center gap-2 font-mono text-caption font-medium uppercase tracking-wider text-white hover:text-blue-300 transition-colors duration-200"
                    >
                        <?php echo esc_html($tile['proof_label']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="flex justify-center">
            <?php get_template_part('templates/parts/cta/two-door', null, [
                'align' => 'center',
                'theme' => 'dark',
            ]); ?>
        </div>

    </div>
</section>
