<?php
/**
 * Template Name: Roof & Wall Panel Machines
 *
 * Category landing page for roof and wall panel machines.
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

    <?php get_template_part('templates/pages/roof-wall/hero'); ?>

    <?php get_template_part('templates/pages/roof-wall/brand-statement'); ?>

    <?php get_template_part('templates/pages/roof-wall/featured'); ?>

    <?php get_template_part('templates/pages/roof-wall/value-prop'); ?>

    <?php get_template_part('templates/pages/roof-wall/product-grid'); ?>

    <?php get_template_part('templates/pages/roof-wall/comparison-table'); ?>

    <?php get_template_part('templates/pages/roof-wall/customer-story'); ?>

    <?php get_template_part('templates/pages/machines/roi-snapshot'); ?>

    <?php get_template_part('templates/pages/machines/uniq-spotlight'); ?>

    <?php get_template_part('templates/pages/machines/which-machine'); ?>

    <?php get_template_part('templates/parts/ironclad-support', null, [
        'section_id' => 'roof-wall-ironclad-support',
        'background' => 'bg-white',
    ]); ?>

    <?php get_template_part('templates/pages/roof-wall/faq'); ?>

    <?php get_template_part('templates/pages/roof-wall/learning-center'); ?>

    <?php get_template_part('templates/pages/roof-wall/final-cta'); ?>

</main>

<?php
get_footer();
