<?php
/**
 * Value Proposition Section Template Part
 *
 * Two-column layout presenting the solution to pain points.
 * Left: action image with marketing message.
 * Right: eyebrow, heading, intro text, bullet points with checkmarks, CTA.
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
    'eyebrow'   => __('The NTM Advantage', 'standard'),
    'title'     => __('Own Your Machine. Own Your Future.', 'standard'),
    'text'      => __("When you invest in an NTM rollformer, you're not just buying equipment, you're unlocking a new revenue stream and taking control of your business.", 'standard'),
    'image'     => 'https://newtechmachinery.com/wp-content/uploads/2024/10/Ross-in-front-of-SSQ-scaled.jpg',
    'image_alt' => __('NTM customer with SSQ roof panel machine', 'standard'),
    'image_caption' => __("Join thousands of contractors who've taken control of their business.", 'standard'),
];

$points = [
    [
        'title' => __('Keep 100% of Panel Profits', 'standard'),
        'text'  => __("Roll your own panels on-site and capture the margin you've been paying to suppliers.", 'standard'),
    ],
    [
        'title' => __('Win More Bids', 'standard'),
        'text'  => __("Quote faster with better pricing. Owning your machine gives you the edge competitors can't match.", 'standard'),
    ],
    [
        'title' => __('Control Your Schedule', 'standard'),
        'text'  => __('No more waiting on deliveries. Produce exactly what you need, when you need it.', 'standard'),
    ],
];
?>

<section class="section" aria-labelledby="value-prop-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div class="order-2 lg:order-1">
                <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                    'class'  => 'w-full h-auto',
                    'width'  => '1280',
                    'height' => '853',
                ]); ?>
                <p class="mt-4 text-center text-sm text-blue-600">
                    <?php echo esc_html($content['image_caption']); ?>
                </p>
            </div>

            <div class="order-1 lg:order-2 grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="value-prop-title" class="section-title">
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
                                <?php icon('check', ['class' => 'w-4 h-4 text-blue-500']); ?>
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
                    'primary_label' => __('Build Your Machine', 'standard'),
                    'primary_url'   => '/configurator/',
                ]); ?>
            </div>

        </div>
    </div>
</section>
