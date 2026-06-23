<?php
/**
 * Template Name: Safety
 *
 * Standalone safety landing page (stakeholder review 2026-06-17, Adam:
 * "safety, safety, safety"). Collects the factual operator-protection story
 * NTM already ships — interlocks, guard sensors, the safety circuit — into one
 * surface, plus links to the already-published safe-operation resources.
 *
 * LEGAL GATE: facts only, no claims. Everything safety-worded is reviewed by
 * counsel before publishing (see docs/legal/safety-copy-review.md). The WP page
 * pointed at this template ships in Draft (scripts/db/029-safety-landing-page-
 * draft.sh); it is not public until counsel signs off.
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

    <?php get_template_part('templates/pages/safety/hero'); ?>

    <?php get_template_part('templates/pages/safety/systems'); ?>

    <?php get_template_part('templates/pages/safety/safe-operation'); ?>

    <?php get_template_part('templates/pages/safety/final-cta'); ?>

</main>

<?php
get_footer();
