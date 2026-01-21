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
                <section class="bg-slate-950 text-slate-500">
                    <!-- Top Bar -->
                    <div class="border-b border-slate-800">
                        <div class="border-x border-slate-800 container mx-auto">
                            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                                <div class="flex items-center gap-3 pl-3">
                                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                    <span><?php echo esc_html(get_the_title()); ?></span>
                                </div>
                                <div class="flex items-center gap-3 pr-3">
                                    <span>Portable Rollforming Channel</span>
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
                                <div class="flex items-center gap-2 pl-3">
                                    <?php icon('calendar', ['class' => 'w-3 h-3 fill-current']); ?>
                                    <span><?php echo esc_html(get_the_date('Y.m.d')); ?></span>
                                </div>
                                <div class="flex items-center gap-4 pr-3">
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
                    <header class="max-w-4xl mx-auto grid gap-6">
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

                    <!-- Content -->
                    <div class="prose prose-lg max-w-4xl mx-auto">
                        <?php the_content(); ?>
                    </div>
                    <div class="max-w-4xl mx-auto">
                        <?php get_template_part('templates/parts/disclaimer'); ?>
                    </div>



                    <?php get_template_part('templates/parts/post-navigation'); ?>

                    <!-- Related Posts -->
                    <?php get_template_part('templates/parts/related-posts'); ?>
                </div>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/youtube'); ?>

<?php get_footer(); ?>
