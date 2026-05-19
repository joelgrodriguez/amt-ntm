<?php
/**
 * Process Strip — Front Page
 *
 * Chrome-bar 3-phase process strip. Styled after the video-section's
 * top/bottom chrome bars (bg-blue-900, mono uppercase labels, hairline
 * separators) so the page reads as a control panel, not a SaaS landing
 * with numbered step cards.
 *
 * Mono step labels (01 / SPEC, 02 / BUILD, 03 / DELIVER) carry the
 * engineered voice; sans body text reads.
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
    'channel'         => __('How You Buy', 'standard'),
    'channel_right'   => __('Explore / Build / Finance', 'standard'),
    'title'           => __('How a machine gets built and shipped.', 'standard'),
    'footer_left_k'   => __('Two doors', 'standard'),
    'footer_left_v'   => __('Configurator or specialist', 'standard'),
    'footer_right_k'  => __('Training', 'standard'),
    'footer_right_v'  => __('Included, on-site', 'standard'),
];

$phases = [
    [
        'index' => '01',
        'label' => __('Explore', 'standard'),
        'title' => __('Find your machine.', 'standard'),
        'text'  => __('Browse profiles, compare throughput, read the manuals. Spend ten minutes or two weeks. The machine you pick is the one we build.', 'standard'),
    ],
    [
        'index' => '02',
        'label' => __('Build', 'standard'),
        'title' => __('Configure it, or call us.', 'standard'),
        'text'  => __('Use the configurator for a live quote, or talk to a specialist who will build it with you. Same price, same machine, your choice.', 'standard'),
    ],
    [
        'index' => '03',
        'label' => __('Finance & ship', 'standard'),
        'title' => __('Finance it. We ship it.', 'standard'),
        'text'  => __('Apply for financing in the same flow. Lead time is 6 to 10 weeks. Your crew runs panels with our team on-site week one.', 'standard'),
    ],
];
?>

<section class="bg-blue-900 text-blue-400 border-b border-blue-700" aria-labelledby="process-title">
    <!-- Top chrome bar -->
    <div class="border-b border-blue-800">
        <div class="border-x border-blue-800 container">
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
    <div class="border-x border-blue-800 container">
        <div class="py-12 lg:py-16">
            <h2 id="process-title" class="sr-only">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <div class="grid md:grid-cols-3">
                <?php foreach ($phases as $i => $phase) : ?>
                    <div class="grid gap-4 p-6 lg:p-8 <?php echo $i > 0 ? 'border-t border-blue-800 md:border-t-0 md:border-l' : ''; ?>">
                        <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-400">
                            <span><?php echo esc_html($phase['index']); ?></span>
                            <span class="w-8 h-px bg-blue-700" aria-hidden="true"></span>
                            <span><?php echo esc_html($phase['label']); ?></span>
                        </div>
                        <h3 class="font-sans font-medium text-white text-lg md:text-xl lg:text-2xl leading-tight">
                            <?php echo esc_html($phase['title']); ?>
                        </h3>
                        <p class="font-sans text-blue-200 text-sm leading-relaxed">
                            <?php echo esc_html($phase['text']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-2 pl-3">
                    <span><?php echo esc_html($content['footer_left_k']); ?></span>
                    <span class="text-white"><?php echo esc_html($content['footer_left_v']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span><?php echo esc_html($content['footer_right_k']); ?></span>
                    <span class="text-white"><?php echo esc_html($content['footer_right_v']); ?></span>
                    <div class="flex gap-1" aria-hidden="true">
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
