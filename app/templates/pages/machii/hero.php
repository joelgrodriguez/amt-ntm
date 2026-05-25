<?php
/**
 * MACH II Family — Hero
 *
 * Dark full-bleed hero. Two columns at lg+: left rail carries the
 * heritage claim headline, subhead, dual CTA, and a 4-cell mono meta
 * strip (Since / Continents / Models / Speed). Right panel is a 16:9
 * click-to-play Wistia facade with the install poster as the LCP.
 *
 * Deliberately bespoke (rather than reusing templates/parts/hero-
 * category.php) so this page can run without the dot-grid background
 * pattern — per project memory, product/marketing pages skip it. The
 * shape mirrors hero-category so it reads as part of the same family
 * of pages, but the chrome is restrained.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$poster     = content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-001.jpg');
$poster_alt = __('NTM MACH II seamless gutter machine on a jobsite', 'standard');
$video      = 'https://fast.wistia.net/embed/iframe/w1u1r55n9v?seo=false&videoFoam=true';

$meta = [
    ['label' => __('Since',    'standard'), 'value' => '1994'],
    ['label' => __('Reach',    'standard'), 'value' => '40+ countries'],
    ['label' => __('Models',   'standard'), 'value' => '4'],
    ['label' => __('Speed',    'standard'), 'value' => '50 ft/min'],
];

$page_title = function_exists('get_the_title') ? get_the_title() : '';
?>

<section class="relative overflow-hidden bg-blue-900 text-white" aria-labelledby="machii-hero-title">

    <?php if ($page_title !== '') : ?>
        <h1 class="sr-only"><?php echo esc_html($page_title); ?></h1>
    <?php endif; ?>

    <div class="container py-16 lg:py-20 xl:py-24">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 lg:items-center">

            <div class="grid gap-8 lg:col-span-6 lg:gap-10">

                <p class="font-mono text-xs uppercase tracking-[0.18em] text-red flex items-center gap-2">
                    <span aria-hidden="true" class="inline-block w-1 h-1 bg-red"></span>
                    <?php esc_html_e('MACH II Family · Seamless Gutter Machines', 'standard'); ?>
                </p>

                <h2
                    id="machii-hero-title"
                    class="font-sans font-medium leading-[0.95] tracking-tight text-white text-3xl md:text-4xl lg:text-5xl xl:text-6xl"
                >
                    <?php esc_html_e('The gutter machine that made the category.', 'standard'); ?>
                </h2>

                <p class="text-lg text-blue-200 max-w-xl lg:text-xl">
                    <?php esc_html_e('NTM built the MACH II in 1994 and didn\'t stop. Three decades later, it\'s still the machine on every reputable gutter truck on seven continents.', 'standard'); ?>
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#machii-family-portrait" class="btn btn-primary">
                        <?php esc_html_e('See the Models', 'standard'); ?>
                        <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-outline-light">
                        <?php esc_html_e('Talk to a Specialist', 'standard'); ?>
                    </a>
                </div>

                <dl class="grid grid-cols-2 gap-x-8 gap-y-4 border-t border-white/15 pt-6 mt-2 max-w-md sm:grid-cols-4">
                    <?php foreach ($meta as $item) : ?>
                        <div class="grid gap-1">
                            <dt class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-300">
                                <?php echo esc_html($item['label']); ?><span class="sr-only">:</span>
                            </dt>
                            <dd class="font-mono text-base text-white">
                                <?php echo esc_html($item['value']); ?>
                            </dd>
                        </div>
                    <?php endforeach; ?>
                </dl>

            </div>

            <div class="video-responsive lg:col-span-6 bg-blue-800">
                <button
                    type="button"
                    class="video-facade"
                    data-video-facade
                    data-video-url="<?php echo esc_url($video); ?>"
                    aria-label="<?php echo esc_attr__('Play MACH II video', 'standard'); ?>"
                >
                    <?php \Standard\Images\responsive_image($poster, $poster_alt, 'full', [
                        'class'         => 'object-cover',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                    ]); ?>
                    <span class="video-facade__play" aria-hidden="true">
                        <?php icon('play', ['class' => 'w-6 h-6']); ?>
                    </span>
                </button>
            </div>

        </div>
    </div>
</section>
