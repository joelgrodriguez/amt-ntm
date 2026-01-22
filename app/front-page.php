<?php
/**
 * The front page template.
 *
 * Displays the site's front page with hero slider showcasing featured machines.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/parts/front-page/hero-slider'); ?>

    <?php get_template_part('templates/parts/front-page/explore-machines'); ?>

    <?php get_template_part('templates/parts/front-page/tools'); ?>

    <?php get_template_part('templates/parts/front-page/who-is-ntm'); ?>

    <?php get_template_part('templates/parts/front-page/pain-points'); ?>

    <?php get_template_part('templates/parts/front-page/value-prop'); ?>

    <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>

    <?php get_template_part('templates/parts/front-page/social-proof'); ?>

    <?php get_template_part('templates/parts/front-page/configurator'); ?>

    <?php get_template_part('templates/parts/front-page/contact'); ?>

    <?php get_template_part('templates/parts/front-page/learning-center'); ?>

    <?php get_template_part('templates/parts/front-page/final-cta'); ?>

</main>

<?php
get_footer();
