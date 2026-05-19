<?php
/**
 * Why Own Section — Front Page
 *
 * Single section that replaces the earlier pain-points + value-prop pair.
 * Two-column compare: left "WITHOUT YOUR MACHINE" (red-square markers,
 * the cost side), right "WITH YOUR NTM" (blue-check markers, the value
 * side). One two-door CTA below the table.
 *
 * No image — the typography and the side-by-side comparison are the
 * composition. Columns are separated by a full-height hairline divider
 * on md+ and stack on mobile with a horizontal divider between.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'title'         => __('Why own an NTM rollformer?', 'standard'),
    'cost_label'    => __('Without your machine', 'standard'),
    'value_label'   => __('With your NTM', 'standard'),
];

$cost_points = [
    [
        'title' => __('Paying supplier markups', 'standard'),
        'text'  => __('Distributor margins come out of your bottom line on every panel.', 'standard'),
    ],
    [
        'title' => __('Losing jobs to competitors', 'standard'),
        'text'  => __('Contractors who own machines quote faster and price sharper.', 'standard'),
    ],
    [
        'title' => __("Stuck on someone else's schedule", 'standard'),
        'text'  => __('Panel delivery delays bleed into your project timeline.', 'standard'),
    ],
];

$value_points = [
    [
        'title' => __('Keep 100% of panel profits', 'standard'),
        'text'  => __('Roll on-site and capture the margin you were paying out.', 'standard'),
    ],
    [
        'title' => __('Win more bids', 'standard'),
        'text'  => __('Quote faster with better pricing. The edge competitors can\'t match.', 'standard'),
    ],
    [
        'title' => __('Control your schedule', 'standard'),
        'text'  => __('Produce what you need, when you need it. No waiting.', 'standard'),
    ],
];
?>

<section class="section bg-white" aria-labelledby="why-own-title">
    <div class="container grid gap-12 lg:gap-16">

        <div class="section-header-left max-w-2xl">
            <h2 id="why-own-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <!-- Two-column compare with a single full-height hairline divider on md+ -->
        <div class="grid gap-12 md:grid-cols-2 md:gap-0 md:divide-x md:divide-blue-200">

            <!-- Cost column -->
            <div class="md:pr-10 lg:pr-16">
                <div class="flex items-center gap-3 pb-6 mb-8 border-b border-blue-200">
                    <span class="w-2 h-2 bg-red shrink-0" aria-hidden="true"></span>
                    <h3 class="font-mono uppercase tracking-wider text-xs text-blue-700">
                        <?php echo esc_html($content['cost_label']); ?>
                    </h3>
                </div>
                <ul class="grid gap-6">
                    <?php foreach ($cost_points as $point) : ?>
                        <li class="flex gap-4">
                            <span class="shrink-0 mt-1">
                                <?php icon('x', ['class' => 'w-4 h-4 text-red']); ?>
                            </span>
                            <div class="grid gap-1">
                                <h4 class="text-lg font-medium text-blue-900">
                                    <?php echo esc_html($point['title']); ?>
                                </h4>
                                <p class="text-blue-600">
                                    <?php echo esc_html($point['text']); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Value column -->
            <div class="md:pl-10 lg:pl-16">
                <div class="flex items-center gap-3 pb-6 mb-8 border-b border-blue-200">
                    <span class="w-2 h-2 bg-blue-500 shrink-0" aria-hidden="true"></span>
                    <h3 class="font-mono uppercase tracking-wider text-xs text-blue-700">
                        <?php echo esc_html($content['value_label']); ?>
                    </h3>
                </div>
                <ul class="grid gap-6">
                    <?php foreach ($value_points as $point) : ?>
                        <li class="flex gap-4">
                            <span class="shrink-0 mt-1">
                                <?php icon('check', ['class' => 'w-4 h-4 text-blue-500']); ?>
                            </span>
                            <div class="grid gap-1">
                                <h4 class="text-lg font-medium text-blue-900">
                                    <?php echo esc_html($point['title']); ?>
                                </h4>
                                <p class="text-blue-600">
                                    <?php echo esc_html($point['text']); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="flex justify-center md:justify-start">
            <?php get_template_part('templates/parts/cta/two-door', null, [
                'primary_label'    => __('Configure & Quote', 'standard'),
                'primary_url'      => '/configurator/',
                'specialist_label' => __('Talk to a Specialist', 'standard'),
                'specialist_url'   => '/contact/',
            ]); ?>
        </div>

    </div>
</section>
