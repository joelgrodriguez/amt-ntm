<?php
/**
 * The template for displaying single download posts.
 *
 * Simpler layout than articles - no TOC sidebar.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

$content = [
    'badge'         => __('Download', 'standard'),
    'sidebar_title' => __('All Downloads', 'standard'),
];

get_header();
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-6 lg:py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
            <header class="container">
                <div class="grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
                    <div class="grid gap-6 justify-items-start">
                        <span class="badge inline"><?php echo esc_html($content['badge']); ?></span>

                        <?php the_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono">', '</h1>'); ?>

                        <?php if (get_the_excerpt()) : ?>
                            <p class="text-slate-600"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <?php endif; ?>
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

            <!-- Two-column layout: Content + Downloads Sidebar -->
            <div class="container lg:grid lg:grid-cols-[1fr_300px] lg:gap-12">
                <!-- Content -->
                <div>
                    <div class="prose prose-lg max-w-full">
                        <?php the_content(); ?>
                    </div>

                    <?php get_template_part('templates/parts/disclaimer'); ?>
                </div>

                <!-- Downloads Sidebar -->
                <aside class="hidden lg:block border-l border-slate-200 pl-12">
                    <nav class="sticky top-16">
                        <p class="text-sm font-semibold text-slate-900 mb-4"><?php echo esc_html($content['sidebar_title']); ?></p>
                        <ul class="grid gap-2">
                            <?php
                            $downloads = new WP_Query([
                                'post_type' => 'download',
                                'posts_per_page' => -1,
                                'orderby' => 'title',
                                'order' => 'ASC',
                                'post__not_in' => [get_the_ID()],
                            ]);

                            if ($downloads->have_posts()) :
                                while ($downloads->have_posts()) : $downloads->the_post();
                            ?>
                                <li>
                                    <a href="<?php the_permalink(); ?>" class="block text-sm text-primary no-underline hover:underline">
                                        <?php the_title(); ?>
                                    </a>
                                </li>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            endif;
                            ?>
                        </ul>
                    </nav>
                </aside>
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

<?php get_footer(); ?>
