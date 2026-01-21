<?php
/**
 * The template for displaying single download posts.
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
            <header class="container mx-auto grid gap-6">
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
            </header>

            <!-- Download Section -->
            <div class="container mx-auto">
                <div class="p-6 bg-white border border-slate-300 flex items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <?php icon('download', ['class' => 'w-8 h-8 text-primary']); ?>
                        <div>
                            <p class="font-semibold text-slate-900">Download File</p>
                            <p class="text-sm text-slate-500 font-mono">
                                <?php
                                // @todo: Replace with ACF file field
                                // $file = get_field('download_file');
                                ?>
                                File details placeholder
                            </p>
                        </div>
                    </div>
                    <a href="#" class="btn btn-primary">
                        <?php icon('download', ['class' => 'w-4 h-4']); ?>
                        <span><?php esc_html_e('Download', 'standard'); ?></span>
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="container mx-auto">
                <div class="prose prose-lg max-w-3xl">
                    <?php the_content(); ?>
                </div>

                <?php get_template_part('templates/parts/disclaimer'); ?>
            </div>

            <!-- Related Posts -->
            <div class="container mx-auto">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
