<?php
/**
 * Template Name: Accessories
 *
 * Landing page for the Accessories & Upgrades catalog.
 *
 * Wraps the 66-product Woo "Accessories & Add-On Equipment" category in an
 * editorial structure: a "no machine ships finished" thesis, six bucketed
 * grids, a UNIQ spotlight, a fits-which-machine matrix, owner resources,
 * and the shared final-CTA block.
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

    <?php get_template_part('templates/pages/accessories/why-upgrade'); ?>

    <?php get_template_part('templates/pages/accessories/category-map'); ?>

    <?php get_template_part('templates/pages/accessories/spotlight-uniq'); ?>

    <?php get_template_part('templates/pages/accessories/catalog-grid'); ?>

    <?php get_template_part('templates/pages/accessories/fit-by-machine'); ?>

    <?php get_template_part('templates/pages/accessories/owner-resources'); ?>

    <?php get_template_part('templates/pages/accessories/final-cta'); ?>

</main>

<?php
get_footer();
