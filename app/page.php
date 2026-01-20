<?php
/**
 * The template for displaying pages.
 *
 * Displays static pages with optional featured image.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-page
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="container py-8">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('max-w-3xl mx-auto'); ?>>
            <header class="mb-8">
                <?php the_title('<h1 class="text-3xl md:text-4xl font-bold">', '</h1>'); ?>
            </header>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="mb-8">
                    <?php the_post_thumbnail('large', [
                        'class' => 'w-full',
                        'loading' => 'lazy',
                    ]); ?>
                </figure>
            <?php endif; ?>

            <div class="prose prose-lg max-w-none">
                <?php the_content(); ?>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php
get_footer();
