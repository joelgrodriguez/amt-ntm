<?php
/**
 * The footer template.
 *
 * Displays the site footer with navigation columns and legal links.
 * Mobile: native details accordions for each section.
 * Desktop: 5-column grid layout.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$footer_sections = [
    [
        'title' => __('About', 'standard'),
        'description' => __('Since 1991, New Tech Machinery (NTM) has helped contractors worldwide gain more control of their projects and profits by manufacturing the world\'s finest portable rollforming machines.', 'standard'),
        'links' => null,
    ],
    [
        'title' => __('Products', 'standard'),
        'description' => null,
        'links' => [
            ['/configure-your-ntm-machine', __('Configure Your Machine', 'standard'), true],
            ['/roof-wall-panel-machines/', __('Roof & Wall Panel Machines', 'standard'), false],
            ['/seamless-gutter-machines/', __('Gutter Machines', 'standard'), false],
            ['/profiles', __('Profiles', 'standard'), false],
        ],
    ],
    [
        'title' => __('Resources', 'standard'),
        'description' => null,
        'links' => [
            ['/learning-center', __('Learning Center', 'standard'), false],
            ['/service-training/', __('Rollforming Training', 'standard'), false],
            ['/machines/leasing-financing', __('Financing', 'standard'), false],
            ['/machines/manuals/', __('NTM Machine Manuals', 'standard'), false],
        ],
    ],
    [
        'title' => __('Company', 'standard'),
        'description' => null,
        'links' => [
            ['/about', __('About', 'standard'), false],
            ['/learning-center/why-choose-new-tech-machinery/', __('Why New Tech Machinery', 'standard'), false],
            ['/careers', __('Careers', 'standard'), false],
            ['/contact', __('Contact Us', 'standard'), false],
        ],
    ],
];

$social_links = [
    ['facebook', 'https://www.facebook.com/NTMMazzella/', __('Facebook', 'standard')],
    ['twitter', 'https://x.com/NewTechMach', __('X (Twitter)', 'standard')],
    ['linkedin', 'https://www.linkedin.com/company/new-tech-machinery', __('LinkedIn', 'standard')],
    ['instagram', 'https://www.instagram.com/newtechmach/', __('Instagram', 'standard')],
];

$legal_links = [
    [__('Privacy', 'standard'), '/privacy-policy/'],
    [__('Terms of Service', 'standard'), '/terms-of-service/'],
    [__('Terms of Sale', 'standard'), '/general-terms-conditions/'],
];
?>

<footer class="mt-auto bg-blue-900 text-blue-300 border-t border-blue-700">
    <div class="container py-8 lg:py-12">
        <div class="grid gap-0 lg:grid-cols-5 lg:gap-12">
            <div class="lg:col-span-1 pb-4 lg:pb-0">
                <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="block">
                    <img
                        src="<?php echo esc_url(content_url('/uploads/2024/09/ntm-logos-1_color-white-e1776460003528.png')); ?>"
                        alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        class="h-16 w-auto lg:h-24"
                        loading="lazy"
                    >
                </a>
            </div>
            <?php foreach ($footer_sections as $index => $section) : ?>
                <details class="footer-section footer-accordion border-t border-blue-700 lg:border-0" data-footer-accordion>
                    <summary class="flex items-center justify-between py-4 cursor-pointer lg:cursor-default lg:py-0 lg:mb-4 focus-visible:outline-2 focus-visible:outline-blue-300 focus-visible:outline-offset-2">
                        <span class="text-white font-medium text-sm uppercase tracking-wider">
                            <?php echo esc_html($section['title']); ?>
                        </span>
                        <span class="footer-accordion-icon lg:hidden">
                            <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-300 transition-transform duration-300']); ?>
                        </span>
                    </summary>
                    <div class="footer-accordion-content">
                        <div class="pb-4 lg:pb-0">
                            <?php if ($section['description']) : ?>
                                <p class="text-sm text-blue-300 leading-relaxed">
                                    <?php echo esc_html($section['description']); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($section['links']) : ?>
                                <ul class="grid gap-2">
                                    <?php foreach ($section['links'] as $link) : ?>
                                        <li>
                                            <a
                                                href="<?php echo esc_url(\Standard\Url\internal($link[0])); ?>"
                                                class="text-sm text-blue-300 hover:text-white transition-colors inline-flex items-center gap-2"
                                            >
                                                <?php echo esc_html($link[1]); ?>
                                                <?php if (!empty($link[2])) : ?>
                                                    <span class="text-[10px] font-medium uppercase tracking-wider bg-blue-500 text-white px-1.5 py-0.5">
                                                        <?php esc_html_e('New', 'standard'); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </details>
            <?php endforeach; ?>

        </div>
    </div>
    <div class="border-t border-blue-700">
        <div class="container py-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <nav class="flex flex-wrap gap-4 text-xs">
                    <?php foreach ($legal_links as $link) : ?>
                        <a
                            href="<?php echo esc_url(\Standard\Url\internal($link[1])); ?>"
                            class="text-blue-300 hover:text-white transition-colors"
                        >
                            <?php echo esc_html($link[0]); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
                <p class="text-xs text-blue-300 md:text-center">
                    &copy; <?php echo esc_html(current_time('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>.
                    <?php esc_html_e('All rights reserved.', 'standard'); ?>
                </p>
                <div class="flex items-center gap-4">
                    <?php foreach ($social_links as $social) : ?>
                        <a
                            href="<?php echo esc_url($social[1]); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-blue-300 hover:text-white transition-colors"
                            aria-label="<?php echo esc_attr($social[2]); ?>"
                        >
                            <?php icon($social[0], ['class' => 'w-5 h-5']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>
    </div>
</footer>
<?php
$floating_build_cta = \Standard\FloatingBuildCta\get_context();
if ($floating_build_cta !== null) {
    get_template_part('templates/parts/floating-build-cta', null, $floating_build_cta);
}
?>
<svg style="position:absolute;width:0;height:0" aria-hidden="true">
    <filter id="hero-grain-filter">
        <feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/>
        <feColorMatrix type="saturate" values="0"/>
    </filter>
</svg>

<?php wp_footer(); ?>
</body>
</html>
