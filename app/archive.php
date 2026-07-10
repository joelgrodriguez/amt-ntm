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
use function Standard\LearningCenter\get_allowed_categories;
use function Standard\LearningCenter\get_type_filter_options;
use function Standard\MachinesData\get_machine_post_tags;
use function Standard\Search\get_post_type_filter_keys;
use function Standard\Search\get_post_type_filter_options;
use function Standard\Search\get_request_values;

$requested_types = get_request_values(get_post_type_filter_keys(), 'post_type');
$archive_type = '';
if (is_post_type_archive()) {
    $archive_query_type = get_query_var('post_type') ?: 'post';
    $archive_type = is_array($archive_query_type)
        ? sanitize_key((string) reset($archive_query_type))
        : sanitize_key((string) $archive_query_type);
}
$active_type = count($requested_types) === 1 ? $requested_types[0] : $archive_type;
$type_options = get_post_type_filter_options();
$active_type_label = $active_type !== '' && isset($type_options[$active_type])
    ? $type_options[$active_type]
    : '';
// Scoped whenever the active type is a catalog CPT — whether that came from a
// ?post_type= filter link OR from landing on the CPT archive itself. The old
// GET-only check made the bare /learning-center/manual|profile/ entry pages
// render the blog-category dashboard sidebar, whose every option was a dead
// combo for catalog content.
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

$is_category         = is_category();
$is_taxonomy_archive = is_category() || is_tag();
$hero_title          = '';
$hero_description    = '';

if ($is_taxonomy_archive && $current_term instanceof WP_Term) {
    $hero_title = $current_term->name;
    $hero_description = trim(wp_strip_all_tags((string) term_description($current_term)));

    if ($hero_description === '') {
        if (is_category()) {
            if ($active_type === 'manual') {
                $hero_description = __('Operator and service manuals in this category.', 'standard');
            } elseif ($active_type === 'profile') {
                $hero_description = __('Panel and gutter profiles in this category.', 'standard');
            } else {
                $hero_description = __('Articles, videos, downloads, and resources in this category.', 'standard');
            }
        } else {
            $hero_description = __('Learning Center content for this topic.', 'standard');
        }
    }
}

get_header();
?>

<main id="primary">

    <?php if ($is_taxonomy_archive && $hero_title !== '') : ?>
        <?php get_template_part('templates/parts/archive/hero', null, [
            'eyebrow'     => $content['eyebrow'],
            'title'       => $hero_title,
            'description' => $hero_description,
        ]); ?>
        <section class="bg-white pt-12 pb-24 lg:pt-16 lg:pb-32">
    <?php else : ?>
        <div class="pattern-dot-grid py-6 lg:py-12">
            <header class="container mb-6 lg:mb-12">
                <div class="grid gap-4 justify-items-start">
                    <span class="text-xs font-mono uppercase tracking-widest text-blue-500"><?php echo esc_html($content['eyebrow']); ?></span>
                    <?php the_archive_title('<h1 class="font-sans text-3xl md:text-4xl lg:text-5xl font-semibold tracking-tight text-blue-900">', '</h1>'); ?>
                    <?php the_archive_description('<p class="text-blue-600 max-w-2xl">', '</p>'); ?>
                </div>
            </header>
    <?php endif; ?>

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
            // every Learning Center archive in sync with the LC landing + search.
            $categories = get_allowed_categories();
            $current_category_id = $is_category && $current_term instanceof WP_Term ? (int) $current_term->term_id : 0;
            $blog_url = get_permalink((int) get_option('page_for_posts')) ?: \Standard\Url\internal('/');

            $groups = [];

            if ($categories !== []) {
                $active_category_ids = $current_category_id > 0 ? [$current_category_id] : [];
                $groups[] = build_term_link_group(
                    'category',
                    $content['filter_category'],
                    $categories,
                    $active_category_ids,
                    'folder',
                    $active_type
                );
            }

            $type_link_options = [];
            foreach (get_type_filter_options(false) as $post_type => $label) {
                $url = '';
                if ($current_term instanceof WP_Term && in_array($current_term->taxonomy, ['category', 'post_tag'], true)) {
                    $term_link = get_term_link($current_term);
                    if (!is_wp_error($term_link)) {
                        $url = add_query_arg(['post_type' => $post_type], (string) $term_link);
                    }
                } else {
                    $archive_link = get_post_type_archive_link($post_type);
                    $url = is_string($archive_link) ? $archive_link : '';
                }

                if ($url === '') {
                    continue;
                }

                $type_link_options[] = [
                    'value'  => $post_type,
                    'label'  => $label,
                    'count'  => null,
                    'active' => $active_type === $post_type,
                    'url'    => $url,
                ];
            }

            if ($type_link_options !== []) {
                $groups[] = [
                    'id'      => 'content-type',
                    'title'   => __('Resource Type', 'standard'),
                    'icon'    => 'file-text',
                    'mode'    => 'link',
                    'name'    => null,
                    'options' => $type_link_options,
                ];
            }

            $machine_terms = get_machine_post_tags();
            if ($machine_terms !== []) {
                $active_machine_ids = $current_term instanceof WP_Term && $current_term->taxonomy === 'post_tag'
                    ? [(int) $current_term->term_id]
                    : [];
                $groups[] = build_term_link_group(
                    'post_tag',
                    $content['filter_machine'],
                    $machine_terms,
                    $active_machine_ids,
                    'settings',
                    $active_type
                );
            }

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

    <?php if ($is_taxonomy_archive && $hero_title !== '') : ?>
        </section>
    <?php else : ?>
        </div>
    <?php endif; ?>

</main>

<?php
get_footer();
