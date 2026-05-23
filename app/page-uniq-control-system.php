<?php
/**
 * Page: UNIQ® Automatic Control System
 *
 * Custom landing page for /machines/uniq-control-system/ — picked up
 * automatically by WordPress because the slug matches the page name.
 * Replaces the legacy classic-editor content with the engineered-showroom
 * section grammar shared by the rest of /machines/*.
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

    <?php get_template_part('templates/pages/uniq/hero'); ?>

    <?php get_template_part('templates/pages/uniq/features'); ?>

    <?php get_template_part('templates/pages/uniq/how-it-works'); ?>

    <?php get_template_part('templates/pages/uniq/resources'); ?>

    <?php get_template_part('templates/pages/uniq/final-cta'); ?>

</main>

<?php
get_footer();
