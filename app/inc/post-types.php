<?php
/**
 * Post type display config.
 *
 * Central lookup for per-post-type display affordances (icon, CTA copy,
 * accessible label). Used by card-post and any other surface that needs
 * to badge a result by post type.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\PostTypes;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display config for a single post type.
 *
 * Returns icon slug, visible CTA copy, and an accessible label used by
 * assistive tech to announce the link's purpose. Falls back to a
 * generic 'link' icon + 'View' for unknown post types so callers don't
 * need to null-check.
 *
 * @return array{icon: string, cta: string, label: string}
 */
function get_display_config(string $post_type): array {
    $config = [
        'post'     => [
            'icon'  => 'file-text',
            'cta'   => \__('Read article', 'standard'),
            'label' => \__('Read full article', 'standard'),
        ],
        'knowledgebase' => [
            'icon'  => 'life-buoy',
            'cta'   => \__('Read article', 'standard'),
            'label' => \__('Read this troubleshooting article', 'standard'),
        ],
        'video'    => [
            'icon'  => 'play',
            'cta'   => \__('Watch video', 'standard'),
            'label' => \__('Watch this video', 'standard'),
        ],
        'download' => [
            'icon'  => 'download',
            'cta'   => \__('View download', 'standard'),
            'label' => \__('Open this download', 'standard'),
        ],
        'resource' => [
            'icon'  => 'folder',
            'cta'   => \__('View resource', 'standard'),
            'label' => \__('Open this resource', 'standard'),
        ],
        'manual'   => [
            'icon'  => 'file-text',
            'cta'   => \__('View manual', 'standard'),
            'label' => \__('Open this manual', 'standard'),
        ],
        'literature' => [
            'icon'  => 'folder',
            'cta'   => \__('View literature', 'standard'),
            'label' => \__('Open this literature', 'standard'),
        ],
        'footprint' => [
            'icon'  => 'settings',
            'cta'   => \__('View footprint', 'standard'),
            'label' => \__('Open this footprint', 'standard'),
        ],
        'cutlist' => [
            'icon'  => 'file-text',
            'cta'   => \__('View cutlist', 'standard'),
            'label' => \__('Open this cutlist', 'standard'),
        ],
        'page'     => [
            'icon'  => 'link',
            'cta'   => \__('View page', 'standard'),
            'label' => \__('Open this page', 'standard'),
        ],
        'product'  => [
            'icon'  => 'shopping-cart',
            'cta'   => \__('View product', 'standard'),
            'label' => \__('See product details', 'standard'),
        ],
        'profile'  => [
            'icon'  => 'user',
            'cta'   => \__('View profile', 'standard'),
            'label' => \__('Read this profile', 'standard'),
        ],
    ];

    return $config[$post_type] ?? [
        'icon'  => 'link',
        'cta'   => \__('View', 'standard'),
        'label' => \__('Open this item', 'standard'),
    ];
}

/**
 * Resolve the primary category for a post.
 *
 * Checks Yoast's primary-category meta first, then Rank Math's, then
 * falls back to the first category WordPress returns. Returns null if
 * the post has no categories at all.
 */
function get_primary_category(int $post_id = 0): ?\WP_Term {
    $post_id = $post_id ?: (int) \get_the_ID();
    if (!$post_id) {
        return null;
    }

    $categories = \get_the_category($post_id);
    if (empty($categories)) {
        return null;
    }
    $yoast_id = (int) \get_post_meta($post_id, '_yoast_wpseo_primary_category', true);
    if ($yoast_id) {
        $term = \get_term($yoast_id, 'category');
        if ($term instanceof \WP_Term) {
            return $term;
        }
    }
    $rm_id = (int) \get_post_meta($post_id, 'rank_math_primary_category', true);
    if ($rm_id) {
        $term = \get_term($rm_id, 'category');
        if ($term instanceof \WP_Term) {
            return $term;
        }
    }

    return $categories[0];
}
