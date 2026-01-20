<?php
/**
 * Related Posts functionality.
 *
 * Provides helper functions for querying and displaying
 * related posts based on shared categories.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

/**
 * Get related posts for the current post.
 *
 * Queries posts that share categories with the current post.
 * Limited to post, video, download, and resource post types.
 *
 * @param int $count Number of posts to return. Default 4.
 * @return \WP_Query Query object with related posts.
 */
function get_related_posts(int $count = 4): \WP_Query
{
    $post_id = get_the_ID();
    $categories = get_the_category($post_id);

    // If no categories, return empty query
    if (empty($categories)) {
        return new \WP_Query();
    }

    // Get category IDs
    $category_ids = array_map(fn($cat) => $cat->term_id, $categories);

    // Limit to specific post types
    $post_types = ['post', 'video', 'download', 'resource'];

    $args = [
        'post_type'      => $post_types,
        'posts_per_page' => $count,
        'post__not_in'   => [$post_id],
        'category__in'   => $category_ids,
        'orderby'        => 'rand',
        'post_status'    => 'publish',
    ];

    return new \WP_Query($args);
}
