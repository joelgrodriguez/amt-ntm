<?php
/**
 * The front page template.
 *
 * Composition order: hero (machine) > router (the unsure) > explore
 * (browse machines) > three-step (how it works) > why-own (the case) >
 * social-proof (trust) > flagships (SSQ3 + MACH II Combo) >
 * tools (decision support) > learning-center (educate) > final-cta (close).
 *
 * Contact lives at /contact/. Pain-points + value-prop are merged
 * into why-own. The flagships section replaces the configurator promo;
 * the configurator job is served by hero-router, why-own's two-door,
 * and final-cta.
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

    <?php get_template_part('templates/parts/front-page/hero-router'); ?>

    <?php get_template_part('templates/parts/front-page/explore-machines'); ?>

    <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>

    <?php get_template_part('templates/parts/front-page/why-own'); ?>

    <?php get_template_part('templates/parts/front-page/social-proof'); ?>

    <?php get_template_part('templates/parts/front-page/flagships'); ?>

    <?php get_template_part('templates/parts/front-page/tools'); ?>

    <?php get_template_part('templates/parts/learning-center'); ?>

    <?php get_template_part('templates/parts/front-page/final-cta'); ?>

</main>

<?php
get_footer();
