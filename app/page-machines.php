<?php
/**
 * Template Name: Machines
 *
 * Landing page for all machine categories and sections.
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

    <?php get_template_part('templates/pages/machines/hero'); ?>

    <?php get_template_part('templates/pages/machines/brand-statement'); ?>

    <?php get_template_part('templates/pages/machines/lineup-grid'); ?>

    <?php get_template_part('templates/pages/machines/machines-flagship'); ?>

    <?php get_template_part('templates/pages/machines/comparison-table'); ?>

    <?php get_template_part('templates/pages/machines/customer-story'); ?>

    <?php get_template_part('templates/pages/machines/which-machine'); ?>

    <?php get_template_part('templates/parts/ironclad-support', null, [
        'section_id' => 'machines-ironclad-support',
        'background' => 'bg-white',
    ]); ?>

    <?php get_template_part('templates/pages/machines/learning-center'); ?>

    <?php get_template_part('templates/pages/machines/faq-accordion'); ?>

    <?php get_template_part('templates/pages/machines/final-cta'); ?>

</main>

<?php
get_footer();
