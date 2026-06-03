<?php
/**
 * Template Name: NTM — Choose Your Machine
 *
 * The machine-chooser landing. Anchor #2 of the four-action IA rebuild,
 * reached from Start Here's "Choose a machine" lane and the "Choose your
 * machine" mega-menu flyout. The reader knows the trade and wants to find
 * the model that matches their work without taking a quiz.
 *
 * Flow: a compact hero, a two-lane fork (roof & wall vs seamless gutter)
 * that jumps to the matching ledger on the same page, then a "fit ledger"
 * per family ranking every machine by the work it suits. It deliberately
 * complements the guided quizzes and the spec comparison (linked under each
 * ledger) rather than duplicating them, and routes the decided buyer to a
 * specialist conversation.
 *
 * Built from the vs/the-fork and start-here/which-path cell grammar and
 * driven by app/data/machines/*.php (see templates/pages/choose/data.php),
 * so prices, specs, and links stay in lockstep with the product pages.
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

    <?php get_template_part('templates/pages/choose/hero'); ?>

    <?php get_template_part('templates/pages/choose/the-fork'); ?>

    <?php get_template_part('templates/pages/choose/roof-ledger'); ?>

    <?php get_template_part('templates/pages/choose/gutter-ledger'); ?>

    <?php get_template_part('templates/pages/choose/final-cta'); ?>

</main>

<?php
get_footer();
