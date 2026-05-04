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
 * @param string|string[] $post_type
 */
function get_latest_query(
    int $count = 4,
    string|array $post_type = [],
    string $category_slug = '',
    string $machine_slug = ''
): \WP_Query {
    $query_args = [
        'post_type'           => normalize_post_type($post_type),
        'posts_per_page'      => $count,
        'post_status'         => 'publish',
        'orderby'             => 'date',
        'order'               => 'DESC',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
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
        'post_type'           => filtered_post_type($filters),
        'posts_per_page'      => $count,
        'post_status'         => 'publish',
        'ignore_sticky_posts' => true,
        'no_found_rows'       => true,
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
