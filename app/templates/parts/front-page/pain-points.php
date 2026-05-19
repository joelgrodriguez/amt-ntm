<?php
/**
 * Pain Points Section Template Part
 *
 * Two-column layout addressing customer pain points.
 * Left: eyebrow, heading, intro text, bullet points with X icons, CTAs.
 * Right: action image with marketing message.
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
    'title'         => __("What's It Costing You to Not Own a Rollformer?", 'standard'),
    'text'          => __("Every panel you buy from someone else is profit you're giving away. Here's what changes when you own your machine.", 'standard'),
    'image'         => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
    'image_alt'     => __('NTM training session with customer', 'standard'),
    'image_caption' => __('NTM is the world leader in portable rollforming.', 'standard'),
];

$points = [
    [
        'title' => __('Paying Supplier Markups', 'standard'),
        'text'  => __('Buying panels from distributors means paying their margins instead of keeping that profit yourself.', 'standard'),
    ],
    [
        'title' => __('Losing Jobs to Competitors', 'standard'),
        'text'  => __("Contractors with their own machines quote faster, price sharper, and win the jobs you're bidding on.", 'standard'),
    ],
    [
        'title' => __("Stuck on Someone Else's Schedule", 'standard'),
        'text'  => __('Waiting on panel deliveries delays your projects and frustrates your customers.', 'standard'),
    ],
];
?>

<section class="section bg-white" aria-labelledby="pain-points-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div class="grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <h2 id="pain-points-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle">
                        <?php echo esc_html($content['text']); ?>
                    </p>
                </div>

                <ul class="grid gap-6">
                    <?php foreach ($points as $point) : ?>
                        <li class="flex gap-4">
                            <span class="shrink-0 mt-1">
                                <?php icon('x', ['class' => 'w-4 h-4 text-blue-400']); ?>
                            </span>
                            <div class="grid gap-1">
                                <h3 class="text-lg font-medium text-blue-900">
                                    <?php echo esc_html($point['title']); ?>
                                </h3>
                                <p class="text-blue-600">
                                    <?php echo esc_html($point['text']); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php get_template_part('templates/parts/cta/two-door', null, [
                    'primary_label'    => __('See What It Costs You', 'standard'),
                    'primary_url'      => '/learning-center/download/portable-rollforming-profit-calculator/',
                    'specialist_label' => __('Talk to a Specialist', 'standard'),
                ]); ?>
            </div>

            <div>
                <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                    'class'  => 'w-full h-auto',
                    'width'  => '1280',
                    'height' => '853',
                ]); ?>
                <p class="mt-4 text-center text-sm text-blue-600">
                    <?php echo esc_html($content['image_caption']); ?>
                </p>
            </div>

        </div>
    </div>
</section>
