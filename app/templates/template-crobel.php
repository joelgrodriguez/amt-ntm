<?php
/**
 * Template Name: Crobel
 *
 * Minimal template with no header and simplified footer (copyright only).
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

<main id="primary" class="flex-1">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<footer class="py-6 border-t border-slate-200">
    <div class="container text-center">
        <p class="text-xs text-slate-500">
            &copy; <?php echo esc_html(current_time('Y')); ?> <?php echo esc_html(get_bloginfo('name')); ?>.
            <?php esc_html_e('All rights reserved.', 'standard'); ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
