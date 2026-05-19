<?php
/**
 * About — Leadership & Industry Standing
 *
 * Closing block. Light frame, chrome bars top and bottom. Headline that
 * restates the leadership claim, three industry-association memberships
 * in a hairline-divided mono row, and a single primary CTA with the red
 * ignition fill (one moment per page; this is it).
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'channel_left'  => __('Leadership', 'standard'),
    'channel_right' => __('40+ countries / 6 continents', 'standard'),
    'eyebrow'       => __('Industry standing', 'standard'),
    'title'         => __('The world\'s leading portable rollforming manufacturer. We don\'t use that line lightly.', 'standard'),
    'lede'          => __('NTM machines run on six continents. If you need a gutter machine in Ghana or a roof panel machine in Russia, we can get it there. We are a proud member of the three associations that keep the industry honest.', 'standard'),
    'cta_primary'         => __('Talk to a specialist', 'standard'),
    'cta_primary_url'     => '/contact/',
    'cta_secondary'       => __('See the machines', 'standard'),
    'cta_secondary_url'   => '/machines/',
    'footer_left'         => __('Close', 'standard'),
    'footer_right'        => __('Aurora, CO — open weekdays', 'standard'),
];

$memberships = [
    [
        'short' => 'MCA',
        'name'  => __('Metal Construction Association', 'standard'),
        'meta'  => __('Industry standards body', 'standard'),
    ],
    [
        'short' => 'NRCA',
        'name'  => __('National Roofing Contractors Association', 'standard'),
        'meta'  => __('National contractor org', 'standard'),
    ],
    [
        'short' => 'CRA',
        'name'  => __('Colorado Roofing Association', 'standard'),
        'meta'  => __('Home state assoc.', 'standard'),
    ],
];
?>

<section class="bg-blue-50 text-blue-600 border-y border-blue-200" aria-labelledby="about-leadership-title">

    <!-- Top chrome bar -->
    <div class="border-b border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['channel_left']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($content['channel_right']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Headline + lede -->
    <div class="border-x border-blue-200 container">

        <div class="px-6 lg:px-10 pt-12 lg:pt-16 pb-8 lg:pb-12 max-w-4xl">
            <div class="grid gap-5">

                <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-blue-500">
                    <span>03</span>
                    <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['eyebrow']); ?></span>
                </div>

                <h2 id="about-leadership-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-4xl leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>

                <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>

            </div>
        </div>

        <!-- Memberships row: three mono cells, hairline-divided, no logos required. -->
        <dl class="grid grid-cols-1 md:grid-cols-3 border-t border-blue-200 [&>div]:border-l [&>div]:border-blue-200 [&>div:first-child]:border-l-0">
            <?php foreach ($memberships as $i => $m) : ?>
                <div class="grid gap-2 px-6 lg:px-10 py-8 lg:py-10 <?php echo $i > 0 ? 'border-t md:border-t-0 border-blue-200' : ''; ?>">
                    <dt class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400">
                        <?php esc_html_e('Member', 'standard'); ?>
                    </dt>
                    <dd class="grid gap-1">
                        <span class="font-mono font-medium text-blue-900 text-2xl md:text-3xl leading-none tracking-tight">
                            <?php echo esc_html($m['short']); ?>
                        </span>
                        <span class="font-sans text-blue-700 text-sm md:text-base leading-snug">
                            <?php echo esc_html($m['name']); ?>
                        </span>
                        <span class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400 mt-1">
                            <?php echo esc_html($m['meta']); ?>
                        </span>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>

        <!-- Closing CTA row. Primary uses the red ignition fill (the one moment). -->
        <div class="border-t border-blue-200 px-6 lg:px-10 py-10 lg:py-14">
            <div class="grid gap-6 md:flex md:items-end md:justify-between max-w-5xl">

                <p class="font-sans text-blue-900 text-lg md:text-xl lg:text-2xl leading-snug tracking-tight max-w-xl">
                    <?php esc_html_e('Ready to bring rollforming in-house? Start with a 15-minute call.', 'standard'); ?>
                </p>

                <div class="flex flex-col sm:flex-row gap-3 shrink-0">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>"
                        class="btn btn-emphasis"
                    >
                        <?php echo esc_html($content['cta_primary']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>"
                        class="btn btn-secondary"
                    >
                        <?php echo esc_html($content['cta_secondary']); ?>
                    </a>
                </div>

            </div>
        </div>

    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-200">
        <div class="border-x border-blue-200 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3">
                    <?php icon('phone', ['class' => 'w-3 h-3 text-red']); ?>
                    <span class="text-blue-900"><?php echo esc_html($content['footer_left']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span class="text-blue-900"><?php echo esc_html($content['footer_right']); ?></span>
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
