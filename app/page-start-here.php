<?php
/**
 * Template Name: NTM — Start Here
 *
 * The business-starting front door. Anchor page #1 of the four-action IA
 * rebuild, reached from the mega menu's "Get started" flyout. The reader
 * is new to the industry and weighing whether to start a business making
 * metal roofing panels or seamless gutters with a portable rollformer.
 *
 * The page sells the opportunity (hero, the business case, what the work
 * looks like), then routes the reader into the right lane (learn the
 * trade, see if it fits, choose a machine, or handle the money). It
 * deliberately sells the opportunity and hands the money mechanics off to
 * How Buying Works and Financing, rather than restating prices here.
 *
 * Built from shared category-page grammar so it reads as the same company
 * that built the pages it routes to. Educational, weighted toward SEO +
 * AEO (one visible <h1>, FAQPage schema, curated internal links).
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

    <?php get_template_part('templates/pages/start-here/hero'); ?>

    <?php get_template_part('templates/pages/start-here/the-case'); ?>

    <?php get_template_part('templates/pages/start-here/the-day'); ?>

    <?php get_template_part('templates/pages/start-here/which-path'); ?>

    <?php get_template_part('templates/pages/start-here/proof'); ?>

    <?php get_template_part('templates/pages/start-here/faq'); ?>

    <?php get_template_part('templates/pages/start-here/final-cta'); ?>

</main>

<?php
get_footer();
