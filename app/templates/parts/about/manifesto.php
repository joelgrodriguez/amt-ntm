<?php
/**
 * About — Manifesto Hero
 *
 * Dark chrome-bar hero. Eyebrow + display headline + subline, anchored
 * over a cinematic shop-floor photograph with a dark wedge for legibility.
 * Bottom of the frame carries a four-cell metric strip (years / countries
 * / facilities / standard) in the same hairline-divider language used by
 * the case-study stats row.
 *
 * Chrome-bar grammar shared with three-step-plan, blueprint, case-study.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'channel_left'  => __('About', 'standard'),
    'channel_right' => __('Est. 1991 / Aurora, Colorado', 'standard'),
    'eyebrow'       => __('New Tech Machinery', 'standard'),
    'title'         => __('The world\'s finest portable rollforming machines. Built here since 1991.', 'standard'),
    'subtitle'      => __('We invented the modern portable roof panel machine. We invented the modern portable seamless gutter machine. Three decades later, our designs are still the ones the industry imitates.', 'standard'),
    'image'         => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
    'image_alt'     => __('NTM rollformer in operation, training a crew at a job site.', 'standard'),
    'footer_left'   => __('Aurora, CO / Hermosillo, MX', 'standard'),
    'footer_right'  => __('Mazzella Companies', 'standard'),
];

$metrics = [
    ['value' => '34+',  'label' => __('Years', 'standard')],
    ['value' => '40+',  'label' => __('Countries', 'standard')],
    ['value' => '2',    'label' => __('Facilities', 'standard')],
    ['value' => '10+',  'label' => __('Category firsts', 'standard')],
];
?>

<section class="bg-blue-900 text-white" aria-labelledby="about-manifesto-title">

    <!-- Top chrome bar -->
    <div class="border-b border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red animate-pulse" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['channel_left']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3 text-blue-300">
                    <span><?php echo esc_html($content['channel_right']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Body: image with overlay + headline stack -->
    <div class="border-x border-blue-800 container">
        <div class="relative">

            <!-- Photograph -->
            <div class="relative h-[420px] md:h-[520px] lg:h-[640px] overflow-hidden">
                <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'full', [
                    'class'         => 'absolute inset-0 w-full h-full object-cover',
                    'loading'       => 'eager',
                    'fetchpriority' => 'high',
                ]); ?>

                <!-- Dark wedge for legibility. Solid on mobile, angled on desktop. -->
                <div class="absolute inset-0 bg-blue-900/80 lg:hidden" aria-hidden="true"></div>
                <div
                    class="hidden lg:block absolute inset-0 bg-blue-900/70"
                    style="clip-path: polygon(0 0, 62% 0, 48% 100%, 0% 100%);"
                    aria-hidden="true"
                ></div>

                <!-- Headline stack, hard-left, vertically centered -->
                <div class="absolute inset-0 flex items-center">
                    <div class="px-6 lg:px-10 max-w-2xl grid gap-6">

                        <div class="flex items-center gap-2 font-mono uppercase tracking-wider text-xs text-red">
                            <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                            <span><?php echo esc_html($content['eyebrow']); ?></span>
                        </div>

                        <h1 id="about-manifesto-title" class="font-sans font-medium text-white text-3xl md:text-4xl lg:text-5xl xl:text-[3.25rem] leading-[1.1] tracking-tight">
                            <?php echo esc_html($content['title']); ?>
                        </h1>

                        <p class="font-sans text-blue-200 text-base md:text-lg leading-relaxed max-w-xl">
                            <?php echo esc_html($content['subtitle']); ?>
                        </p>

                    </div>
                </div>
            </div>

            <!-- Metric strip: flat row, hairline dividers, no cards.
                 Borders: top one to separate from photo, internal verticals between cells. -->
            <dl class="grid grid-cols-2 md:grid-cols-4 border-t border-blue-800 [&>div]:border-l [&>div]:border-blue-800 [&>div:first-child]:border-l-0 [&>div:nth-child(3)]:border-l-0 md:[&>div:nth-child(3)]:border-l">
                <?php foreach ($metrics as $i => $metric) : ?>
                    <div class="grid gap-1 p-5 lg:p-7 <?php echo $i >= 2 ? 'border-t md:border-t-0 border-blue-800' : ''; ?>">
                        <dd class="font-sans font-medium text-white text-3xl md:text-4xl lg:text-5xl leading-none tracking-tight">
                            <?php echo esc_html($metric['value']); ?>
                        </dd>
                        <dt class="font-mono uppercase tracking-wider text-xs text-blue-400">
                            <?php echo esc_html($metric['label']); ?>
                        </dt>
                    </div>
                <?php endforeach; ?>
            </dl>

        </div>
    </div>

    <!-- Bottom chrome bar -->
    <div class="border-t border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 font-mono uppercase tracking-wider text-[0.625rem] md:text-xs">
                <div class="flex items-center gap-2 pl-3 text-blue-300">
                    <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                    <span><?php echo esc_html($content['footer_left']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3 text-blue-300">
                    <span class="hidden md:inline"><?php esc_html_e('Part of', 'standard'); ?></span>
                    <span class="text-white"><?php echo esc_html($content['footer_right']); ?></span>
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
