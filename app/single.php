<?php
/**
 * The template for displaying single posts.
 *
 * Displays a single blog post with full content, meta, TOC, and post navigation.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="pattern-dot-grid pb-6 lg:pb-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>

            <div class="container">
                <?php get_template_part('templates/parts/single/article-hero'); ?>

                <div class="article-layout">
                    <aside id="table-of-contents" class="hidden lg:block" aria-label="<?php esc_attr_e('Table of Contents', 'standard'); ?>">
                        <nav class="toc sticky top-24">
                            <p class="toc__title"><?php esc_html_e('On this page', 'standard'); ?></p>
                            <ol id="toc-list" class="toc__list"></ol>
                        </nav>
                    </aside>

                    <div class="min-w-0">
                        <div class="prose prose-lg max-w-full" data-toc-content>
                            <?php the_content(); ?>
                        </div>

                        <?php get_template_part('templates/parts/disclaimer'); ?>
                    </div>
                </div>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/post-navigation'); ?>
            </div>
            <div class="container">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php
get_footer();
