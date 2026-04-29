<?php
/**
 * The header template.
 *
 * Displays the document head, site header with navigation, and mobile menu.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class('min-h-screen flex flex-col'); ?>>
<?php wp_body_open(); ?>

<a href="#primary" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-primary focus:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
    <?php esc_html_e('Skip to main content', 'standard'); ?>
</a>

<header id="site-header" class="bg-white border-b border-slate-200 z-50 transition-colors duration-200">
    <div class="mx-auto lg:container lg:border-x lg:border-slate-200">
        <div class="flex items-center h-12">
            <!-- Mobile menu toggle -->
            <button
                type="button"
                id="mobile-menu-toggle"
                class="flex items-center justify-center w-12 h-12 border-r border-slate-200 text-slate-700 hover:bg-slate-100 transition-colors lg:hidden"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="Toggle menu"
            >
                <span id="menu-icon-open"><?php icon('menu', ['class' => 'w-5 h-5']); ?></span>
                <span id="menu-icon-close" class="hidden"><?php icon('x', ['class' => 'w-4 h-4']); ?></span>
            </button>

            <!-- Logo + Site Name -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center gap-4 h-12 px-4 lg:px-6 lg:border-r border-slate-200 no-underline">
                <?php if (has_custom_logo()) : ?>
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    $logo_url = wp_get_attachment_image_url($custom_logo_id, 'full');
                    ?>
                    <img src="<?php echo esc_url($logo_url); ?>" alt="" class="w-14 object-contain">
                <?php endif; ?>
                <span class="text-sm font-mono font-bold text-slate-600 hover:text-primary">
                    <?php echo esc_html(get_bloginfo('name')); ?>
                </span>
            </a>

            <!-- Desktop navigation -->
            <nav id="desktop-navigation" class="hidden lg:flex items-center h-12 flex-1 border-r border-slate-200">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'container'      => false,
                    'menu_class'     => 'flex items-center h-full',
                    'fallback_cb'    => false,
                    'link_before'    => '<span class="flex items-center gap-1 font-medium">',
                    'link_after'     => '</span>',
                    'walker'         => new \Standard\Walkers\Primary_Nav_Walker(),
                ]);
                ?>
            </nav>

            <!-- Right side icons -->
            <div id="header-actions" class="flex items-center h-12 ml-auto px-2 lg:px-0 transition-opacity duration-200">
                <a href="<?php echo esc_url(home_url('/?s=')); ?>" class="flex items-center justify-center w-12 h-12 text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Search">
                    <?php icon('search', ['class' => 'w-5 h-5']); ?>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mega menu overlay -->
<div id="mega-menu-overlay" aria-hidden="true"></div>

<!-- Mobile menu (full width, below header) -->
<?php $mobile_nav = \Standard\Nav\get_mobile_nav_tree(); ?>
<nav id="mobile-menu" class="mobile-menu lg:hidden" aria-hidden="true" aria-label="<?php esc_attr_e('Mobile navigation', 'standard'); ?>">
    <div class="mobile-menu__viewport">
        <div class="mobile-menu__track" data-active-panel="root">

            <!-- L1 (root) panel -->
            <section class="mobile-menu__panel" data-panel="root" aria-hidden="false">
                <ul class="mobile-menu__list mobile-menu__list--top">
                    <?php foreach ($mobile_nav['top'] as $item) : ?>
                        <li class="mobile-menu__item">
                            <?php if ($item['type'] === 'panel') : ?>
                                <button type="button" class="mobile-menu__row mobile-menu__row--panel" data-panel-target="<?php echo esc_attr($item['slug']); ?>">
                                    <span class="mobile-menu__row-label"><?php echo esc_html($item['label']); ?></span>
                                    <?php icon('chevron-right', ['class' => 'w-4 h-4 mobile-menu__row-chevron']); ?>
                                </button>
                            <?php else : ?>
                                <a class="mobile-menu__row mobile-menu__row--link" href="<?php echo esc_url($item['url']); ?>">
                                    <span class="mobile-menu__row-label"><?php echo esc_html($item['label']); ?></span>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($mobile_nav['featured'])) : $featured = $mobile_nav['featured']; ?>
                    <a class="mobile-menu__featured" href="<?php echo esc_url($featured['url']); ?>">
                        <span class="mobile-menu__featured-image" aria-hidden="true">
                            <img src="<?php echo esc_url($featured['image']); ?>" alt="" loading="lazy" />
                        </span>
                        <span class="mobile-menu__featured-text">
                            <span class="mobile-menu__featured-label"><?php echo esc_html($featured['label']); ?></span>
                            <span class="mobile-menu__featured-subtitle">
                                <?php echo esc_html($featured['subtitle']); ?>
                                <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                            </span>
                        </span>
                    </a>
                <?php endif; ?>

                <ul class="mobile-menu__list mobile-menu__list--bottom">
                    <?php foreach ($mobile_nav['bottom'] as $item) : ?>
                        <li class="mobile-menu__item">
                            <a class="mobile-menu__row mobile-menu__row--link mobile-menu__row--secondary" href="<?php echo esc_url($item['url']); ?>">
                                <?php if (!empty($item['icon'])) : ?>
                                    <span class="mobile-menu__row-icon" aria-hidden="true">
                                        <?php icon($item['icon'], ['class' => 'w-4 h-4']); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="mobile-menu__row-label"><?php echo esc_html($item['label']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <!-- L2 panels (one per panel-type top item) -->
            <?php foreach ($mobile_nav['top'] as $item) : ?>
                <?php if ($item['type'] === 'panel') : ?>
                    <?php get_template_part('templates/parts/mobile-menu-panel', null, [
                        'slug'         => $item['slug'],
                        'label'        => $item['label'],
                        'category'     => $item['category'],
                        'view_all_url' => $item['view_all_url'],
                    ]); ?>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
</nav>