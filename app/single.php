<?php
/**
 * The template for displaying single posts.
 *
 * Displays a single blog post with full content, meta, tags, and post navigation.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="container py-8">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('max-w-3xl mx-auto'); ?>>
            <header class="mb-8">
                <?php the_title('<h1 class="text-3xl md:text-4xl font-bold mb-4">', '</h1>'); ?>

                <div class="flex items-center gap-4 text-slate-500">
                    <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                        <?php echo esc_html(get_the_date()); ?>
                    </time>
                    <span>&middot;</span>
                    <span><?php the_author(); ?></span>
                    <?php if (has_category()) : ?>
                        <span>&middot;</span>
                        <span><?php the_category(', '); ?></span>
                    <?php endif; ?>
                </div>
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

            <?php if (has_tag()) : ?>
                <footer class="mt-8 pt-8 border-t border-slate-200">
                    <div class="flex flex-wrap gap-2">
                        <?php the_tags('<span class="text-slate-600">Tags:</span> ', ', '); ?>
                    </div>
                </footer>
            <?php endif; ?>

            <nav class="mt-8 pt-8 border-t border-slate-200 flex justify-between">
                <div>
                    <?php previous_post_link('%link', '&larr; %title'); ?>
                </div>
                <div>
                    <?php next_post_link('%link', '%title &rarr;'); ?>
                </div>
            </nav>
        </article>

        <?php if (comments_open() || get_comments_number()) : ?>
            <div class="max-w-3xl mx-auto mt-12">
                <?php comments_template(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; ?>
</main>

<?php
get_footer();
