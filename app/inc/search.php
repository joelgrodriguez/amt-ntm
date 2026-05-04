<?php
/**
 * Search query contract.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Search;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return string[]
 */
function get_default_post_types(): array {
    return [
        'post',
        'page',
        'video',
        'resource',
        'download',
        'manual',
        'profile',
        'product',
    ];
}

/**
 * @return string[]
 */
function get_searchable_post_types(): array {
    $post_types = \apply_filters('standard_search_post_types', get_default_post_types());

    if (!is_array($post_types)) {
        return ['post', 'page'];
    }

    return array_values(array_filter(
        array_map(fn(mixed $post_type): string => \sanitize_key((string) $post_type), $post_types),
        fn(string $post_type): bool => \post_type_exists($post_type)
    ));
}

function configure_main_query(\WP_Query $query): void {
    if (\is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $query_args = \apply_filters('standard_search_query_args', [
        'post_type'           => get_searchable_post_types(),
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'ignore_sticky_posts' => true,
    ], $query);

    if (!is_array($query_args)) {
        return;
    }

    foreach ($query_args as $key => $value) {
        $query->set((string) $key, $value);
    }
}

\add_action('pre_get_posts', __NAMESPACE__ . '\\configure_main_query');
