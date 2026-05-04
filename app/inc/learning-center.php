<?php
/**
 * Learning Center query helpers.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\LearningCenter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return string[]
 */
function get_post_types(): array {
    return ['post', 'video', 'resource', 'download'];
}

/**
 * @return array{category: string, machine: string, type: string}
 */
function get_active_filters(): array {
    $type = get_filter_query_value('lc_type');

    return [
        'category' => get_filter_query_value('lc_category'),
        'machine'  => get_filter_query_value('lc_machine'),
        'type'     => in_array($type, get_post_types(), true) ? $type : '',
    ];
}

function get_learning_center_url(): string {
    $posts_page_id = (int) \get_option('page_for_posts');

    if ($posts_page_id > 0) {
        return \get_permalink($posts_page_id) ?: \home_url('/');
    }

    return \home_url('/');
}

function get_filter_query_value(string $key): string {
    if (!isset($_GET[$key])) {
        return '';
    }

    $value = \wp_unslash($_GET[$key]);

    return is_string($value) ? \sanitize_key($value) : '';
}

/**
 * @param string|string[] $post_type
 */
function get_latest_query(
    int $count = 4,
    string|array $post_type = [],
    string $category_slug = '',
    string $machine_slug = ''
): \WP_Query {
    $query_args = [
        'post_type'              => normalize_post_type($post_type),
        'posts_per_page'         => $count,
        'post_status'            => 'publish',
        'orderby'                => 'date',
        'order'                  => 'DESC',
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
    ];

    if ($category_slug !== '') {
        $query_args['category_name'] = $category_slug;
    }

    if ($machine_slug !== '') {
        $query_args['tag'] = $machine_slug;
    }

    return new \WP_Query($query_args);
}

/**
 * @param array{category?: string, machine?: string, type?: string} $filters
 */
function get_featured_query(array $filters = []): \WP_Query {
    return get_latest_query(
        1,
        filtered_post_type($filters),
        (string) ($filters['category'] ?? ''),
        (string) ($filters['machine'] ?? '')
    );
}

/**
 * @param array{category?: string, machine?: string, type?: string} $filters
 */
function get_recent_query(int $exclude_id = 0, int $count = 4, array $filters = []): \WP_Query {
    $query_args = [
        'post_type'              => filtered_post_type($filters),
        'posts_per_page'         => $count,
        'post_status'            => 'publish',
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
    ];

    if ($exclude_id > 0) {
        $query_args['post__not_in'] = [$exclude_id];
    }

    if (!empty($filters['category'])) {
        $query_args['category_name'] = (string) $filters['category'];
    }

    if (!empty($filters['machine'])) {
        $query_args['tag'] = (string) $filters['machine'];
    }

    return new \WP_Query($query_args);
}

/**
 * @param array{category?: string, machine?: string, type?: string} $filters
 */
function get_section_query(string $post_type, int $count = 4, array $filters = []): \WP_Query {
    return get_latest_query(
        $count,
        $post_type,
        (string) ($filters['category'] ?? ''),
        (string) ($filters['machine'] ?? '')
    );
}

/**
 * @return string|string[]
 */
function normalize_post_type(string|array $post_type): string|array {
    if (is_string($post_type)) {
        return in_array($post_type, get_post_types(), true) ? $post_type : get_post_types();
    }

    $allowed = array_values(array_intersect($post_type, get_post_types()));

    return !empty($allowed) ? $allowed : get_post_types();
}

/**
 * @param array{type?: string} $filters
 * @return string|string[]
 */
function filtered_post_type(array $filters): string|array {
    $type = (string) ($filters['type'] ?? '');

    return $type !== '' ? normalize_post_type($type) : get_post_types();
}

/**
 * @return array<int, array{title: string, post_type: string, icon: string, link: string|false, link_text: string}>
 */
function get_content_sections(): array {
    return [
        [
            'title'     => \__('Latest Articles', 'standard'),
            'post_type' => 'post',
            'icon'      => 'file-text',
            'link'      => \home_url('/learning-center/articles/'),
            'link_text' => \__('View All Articles', 'standard'),
        ],
        [
            'title'     => \__('Latest Videos', 'standard'),
            'post_type' => 'video',
            'icon'      => 'play',
            'link'      => \get_post_type_archive_link('video'),
            'link_text' => \__('View All Videos', 'standard'),
        ],
        [
            'title'     => \__('Latest Resources', 'standard'),
            'post_type' => 'resource',
            'icon'      => 'folder',
            'link'      => \get_post_type_archive_link('resource'),
            'link_text' => \__('View All Resources', 'standard'),
        ],
        [
            'title'     => \__('Latest Downloads', 'standard'),
            'post_type' => 'download',
            'icon'      => 'download',
            'link'      => \get_post_type_archive_link('download'),
            'link_text' => \__('View All Downloads', 'standard'),
        ],
    ];
}

/**
 * @param array<int, array{title: string, post_type: string, icon: string, link: string|false, link_text: string}> $sections
 * @param array{type?: string} $filters
 * @return array<int, array{title: string, post_type: string, icon: string, link: string|false, link_text: string}>
 */
function filter_content_sections(array $sections, array $filters): array {
    $type = (string) ($filters['type'] ?? '');

    if ($type === '') {
        return $sections;
    }

    return array_values(array_filter(
        $sections,
        fn(array $section): bool => $section['post_type'] === $type
    ));
}

function get_sidebar_items_query(string $post_type, int $exclude_id = 0, int $limit = 50): \WP_Query {
    $args = [
        'post_type'              => normalize_post_type($post_type),
        'post_status'            => 'publish',
        'posts_per_page'         => $limit,
        'orderby'                => 'title',
        'order'                  => 'ASC',
        'ignore_sticky_posts'    => true,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ];

    if ($exclude_id > 0) {
        $args['post__not_in'] = [$exclude_id];
    }

    return new \WP_Query($args);
}

/**
 * @return array<string, array{icon: string, label: string, cta: string}>
 */
function get_type_config(): array {
    return [
        'post'     => ['icon' => 'file-text', 'label' => \__('Article', 'standard'), 'cta' => \__('Read Article', 'standard')],
        'video'    => ['icon' => 'play', 'label' => \__('Video', 'standard'), 'cta' => \__('Watch Video', 'standard')],
        'resource' => ['icon' => 'folder', 'label' => \__('Resource', 'standard'), 'cta' => \__('View Resource', 'standard')],
        'download' => ['icon' => 'download', 'label' => \__('Download', 'standard'), 'cta' => \__('View Download', 'standard')],
    ];
}

function get_type_icon(string $post_type): string {
    $config = get_type_config();
    return $config[$post_type]['icon'] ?? 'file-text';
}

function get_type_label(string $post_type): string {
    $config = get_type_config();
    return $config[$post_type]['label'] ?? '';
}

function get_type_cta(string $post_type): string {
    $config = get_type_config();
    return $config[$post_type]['cta'] ?? \__('Read More', 'standard');
}
