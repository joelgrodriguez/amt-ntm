<?php
/**
 * Breadcrumb trail data for deep single templates.
 *
 * Returns the ancestor path for the current post so the breadcrumbs
 * template part can render the desktop trail and the mobile parent-only
 * fallback. Pure data; no markup, no globals.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Breadcrumbs;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Build the trail for the current single post.
 *
 * Shape:
 *   [
 *     'items'  => [ ['label' => 'Resources', 'url' => '...'], ... ],
 *     'parent' => ['label' => 'Roof & Wall Panel Machines', 'url' => '...'] | null,
 *     'current'=> 'SSQ II MultiPro',
 *   ]
 *
 * 'items' carries every ancestor from broadest to nearest (Home is omitted —
 * the logo already owns that affordance). 'parent' is the last ancestor,
 * surfaced separately for the mobile parent-only collapse.
 *
 * Returns null when the trail has fewer than two segments (current page only),
 * which signals the template part to render nothing.
 *
 * @return array{items: array<int, array{label: string, url: string}>, parent: ?array{label: string, url: string}, current: string}|null
 */
function for_current_post(): ?array {
    if (!is_singular()) {
        return null;
    }

    $post = get_post();
    if (!$post instanceof \WP_Post) {
        return null;
    }

    $post_type = (string) $post->post_type;

    // Deep-single post types only. Anything else (pages, products, custom
    // marketing CPTs) opts in by adding itself to this list.
    $supported = ['post', 'profile', 'resource', 'download', 'video', 'manual'];
    if (!in_array($post_type, $supported, true)) {
        return null;
    }

    $items = [];

    // Post-type archive ancestor (e.g. "Resources", "Manuals", "Videos").
    $pto = get_post_type_object($post_type);
    if ($pto instanceof \WP_Post_Type) {
        $archive_url = get_post_type_archive_link($post_type);
        if (is_string($archive_url) && $archive_url !== '') {
            $items[] = [
                'label' => (string) ($pto->labels->name ?? ucfirst($post_type)),
                'url'   => $archive_url,
            ];
        }
    }

    // Category ancestor for posts that carry one. Profiles, manuals, videos,
    // and standard posts all use the 'category' taxonomy.
    $primary_term = primary_category_for($post);
    if ($primary_term instanceof \WP_Term) {
        $term_link = get_term_link($primary_term);
        if (!is_wp_error($term_link) && is_string($term_link)) {
            $items[] = [
                'label' => $primary_term->name,
                'url'   => $term_link,
            ];
        }
    }

    if (empty($items)) {
        return null;
    }

    return [
        'items'   => $items,
        'parent'  => end($items) ?: null,
        'current' => (string) get_the_title($post),
    ];
}

/**
 * Pick the most representative category for a post.
 *
 * Prefers a Yoast / SEO plugin "primary category" if one is set, then falls
 * back to the first category attached to the post. Returns null when the
 * post type doesn't use the category taxonomy or none is attached.
 */
function primary_category_for(\WP_Post $post): ?\WP_Term {
    if (!is_object_in_taxonomy($post->post_type, 'category')) {
        return null;
    }

    $primary_id = (int) get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);
    if ($primary_id > 0) {
        $term = get_term($primary_id, 'category');
        if ($term instanceof \WP_Term) {
            return $term;
        }
    }

    $terms = get_the_terms($post, 'category');
    if (is_array($terms) && !empty($terms)) {
        return $terms[0] instanceof \WP_Term ? $terms[0] : null;
    }

    return null;
}
