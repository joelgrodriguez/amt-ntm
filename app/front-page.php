<?php
/**
 * The front page template.
 *
 * Composition order: hero (machine) > router (the unsure) > explore
 * (browse machines) > tools (decision support) > who (the company) >
 * three-step (how it works) > why-own (the case) > social-proof (trust) >
 * configurator (act) > learning-center (educate) > final-cta (close).
 *
 * Contact lives at /contact/. Pain-points + value-prop are merged
 * into why-own.
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

    <?php get_template_part('templates/parts/front-page/tools'); ?>

    <?php get_template_part('templates/parts/front-page/who-is-ntm'); ?>

    <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>

    <?php get_template_part('templates/parts/front-page/why-own'); ?>

    <?php get_template_part('templates/parts/front-page/social-proof'); ?>

    <?php get_template_part('templates/parts/configurator'); ?>

    <?php get_template_part('templates/parts/learning-center'); ?>

    <?php get_template_part('templates/parts/front-page/final-cta'); ?>

</main>

<?php
get_footer();
