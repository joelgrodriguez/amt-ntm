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
        'literature',
        'resource',
        'download',
        'manual',
        'profile',
        'product',
        'footprint',
    ];
}

/**
 * @return string[]
 */
function get_excluded_post_types(): array {
    $post_types = \apply_filters('standard_search_excluded_post_types', [
        'pricesheet',
        'cutlist',
        'attachment',
    ]);

    if (!is_array($post_types)) {
        return ['pricesheet'];
    }

    return array_values(array_unique(array_filter(
        array_map(fn($post_type): string => \sanitize_key((string) $post_type), $post_types)
    )));
}

/**
 * @return string[]
 */
function get_excluded_index_post_types(): array {
    $post_types = \apply_filters('standard_search_excluded_index_post_types', [
        'pricesheet',
        'cutlist',
    ]);

    if (!is_array($post_types)) {
        return ['pricesheet'];
    }

    return array_values(array_unique(array_filter(
        array_map(fn($post_type): string => \sanitize_key((string) $post_type), $post_types)
    )));
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
        array_map(fn($post_type): string => \sanitize_key((string) $post_type), $post_types),
        fn(string $post_type): bool => \post_type_exists($post_type) && !in_array($post_type, get_excluded_post_types(), true)
    ));
}

/**
 * @return string[]
 */
function get_requested_post_types(): array {
    $requested = get_request_values(get_post_type_filter_keys(), 'post_type');

    if ($requested === []) {
        return get_searchable_post_types();
    }

    $allowed = get_searchable_post_types();

    return array_values(array_intersect($requested, $allowed));
}

/**
 * @return string[]
 */
function get_post_type_filter_keys(): array {
    return ['post_type', 'type', 'lc_type'];
}

/**
 * @return array<string|int, mixed>
 */
function get_requested_tax_query(): array {
    $filters = \apply_filters('standard_search_taxonomy_filters', [
        'category'    => ['category', 'lc_category', '_sft_category'],
        'post_tag'    => ['tag', 'post_tag', 'lc_machine', '_sft_post_tag'],
        'machine'     => ['machine'],
        'department'  => ['department'],
        'product_cat' => ['product_cat'],
        'product_tag' => ['product_tag'],
    ]);

    if (!is_array($filters)) {
        return [];
    }

    $tax_query = [];

    foreach ($filters as $taxonomy => $keys) {
        $taxonomy = \sanitize_key((string) $taxonomy);

        if (!\taxonomy_exists($taxonomy) || !is_array($keys)) {
            continue;
        }

        $terms = get_request_values($keys, 'term', $taxonomy);

        if ($terms === []) {
            continue;
        }

        $tax_query[] = [
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $terms,
            'operator' => 'IN',
        ];
    }

    if (count($tax_query) > 1) {
        return array_merge(['relation' => 'AND'], $tax_query);
    }

    return $tax_query;
}

/**
 * @param string[] $keys
 * @return string[]
 */
function get_request_values(array $keys, string $context, string $taxonomy = ''): array {
    $values = [];

    foreach ($keys as $key) {
        if (!isset($_GET[$key])) {
            continue;
        }

        $raw = \wp_unslash($_GET[$key]);
        $raw_values = is_array($raw) ? $raw : [$raw];

        foreach ($raw_values as $raw_value) {
            if (!is_scalar($raw_value)) {
                continue;
            }

            $parts = preg_split('/[\s,+]+/', (string) $raw_value) ?: [];

            foreach ($parts as $part) {
                $part = trim($part);

                if ($part === '') {
                    continue;
                }

                $value = $context === 'post_type'
                    ? \sanitize_key($part)
                    : normalize_term_slug($part, $taxonomy);

                if ($value !== '') {
                    $values[] = $value;
                }
            }
        }
    }

    return array_values(array_unique($values));
}

function normalize_term_slug(string $value, string $taxonomy): string {
    if (ctype_digit($value)) {
        $term = \get_term((int) $value, $taxonomy);

        if ($term instanceof \WP_Term) {
            return $term->slug;
        }
    }

    return \sanitize_title($value);
}

/**
 * Relevanssi does not reliably honor post__in=[0] on empty searches,
 * so use an impossible post type too.
 *
 * @param array<string, mixed> $query_args
 */
function force_no_results(array &$query_args): void {
    $query_args['post_type'] = ['__standard_no_results'];
    $query_args['post__in'] = [0];
}

function is_excluded_post_type(string $post_type): bool {
    return in_array(\sanitize_key($post_type), get_excluded_post_types(), true);
}

function is_excluded_index_post_type(string $post_type): bool {
    return in_array(\sanitize_key($post_type), get_excluded_index_post_types(), true);
}

/**
 * @param bool|string $do_not_index
 * @param int         $post_id
 * @param \WP_Post|null $post
 * @return bool|string
 */
function exclude_relevanssi_indexed_post_types($do_not_index, int $post_id, ?\WP_Post $post = null) {
    $post = $post ?: \get_post($post_id);

    if ($post instanceof \WP_Post && is_excluded_index_post_type($post->post_type)) {
        return 'Standard search excludes this post type';
    }

    return $do_not_index;
}

function exclude_relevanssi_result_post_types(bool $post_ok, int $post_id): bool {
    if (!$post_ok) {
        return false;
    }

    $post_type = \get_post_type($post_id);

    return is_string($post_type) ? !is_excluded_post_type($post_type) : $post_ok;
}

function configure_main_query(\WP_Query $query): void {
    if (\is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $requested_post_types = get_request_values(get_post_type_filter_keys(), 'post_type');
    $post_types = $requested_post_types === []
        ? get_searchable_post_types()
        : get_requested_post_types();
    $tax_query = get_requested_tax_query();

    $query_args = \apply_filters('standard_search_query_args', [
        'post_type'           => $post_types !== [] ? $post_types : get_searchable_post_types(),
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'ignore_sticky_posts' => true,
        'suppress_filters'    => false,
    ], $query);

    if (!is_array($query_args)) {
        return;
    }

    if ($tax_query !== []) {
        $query_args['tax_query'] = $tax_query;
    }

    if ($requested_post_types !== [] && $post_types === []) {
        force_no_results($query_args);
    }

    if (trim((string) $query->get('s')) === '' && $tax_query === [] && $requested_post_types === []) {
        force_no_results($query_args);
    }

    foreach ($query_args as $key => $value) {
        $query->set((string) $key, $value);
    }
}

\add_action('pre_get_posts', __NAMESPACE__ . '\\configure_main_query');
\add_filter('relevanssi_do_not_index', __NAMESPACE__ . '\\exclude_relevanssi_indexed_post_types', 10, 3);
\add_filter('relevanssi_post_ok', __NAMESPACE__ . '\\exclude_relevanssi_result_post_types', 10, 2);
