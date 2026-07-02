<?php
/**
 * Template Name: Footprints
 *
 * Catalog landing for the machine-footprint library. Hero, the flat
 * drawing grid (footprints aren't split by category, so no filter
 * sidebar), a per-machine dimensions quick reference sourced from the
 * spec data files, and the shared closer CTA.
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

    <?php get_template_part('templates/pages/footprints/hero'); ?>

    <?php get_template_part('templates/pages/footprints/library'); ?>

    <?php get_template_part('templates/pages/footprints/dimensions'); ?>

    <?php get_template_part('templates/pages/footprints/final-cta'); ?>

</main>

<?php
get_footer();
