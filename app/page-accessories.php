<?php
/**
 * Template Name: Accessories
 *
 * Landing page for the Accessories & Upgrades catalog.
 *
 * Wraps the 60+ product Woo "Accessories & Add-On Equipment" category in
 * a hero, a horizontal jump-nav strip, the bucketed catalog grid, a
 * fits-which-machine matrix, owner resources, and a final CTA.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/accessories/hero'); ?>

    <?php get_template_part('templates/pages/accessories/catalog-nav'); ?>

    <?php get_template_part('templates/pages/accessories/catalog-grid'); ?>

    <?php get_template_part('templates/pages/accessories/fit-by-machine'); ?>

    <?php get_template_part('templates/pages/accessories/owner-resources'); ?>

    <?php get_template_part('templates/pages/accessories/final-cta'); ?>

</main>

<?php
get_footer();
