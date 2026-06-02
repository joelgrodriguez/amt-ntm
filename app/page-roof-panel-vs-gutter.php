<?php
/**
 * Template Name: NTM — Roof Panel vs Gutter
 *
 * Top-of-funnel decision page. Helps a first-time portable-rollforming
 * buyer figure out whether they need a roof & wall panel machine or a
 * seamless gutter machine, framed by what each machine makes, and routes
 * them to the right category page. Reached from the mega menu's
 * "Get started → Start here → Roof panel vs gutter machines."
 *
 * Built from shared category-page grammar so it reads as the same
 * company that built the two pages it links to. Primarily educational;
 * heavily weighted toward SEO + AEO (FAQPage schema, internal links).
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

    <?php get_template_part('templates/pages/vs/hero'); ?>

    <?php get_template_part('templates/pages/vs/the-fork'); ?>

    <?php get_template_part('templates/pages/vs/comparison'); ?>

    <?php get_template_part('templates/pages/vs/run-both'); ?>

    <?php get_template_part('templates/pages/vs/faq'); ?>

    <?php get_template_part('templates/pages/vs/keep-reading'); ?>

    <?php get_template_part('templates/pages/vs/final-cta'); ?>

</main>

<?php
get_footer();
