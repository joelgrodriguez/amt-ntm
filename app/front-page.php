<?php
/**
 * The front page template.
 *
 * Composition follows the buyer's journey:
 *
 *   hero          (the machine, cinematic intro leading with flagships)
 *   doors         (two-cell category chooser: roof or gutter)
 *   flagships     (curated callouts: SSQ3 + MACH II Combo)
 *   router        (for the unsure: dial in 3 answers)
 *   learning      (educate: recent posts, videos, downloads)
 *   tools         (research aids: quiz, calc, manuals, compare)
 *   why-own       (the case)
 *   social-proof  (trust)
 *   three-step    (how buying works)
 *   final-cta     (close)
 *
 * Research cluster (learning + tools) sits BEFORE the case-and-trust
 * surfaces (why-own, social-proof) so the page commits forward in the
 * back half instead of zigzagging buyer → researcher → buyer. Tools
 * sits after learning (not before router) so it doesn't read as a
 * second 'find your machine' surface right after the router itself.
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

    <?php get_template_part('templates/parts/front-page/category-doors'); ?>

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
