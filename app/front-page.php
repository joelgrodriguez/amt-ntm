<?php
/**
 * The front page template.
 *
 * Composition follows the buyer's job-state: capture → route → sell
 * → educate → close. Every section belongs to one job; same-job
 * sections sit together. No bouncing between buyer / researcher
 * states — the page commits forward.
 *
 *   CAPTURE
 *   hero          (slider; CTAs route to category landings — hero
 *                  is the "doors" surface as well as the anchor)
 *
 *   ROUTE
 *   explore       (browse the catalog by category, with per-tab
 *                  jumps to the category landing pages)
 *   quiz          (10-question assessment for the unsure)
 *
 *   SELL
 *   why-own       (the case for owning a machine vs. subbing)
 *   flagships     (curated callout: SSQ3, the specific answer)
 *
 *   EDUCATE
 *   social-proof  (trust, most anxiety-relieving first)
 *   learning      (recent posts, videos, downloads)
 *   tools         (research aids: calc, manuals, compare)
 *
 *   CLOSE
 *   three-step    (process: lead time + on-site training)
 *   final-cta     (talk to a specialist)
 *
 * Contact lives at /contact/. Pain-points + value-prop are merged
 * into why-own. The category-doors section was removed when the
 * hero took over the doors role.
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

    <?php // CAPTURE ?>
    <?php get_template_part('templates/parts/front-page/hero-slider'); ?>

    <?php // ROUTE ?>
    <?php get_template_part('templates/parts/front-page/explore-machines'); ?>
    <?php get_template_part('templates/parts/front-page/quiz'); ?>

    <?php // SELL ?>
    <?php get_template_part('templates/parts/front-page/why-own'); ?>
    <?php get_template_part('templates/parts/front-page/flagships'); ?>

    <?php // EDUCATE ?>
    <?php get_template_part('templates/parts/front-page/social-proof'); ?>
    <?php get_template_part('templates/parts/learning-center'); ?>
    <?php get_template_part('templates/parts/front-page/tools'); ?>

    <?php // CLOSE ?>
    <?php get_template_part('templates/parts/front-page/three-step-plan'); ?>
    <?php get_template_part('templates/parts/front-page/final-cta'); ?>

</main>

<?php
get_footer();
