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

<main id="primary" class="py-6 lg:py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="mb-8">
                <div class="container mx-auto grid gap-12">
                    <?php if (has_category()) : ?>
                        <div class="flex flex-wrap gap-2">
                            <?php
                            $categories = get_the_category();
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
            <div class="container mx-auto lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">
                <!-- TOC Sidebar (desktop only) -->
                <aside id="table-of-contents" class="hidden lg:block" aria-label="<?php esc_attr_e('Table of Contents', 'theme'); ?>">
                    <nav class="toc sticky top-16">
                        <p class="toc__title"><?php esc_html_e('On this page', 'theme'); ?></p>
                        <ol id="toc-list" class="toc__list"></ol>
                    </nav>
                </aside>

                <!-- Content -->
                <div>
                    <div class="prose prose-lg max-w-full" data-toc-content>
                        <?php the_content(); ?>
                    </div>

                    <nav class="mt-12 pt-8 border-t border-slate-200 flex justify-between">
                        <div>
                            <?php previous_post_link('%link', '&larr; %title'); ?>
                        </div>
                        <div>
                            <?php next_post_link('%link', '%title &rarr;'); ?>
                        </div>
                    </nav>
                </div>
            </div>
        </article>

    <?php endwhile; ?>
</main>

<?php
get_footer();
