<?php
/**
 * The template for displaying archive pages.
 *
 * Dashboard-style layout with filter sidebar.
 * Displays posts for categories, tags, authors, dates, and custom taxonomies.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\ContentTaxonomy\get_terms_for_post_type;
use function Standard\Filters\build_term_link_group;
use function Standard\Filters\get_post_type_counts;
use function Standard\LearningCenter\get_allowed_categories;
use function Standard\Search\get_post_type_filter_keys;
use function Standard\Search\get_post_type_filter_options;
use function Standard\Search\get_request_values;

$requested_types = get_request_values(get_post_type_filter_keys(), 'post_type');
$active_type = count($requested_types) === 1 ? $requested_types[0] : '';
$type_options = get_post_type_filter_options();
$active_type_label = $active_type !== '' && isset($type_options[$active_type])
    ? $type_options[$active_type]
    : '';
$is_scoped_catalog = in_array($active_type, ['profile', 'manual'], true);
$current_term = get_queried_object();
$current_category_terms = $current_term instanceof WP_Term && $current_term->taxonomy === 'category'
    ? [$current_term]
    : [];
$current_machine_terms = $current_term instanceof WP_Term && $current_term->taxonomy === 'post_tag'
    ? [$current_term]
    : [];

$content = [
    'eyebrow'           => $active_type_label !== '' ? $active_type_label : __('Learning Center', 'standard'),
    'filter_category'   => __('Filter by Category', 'standard'),
    'filter_type'       => __('Filter by Type', 'standard'),
    'filter_machine'    => __('Filter by Machine', 'standard'),
    'blog_posts'        => __('Blog Posts', 'standard'),
    'view_all'          => __('View All Posts', 'standard'),
];

get_header();

// Get current query info
$is_category = is_category();
?>

<main id="primary" class="pattern-dot-grid py-6 lg:py-12">

    <!-- Header -->
    <header class="container mb-6 lg:mb-12">
        <div class="grid gap-4 justify-items-start">
            <span class="text-xs font-mono uppercase tracking-widest text-red"><?php echo esc_html($content['eyebrow']); ?></span>
            <?php the_archive_title('<h1 class="font-sans text-3xl md:text-4xl lg:text-5xl font-semibold tracking-tight text-blue-900">', '</h1>'); ?>
            <?php the_archive_description('<p class="text-blue-600 max-w-2xl">', '</p>'); ?>
        </div>
    </header>

    <!-- Two-column layout: Filter Sidebar + Content -->
    <div class="container layout-with-rail">

        <?php if ($is_scoped_catalog) : ?>
            <?php
            get_template_part('templates/parts/taxonomy-filter-sidebar', null, [
                'post_type' => $active_type,
                'sections'  => [
                    [
                        'title'         => $content['filter_type'],
                        'icon'          => 'filter',
                        'terms'         => get_terms_for_post_type($active_type, 'category'),
                        'current_terms' => $current_category_terms,
                    ],
                    [
                        'title'         => $content['filter_machine'],
                        'icon'          => 'settings',
                        'terms'         => get_terms_for_post_type($active_type, 'post_tag'),
                        'current_terms' => $current_machine_terms,
                    ],
                ],
                'back_url'   => \Standard\Url\internal($active_type === 'profile' ? '/profiles/' : '/machines/manuals/'),
                'back_label' => $active_type === 'profile' ? __('All profiles', 'standard') : __('All manuals', 'standard'),
            ]);
            ?>
        <?php else : ?>
            <?php
            // Curated allowlist (see inc/learning-center/config.php) keeps
            // the blog category rail in sync with the LC landing + search.
            $categories = get_allowed_categories();
            $current_category_id = $is_category && $current_term instanceof WP_Term ? (int) $current_term->term_id : 0;

            $post_type_counts = get_post_type_counts();
            $blog_url = get_permalink((int) get_option('page_for_posts')) ?: \Standard\Url\internal('/');
            $type_options = [
                [
                    'value'  => 'post',
                    'label'  => $content['blog_posts'],
                    'count'  => $post_type_counts['post'] ?? 0,
                    'active' => is_home() || $current_term instanceof WP_Post,
                    'url'    => (string) $blog_url,
                ],
            ];

            $public_post_types = get_post_types([
                'public'      => true,
                'has_archive' => true,
            ], 'objects');
            foreach ($public_post_types as $post_type) {
                if ($post_type->name === 'post' || $post_type->name === 'page') {
                    continue;
                }
                $archive_link = get_post_type_archive_link($post_type->name);
                if (!$archive_link) {
                    continue;
                }
                $type_options[] = [
                    'value'  => $post_type->name,
                    'label'  => $post_type->labels->name,
                    'count'  => $post_type_counts[$post_type->name] ?? 0,
                    'active' => false,
                    'url'    => (string) $archive_link,
                ];
            }

            $groups = [];

            if ($categories !== []) {
                $active_category_ids = $current_category_id > 0 ? [$current_category_id] : [];
                $groups[] = build_term_link_group(
                    'category',
                    $content['filter_category'],
                    $categories,
                    $active_category_ids,
                    'filter'
                );
            }

            $groups[] = [
                'id'      => 'content-type',
                'title'   => $content['filter_type'],
                'icon'    => 'settings',
                'mode'    => 'link',
                'name'    => null,
                'options' => $type_options,
            ];

            get_template_part('templates/parts/filter-sidebar', null, [
                'groups'       => $groups,
                'show_actions' => false,
                'back_url'     => $blog_url,
                'back_label'   => $content['view_all'],
                'drawer_label' => __('Filters', 'standard'),
                'aria_label'   => __('Filters', 'standard'),
            ]);
            ?>
        <?php endif; ?>

        <!-- Main Content -->
        <div class="grid gap-8">
            <?php if (have_posts()) : ?>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php get_template_part('templates/parts/content', 'search'); ?>
                    <?php endwhile; ?>
                </div>

                <?php \Standard\Walkers\Pagination::render(); ?>
            <?php else : ?>
                <?php get_template_part('templates/parts/content', 'none'); ?>
            <?php endif; ?>
        </div>

    </div>

</main>

<?php
get_footer();
