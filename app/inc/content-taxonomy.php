<?php
/**
 * Taxonomy helpers for content templates.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ContentTaxonomy;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return int[]
 */
function get_post_type_ids(string $post_type, int $limit = 500): array {
    static $cache = [];

    $post_type = sanitize_key($post_type);
    $cache_key = $post_type . ':' . $limit;

    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }

    $ids = get_posts([
        'post_type'              => $post_type,
        'post_status'            => 'publish',
        'posts_per_page'         => $limit,
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    $cache[$cache_key] = array_map('intval', $ids);

    return $cache[$cache_key];
}

/**
 * @return \WP_Term[]
 */
function get_terms_for_post_type(string $post_type, string $taxonomy, int $limit = 500): array {
    static $cache = [];

    $post_type = sanitize_key($post_type);
    $taxonomy = sanitize_key($taxonomy);
    $cache_key = $post_type . ':' . $taxonomy . ':' . $limit;

    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }

    $post_ids = get_post_type_ids($post_type, $limit);

    if ($post_ids === []) {
        $cache[$cache_key] = [];
        return [];
    }

    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => true,
        'object_ids' => $post_ids,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    $cache[$cache_key] = is_array($terms) ? $terms : [];

    return $cache[$cache_key];
}
