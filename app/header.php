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
    <?php esc_html_e('Skip to main content', 'standard-press'); ?>
</a>

<header id="site-header" class="bg-white border-b border-slate-200 sticky top-0 z-50 transition-colors duration-200">
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
                <span id="menu-icon-close" class="hidden"><?php icon('close', ['class' => 'w-4 h-4']); ?></span>
            </button>

            <!-- Logo -->
            <div class="flex items-center h-12 px-4 lg:px-6 lg:border-r border-slate-200">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="font-mono font-bold text-primary no-underline hover:text-blue-700">
                        <?php echo esc_html(get_bloginfo('name')); ?>
                    </a>
                <?php endif; ?>
            </div>

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
                ]);
                ?>
            </nav>

            <!-- Right side icons -->
            <div id="header-actions" class="flex items-center h-12 ml-auto px-2 lg:px-6 transition-opacity duration-200">
                <a href="<?php echo esc_url(home_url('/?s=')); ?>" class="flex items-center justify-center w-12 h-12 text-slate-600 hover:bg-slate-100 transition-colors" aria-label="Search">
                    <?php icon('search', ['class' => 'w-5 h-5']); ?>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Mobile menu (full width, below header) -->
<nav id="mobile-menu" class="mobile-menu fixed top-12 left-0 right-0 bottom-0 bg-white z-40 lg:hidden overflow-y-auto">
    <?php
    wp_nav_menu([
        'theme_location' => 'mobile',
        'menu_id'        => 'mobile-menu-list',
        'container'      => false,
        'menu_class'     => 'divide-y divide-slate-200',
        'fallback_cb'    => '__return_false',
        'walker'         => new \Standard\Walkers\Mobile_Nav_Walker(),
    ]);
    ?>
</nav>