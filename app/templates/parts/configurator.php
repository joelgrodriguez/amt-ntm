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

if (!defined('ABSPATH')) {
    exit;
}

$defaults = [
    'eyebrow'   => __('Machine Configurator', 'standard'),
    'title'     => __('Configure Your Machine Online', 'standard'),
    'text'      => __('Design your perfect rollformer, see exactly what it costs, and apply for financing, all from your browser. No phone calls. No waiting.', 'standard'),
    'image'     => get_theme_file_uri('assets/images/config-mockup.png'),
    'image_alt' => __('NTM Machine Configurator Interface', 'standard'),
    'section_id' => 'configurator',
];

$content = wp_parse_args($args ?? [], $defaults);

$features = [
    [
        'icon'  => 'settings',
        'title' => __('Build Your Machine', 'standard'),
        'text'  => __('Choose your profile, coil width, and options.', 'standard'),
    ],
    [
        'icon'  => 'dollar-sign',
        'title' => __('See Real Pricing', 'standard'),
        'text'  => __('Get transparent pricing instantly, no waiting.', 'standard'),
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Apply for Financing', 'standard'),
        'text'  => __('Flexible financing options built right in.', 'standard'),
    ],
];
?>

<section class="configurator section" aria-labelledby="<?php echo esc_attr($content['section_id']); ?>-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <!-- Image Panel -->
            <div class="order-2 lg:order-1">
                <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                    'class'  => 'w-full h-auto',
                    'width'  => '2613',
                    'height' => '1634',
                ]); ?>
            </div>

            <!-- Content Panel -->
            <div class="order-1 lg:order-2 grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="<?php echo esc_attr($content['section_id']); ?>-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle max-w-xl">
                        <?php echo esc_html($content['text']); ?>
                    </p>
                </div>

                <!-- Feature Grid -->
                <div class="grid gap-8 sm:grid-cols-3">
                    <?php foreach ($features as $feature) : ?>
                        <div class="flex flex-col gap-3 border-t border-blue-200 pt-4">
                            <?php icon($feature['icon'], ['class' => 'w-6 h-6 text-blue-500']); ?>
                            <h3 class="text-base font-medium text-blue-700">
                                <?php echo esc_html($feature['title']); ?>
                            </h3>
                            <p class="text-sm text-blue-600">
                                <?php echo esc_html($feature['text']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php get_template_part('templates/parts/cta/two-door', null, [
                    'primary_label' => __('Try the Configurator', 'standard'),
                    'primary_url'   => '/configurator/',
                ]); ?>
            </div>

        </div>
    </div>
</section>
