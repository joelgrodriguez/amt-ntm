<?php
/**
 * Template Name: NTM — First-Time Buyer Playlist
 *
 * A curated, ordered onboarding sequence for someone brand new to
 * portable rollforming. Reached from the mega menu's "Get started"
 * flyout. The video archive already lets anyone browse all 200+ videos
 * by category; this page does the thing an archive structurally cannot:
 * it hand-picks a small set and puts them in the order a nervous first
 * buyer should actually watch them, from "what even is this" through
 * "which machine" and "what happens after I buy."
 *
 * Every card links to the existing single-video page rather than
 * embedding a player inline, because the library mixes YouTube and
 * Wistia sources and single-video.php already renders both correctly.
 *
 * Built from the same dressed-page grammar as /start-here/ and
 * /roof-panel-vs-gutter/ so it reads as the same company.
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

    <?php get_template_part('templates/pages/playlist/hero'); ?>

    <?php get_template_part('templates/pages/playlist/chapters'); ?>

    <?php get_template_part('templates/pages/playlist/after'); ?>

    <?php get_template_part('templates/pages/playlist/final-cta'); ?>

</main>

<?php
get_footer();
