<?php
/**
 * Template part for displaying related posts.
 *
 * Shows up to 4 related posts based on shared categories.
 * Displays posts from any post type.
 *
 * @package Standard
 */

$related = \Standard\get_related_posts(4);

if (!$related->have_posts()) {
    return;
}
?>

<section class="related-posts pt-6 lg:pt-12 border-t border-slate-200">
    <h2 class="text-2xl font-bold font-mono mb-8"><?php esc_html_e('Related', 'standard'); ?></h2>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <?php while ($related->have_posts()) : $related->the_post(); ?>
            <?php get_template_part('templates/parts/card', 'post'); ?>
        <?php endwhile; ?>
    </div>
</section>

<?php
wp_reset_postdata();
