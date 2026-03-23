<?php
/**
 * Template Name: Roof & Wall Panel Machines
 *
 * Category landing page for roof and wall panel machines.
 * Combines category-specific sections with reused machines page sections.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$video_url = function_exists('get_field') ? get_field('video', false, false) : null;

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/roof-wall/hero'); ?>

    <?php get_template_part('templates/pages/roof-wall/value-prop'); ?>

    <?php get_template_part('templates/pages/roof-wall/product-grid'); ?>

    <?php
    get_template_part('templates/parts/video-section', null, [
        'title'      => __('Roof & Wall Panel Machines', 'standard'),
        'video_url'  => is_string($video_url) ? $video_url : null,
        'video_type' => __('Category Overview', 'standard'),
        'section_id' => 'roof-wall-video',
    ]);
    ?>

    <?php get_template_part('templates/pages/roof-wall/configurator'); ?>

    <?php get_template_part('templates/pages/machines/roi-snapshot'); ?>

    <?php get_template_part('templates/pages/machines/which-machine'); ?>

    <?php get_template_part('templates/pages/roof-wall/faq'); ?>

    <?php get_template_part('templates/pages/roof-wall/customer-story'); ?>

    <?php get_template_part('templates/pages/roof-wall/learning-center'); ?>

    <?php get_template_part('templates/pages/roof-wall/final-cta'); ?>

</main>

<?php
get_footer();
