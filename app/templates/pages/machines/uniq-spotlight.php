<?php
/**
 * Machines Page — UNIQ Technology Spotlight
 *
 * Two-column light section: product image left (with availability mono
 * caption beneath), features-as-accordion right. Accordion items are
 * all closed by default so the section reads compact and the visitor
 * opens whatever's relevant to them.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
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
    'image'        => content_url('/uploads/2021/10/SSQ-II-Training-General-Overview-Featured-Image-2048x1152.png'),
    'cta_text'     => __('Learn More', 'standard'),
    'cta_url'      => '/technology/uniq/',
];

$features = get_uniq_features();
?>

<section class="section" aria-labelledby="uniq-spotlight-title">
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

                <div data-accordion-group>
                    <?php foreach ($features as $feature) : ?>
                        <details class="accordion">
                            <summary>
                                <?php echo esc_html($feature['title']); ?>
                                <span class="accordion__icon">
                                    <?php icon('chevron-down', ['class' => 'w-5 h-5']); ?>
                                </span>
                            </summary>
                            <div class="accordion__body text-base text-blue-600 leading-relaxed">
                                <p><?php echo esc_html($feature['text']); ?></p>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>

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
