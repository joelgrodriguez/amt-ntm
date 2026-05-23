<?php
/**
 * Roof & Wall Pillar — UNIQ Technology Spotlight
 *
 * Two-column light section: product image left (with availability mono
 * caption beneath), feature bullets right with a CTA into the deeper
 * technology page. The accordion variant was retired because the four
 * UNIQ features are short enough to read at a glance.
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
    'title'        => __('UNIQ® Automatic Control System', 'standard'),
    'subtitle'     => __("NTM's most advanced programmable controller, designed to improve automation, safety, and the operator experience.", 'standard'),
    'availability' => __('Standard on WAV · Optional on SSQ II & SSQ3', 'standard'),
    'image'        => content_url('/uploads/2023/09/WAV-with-UNIQ-Controller-scaled.jpg'),
    'cta_text'     => __('Learn More', 'standard'),
    'cta_url'      => '/machines/uniq-control-system/',
];

$features = get_uniq_features();
?>

<section class="section bg-blue-50" aria-labelledby="uniq-spotlight-title">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-start">

            <!-- Image + availability caption -->
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

            <!-- Header + accordion + CTA -->
            <div class="grid gap-8 md:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="uniq-spotlight-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle">
                        <?php echo esc_html($content['subtitle']); ?>
                    </p>
                </div>

                <ul class="grid gap-5 border-t border-blue-200 pt-6">
                    <?php foreach ($features as $feature) : ?>
                        <li class="grid gap-1">
                            <h3 class="text-base font-medium text-blue-900">
                                <?php echo esc_html($feature['title']); ?>
                            </h3>
                            <p class="text-base text-blue-600 leading-relaxed">
                                <?php echo esc_html($feature['text']); ?>
                            </p>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-outline-dark">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
