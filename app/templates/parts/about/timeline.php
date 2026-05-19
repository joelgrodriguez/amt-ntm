<?php
/**
 * About — Product Evolution Timeline
 *
 * Dark band. Horizontal blueprint track of NTM's 10 category firsts,
 * 1991 → 2021. Each entry is a mono cell on a continuous hairline rail.
 * Red dots mark the three category-defining originals (SSP '91,
 * MACH II '94, polyurethane drive roller). Mobile collapses to a vertical
 * list with the same dot grammar.
 *
 * No card grid, no icon set. The rail and the dot are the design.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'channel_left'  => __('Evolution', 'standard'),
    'channel_right' => __('1991 – 2021 / 10 firsts', 'standard'),
    'eyebrow'       => __('Product evolution', 'standard'),
    'title'         => __('Ten firsts, one industry standard.', 'standard'),
    'lede'          => __('Every machine on this list shipped before the rest of the category had an answer for it. Most are still imitated.', 'standard'),
    'footer_left'   => __('Track', 'standard'),
    'footer_right'  => __('Originals, marked in red', 'standard'),
];

$milestones = [
    ['year' => '1991', 'model' => 'SSP',         'name' => __('Roof Panel Machine',         'standard'), 'original' => true],
    ['year' => '1994', 'model' => 'MACH II',     'name' => __('Seamless Gutter Machine',    'standard'), 'original' => true],
    ['year' => '2001', 'model' => 'SSR Multi Pro Jr.', 'name' => __('Roof Panel Machine',  'standard'), 'original' => false],
    ['year' => '2004', 'model' => 'SSH',         'name' => __('Roof Panel Machine',         'standard'), 'original' => false],
    ['year' => '2005', 'model' => 'BG7',         'name' => __('Box Gutter Machine',         'standard'), 'original' => false],
    ['year' => '2006', 'model' => '5V Crimp',    'name' => __('Roof Panel Machine',         'standard'), 'original' => false],
    ['year' => '2008', 'model' => 'SSQ',         'name' => __('Quick Change Roof Panel',    'standard'), 'original' => false],
    ['year' => '2017', 'model' => 'WPM WAV',     'name' => __('Wall Panel Machine',         'standard'), 'original' => false],
    ['year' => '2018', 'model' => 'SSQ II',      'name' => __('Roof Panel Machine',         'standard'), 'original' => false],
    ['year' => '2021', 'model' => 'UNIQ',        'name' => __('Control System',             'standard'), 'original' => true],
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
        <div class="px-6 lg:px-10 pt-12 lg:pt-16 pb-8 lg:pb-12 max-w-3xl">
            <div class="grid gap-5">

                <div class="flex items-baseline gap-2 font-mono uppercase tracking-wider text-xs text-red">
                    <span>02</span>
                    <span class="w-8 h-px bg-blue-700" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['eyebrow']); ?></span>
                </div>

                <h2 id="about-timeline-title" class="font-sans font-medium text-white text-2xl md:text-3xl lg:text-4xl leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>

                <p class="font-sans text-blue-300 text-base lg:text-lg leading-relaxed max-w-2xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>

            </div>
        </div>

        <!-- The rail: desktop = horizontal scroll track with continuous hairline,
             mobile = vertical list with continuous hairline. Same grammar both ways. -->
        <div class="border-t border-blue-800">
            <!-- Mobile: vertical -->
            <ol class="lg:hidden">
                <?php foreach ($milestones as $i => $m) : ?>
                    <li class="<?php echo $i > 0 ? 'border-t border-blue-800' : ''; ?>">
                        <div class="flex items-center gap-4 px-6 py-5">
                            <span class="w-2 h-2 shrink-0 <?php echo $m['original'] ? 'bg-red' : 'bg-blue-500'; ?>" aria-hidden="true"></span>
                            <span class="font-mono text-sm text-blue-400 w-12 shrink-0"><?php echo esc_html($m['year']); ?></span>
                            <div class="grid gap-0.5">
                                <span class="font-mono text-sm text-white"><?php echo esc_html($m['model']); ?></span>
                                <span class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400">
                                    <?php echo esc_html($m['name']); ?>
                                    <?php if ($m['original']) : ?>
                                        <span class="text-red"> / Original</span>
                                    <?php endif; ?>
                                </span>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ol>

            <!-- Desktop: horizontal track. Overflow-x for narrower lg viewports;
                 widens evenly on xl+. -->
            <div class="hidden lg:block overflow-x-auto">
                <ol class="grid grid-cols-10 min-w-[1024px]">
                    <?php foreach ($milestones as $i => $m) : ?>
                        <li class="relative px-5 py-8 <?php echo $i > 0 ? 'border-l border-blue-800' : ''; ?>">
                            <div class="grid gap-3">
                                <!-- Year + dot, aligned together -->
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 <?php echo $m['original'] ? 'bg-red' : 'bg-blue-500'; ?>" aria-hidden="true"></span>
                                    <span class="font-mono text-sm text-blue-400"><?php echo esc_html($m['year']); ?></span>
                                </div>
                                <!-- Model -->
                                <div class="grid gap-1">
                                    <span class="font-mono font-medium text-base text-white leading-tight">
                                        <?php echo esc_html($m['model']); ?>
                                    </span>
                                    <span class="font-mono uppercase tracking-wider text-[0.625rem] text-blue-400 leading-snug">
                                        <?php echo esc_html($m['name']); ?>
                                    </span>
                                    <?php if ($m['original']) : ?>
                                        <span class="font-mono uppercase tracking-wider text-[0.625rem] text-red mt-1">
                                            <?php esc_html_e('Original', 'standard'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
        </div>
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
