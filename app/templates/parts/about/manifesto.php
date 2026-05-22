<?php
/**
 * About — Manifesto
 *
 * Quiet, tall, light-mode hero. No chrome bars, no overlay wedge, no
 * grain. Editorial plate: eyebrow + display headline + lede on the left,
 * a single hard-cropped photograph on the right. Below the headline
 * stack, a four-cell flat metric strip on a hairline rail (years,
 * countries, facilities, category firsts).
 *
 * Leadership and grandeur in this register read as quiet and spaced-out,
 * not loud and dark. Hold the room with restraint.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'   => __('About New Tech Machinery', 'standard'),
    'title'     => __('The world\'s finest portable rollforming machines.', 'standard'),
    'subhead'   => __('Since 1991', 'standard'),
    'lede'      => __('NTM invented the modern portable roof panel machine in 1991 and the modern portable seamless gutter machine three years later. Three decades on, our designs are the ones the industry imitates.', 'standard'),
    'image'     => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
    'image_alt' => __('NTM rollformer in operation, training a crew at a jobsite.', 'standard'),
];

$metrics = [
    ['value' => '34+',  'label' => __('Years',           'standard')],
    ['value' => '40+',  'label' => __('Countries',       'standard')],
    ['value' => '2',    'label' => __('Facilities',      'standard')],
    ['value' => '10+',  'label' => __('Category firsts', 'standard')],
];
?>

<section class="bg-white" aria-labelledby="about-manifesto-title">
    <div class="container">
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 pt-16 lg:pt-24 pb-12 lg:pb-16">

            <!-- Copy column -->
            <div class="lg:col-span-7 grid gap-7 content-start">

                <p class="font-mono uppercase tracking-wider text-xs text-red">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>

                <div class="grid gap-3 max-w-3xl">
                    <h1 id="about-manifesto-title" class="font-sans font-medium text-blue-900 text-3xl md:text-4xl lg:text-5xl xl:text-[3.5rem] leading-[1.05] tracking-tight">
                        <?php echo esc_html($content['title']); ?>
                    </h1>
                    <p class="font-mono uppercase tracking-wider text-sm md:text-base text-blue-500">
                        <?php echo esc_html($content['subhead']); ?>
                    </p>
                </div>

                <p class="font-sans text-blue-700 text-lg lg:text-xl leading-relaxed max-w-2xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>

            </div>

            <!-- Image column: locked to 16:9 across breakpoints -->
            <div class="lg:col-span-5">
                <div class="aspect-video w-full overflow-hidden">
                    <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'full', [
                        'class'         => 'block w-full h-full object-cover',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                    ]); ?>
                </div>
            </div>

        </div>

        <!-- Metric strip: flat row on a hairline rail, mono labels, sans values.
             Quiet by default; no chrome bars wrapping it. -->
        <dl class="grid grid-cols-2 md:grid-cols-4 border-t border-blue-200 [&>div]:border-l [&>div]:border-blue-200 [&>div:first-child]:border-l-0 [&>div:nth-child(3)]:border-l-0 md:[&>div:nth-child(3)]:border-l">
            <?php foreach ($metrics as $i => $metric) : ?>
                <div class="grid gap-1 px-4 py-6 lg:px-6 lg:py-8 <?php echo $i >= 2 ? 'border-t md:border-t-0 border-blue-200' : ''; ?>">
                    <dd class="font-sans font-medium text-blue-900 text-3xl md:text-4xl lg:text-5xl leading-none tracking-tight">
                        <?php echo esc_html($metric['value']); ?>
                    </dd>
                    <dt class="font-mono uppercase tracking-wider text-xs text-blue-500">
                        <?php echo esc_html($metric['label']); ?>
                    </dt>
                </div>
            <?php endforeach; ?>
        </dl>

    </div>
</section>
