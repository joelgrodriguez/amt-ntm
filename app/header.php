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
    <!-- Mobile row: toggle | logo | actions -->
    <div class="flex items-center h-16 lg:hidden">
        <!-- Mobile menu toggle -->
        <button
            type="button"
            id="mobile-menu-toggle"
            class="flex items-center justify-center w-16 h-16 border-r border-blue-200 text-blue-700 hover:bg-blue-100 transition-colors"
            aria-expanded="false"
            aria-controls="mobile-menu"
            aria-label="<?php esc_attr_e('Open menu', 'standard'); ?>"
        >
            <span id="menu-icon-open"><?php icon('menu', ['class' => 'w-5 h-5']); ?></span>
            <span id="menu-icon-close" class="hidden"><?php icon('x', ['class' => 'w-4 h-4']); ?></span>
        </button>

        <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="flex items-center h-16 px-4 no-underline">
            <?php if (has_custom_logo()) : ?>
                <?php echo wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
                    'class'    => 'w-14 object-contain',
                    'alt'      => get_bloginfo('name'),
                    'loading'  => 'eager',
                    'decoding' => 'async',
                ]); ?>
            <?php endif; ?>
        </a>

        <div class="flex items-center h-16 ml-auto">
            <a href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>" class="flex items-center justify-center w-16 h-16 text-blue-600 hover:bg-blue-100 transition-colors" aria-label="<?php esc_attr_e('Search', 'standard'); ?>">
                <?php icon('search', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>
    </div>

    <?php $desktop_nav = \Standard\Nav\get_desktop_nav(); ?>

    <!-- Desktop row: logo | [mega triggers centered] | utility rail — full bleed -->
    <div class="hidden lg:grid h-16" style="grid-template-columns: auto 1fr auto;">

        <!-- Logo flush left -->
        <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="flex items-center h-16 px-10 no-underline">
            <?php if (has_custom_logo()) : ?>
                <?php echo wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
                    'class'    => 'w-14 object-contain',
                    'alt'      => get_bloginfo('name'),
                    'loading'  => 'eager',
                    'decoding' => 'async',
                ]); ?>
            <?php endif; ?>
        </a>

        <!-- Mega menu triggers — centered in full header width -->
        <div class="flex items-center justify-center">
            <nav id="desktop-navigation" aria-label="<?php esc_attr_e('Primary navigation', 'standard'); ?>">
                <ul id="primary-menu" class="flex items-center h-16 m-0 p-0 list-none">
                    <?php foreach ($desktop_nav['items'] as $item) : ?>
                        <li class="h-full">
                            <?php if (($item['kind'] ?? '') === 'mega') : ?>
                                <button
                                    type="button"
                                    class="mega-trigger flex items-center h-full px-5 font-sans font-medium text-body text-blue-700 bg-transparent border-0 cursor-pointer hover:bg-blue-100 transition-colors"
                                    style="letter-spacing: 0.01em;"
                                    data-mega-panel="<?php echo esc_attr($item['id']); ?>"
                                    aria-expanded="false"
                                    aria-controls="mega-panel-<?php echo esc_attr($item['id']); ?>"
                                >
                                    <?php echo esc_html($item['label']); ?>
                                </button>
                            <?php else : ?>
                                <a
                                    href="<?php echo esc_url($item['url']); ?>"
                                    class="flex items-center h-full px-5 font-sans font-medium text-body text-blue-700 no-underline hover:bg-blue-100 transition-colors"
                                    style="letter-spacing: 0.01em;"
                                >
                                    <?php echo esc_html($item['label']); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>

        <!-- Contact + search flush right -->
        <div class="flex items-center h-16 gap-3 px-6">
            <?php $contact = array_values(array_filter($desktop_nav['utility'], fn($i) => !empty($i['highlight'])))[0] ?? null; ?>
            <?php if ($contact) : ?>
                <a href="<?php echo esc_url($contact['url']); ?>" class="inline-flex items-center px-3 py-1 font-sans text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 transition-colors no-underline">
                    <?php echo esc_html($contact['label']); ?>
                </a>
            <?php endif; ?>
            <a href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>" class="flex items-center justify-center w-8 h-16 text-blue-600 hover:text-blue-800 transition-colors" aria-label="<?php esc_attr_e('Search', 'standard'); ?>">
                <?php icon('search', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>

    </div>
</header>

<!-- Mega menu panels (desktop) -->
<?php get_template_part('templates/parts/mega-menu'); ?>

<!-- Mobile menu (full width, below header) -->
<?php get_template_part('templates/parts/mobile-menu'); ?>
