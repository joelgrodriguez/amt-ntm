<?php
/**
 * Template Name: NTM Trailer
 *
 * Dedicated landing page for the NTM trailer — "NTM trailer vs. traditional."
 * Tells the engineered-as-one-system story (bolt-down balance, scrap trays,
 * crane lifting eyes, NATM-compliant brakes) that explains why the trailer
 * costs what it does, pairing with the machine-page "trailer sold separately"
 * pricing note.
 *
 * Lives at /machines/upgrades/trailer/ (page assigned this template via the
 * companion db script scripts/db/028-trailer-landing-page.sh).
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

    <?php get_template_part('templates/pages/trailer/hero'); ?>

    <?php get_template_part('templates/pages/trailer/vs-traditional'); ?>

    <?php get_template_part('templates/pages/trailer/engineering'); ?>

    <?php get_template_part('templates/pages/trailer/models'); ?>

    <?php get_template_part('templates/pages/trailer/final-cta'); ?>

</main>

<?php
get_footer();
