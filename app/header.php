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

<header id="site-header" class="bg-white z-50">
    <!-- Mobile row: toggle | logo | actions -->
    <div class="flex items-center h-14 lg:hidden">
        <!-- Mobile menu toggle -->
        <button
            type="button"
            id="mobile-menu-toggle"
            class="flex items-center justify-center w-14 h-14 text-neutral-800 hover:text-neutral-600 transition-colors"
            aria-expanded="false"
            aria-controls="mobile-menu"
            aria-label="<?php esc_attr_e('Open menu', 'standard'); ?>"
        >
            <span id="menu-icon-open"><?php icon('menu', ['class' => 'w-5 h-5']); ?></span>
            <span id="menu-icon-close" class="hidden"><?php icon('x', ['class' => 'w-4 h-4']); ?></span>
        </button>

        <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="flex items-center h-14 px-4 no-underline">
            <?php if (has_custom_logo()) : ?>
                <?php echo wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
                    'class'    => 'w-12 object-contain',
                    'alt'      => get_bloginfo('name'),
                    'loading'  => 'eager',
                    'decoding' => 'async',
                ]); ?>
            <?php endif; ?>
        </a>

        <div class="flex items-center h-14 ml-auto">
            <a href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>" class="flex items-center justify-center w-14 h-14 text-neutral-800 hover:text-neutral-600 transition-colors" aria-label="<?php esc_attr_e('Search', 'standard'); ?>">
                <?php icon('search', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>
    </div>

    <?php $desktop_nav = \Standard\Nav\get_desktop_nav(); ?>

    <!-- Desktop row: logo | [mega triggers centered] | utility rail — full bleed -->
    <div class="hidden lg:grid h-11" style="grid-template-columns: auto 1fr auto;">

        <!-- Logo flush left -->
        <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="flex items-center h-11 px-8 no-underline">
            <?php if (has_custom_logo()) : ?>
                <?php echo wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
                    'class'    => 'h-5 w-auto object-contain',
                    'alt'      => get_bloginfo('name'),
                    'loading'  => 'eager',
                    'decoding' => 'async',
                ]); ?>
            <?php endif; ?>
        </a>

        <!-- Mega menu triggers — centered in full header width -->
        <div class="flex items-center justify-center">
            <nav id="desktop-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'standard'); ?>">
                <ul id="primary-menu" class="flex items-center h-11 m-0 p-0 list-none">
                    <?php foreach ($desktop_nav['panels'] as $panel) : ?>
                        <li class="h-full">
                            <button
                                type="button"
                                class="mega-trigger flex items-center h-full px-4 font-sans text-sm font-normal tracking-wide text-neutral-800 bg-transparent border-0 cursor-pointer hover:text-neutral-500 transition-colors"
                                data-mega-panel="<?php echo esc_attr($panel['id']); ?>"
                                aria-expanded="false"
                                aria-controls="mega-panel-<?php echo esc_attr($panel['id']); ?>"
                            >
                                <?php echo esc_html($panel['label']); ?>
                                <?php icon('chevron-down', ['class' => 'w-3 h-3 ml-1 mega-trigger__caret transition-transform duration-200']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>

        <!-- Utility rail flush right -->
        <div id="header-actions" class="flex items-center h-11 gap-0.5 px-6">
            <?php foreach ($desktop_nav['utility'] as $item) : ?>
                <?php if (!empty($item['highlight'])) : ?>
                    <a
                        href="<?php echo esc_url($item['url']); ?>"
                        class="inline-flex items-center px-3.5 py-1 font-sans text-sm font-normal tracking-wide text-neutral-800 border border-neutral-300 hover:bg-neutral-100 transition-colors no-underline"
                    >
                        <?php echo esc_html($item['label']); ?>
                    </a>
                <?php else : ?>
                    <a
                        href="<?php echo esc_url($item['url']); ?>"
                        class="flex items-center h-full px-3 font-sans text-sm font-normal tracking-wide text-neutral-800 no-underline hover:text-neutral-500 transition-colors"
                    >
                        <?php echo esc_html($item['label']); ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
            <a href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>" class="flex items-center justify-center w-10 h-11 text-neutral-800 hover:text-neutral-500 transition-colors" aria-label="<?php esc_attr_e('Search', 'standard'); ?>">
                <?php icon('search', ['class' => 'w-4 h-4']); ?>
            </a>
        </div>

    </div>
</header>

<!-- Mega menu panels (desktop) -->
<?php get_template_part('templates/parts/mega-menu'); ?>

<!-- Mobile menu (full width, below header) -->
<?php get_template_part('templates/parts/mobile-menu'); ?>
