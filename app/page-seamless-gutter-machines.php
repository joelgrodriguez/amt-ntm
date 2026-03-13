<?php
/**
 * Template Name: Seamless Gutter Machines
 *
 * Category landing page for seamless gutter machines.
 * Combines category-specific sections with reused machines page sections.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/gutter/hero'); ?>

    <?php get_template_part('templates/pages/gutter/value-prop'); ?>

    <?php get_template_part('templates/pages/gutter/product-grid'); ?>

    <?php get_template_part('templates/pages/machines/roi-snapshot'); ?>

    <?php get_template_part('templates/pages/machines/which-machine'); ?>

    <?php get_template_part('templates/pages/gutter/faq'); ?>

    <?php get_template_part('templates/pages/gutter/customer-story'); ?>

    <?php get_template_part('templates/pages/gutter/final-cta'); ?>

</main>

<?php
get_footer();
