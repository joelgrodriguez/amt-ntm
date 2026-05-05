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

<a href="#primary" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-[100] focus:px-4 focus:py-2 focus:bg-blue-500 focus:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
    <?php esc_html_e('Skip to main content', 'standard'); ?>
</a>

<header id="site-header" class="bg-white border-b border-blue-200 z-50 transition-colors duration-200">
    <div class="mx-auto lg:container lg:border-x lg:border-blue-200">
        <div class="flex items-center h-16">
            <!-- Mobile menu toggle -->
            <button
                type="button"
                id="mobile-menu-toggle"
                class="flex items-center justify-center w-16 h-16 border-r border-blue-200 text-blue-700 hover:bg-blue-100 transition-colors lg:hidden"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="<?php esc_attr_e('Open menu', 'standard'); ?>"
            >
                <span id="menu-icon-open"><?php icon('menu', ['class' => 'w-5 h-5']); ?></span>
                <span id="menu-icon-close" class="hidden"><?php icon('x', ['class' => 'w-4 h-4']); ?></span>
            </button>

            <!-- Logo + Site Name -->
            <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="flex items-center gap-4 h-16 px-4 lg:px-6 lg:border-r border-blue-200 no-underline">
                <?php if (has_custom_logo()) : ?>
                    <?php
                    $custom_logo_id = get_theme_mod('custom_logo');
                    ?>
                    <?php echo wp_get_attachment_image((int) $custom_logo_id, 'full', false, [
                        'class'    => 'w-14 object-contain',
                        'alt'      => get_bloginfo('name'),
                        'loading'  => 'eager',
                        'decoding' => 'async',
                    ]); ?>
                <?php endif; ?>
                <span class="hidden lg:block text-sm font-mono font-medium text-blue-600 hover:text-blue-500">
                    <?php echo esc_html(get_bloginfo('name')); ?>
                </span>
            </a>

            <!-- Desktop navigation -->
            <nav id="desktop-navigation" class="hidden lg:flex items-center h-16 flex-1 border-r border-blue-200">
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
            <div id="header-actions" class="flex items-center h-16 ml-auto px-2 lg:px-0 transition-opacity duration-200">
                <a href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>" class="flex items-center justify-center w-16 h-16 text-blue-600 hover:bg-blue-100 transition-colors" aria-label="Search">
                    <?php icon('search', ['class' => 'w-5 h-5']); ?>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mega menu overlay -->
<div id="mega-menu-overlay" aria-hidden="true"></div>

<!-- Mobile menu (full width, below header) -->
<?php get_template_part('templates/parts/mobile-menu'); ?>
