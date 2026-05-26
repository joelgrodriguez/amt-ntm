<?php
/**
 * The template for displaying single download posts.
 *
 * Shares the article shell and uses the shared Learning Center filter rail.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_filter_link_groups;

get_header();

$archive_url = get_post_type_archive_link('download');
?>

<main id="primary" class="pattern-dot-grid pb-6 lg:pb-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>

            <div class="container">
                <?php get_template_part('templates/parts/single/article-hero', null, [
                    'show_meta' => false,
                ]); ?>

                <div class="article-layout">
                    <?php
                    get_template_part('templates/parts/filter-sidebar', null, [
                        'groups'       => get_filter_link_groups(
                            ['type' => 'download'],
                            ['type' => 'download']
                        ),
                        'show_actions' => false,
                        'back_url'     => is_string($archive_url) ? $archive_url : '',
                        'back_label'   => __('View All Downloads', 'standard'),
                        'drawer_label' => __('Filters', 'standard'),
                        'aria_label'   => __('Learning Center filters', 'standard'),
                    ]);
                    ?>

                    <div class="min-w-0">
                        <div class="prose prose-lg max-w-full">
                            <?php the_content(); ?>
                        </div>

                        <?php get_template_part('templates/parts/disclaimer'); ?>
                    </div>
                </div>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/post-navigation'); ?>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
