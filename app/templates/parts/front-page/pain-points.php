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

$pain_points = [
    [
        'title' => __('Paying Supplier Markups', 'standard'),
        'text'  => __('Buying panels from distributors means paying their margins instead of keeping that profit yourself.', 'standard'),
    ],
    [
        'title' => __('Losing Jobs to Competitors', 'standard'),
        'text'  => __('Contractors with their own machines quote faster, price sharper, and win the jobs you\'re bidding on.', 'standard'),
    ],
    [
        'title' => __('Stuck on Someone Else\'s Schedule', 'standard'),
        'text'  => __('Waiting on panel deliveries delays your projects and frustrates your customers.', 'standard'),
    ],
];
?>

<section class="py-16 bg-white md:py-20 lg:py-24" aria-labelledby="pain-points-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-secondary mb-2">
                    <?php esc_html_e('Why Own Your Machine', 'standard'); ?>
                </p>
                <div class="w-12 h-1 bg-secondary mb-6"></div>

                <h2 id="pain-points-title" class="text-3xl font-bold text-slate-900 mb-4 md:text-4xl lg:text-5xl">
                    <?php esc_html_e('What\'s It Costing You to Not Own a Rollformer?', 'standard'); ?>
                </h2>

                <p class="text-lg text-slate-600 mb-8 lg:mb-10">
                    <?php esc_html_e('Every panel you buy from someone else is profit you\'re giving away. Here\'s what changes when you own your machine.', 'standard'); ?>
                </p>

                <ul class="space-y-6 mb-8 lg:mb-10">
                    <?php foreach ($pain_points as $point) : ?>
                        <li class="flex gap-4">
                            <span class="shrink-0 mt-1">
                                <?php icon('close', ['class' => 'w-4 h-4 text-red-500']); ?>
                            </span>
                            <div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-1">
                                    <?php echo esc_html($point['title']); ?>
                                </h3>
                                <p class="text-slate-600">
                                    <?php echo esc_html($point['text']); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div class="flex flex-wrap gap-4">
                    <a href="/contact/" class="btn btn-primary">
                        <?php esc_html_e('Schedule a Call', 'standard'); ?>
                    </a>
                    <a href="/machines/" class="btn btn-outline-dark">
                        <?php esc_html_e('Explore Machines', 'standard'); ?>
                    </a>
                </div>
            </div>

            <div>
                <img
                    src="https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg"
                    alt="<?php esc_attr_e('NTM training session with customer', 'standard'); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                >
                <p class="mt-4 text-center text-sm text-slate-600">
                    <?php esc_html_e('NTM is the world leader in portable rollforming.', 'standard'); ?>
                    <a href="/contact/" class="text-primary font-medium hover:underline">
                        <?php esc_html_e('Talk to our team to get started.', 'standard'); ?>
                    </a>
                </p>
            </div>

        </div>
    </div>
</section>
