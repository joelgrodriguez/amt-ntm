<?php
/**
 * The template for displaying single literature posts.
 *
 * Literature content with the shared Learning Center filter rail.
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

$content = [
    'badge'    => __('Literature', 'standard'),
    'view_all' => __('View All Literature', 'standard'),
];

get_header();
?>

<main id="primary">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/parts/single/profile-style-hero', null, [
            'eyebrow' => $content['badge'],
        ]); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12 py-6 lg:py-12'); ?>>

            <div class="container layout-with-rail">

                <?php
                get_template_part('templates/parts/filter-sidebar', null, [
                    'groups'       => get_filter_link_groups(
                        ['type' => 'literature'],
                        ['type' => 'literature']
                    ),
                    'show_actions' => false,
                    'back_url'     => get_post_type_archive_link('literature') ?: '',
                    'back_label'   => $content['view_all'],
                    'drawer_label' => __('Filters', 'standard'),
                    'aria_label'   => __('Learning Center filters', 'standard'),
                ]);
                ?>

                <div class="grid gap-8">
                    <section>
                        <div class="prose prose-lg max-w-full">
                            <?php the_content(); ?>
                        </div>
                    </section>
                </div>

            </div>

        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
