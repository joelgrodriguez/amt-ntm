<?php
/**
 * Why Own Section — Front Page
 *
 * Single section that replaces the earlier pain-points + value-prop pair.
 *
 * Composition:
 *   - Header: title + lede + single specialist CTA on the left, action
 *     image (16:9) on the right, vertically centered on lg+.
 *   - Compare: two-column "WITHOUT YOUR MACHINE" (red-x markers, the
 *     cost side) vs "WITH YOUR NTM" (blue-check markers, the value
 *     side). Columns separated by a full-height hairline divider on md+
 *     and stack on mobile with a horizontal divider between.
 *
 * Only a Talk-to-a-Specialist CTA here. Configure & Quote lives in the
 * flagships, router, and final-cta sections; repeating it on every
 * surface dilutes the door choice.
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
    'title'       => __('Why own an NTM rollformer?', 'standard'),
    'lede'        => __('Buying panels from someone else is profit you give away. Rolling your own is the path to keeping it. Your first NTM machine pays for itself in the panels you stop buying from a distributor.', 'standard'),
    'image'       => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-customer-onsite-001.jpg',
    'image_alt'   => __('Overhead drone view of an NTM portable rollformer on its trailer at a customer jobsite.', 'standard'),
    'cost_label'  => __('Without your machine', 'standard'),
    'value_label' => __('With your NTM', 'standard'),
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

        <!-- Header: title + lede on the left, image on the right (lg+) -->
        <div class="grid gap-8 lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div class="section-header-left max-w-xl grid gap-6 content-start">
                <h2 id="why-own-title" class="section-title">
                    <?php echo esc_html($content['title']); ?>
                </h2>
                <p class="font-sans text-blue-600 text-base lg:text-lg leading-relaxed">
                    <?php echo esc_html($content['lede']); ?>
                </p>
                <div class="flex">
                    <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Talk to a Specialist', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>
            <div class="aspect-video overflow-hidden">
                <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                    'class'   => 'w-full h-full object-cover block',
                    'loading' => 'lazy',
                ]); ?>
            </div>
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

    </div>
</section>
