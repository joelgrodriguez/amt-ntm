<?php
/**
 * Template Name: MACH II Family Landing
 *
 * Dedicated marketing landing page for the MACH II family of seamless
 * gutter machines. Lives at /machines/machii/ as a deep-dive sibling
 * to the broader /seamless-gutter-machines/ category page. The family
 * page leads with heritage (1994, polyurethane drive rollers, 30 years
 * as the industry benchmark), then walks the buyer through the four
 * variants (5", 6", 5"/6" Combo, BG7 box gutter) before closing on
 * Abel's customer story, the comparison table, FAQ, and a final
 * configurator CTA.
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

    <?php get_template_part('templates/pages/machii/hero'); ?>

    <?php get_template_part('templates/pages/machii/heritage'); ?>

    <?php get_template_part('templates/pages/machii/family-portrait'); ?>

    <?php get_template_part('templates/pages/machii/variant-matrix'); ?>

    <?php get_template_part('templates/pages/machii/workflow'); ?>

    <?php get_template_part('templates/pages/machii/customer-story'); ?>

    <?php get_template_part('templates/pages/machii/comparison-table'); ?>

    <?php get_template_part('templates/pages/machii/faq'); ?>

    <?php get_template_part('templates/pages/machii/final-cta'); ?>

</main>

<?php
get_footer();
