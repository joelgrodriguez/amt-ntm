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

<header id="site-header" class="bg-white border-y border-blue-200 z-50 transition-colors duration-200">
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
            <span class="t-icon-swap" data-state="a" data-menu-icon-swap>
                <span class="t-icon" data-icon="a" id="menu-icon-open"><?php icon('menu', ['class' => 'w-5 h-5']); ?></span>
                <span class="t-icon" data-icon="b" id="menu-icon-close"><?php icon('x', ['class' => 'w-4 h-4 text-red']); ?></span>
            </span>
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
            <a
                href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>"
                class="flex items-center justify-center w-16 h-16 text-blue-600 hover:bg-blue-100 transition-colors"
                aria-label="<?php esc_attr_e('Search', 'standard'); ?>"
                aria-haspopup="dialog"
                aria-controls="site-search-modal"
                data-search-modal-open
            >
                <?php icon('search', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>
    </div>

    <?php $desktop_nav = \Standard\Nav\get_desktop_nav(); ?>

    <!-- Desktop row: logo rail | mega triggers | utility rail — capped at --layout-wide.
         Uses .container so the 16px gutter matches body content alignment below.
         Grid is 1fr | auto | 1fr so the side rails are equal width and the
         middle nav stays mathematically centered in the header. -->
    <div class="container hidden lg:grid h-16" style="grid-template-columns: 1fr auto 1fr;">

        <!-- Logo rail: flush left, equal-width with utility rail -->
        <div class="flex items-center w-full h-16">
            <a href="<?php echo esc_url(\Standard\Url\internal('/')); ?>" class="flex items-center h-16 no-underline">
                <?php if (has_custom_logo()) : ?>
                    <?php echo wp_get_attachment_image((int) get_theme_mod('custom_logo'), 'full', false, [
                        'class'    => 'w-14 object-contain',
                        'alt'      => get_bloginfo('name'),
                        'loading'  => 'eager',
                        'decoding' => 'async',
                    ]); ?>
                <?php endif; ?>
            </a>
        </div>

        <!-- Mega menu triggers — centered in full header width -->
        <div class="flex items-stretch">
            <nav id="desktop-navigation" class="flex" aria-label="<?php esc_attr_e('Primary navigation', 'standard'); ?>">
                <ul id="primary-menu" class="flex items-stretch m-0 p-0 list-none">
                    <?php foreach ($desktop_nav['items'] as $item) :
                        $is_mega       = ($item['kind'] ?? '') === 'mega';
                        $fallback_url  = $item['url'] ?? ($item['view_all_url'] ?? '#');
                        $is_current    = \Standard\Nav\is_current_item($item);
                        $current_class = $is_current ? ' is-current' : '';
                        $current_attr  = $is_current ? ' aria-current="page"' : '';
                    ?>
                        <li class="flex h-full">
                            <?php if ($is_mega) : ?>
                                <button
                                    type="button"
                                    class="mega-trigger relative flex items-center w-full h-full px-5 font-sans font-medium text-body text-blue-700 bg-transparent border-0 cursor-pointer<?php echo $current_class; ?>"
                                    data-mega-panel="<?php echo esc_attr($item['id']); ?>"
                                    aria-expanded="false"
                                    aria-controls="mega-panel-<?php echo esc_attr($item['id']); ?>"
                                    <?php echo $current_attr; ?>
                                >
                                    <?php echo esc_html($item['label']); ?>
                                </button>
                            <?php else : ?>
                                <a
                                    href="<?php echo esc_url($fallback_url); ?>"
                                    class="relative flex items-center w-full h-full px-5 font-sans font-medium text-body text-blue-700 no-underline<?php echo $current_class; ?>"
                                    <?php echo $current_attr; ?>
                                >
                                    <?php echo esc_html($item['label']); ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </div>

        <!-- Utility rail: contact + search flush right, equal-width with logo rail -->
        <div class="flex items-center justify-end w-full h-16 gap-10">
            <?php $contact = array_values(array_filter($desktop_nav['utility'], fn($i) => !empty($i['highlight'])))[0] ?? null; ?>
            <?php if ($contact) : ?>
                <a href="<?php echo esc_url($contact['url']); ?>" class="inline-flex items-center px-3 py-1 font-sans text-sm font-medium text-blue-700 border border-blue-500 hover:bg-blue-50 transition-colors no-underline">
                    <?php echo esc_html($contact['label']); ?>
                </a>
            <?php endif; ?>
            <a
                href="<?php echo esc_url(\Standard\Url\with_query('/', ['s' => ''])); ?>"
                class="search-trigger search-trigger--desktop flex items-center justify-center w-8 h-16 text-blue-600 hover:text-blue-800 transition-colors"
                aria-label="<?php esc_attr_e('Search', 'standard'); ?>"
                aria-haspopup="dialog"
                aria-controls="site-search-modal"
                data-search-modal-open
            >
                <?php icon('search', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>

    </div>
</header>

<!-- Breadcrumb trail — renders only on supported deep single templates.
     Sits OUTSIDE <header> so the mega-menu panel (fixed, top: 4rem) cleanly
     overlaps it when open. The <header>'s own border-bottom acts as the
     trail's top edge; the trail keeps its own border-bottom as the seam
     into the page content. Hides with the header on scroll via CSS. -->
<?php get_template_part('templates/parts/breadcrumbs'); ?>

<!-- Mega menu panels (desktop) -->
<?php get_template_part('templates/parts/mega-menu'); ?>

<!-- Mobile menu (full width, below header) -->
<?php get_template_part('templates/parts/mobile-menu'); ?>

<?php get_template_part('templates/parts/search-modal'); ?>
