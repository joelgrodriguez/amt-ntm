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

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$video = function_exists('get_field') ? get_field('video', false, false) : null;

get_header();
?>

<main id="primary" class="">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>

            <!-- Video Player Section -->
            <?php get_template_part('templates/parts/video-section', null, [
                'title'            => __('Portable Rollforming Channel', 'standard'),
                'channel'          => __('New Tech Machinery', 'standard'),
                'video_url'        => is_string($video) ? $video : '',
                'video_type'       => get_the_date('j F Y'),
                'company_name'     => __('Video', 'standard'),
                'section_id'       => 'video-' . get_the_ID(),
                'top_left_icon'    => 'live-dot',
                'bottom_left_icon' => 'calendar',
                'show_led_strip'   => false,
            ]); ?>

            <!-- Content Section -->
            <section class="pattern-dot-grid gradient-fade-bottom py-6 lg:py-12">
                <div class="container grid gap-6 lg:gap-12">
                    <header class="max-w-4xl mx-auto grid gap-6">
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

                        <?php the_title('<h1 class="font-mono font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight m-0">', '</h1>'); ?>
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
