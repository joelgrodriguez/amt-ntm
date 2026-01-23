<?php
/**
 * Configurator CTA Section Template Part
 *
 * IBM Carbon Design-inspired asymmetric layout promoting the machine configurator.
 * Features split layout with image and feature grid.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow'  => __('Machine Configurator', 'standard'),
    'title'    => __('Configure Your Machine Online', 'standard'),
    'text'     => __('Design your perfect rollformer, see exactly what it costs, and apply for financing—all from your browser. No phone calls. No waiting.', 'standard'),
    'image'    => get_theme_file_uri('assets/images/config-mockup.png'),
    'image_alt' => __('NTM Machine Configurator Interface', 'standard'),
    'cta_text' => __('Try the Configurator', 'standard'),
    'cta_url'  => '/configurator/',
    'cta_note' => __('Get your machine today', 'standard'),
];

$features = [
    [
        'icon'  => 'settings',
        'title' => __('Build Your Machine', 'standard'),
        'text'  => __('Choose your profile, coil width, and options.', 'standard'),
    ],
    [
        'icon'  => 'dollar-sign',
        'title' => __('See Real Pricing', 'standard'),
        'text'  => __('Get transparent pricing instantly—no waiting.', 'standard'),
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Apply for Financing', 'standard'),
        'text'  => __('Flexible financing options built right in.', 'standard'),
    ],
];
?>

<section class="configurator section" aria-labelledby="configurator-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <!-- Image Panel -->
            <div class="order-2 lg:order-1">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['image_alt']); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                >
            </div>

            <!-- Content Panel -->
            <div class="order-1 lg:order-2 grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="configurator-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle max-w-xl">
                        <?php echo esc_html($content['text']); ?>
                    </p>
                </div>

                <!-- Feature Grid -->
                <div class="grid gap-6 sm:grid-cols-3">
                    <?php foreach ($features as $feature) : ?>
                        <div class="flex flex-col">
                            <div class="flex items-center justify-center w-12 h-12 bg-primary/10 text-primary mb-4">
                                <?php icon($feature['icon'], ['class' => 'w-6 h-6']); ?>
                            </div>
                            <h3 class="text-base font-semibold text-slate-900 mb-1">
                                <?php echo esc_html($feature['title']); ?>
                            </h3>
                            <p class="text-sm text-slate-600">
                                <?php echo esc_html($feature['text']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- CTA -->
                <div class="flex flex-wrap items-center gap-4">
                    <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-primary btn-lg group">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5 ml-2 transition-transform group-hover:translate-x-1']); ?>
                    </a>
                    <span class="text-sm text-slate-500">
                        <?php echo esc_html($content['cta_note']); ?>
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>
