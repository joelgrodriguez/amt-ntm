<?php
/**
 * Template part for displaying a message when no posts are found.
 *
 * Displays a "Nothing Found" message with contextual help text
 * and search form when appropriate.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

?>

<section class="no-results text-center py-16">
    <h1 class="text-2xl font-semibold mb-4"><?php esc_html_e('Nothing Found', 'standard'); ?></h1>

    <?php if (is_search()) : ?>
        <p class="text-slate-600 mb-6"><?php esc_html_e('Sorry, but nothing matched your search terms. Please try again with different keywords.', 'standard'); ?></p>
        <?php get_search_form(); ?>
    <?php else : ?>
        <p class="text-slate-600"><?php esc_html_e('It seems we can\'t find what you\'re looking for.', 'standard'); ?></p>
    <?php endif; ?>
</section>
