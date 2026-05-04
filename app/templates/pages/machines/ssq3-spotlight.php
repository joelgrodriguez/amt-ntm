<?php
/**
 * Machines Page — SSQ3 Spotlight
 *
 * Feature spotlight for the SSQ3 flagship machine.
 * Two-column split: image left, features right.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_ssq3_features;

$content = [
    'eyebrow'  => __('New — Flagship', 'standard'),
    'title'    => __('SSQ3™ MultiPro Roof Panel Machine', 'standard'),
    'subtitle' => __("NTM's latest and most advanced portable rollformer, building on the proven SSQ II platform. Up to 16 panel profiles with high-speed hydraulic drive and advanced touchscreen controls.", 'standard'),
    'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/10/SSQ3_For-Render_Trailer_Flattened-SQUARE.png',
    'cta_text' => __('Learn More About SSQ3', 'standard'),
    'cta_url'  => '/machines/roof-wall-panel-machines/ssq3-multipro/',
];

$features = get_ssq3_features();
?>

<section class="section" aria-labelledby="ssq3-spotlight-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div class="order-2 lg:order-1">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['title']); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                >
            </div>

            <div class="order-1 lg:order-2 grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="ssq3-spotlight-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle">
                        <?php echo esc_html($content['subtitle']); ?>
                    </p>
                </div>

                <ul class="space-y-4">
                    <?php foreach ($features as $feature) : ?>
                        <li class="flex gap-4">
                            <span class="shrink-0 mt-1">
                                <?php icon('check', ['class' => 'w-4 h-4 text-green-600']); ?>
                            </span>
                            <div>
                                <h3 class="font-medium text-blue-900 mb-0.5">
                                    <?php echo esc_html($feature['title']); ?>
                                </h3>
                                <p class="text-sm text-blue-600">
                                    <?php echo esc_html($feature['text']); ?>
                                </p>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_url'])); ?>" class="btn btn-primary">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
