<?php
/**
 * Template Name: Roof & Wall Panel Machines
 *
 * Category landing page for roof and wall panel machines.
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

    <?php get_template_part('templates/pages/roof-wall/hero'); ?>

    <?php get_template_part('templates/pages/roof-wall/value-prop'); ?>

    <?php get_template_part('templates/pages/roof-wall/product-grid'); ?>

    <?php get_template_part('templates/pages/machines/roi-snapshot'); ?>

    <?php get_template_part('templates/pages/machines/which-machine'); ?>

    <?php get_template_part('templates/pages/roof-wall/faq'); ?>

    <?php get_template_part('templates/pages/roof-wall/customer-story'); ?>

    <?php get_template_part('templates/pages/roof-wall/final-cta'); ?>

</main>

<?php
get_footer();
