<?php
/**
 * Roof & Wall Pillar — UNIQ Technology Spotlight
 *
 * Two-row composition:
 *   1. Image + header (two columns on md+).
 *   2. Four-feature tile grid (2×2 on md+), full-width below.
 *      Each tile carries a mono index, title, and short description so
 *      the section reads as engineering-grade specs rather than a long
 *      thin right column.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_uniq_features;

$content = [
    'eyebrow'      => __('Technology', 'standard'),
    'title'        => __('UNIQ® Automatic<br class="hidden lg:inline"> Control System', 'standard'),
    'subtitle'     => __("NTM's most advanced programmable controller, designed to improve automation, safety, and the operator experience.", 'standard'),
    'availability' => __('Standard on WAV · Optional on SSQ II & SSQ3', 'standard'),
    'image'        => content_url('/uploads/2023/09/WAV-with-UNIQ-Controller-scaled.jpg'),
    'cta_text'     => __('Learn More', 'standard'),
    'cta_url'      => '/machines/uniq-control-system/',
];

$features = get_uniq_features();
?>

<section class="section bg-blue-50" aria-labelledby="uniq-spotlight-title">
    <div class="container grid gap-12 lg:gap-16">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-center">
            <div class="grid gap-3">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr__('UNIQ Automatic Control System touchscreen', 'standard'); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                >
                <p class="text-sm font-mono uppercase tracking-wider text-blue-500">
                    <?php echo esc_html($content['availability']); ?>
                </p>
            </div>

            <div class="section-header-left">
                <p class="section-eyebrow">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <div class="section-divider"></div>
                <h2 id="uniq-spotlight-title" class="section-title">
                    <?php echo wp_kses($content['title'], ['br' => ['class' => []]]); ?>
                </h2>
                <p class="section-subtitle">
                    <?php echo esc_html($content['subtitle']); ?>
                </p>
                <div class="mt-6">
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-outline-dark">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>
        </div>
        <ol class="grid gap-px bg-blue-200 border border-blue-200 md:grid-cols-2" role="list">
            <?php foreach ($features as $idx => $feature) : ?>
                <li class="grid content-start gap-3 bg-white p-6 lg:p-8">
                    <div class="flex items-center gap-3 font-mono text-xs uppercase tracking-wider text-blue-500">
                        <span><?php echo esc_html(sprintf('%02d', $idx + 1)); ?></span>
                        <span class="w-8 h-px bg-blue-300" aria-hidden="true"></span>
                    </div>
                    <h3 class="text-lg font-medium text-blue-900 lg:text-xl">
                        <?php echo esc_html($feature['title']); ?>
                    </h3>
                    <p class="text-base text-blue-600 leading-relaxed">
                        <?php echo esc_html($feature['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ol>

    </div>
</section>
