<?php
/**
 * The template for displaying single resource posts.
 *
 * Mirrors single-manual.php (profile-style hero + two-column body),
 * but the sidebar lists every published resource as a link instead
 * of taxonomy filters. No related-machines block.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'badge'       => __('Resource', 'standard'),
    'all_label'   => __('All Resources', 'standard'),
    'view_all'    => __('View All Resources', 'standard'),
    'aria_label'  => __('Resources', 'standard'),
];

get_header();

$current_id = (int) get_the_ID();

$resource_posts = get_posts([
    'post_type'      => 'resource',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'title',
    'order'          => 'ASC',
]);

$resource_options = [];
foreach ($resource_posts as $resource_post) {
    $resource_options[] = [
        'value'  => (string) $resource_post->ID,
        'label'  => get_the_title($resource_post),
        'url'    => (string) get_permalink($resource_post),
        'active' => (int) $resource_post->ID === $current_id,
    ];
}

$resource_groups = $resource_options === [] ? [] : [
    [
        'id'      => 'resources-all',
        'title'   => $content['all_label'],
        'icon'    => 'file-text',
        'mode'    => 'link',
        'options' => $resource_options,
    ],
];
?>

<main id="primary">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/parts/single/profile-style-hero', null, [
            'eyebrow' => $content['badge'],
        ]); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12 py-6 lg:py-12'); ?>>

            <div class="container layout-with-rail">

                <?php if ($resource_groups !== []) : ?>
                    <?php get_template_part('templates/parts/filter-sidebar', null, [
                        'groups'       => $resource_groups,
                        'show_actions' => false,
                        'collapsible'  => false,
                        'back_url'     => get_post_type_archive_link('resource') ?: '',
                        'back_label'   => $content['view_all'],
                        'drawer_label' => $content['aria_label'],
                        'aria_label'   => $content['aria_label'],
                    ]); ?>
                <?php endif; ?>

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

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
