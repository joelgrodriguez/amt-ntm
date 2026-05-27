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

use function Standard\PostTypes\get_primary_category;

$video = function_exists('get_field') ? get_field('video', false, false) : null;

get_header();
?>

<main id="primary" class="">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(''); ?>>
            <?php get_template_part('templates/parts/video-section', null, [
                'title'             => __('New Tech Machinery', 'standard'),
                'title_short'       => __('NTM', 'standard'),
                'channel'           => __('Portable Rollforming Channel', 'standard'),
                'channel_short'     => __('Rollforming Channel', 'standard'),
                'video_url'         => is_string($video) ? $video : '',
                'video_type'        => get_the_date('Y.m.d'),
                'company_name'      => __('Video', 'standard'),
                'section_id'        => 'video-' . get_the_ID(),
                'top_left_icon'     => 'live-dot',
                'bottom_left_icon'  => 'calendar',
                'bottom_right_icon' => 'video',
                'show_led_strip'    => false,
            ]); ?>
            <section class="pattern-dot-grid py-6 lg:py-12">
                <div class="grid gap-6 lg:gap-12 max-w-4xl mx-auto w-full px-4">
                    <header class="grid gap-6">
                        <?php $primary_category = get_primary_category((int) get_the_ID()); ?>
                        <?php if ($primary_category instanceof \WP_Term) : ?>
                            <div>
                                <a href="<?php echo esc_url(get_category_link($primary_category->term_id)); ?>"
                                   class="font-mono uppercase tracking-widest text-caption text-blue-500 no-underline hover:text-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                    <?php echo esc_html($primary_category->name); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php the_title('<h1 class="font-sans font-semibold text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight m-0">', '</h1>'); ?>
                    </header>
                    <div class="prose prose-lg max-w-full">
                        <?php the_content(); ?>
                    </div>
                    <?php get_template_part('templates/parts/disclaimer'); ?>

                    <?php get_template_part('templates/parts/post-navigation'); ?>
                    <?php get_template_part('templates/parts/related-posts'); ?>
                </div>
            </section>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/youtube'); ?>

<?php get_footer(); ?>
