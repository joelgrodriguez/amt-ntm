<?php
/**
 * Configurator Section — Front Page
 *
 * Chrome-bar process strip in the same vocabulary as three-step-plan and
 * video-section. The earlier icon-trio template read as the canonical
 * SaaS landing page; this composition reads as a control panel.
 *
 * Three columns: PROFILE / WIDTH / PRICE. Mono numerals and labels,
 * sans body, hairline dividers. CTA sits beneath the strip and
 * occupies its own dedicated row so the action isn't buried in the grid.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'section_id' => 'configurator',
];

$content = wp_parse_args($args ?? [], $defaults);

$top_left      = __('Configurator', 'standard');
$top_right     = __('Build / Price / Finance', 'standard');
$sr_title      = __('Configure your machine online', 'standard');
$cta_label     = __('Start Configuring', 'standard');
$footer_left_k = __('Lead time', 'standard');
$footer_left_v = __('6 to 10 weeks', 'standard');
$footer_right_k = __('Need a hand', 'standard');
$footer_right_v = __('Talk to a specialist', 'standard');

$steps = [
    [
        'index' => '01',
        'label' => __('Profile', 'standard'),
        'title' => __('Pick your panel.', 'standard'),
        'text'  => __('Standing seam, snap-lock, mechanical-lock, corrugated, box gutter. We carry the dies.', 'standard'),
    ],
    [
        'index' => '02',
        'label' => __('Width', 'standard'),
        'title' => __('Pick your stock.', 'standard'),
        'text'  => __('Coil width from 12 inches to 20 inches and wider. Your existing inventory works.', 'standard'),
    ],
    [
        'index' => '03',
        'label' => __('Price', 'standard'),
        'title' => __('See it instantly.', 'standard'),
        'text'  => __('Live quote in your browser. Apply for financing in the same flow. No phone calls until you want them.', 'standard'),
    ],
];
?>

<section class="bg-blue-900 text-blue-400" aria-labelledby="<?php echo esc_attr($content['section_id']); ?>-title">
    <!-- Top chrome bar -->
    <div class="border-b border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span id="<?php echo esc_attr($content['section_id']); ?>-title"><?php echo esc_html($top_left); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($top_right); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Process columns -->
    <div class="border-x border-blue-800 container">
        <div class="py-12 lg:py-16">
            <h2 class="sr-only">
                <?php echo esc_html($sr_title); ?>
            </h2>
            <div class="grid md:grid-cols-3">
                <?php foreach ($steps as $i => $step) : ?>
                    <div class="grid gap-4 p-6 lg:p-8 <?php echo $i > 0 ? 'border-t border-blue-800 md:border-t-0 md:border-l' : ''; ?>">
                        <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-400">
                            <span><?php echo esc_html($step['index']); ?></span>
                            <span class="w-8 h-px bg-blue-700" aria-hidden="true"></span>
                            <span><?php echo esc_html($step['label']); ?></span>
                        </div>
                        <h3 class="font-sans font-medium text-white text-lg md:text-xl lg:text-2xl leading-tight">
                            <?php echo esc_html($step['title']); ?>
                        </h3>
                        <p class="font-sans text-blue-200 text-sm leading-relaxed">
                            <?php echo esc_html($step['text']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- CTA row, dedicated under the process strip -->
            <div class="flex justify-center pt-2 pb-8 lg:pb-10">
                <a
                    href="<?php echo esc_url(\Standard\Url\internal('/configurator/')); ?>"
                    class="btn btn-light"
                >
                    <?php echo esc_html($cta_label); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-2 pl-3">
                    <span><?php echo esc_html($footer_left_k); ?></span>
                    <span class="text-white"><?php echo esc_html($footer_left_v); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span><?php echo esc_html($footer_right_k); ?></span>
                    <span class="text-white"><?php echo esc_html($footer_right_v); ?></span>
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
