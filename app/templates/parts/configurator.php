<?php
/**
 * Configurator CTA Section Template Part
 *
 * Two-column promotion for the configurator: a screenshot of the tool on the
 * left, a content stack on the right with section title, supporting copy, a
 * three-up feature grid, and the primary CTA.
 *
 * The feature grid uses hairline top-borders (not boxed cards) so the trio
 * reads as a spec list, not an icon-card row.
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
    'title'      => __('Configure Your Machine Online', 'standard'),
    'text'       => __('Design your rollformer, price it, and apply for financing, all from your browser.', 'standard'),
    'image'      => get_theme_file_uri('assets/images/config-mockup.png'),
    'image_alt'  => __('NTM Machine Configurator Interface', 'standard'),
    'section_id' => 'configurator',
];

$content = wp_parse_args($args ?? [], $defaults);

$features = [
    [
        'icon'  => 'settings',
        'title' => __('Build it', 'standard'),
        'text'  => __('Profile, coil width, options. All your choices, your way.', 'standard'),
    ],
    [
        'icon'  => 'dollar-sign',
        'title' => __('Price it', 'standard'),
        'text'  => __('Transparent pricing the moment you finish the build.', 'standard'),
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Finance it', 'standard'),
        'text'  => __('Apply for flexible financing in the same flow.', 'standard'),
    ],
];
?>

<section class="configurator section" aria-labelledby="<?php echo esc_attr($content['section_id']); ?>-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-2 lg:gap-16 lg:items-center">
            <div class="order-2 lg:order-1">
                <?php \Standard\Images\responsive_image($content['image'], $content['image_alt'], 'large', [
                    'class'  => 'w-full h-auto',
                    'width'  => '2613',
                    'height' => '1634',
                ]); ?>
            </div>
            <div class="order-1 lg:order-2 grid gap-8 lg:gap-10 content-start">
                <div class="section-header-left">
                    <h2 id="<?php echo esc_attr($content['section_id']); ?>-title" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                    <p class="section-subtitle max-w-xl">
                        <?php echo esc_html($content['text']); ?>
                    </p>
                </div>
                <div class="grid gap-8 sm:grid-cols-3">
                    <?php foreach ($features as $feature) : ?>
                        <div class="flex flex-col gap-3 border-t border-blue-200 pt-4">
                            <?php icon($feature['icon'], ['class' => 'w-6 h-6 text-blue-500']); ?>
                            <h3 class="text-base font-medium text-blue-900">
                                <?php echo esc_html($feature['title']); ?>
                            </h3>
                            <p class="text-sm text-blue-600">
                                <?php echo esc_html($feature['text']); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="flex">
                    <a
                        href="<?php echo esc_url(\Standard\Url\internal('/configurator/')); ?>"
                        class="btn btn-primary btn--commit"
                        target="_blank" rel="noopener"
                    >
                        <?php esc_html_e('Start Configuring', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
