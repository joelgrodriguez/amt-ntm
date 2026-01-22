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

$features = [
    [
        'icon'  => 'settings',
        'title' => __('Build Your Machine', 'standard'),
        'text'  => __('Choose your profile, coil width, and options.', 'standard'),
    ],
    [
        'icon'  => 'dollars',
        'title' => __('See Real Pricing', 'standard'),
        'text'  => __('Get transparent pricing instantly—no waiting.', 'standard'),
    ],
    [
        'icon'  => 'finance',
        'title' => __('Apply for Financing', 'standard'),
        'text'  => __('Flexible financing options built right in.', 'standard'),
    ],
];
?>

<section class="configurator py-16 bg-slate-50 md:py-20 lg:py-24" aria-labelledby="configurator-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <!-- Image Panel -->
            <div class="order-2 lg:order-1">
                <img
                    src="<?php echo esc_url(get_theme_file_uri('assets/images/config-mockup.png')); ?>"
                    alt="<?php esc_attr_e('NTM Machine Configurator Interface', 'standard'); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                >
            </div>

            <!-- Content Panel -->
            <div class="order-1 lg:order-2">
                <p class="text-sm font-semibold uppercase tracking-wider text-secondary mb-2">
                    <?php esc_html_e('Machine Configurator', 'standard'); ?>
                </p>
                <div class="w-12 h-1 bg-secondary mb-6"></div>

                <h2 id="configurator-title" class="text-3xl font-bold text-slate-900 mb-4 md:text-4xl lg:text-5xl">
                    <?php esc_html_e('Configure Your Machine Online', 'standard'); ?>
                </h2>

                <p class="text-lg text-slate-600 mb-10 max-w-xl">
                    <?php esc_html_e('Design your perfect rollformer, see exactly what it costs, and apply for financing—all from your browser. No phone calls. No waiting.', 'standard'); ?>
                </p>

                <!-- Feature Grid -->
                <div class="grid gap-6 sm:grid-cols-3 mb-10">
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
                    <a href="/configurator/" class="btn btn-primary btn-lg group">
                        <?php esc_html_e('Try the Configurator', 'standard'); ?>
                        <?php icon('arrow--right', ['class' => 'w-5 h-5 ml-2 transition-transform group-hover:translate-x-1']); ?>
                    </a>
                    <span class="text-sm text-slate-500">
                        <?php esc_html_e('Get your machine today', 'standard'); ?>
                    </span>
                </div>
            </div>

        </div>
    </div>
</section>
