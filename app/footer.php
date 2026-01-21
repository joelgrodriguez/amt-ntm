<?php
/**
 * The footer template.
 *
 * Displays the site footer with navigation columns and legal links.
 * Mobile: CSS-only accordions for each section.
 * Desktop: 5-column grid layout.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Standard
 */

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
            ['/product-category/roof-wall-panel-machines', __('Roof & Wall Panel Machines', 'standard'), false],
            ['/product-category/gutter-machines', __('Gutter Machines', 'standard'), false],
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
            ['/manuals', __('NTM Machine Manuals', 'standard'), false],
        ],
    ],
    [
        'title' => __('Company', 'standard'),
        'description' => null,
        'links' => [
            ['/about', __('About', 'standard'), false],
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

<footer class="mt-auto bg-slate-900 text-slate-300">
    <!-- Main Footer Content -->
    <div class="container mx-auto py-8 lg:py-12">
        <!-- Desktop: 5 columns | Mobile: Logo + Accordions -->
        <div class="grid gap-0 lg:grid-cols-5 lg:gap-12">

            <!-- Logo Column (always visible) -->
            <div class="lg:col-span-1 pb-4 lg:pb-0">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="block">
                    <img
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/ntm-logo-white.png'); ?>"
                        alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                        class="h-12 w-auto"
                        loading="lazy"
                    >
                </a>
            </div>

            <!-- Footer Sections -->
            <?php foreach ($footer_sections as $index => $section) : ?>
                <div class="footer-section border-t border-slate-700 lg:border-0">
                    <!-- Accordion Header (mobile) / Title (desktop) -->
                    <input
                        type="checkbox"
                        id="footer-accordion-<?php echo esc_attr($index); ?>"
                        class="footer-accordion-toggle peer sr-only"
                    >
                    <label
                        for="footer-accordion-<?php echo esc_attr($index); ?>"
                        class="flex items-center justify-between py-4 cursor-pointer lg:cursor-default lg:py-0 lg:mb-4"
                    >
                        <h3 class="text-white font-semibold text-sm uppercase tracking-wider">
                            <?php echo esc_html($section['title']); ?>
                        </h3>
                        <span class="footer-accordion-icon lg:hidden">
                            <?php icon('caret--down', ['class' => 'w-3 h-3 text-slate-300 transition-transform duration-300']); ?>
                        </span>
                    </label>

                    <!-- Accordion Content -->
                    <div class="footer-accordion-content grid overflow-hidden transition-all duration-300 ease-in-out max-h-0 peer-checked:max-h-96 lg:max-h-none">
                        <div class="pb-4 lg:pb-0">
                            <?php if ($section['description']) : ?>
                                <p class="text-sm text-slate-400 leading-relaxed">
                                    <?php echo esc_html($section['description']); ?>
                                </p>
                            <?php endif; ?>

                            <?php if ($section['links']) : ?>
                                <ul class="grid gap-2">
                                    <?php foreach ($section['links'] as $link) : ?>
                                        <li>
                                            <a
                                                href="<?php echo esc_url(home_url($link[0])); ?>"
                                                class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-2"
                                            >
                                                <?php echo esc_html($link[1]); ?>
                                                <?php if (!empty($link[2])) : ?>
                                                    <span class="text-[10px] font-bold uppercase tracking-wider bg-primary text-white px-1.5 py-0.5">
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
                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-slate-700">
        <div class="container mx-auto py-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">

                <!-- Legal Links (left) -->
                <nav class="flex flex-wrap gap-4 text-xs">
                    <?php foreach ($legal_links as $link) : ?>
                        <a
                            href="<?php echo esc_url(home_url($link[1])); ?>"
                            class="text-slate-400 hover:text-white transition-colors"
                        >
                            <?php echo esc_html($link[0]); ?>
                        </a>
                    <?php endforeach; ?>
                </nav>

                <!-- Copyright (center) -->
                <p class="text-xs text-slate-500 md:text-center">
                    &copy; <?php echo esc_html(current_time('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>.
                    <?php esc_html_e('All rights reserved.', 'standard'); ?>
                </p>

                <!-- Social Links (right) -->
                <div class="flex items-center gap-4">
                    <?php foreach ($social_links as $social) : ?>
                        <a
                            href="<?php echo esc_url($social[1]); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-slate-400 hover:text-white transition-colors"
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

<!-- Scroll to Top Button -->
<button
    id="scroll-to-top"
    type="button"
    class="fixed bottom-6 right-6 z-50 w-10 h-10 bg-secondary text-white flex items-center justify-center hover:bg-slate-600 transition-all duration-300 opacity-0 pointer-events-none"
    aria-label="<?php esc_attr_e('Scroll to top', 'standard'); ?>"
>
    <?php icon('back-to-top', ['class' => 'w-4 h-4']); ?>
</button>

<?php wp_footer(); ?>
</body>
</html>
