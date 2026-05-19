<?php
/**
 * The front page template.
 *
 * Composition follows the buyer's journey:
 *
 *   hero          (the machine, cinematic intro leading with flagships)
 *   explore       (browse the catalog)
 *   flagships     (curated callouts: SSQ3 + MACH II Combo)
 *   router        (for the unsure: dial in 3 answers)
 *   tools         (research aids)
 *   learning      (educate)
 *   why-own       (the case)
 *   social-proof  (trust)
 *   three-step    (how buying works)
 *   final-cta     (close)
 *
 * Research surfaces (tools, learning) sit BEFORE the case-and-trust
 * surfaces (why-own, social-proof) so the page commits forward in the
 * back half instead of zigzagging buyer → researcher → buyer.
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

    <?php get_template_part('templates/parts/front-page/explore-machines'); ?>

    <?php get_template_part('templates/parts/front-page/flagships'); ?>

    <?php get_template_part('templates/parts/front-page/router'); ?>

    <?php get_template_part('templates/parts/front-page/tools'); ?>

    <?php get_template_part('templates/parts/learning-center'); ?>

    <?php get_template_part('templates/parts/front-page/why-own'); ?>

    <?php get_template_part('templates/parts/front-page/social-proof'); ?>

    <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>

    <?php get_template_part('templates/parts/front-page/final-cta'); ?>

</main>

<?php
get_footer();
