<?php
/**
 * The template for displaying archive pages.
 *
 * Displays posts for categories, tags, authors, dates, and custom taxonomies.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="container py-8">
    <header class="mb-8">
        <?php
        the_archive_title('<h1 class="text-3xl font-bold mb-2">', '</h1>');
        the_archive_description('<div class="text-slate-600">', '</div>');
        ?>
    </header>

    <?php if (have_posts()) : ?>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('templates/parts/content', get_post_type()); ?>
            <?php endwhile; ?>
        </div>

        <?php \Standard\Walkers\Pagination::render(); ?>
    <?php else : ?>
        <?php get_template_part('templates/parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
