<?php
/**
 * Template Name: Seamless Gutter Machines
 *
 * Category landing page for seamless gutter machines.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
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

    <?php get_template_part('templates/pages/gutter/hero'); ?>

    <?php get_template_part('templates/pages/gutter/brand-statement'); ?>

    <?php get_template_part('templates/pages/gutter/featured'); ?>

    <?php get_template_part('templates/pages/gutter/value-prop'); ?>

    <?php get_template_part('templates/pages/gutter/product-grid'); ?>

    <?php get_template_part('templates/pages/gutter/comparison-table'); ?>

    <?php get_template_part('templates/pages/gutter/customer-story'); ?>

    <?php get_template_part('templates/pages/gutter/machii-callout'); ?>

    <?php get_template_part('templates/parts/ironclad-support', null, [
        'section_id' => 'gutter-ironclad-support',
        'background' => 'bg-white',
    ]); ?>

    <?php get_template_part('templates/pages/gutter/faq'); ?>

    <?php get_template_part('templates/pages/gutter/learning-center'); ?>

    <?php get_template_part('templates/pages/gutter/final-cta'); ?>

</main>

<?php
get_footer();
