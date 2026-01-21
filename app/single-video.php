<?php
/**
 * The template for displaying single video posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="pattern-dot-grid py-6 lg:py-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
            <header class="container mx-auto grid gap-6">
                <?php if (has_category()) : ?>
                    <div class="flex flex-wrap gap-3">
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
                </div>
            </header>

            <!-- Video Player Section -->
            <div class="container mx-auto">
                <div class="aspect-video bg-slate-900 flex items-center justify-center">
                    <?php
                    // @todo: Replace with ACF video field
                    // $video_url = get_field('video_url');
                    ?>
                    <span class="text-slate-500 font-mono text-sm">Video Player Placeholder</span>
                </div>
            </div>

            <!-- Content -->
            <div class="container mx-auto">
                <div class="prose prose-lg max-w-3xl">
                    <?php the_content(); ?>
                </div>
            </div>

            <!-- Post Navigation -->
            <?php
            $prev_post = get_previous_post();
            $next_post = get_next_post();
            if ($prev_post || $next_post) :
            ?>
                <nav class="container mx-auto pt-6 lg:pt-12 border-t border-slate-200" aria-label="<?php esc_attr_e('Post navigation', 'Standard'); ?>">
                    <div class="grid md:grid-cols-2 gap-6">
                        <?php if ($prev_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="block p-4 border border-slate-300 bg-white no-underline hover:bg-slate-50 transition-colors">
                                <span class="flex items-center gap-2 text-xs text-slate-500 font-mono uppercase tracking-wide mb-2">
                                    <?php icon('arrow--left', ['class' => 'w-3 h-3']); ?>
                                    <span class="font-mono"><?php esc_html_e('Previous', 'standard'); ?></span>
                                </span>
                                <span class="block text-base text-primary line-clamp-2"><?php echo esc_html($prev_post->post_title); ?></span>
                            </a>
                        <?php else : ?>
                            <div></div>
                        <?php endif; ?>

                        <?php if ($next_post) : ?>
                            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="block p-4 border border-slate-300 bg-white no-underline hover:bg-slate-50 transition-colors text-right">
                                <span class="flex items-center justify-end gap-2 text-xs text-slate-500 font-mono uppercase tracking-wide mb-2">
                                    <span class="font-mono"><?php esc_html_e('Next', 'standard'); ?></span>
                                    <?php icon('arrow--right', ['class' => 'w-3 h-3']); ?>
                                </span>
                                <span class="block text-base text-primary line-clamp-2"><?php echo esc_html($next_post->post_title); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>

            <!-- Related Posts -->
            <div class="container mx-auto">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
