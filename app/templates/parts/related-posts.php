<?php
/**
 * Template part for displaying related posts.
 *
 * Shows up to 4 related posts based on shared categories, rendered as
 * the standard post-card grid.
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

$related = \Standard\get_related_posts(4);

if (!$related->have_posts()) {
    return;
}
?>

<section class="related-posts pt-6 lg:pt-12 border-t border-blue-200">
    <div class="flex items-baseline justify-between gap-4 mb-6 lg:mb-8">
        <h2 class="font-mono font-medium text-heading-sm lg:text-heading text-blue-900 leading-tight tracking-tight m-0">
            <?php esc_html_e('Read next', 'standard'); ?>
        </h2>
        <span class="font-mono uppercase tracking-widest text-caption text-blue-500">
            <?php esc_html_e('Related', 'standard'); ?>
        </span>
    </div>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <?php while ($related->have_posts()) : $related->the_post(); ?>
            <?php get_template_part('templates/parts/card', 'post'); ?>
        <?php endwhile; ?>
    </div>
</section>

<?php
wp_reset_postdata();
