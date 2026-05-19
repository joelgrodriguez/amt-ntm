<?php
/**
 * About — Product Evolution Timeline
 *
 * Dark band. Five signature firsts that defined the portable rollforming
 * category. Each is a roomy mono cell on a continuous hairline rail.
 * Chrome bars top and bottom; this is the one section on the page where
 * the chrome-bar grammar earns its seat (a literal timeline, not a
 * generic content frame).
 *
 * Five entries instead of ten: leadership reads through restraint. The
 * full evolution can live elsewhere; this page is the signature.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'channel_left'  => __('Signature firsts', 'standard'),
    'channel_right' => __('1991 – 2021 / Five turning points', 'standard'),
    'eyebrow'       => __('Product evolution', 'standard'),
    'title'         => __('The five machines that defined the category.', 'standard'),
    'lede'          => __('Each one shipped before the rest of the industry had an answer. Most are still imitated.', 'standard'),
    'footer_left'   => __('Track', 'standard'),
    'footer_right'  => __('Originals only', 'standard'),
];

$milestones = [
    [
        'year'  => '1991',
        'model' => 'SSP',
        'name'  => __('Roof Panel Machine', 'standard'),
        'note'  => __('The machine that started the modern portable roof panel category.', 'standard'),
    ],
    [
        'year'  => '1994',
        'model' => 'MACH II',
        'name'  => __('Seamless Gutter Machine', 'standard'),
        'note'  => __('Did for gutters what the SSP did for roof panels.', 'standard'),
    ],
    [
        'year'  => 'Early 90s',
        'model' => __('Polyurethane Drive Roller', 'standard'),
        'name'  => __('Industry-First Mechanism', 'standard'),
        'note'  => __('Separate forming rollers, polyurethane drive. Today almost every portable rollformer uses the approach.', 'standard'),
    ],
    [
        'year'  => '2008',
        'model' => 'SSQ',
        'name'  => __('Quick Change Roof Panel Machine', 'standard'),
        'note'  => __('Profile changeovers in minutes, not hours. The platform that became SSQ II.', 'standard'),
    ],
    [
        'year'  => '2021',
        'model' => 'UNIQ',
        'name'  => __('Control System', 'standard'),
        'note'  => __('NTM\'s digital control platform. The current standard for our machines.', 'standard'),
    ],
];
?>

<section class="bg-blue-900 text-blue-200" aria-labelledby="about-timeline-title">

    <!-- Top chrome bar -->
    <div class="border-b border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red animate-pulse" aria-hidden="true"></span>
                    <span class="text-blue-200"><?php echo esc_html($content['channel_left']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3 text-blue-400">
                    <span><?php echo esc_html($content['channel_right']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Headline -->
    <div class="border-x border-blue-800 container">
        <div class="px-6 lg:px-10 pt-14 lg:pt-20 pb-10 lg:pb-14 max-w-3xl">
            <div class="grid gap-5">

                <p class="font-mono uppercase tracking-wider text-xs text-red">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>

                <h2 id="about-timeline-title" class="font-sans font-medium text-white text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>

                <p class="font-sans text-blue-300 text-base lg:text-lg leading-relaxed max-w-2xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>

            </div>
        </div>

        <!-- The rail: five roomy cells on a continuous hairline. Desktop = five
             columns, mobile = vertical stack. Each cell has air to breathe. -->
        <ol class="border-t border-blue-800 grid grid-cols-1 lg:grid-cols-5">
            <?php foreach ($milestones as $i => $m) : ?>
                <li class="relative px-6 lg:px-7 py-10 lg:py-12
                    <?php echo $i > 0 ? 'border-t lg:border-t-0 lg:border-l border-blue-800' : ''; ?>">
                    <div class="grid gap-4">
                        <!-- Year + dot -->
                        <div class="flex items-center gap-2 font-mono">
                            <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                            <span class="text-sm text-red uppercase tracking-wider"><?php echo esc_html($m['year']); ?></span>
                        </div>
                        <!-- Model -->
                        <h3 class="font-mono font-medium text-white text-lg leading-tight">
                            <?php echo esc_html($m['model']); ?>
                        </h3>
                        <!-- Subtitle in mono caps -->
                        <p class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400 leading-snug -mt-2">
                            <?php echo esc_html($m['name']); ?>
                        </p>
                        <!-- Body note in sans for readability -->
                        <p class="font-sans text-blue-300 text-sm leading-relaxed">
                            <?php echo esc_html($m['note']); ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ol>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3 text-blue-300">
                    <?php icon('trending-up', ['class' => 'w-3 h-3 text-red']); ?>
                    <span><?php echo esc_html($content['footer_left']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3 text-blue-300">
                    <span><?php echo esc_html($content['footer_right']); ?></span>
                    <div class="hidden md:flex gap-1" aria-hidden="true">
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-blue-600"></span>
                        <span class="w-1 h-3 bg-red"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>
