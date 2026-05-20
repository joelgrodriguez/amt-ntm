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

<main id="primary" class="pb-6 lg:pb-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
            <header class="pattern-dot-grid gradient-fade-bottom-sm">
                <div class="container grid gap-6 pt-6 lg:pt-10 pb-6 lg:pb-10">
                    <?php if (has_category()) : ?>
                        <div class="flex flex-wrap items-center gap-x-3 gap-y-2 font-mono uppercase tracking-widest text-caption">
                            <?php
                            $categories = array_slice(get_the_category(), 0, 3);
                            foreach ($categories as $i => $category) :
                                if ($i > 0) : ?>
                                    <span class="text-blue-300" aria-hidden="true">/</span>
                                <?php endif; ?>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>"
                                   class="text-blue-700 no-underline hover:text-blue-500 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php the_title('<h1 class="font-mono font-medium text-heading lg:text-display text-blue-900 leading-tight tracking-tight m-0">', '</h1>'); ?>

                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-blue-600 font-mono text-sm">
                        <span class="flex items-center gap-2">
                            <?php icon('calendar', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date('j F Y')); ?>
                            </time>
                        </span>
                        <span class="flex items-center gap-2">
                            <?php icon('user', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                            <span><?php echo esc_html(get_the_author_meta('display_name')); ?></span>
                        </span>
                        <?php
                        $word_count    = str_word_count(wp_strip_all_tags(get_the_content()));
                        $reading_time  = max(1, (int) ceil($word_count / 220));
                        ?>
                        <span class="text-blue-400" aria-hidden="true">/</span>
                        <span><?php
                            /* translators: %d minutes of reading time. */
                            printf(esc_html(_n('%d min read', '%d min read', $reading_time, 'standard')), $reading_time);
                        ?></span>
                    </div>

                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="featured-image m-0">
                            <?php the_post_thumbnail('large', [
                                'class'         => 'w-full h-auto block',
                                'loading'       => 'eager',
                                'fetchpriority' => 'high',
                                'sizes'         => '(min-width: 1024px) 1024px, 100vw',
                                'alt'           => esc_attr(get_the_title()),
                            ]); ?>
                        </figure>
                    <?php endif; ?>
                </div>
            </header>

            <!-- Two-column layout: TOC sidebar + Content -->
            <div class="container lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">
                <!-- TOC Sidebar (desktop only) -->
                <aside id="table-of-contents" class="hidden lg:block" aria-label="<?php esc_attr_e('Table of Contents', 'standard'); ?>">
                    <nav class="toc sticky top-16">
                        <p class="toc__title"><?php esc_html_e('On this page', 'standard'); ?></p>
                        <ol id="toc-list" class="toc__list"></ol>
                    </nav>
                </aside>

                <!-- Content -->
                <div class="min-w-0">
                    <div class="prose prose-lg max-w-[72ch]" data-toc-content>
                        <?php the_content(); ?>
                    </div>

                    <?php get_template_part('templates/parts/disclaimer'); ?>
                </div>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/post-navigation'); ?>
            </div>

            <!-- Related Posts -->
            <div class="container">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php
get_footer();
