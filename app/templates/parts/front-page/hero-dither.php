<?php
/**
 * Front Page — Dithered Canvas Hero (experiment)
 *
 * SSQ3-focused replacement for the hero slider, gated behind the
 * `ntm_dither_hero` filter in front-page.php. The base <img> carries the
 * LCP and is the no-JS fallback; <canvas> renders an animated halftone
 * dot field over it (see DitherHero.js). Text + CTAs are real DOM.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$ssq3_url  = function_exists('Standard\\MachinesData\\get_product_url')
    ? \Standard\MachinesData\get_product_url('ssq3-multipro')
    : \Standard\Url\internal('/machines/ssq3-multipro/');
$lineup_url = \Standard\Url\internal('/machines/');
$image      = content_url('/uploads/2026/05/ntm-ssq3-manual-controller-050.jpg');
?>

<link rel="preload" as="image" href="<?php echo esc_url($image); ?>" fetchpriority="high">

<section
    class="dither-hero relative isolate overflow-hidden bg-blue-900 text-white min-h-[75vh] sm:min-h-[88vh] flex items-end"
    data-dither-hero
    aria-label="<?php esc_attr_e('Featured: SSQ3 MultiPro', 'standard'); ?>"
>
    <h1 class="sr-only">
        <?php echo esc_html(sprintf(
            /* translators: %s = site name */
            __('%s: Portable Rollforming Machines', 'standard'),
            get_bloginfo('name')
        )); ?>
    </h1>

    <img
        data-dither-img
        src="<?php echo esc_url($image); ?>"
        alt=""
        aria-hidden="true"
        fetchpriority="high"
        decoding="sync"
        class="dither-hero__img absolute inset-0 h-full w-full object-cover"
    >

    <canvas
        data-dither-canvas
        aria-hidden="true"
        class="dither-hero__canvas absolute inset-0 h-full w-full"
    ></canvas>

    <div class="dither-hero__scrim absolute inset-0" aria-hidden="true"></div>

    <div class="container relative z-10 pb-12 pt-24 lg:pb-20">
        <p class="font-mono text-xs uppercase tracking-[0.2em] text-blue-300">
            <?php esc_html_e('NTM // FLAGSHIP', 'standard'); ?>
        </p>
        <p class="dither-hero__headline mt-4 max-w-3xl text-4xl font-medium leading-[1.05] tracking-tight text-white sm:text-5xl lg:text-6xl">
            <?php esc_html_e('16 Panel Profiles. One Machine.', 'standard'); ?>
        </p>
        <p class="mt-5 max-w-xl text-base text-blue-100 lg:text-lg">
            <?php esc_html_e('The most advanced portable roof and wall panel machine we\'ve ever built. Smarter, safer, more efficient.', 'standard'); ?>
        </p>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="<?php echo esc_url($ssq3_url); ?>" class="btn btn-emphasis dither-hero__cta">
                <?php esc_html_e('Explore the SSQ3', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($lineup_url); ?>" class="btn btn-outline-light">
                <?php esc_html_e('See the lineup', 'standard'); ?>
            </a>
        </div>
    </div>
</section>
