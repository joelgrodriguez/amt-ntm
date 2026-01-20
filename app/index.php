<?php
/**
 * The main template file.
 *
 * The default template used when no more specific template is available.
 * Displays a grid of posts with pagination.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="container py-8">
    <?php if (have_posts()) : ?>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('templates/parts/content', get_post_type()); ?>
            <?php endwhile; ?>
        </div>

        <nav class="mt-8">
            <?php the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => '&larr; ' . esc_html__('Previous', 'standard-press'),
                'next_text' => esc_html__('Next', 'standard-press') . ' &rarr;',
                'class'     => 'flex items-center justify-center gap-2',
            ]); ?>
        </nav>
    <?php else : ?>
        <?php get_template_part('templates/parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
