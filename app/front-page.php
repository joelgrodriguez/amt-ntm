<?php
/**
 * The front page template.
 *
 * Builds the home page by composing hero, machine showcase, tools,
 * funnel, and resource/CTA sections.
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

    <?php get_template_part('templates/parts/front-page/hero-slider'); ?>

    <?php get_template_part('templates/parts/front-page/explore-machines'); ?>

    <?php get_template_part('templates/parts/front-page/flagships'); ?>

    <?php get_template_part('templates/parts/front-page/router'); ?>

    <?php get_template_part('templates/parts/learning-center'); ?>

    <?php get_template_part('templates/parts/front-page/tools'); ?>

    <?php get_template_part('templates/parts/front-page/why-own'); ?>

    <?php get_template_part('templates/parts/front-page/social-proof'); ?>

    <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>

    <?php get_template_part('templates/parts/front-page/final-cta'); ?>

</main>

<?php
get_footer();
