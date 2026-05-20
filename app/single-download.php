<?php
/**
 * The template for displaying single download posts.
 *
 * Thin wrapper around templates/parts/single-sidebar-layout.php, which
 * also powers single-resource.php. The only thing that differs between
 * the two is the current post type, which the shared part reads via
 * get_post_type() to drive the same-type sidebar.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="pb-6 lg:pb-12">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/parts/single-sidebar-layout'); ?>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
