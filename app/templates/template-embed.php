<?php
/**
 * Template Name: Full Height Embed
 *
 * Header + footer with a main region that fills the viewport between them.
 * For Outgrow quizzes, Abacus iframes, and other third-party embeds that
 * need the full content area — not Corbel/Empty Shell (those drop site chrome).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="embed-canvas">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</main>

<?php
get_footer();