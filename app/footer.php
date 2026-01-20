<?php
/**
 * The footer template.
 *
 * Displays the site footer with widgets, navigation, and copyright.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Standard
 */

?>

<footer class="mt-auto bg-slate-900 text-slate-300">
    <div class="container py-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <h3 class="text-white font-semibold mb-4"><?php echo esc_html(get_bloginfo('name')); ?></h3>
                <p class="text-sm"><?php echo esc_html(get_bloginfo('description')); ?></p>
            </div>

            <?php if (is_active_sidebar('footer-1')) : ?>
                <div>
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
            <?php endif; ?>

            <div>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'menu_id'        => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'flex flex-col gap-2 text-sm',
                    'fallback_cb'    => false,
                ]);
                ?>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-slate-700 text-sm text-center">
            <p>&copy; <?php echo esc_html(current_time('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>. <?php esc_html_e('All rights reserved.', 'standard-press'); ?></p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
