<?php
/**
 * The template for displaying single video posts.
 *
 * Utilitarian-style video player with channel branding.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

get_header();
?>

<main id="primary" class="">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>

            <!-- Video Player Section -->
            <?php $video = get_field('video'); ?>
            <?php if ($video) : ?>
                <section class="bg-slate-950">
                    <!-- Top Bar -->
                    <div class="border-b border-slate-800">
                        <div class="border-x border-slate-800 container mx-auto">
                            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                                <div class="flex items-center gap-3 text-slate-500 pl-3">
                                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    <span class="text-slate-400"><?php echo esc_html(get_the_title()); ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-500 pr-3">
                                    <span class="text-slate-400">Portable Rollforming Channel</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Video Embed -->
                    <div class="border-x border-slate-800 container mx-auto py-6 lg:py-12">
                        <div class="max-w-5xl mx-auto">
                            <div class="video-responsive">
                                <?php echo $video; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="border-t border-slate-800">
                        <div class="border-x border-slate-800 container mx-auto">
                            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                                <div class="flex items-center gap-2 text-slate-500 pl-3">
                                    <?php icon('calendar', ['class' => 'w-3 h-3 fill-current']); ?>
                                    <span><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                                </div>
                                <div class="flex items-center gap-4 text-slate-600 pr-3">
                                    <span>New Tech Machinery</span>
                                    <div class="flex gap-1">
                                        <span class="w-1 h-3 bg-slate-700"></span>
                                        <span class="w-1 h-3 bg-slate-700"></span>
                                        <span class="w-1 h-3 bg-slate-600"></span>
                                        <span class="w-1 h-3 bg-slate-500"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Content Section -->
            <section class="pattern-dot-grid gradient-fade-bottom py-6 lg:py-12">
                <div class="container mx-auto grid gap-6 lg:gap-12">
                    <header class="grid gap-6">
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

                    <!-- Content -->
                    <div class="prose prose-lg max-w-3xl">
                        <?php the_content(); ?>
                    </div>

                    <!-- Post Navigation -->
                    <?php
                    $prev_post = get_previous_post();
                    $next_post = get_next_post();
                    if ($prev_post || $next_post) :
                    ?>
                        <nav class="pt-6 lg:pt-12 border-t border-slate-200" aria-label="<?php esc_attr_e('Post navigation', 'Standard'); ?>">
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
                    <?php get_template_part('templates/parts/related-posts'); ?>
                </div>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
