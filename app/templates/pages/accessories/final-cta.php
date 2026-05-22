<?php
/**
 * Accessories Page — Final CTA
 *
 * Chrome-bar process strip closer. Same grammar as the front-page
 * three-step process: top chrome bar (red dot + mono labels), framed
 * 3-column body with mono index labels + sans headlines + sans body,
 * bottom chrome bar with mono labels and segmented red-tipped indicator.
 *
 * Reusing the front page's signature composition closes the page on the
 * same chrome the site opens on. Single red moment of the page lives in
 * the chrome bars.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'channel'        => __('What to Expect', 'standard'),
    'channel_right'  => __('Fit / Pricing / Ship', 'standard'),
    'title'          => __('What happens when you call.', 'standard'),
    'footer_left_k'  => __('Online', 'standard'),
    'footer_left_v'  => __('Browse the catalog', 'standard'),
    'footer_right_k' => __('Or call', 'standard'),
    'footer_right_v' => __('Talk to a specialist', 'standard'),
    'footer_url'     => '/contact/',
];

$phases = [
    [
        'index' => '01',
        'label' => __('Fit', 'standard'),
        'title' => __('Tell us your machine.', 'standard'),
        'text'  => __('Bring the model and year. A specialist tells you what fits, what doesn\'t, and what\'s worth it.', 'standard'),
    ],
    [
        'index' => '02',
        'label' => __('Pricing', 'standard'),
        'title' => __('Real numbers, no quote dance.', 'standard'),
        'text'  => __('Get pricing on the parts that move the needle. In-stock vs. built-to-order lead times, on the same call.', 'standard'),
    ],
    [
        'index' => '03',
        'label' => __('Ship', 'standard'),
        'title' => __('It arrives ready to run.', 'standard'),
        'text'  => __('Most accessories ship within the week. Larger upgrades arrive built to spec and tested before they leave Aurora.', 'standard'),
    ],
];
?>

<section class="bg-white text-blue-600 border-y border-blue-200" aria-labelledby="accessories-final-cta-title">
    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['channel']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($content['channel_right']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Process columns -->
    <div class="border-x border-blue-200 container">
        <div class="py-12 lg:py-16">
            <h2 id="accessories-final-cta-title" class="sr-only">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <div class="grid md:grid-cols-3">
                <?php foreach ($phases as $i => $phase) : ?>
                    <div class="grid gap-4 p-6 lg:p-8 <?php echo $i > 0 ? 'border-t border-blue-200 md:border-t-0 md:border-l' : ''; ?>">
                        <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-500">
                            <span><?php echo esc_html($phase['index']); ?></span>
                            <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                            <span><?php echo esc_html($phase['label']); ?></span>
                        </div>
                        <h3 class="font-sans font-medium text-blue-900 text-lg md:text-xl lg:text-2xl leading-tight">
                            <?php echo esc_html($phase['title']); ?>
                        </h3>
                        <p class="font-sans text-blue-600 text-sm leading-relaxed">
                            <?php echo esc_html($phase['text']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <span class="hidden md:inline"><?php echo esc_html($content['footer_left_k']); ?></span>
                    <a href="#catalog" class="text-blue-900 hover:text-blue-500 no-underline transition-colors">
                        <?php echo esc_html($content['footer_left_v']); ?>
                    </a>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span class="hidden md:inline"><?php echo esc_html($content['footer_right_k']); ?></span>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['footer_url'])); ?>" class="text-blue-900 hover:text-blue-500 no-underline transition-colors">
                        <?php echo esc_html($content['footer_right_v']); ?>
                    </a>
                    <div class="hidden md:flex gap-1" aria-hidden="true">
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-blue-300"></span>
                        <span class="w-1 h-3 bg-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
