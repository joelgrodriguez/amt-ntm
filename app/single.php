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

get_header();
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom py-6 lg:py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
            <header>
                <div class="container grid gap-6">
                    <?php if (has_category()) : ?>
                        <div class="flex flex-wrap gap-3">
                            <?php
                            $categories = array_slice(get_the_category(), 0, 3);
                            foreach ($categories as $category) :
                            ?>
                                <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="badge">
                                    <?php echo esc_html($category->name); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php the_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono">', '</h1>'); ?>

                    <div class="flex items-center gap-6 text-slate-500 font-mono text-sm">
                        <span class="flex items-center gap-2">
                            <?php icon('calendar', ['class' => 'w-4 h-4']); ?>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date('j F Y')); ?>
                            </time>
                        </span>
                        <span class="flex items-center gap-2">
                            <?php icon('user', ['class' => 'w-4 h-4']); ?>
                            <span><?php the_author(); ?></span>
                        </span>
                    </div>
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="featured-image">
                            <?php the_post_thumbnail('full', [
                                'loading' => 'eager',
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
                <div>
                    <div class="prose prose-lg max-w-full" data-toc-content>
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
