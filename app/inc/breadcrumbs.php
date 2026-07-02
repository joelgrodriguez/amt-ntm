<?php
/**
 * Breadcrumb trail data for deep single templates, products, and pages.
 *
 * Returns the ancestor path for the current post so the breadcrumbs
 * template part can render the desktop trail and the mobile parent-only
 * fallback. Pure trail data plus the matching BreadcrumbList JSON-LD
 * (emitted on wp_head only when no SEO plugin owns the head).
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Breadcrumbs;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Decode stored HTML entities so the template's esc_html() doesn't
 * double-encode them ("Roof &amp;amp; Wall…"). Term names and titles come
 * out of WordPress entity-encoded.
 */
function clean_label(string $label): string {
    return html_entity_decode($label, ENT_QUOTES, 'UTF-8');
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

    if ($post_type === 'product') {
        return for_product($post);
    }

    if ($post_type === 'page') {
        return for_page($post);
    }

    // Deep-single post types only. Anything else opts in by adding itself
    // to this list or getting its own branch above.
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
                'label' => clean_label((string) ($pto->labels->name ?? ucfirst($post_type))),
                'url'   => $archive_url,
            ];
        }
    }

    // Category ancestor for posts that carry one. Profiles, manuals, videos,
    // and standard posts all use the 'category' taxonomy.
    $primary_term = primary_term_for($post, 'category');
    if ($primary_term instanceof \WP_Term) {
        $term_link = get_term_link($primary_term);
        if (!is_wp_error($term_link) && is_string($term_link)) {
            $items[] = [
                'label' => clean_label($primary_term->name),
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
        'current' => clean_label((string) get_the_title($post)),
    ];
}

/**
 * Trail for a WooCommerce product.
 *
 * Products live under /machines/%product_cat%/, so the trail mirrors the
 * URL: Machines > [category lineage] > Product. Category crumbs link to
 * the product_cat term archives the footer already exposes.
 */
function for_product(\WP_Post $post): ?array {
    $items = [[
        // Static label: the /machines/ page's real title is a paragraph.
        'label' => __('Machines', 'standard'),
        'url'   => home_url('/machines/'),
    ]];

    $primary_term = primary_term_for($post, 'product_cat');
    if ($primary_term instanceof \WP_Term) {
        $lineage   = array_reverse(get_ancestors($primary_term->term_id, 'product_cat', 'taxonomy'));
        $lineage[] = $primary_term->term_id;

        foreach ($lineage as $term_id) {
            $term = get_term((int) $term_id, 'product_cat');
            if (!$term instanceof \WP_Term) {
                continue;
            }
            $term_link = get_term_link($term);
            if (is_string($term_link)) {
                $items[] = [
                    'label' => clean_label($term->name),
                    'url'   => $term_link,
                ];
            }
        }
    }

    return [
        'items'   => $items,
        'parent'  => end($items) ?: null,
        'current' => clean_label((string) get_the_title($post)),
    ];
}

/**
 * Trail for a page, built from its ancestry.
 *
 * Top-level pages (and the front page) return null: with Home omitted from
 * the visual trail by design, a parentless page has nothing to show.
 */
function for_page(\WP_Post $post): ?array {
    if (is_front_page()) {
        return null;
    }

    $items = [];

    foreach (array_reverse(get_post_ancestors($post)) as $ancestor_id) {
        $ancestor = get_post((int) $ancestor_id);
        if (!$ancestor instanceof \WP_Post || $ancestor->post_status !== 'publish') {
            continue;
        }
        $items[] = [
            'label' => crumb_label($ancestor),
            'url'   => (string) get_permalink($ancestor),
        ];
    }

    if (empty($items)) {
        return null;
    }

    return [
        'items'   => $items,
        'parent'  => end($items) ?: null,
        'current' => crumb_label($post),
    ];
}

/**
 * Crumb-friendly label for a page.
 *
 * Prefers Yoast's per-post breadcrumb title, then a short theme override
 * (some legacy page titles are SEO paragraphs, not labels), then the title.
 */
function crumb_label(\WP_Post $post): string {
    $bctitle = trim((string) get_post_meta($post->ID, '_yoast_wpseo_bctitle', true));
    if ($bctitle !== '') {
        return clean_label($bctitle);
    }

    $overrides = [
        'machines' => __('Machines', 'standard'),
    ];
    if (isset($overrides[$post->post_name])) {
        return $overrides[$post->post_name];
    }

    return clean_label((string) get_the_title($post));
}

/**
 * Pick the most representative term for a post in a taxonomy.
 *
 * Prefers a Yoast / SEO plugin "primary term" if one is set, then falls
 * back to the first term attached to the post. Returns null when the
 * post type doesn't use the taxonomy or none is attached.
 */
function primary_term_for(\WP_Post $post, string $taxonomy): ?\WP_Term {
    if (!is_object_in_taxonomy($post->post_type, $taxonomy)) {
        return null;
    }

    $primary_id = (int) get_post_meta($post->ID, '_yoast_wpseo_primary_' . $taxonomy, true);
    if ($primary_id > 0) {
        $term = get_term($primary_id, $taxonomy);
        if ($term instanceof \WP_Term) {
            return $term;
        }
    }

    $terms = get_the_terms($post, $taxonomy);
    if (is_array($terms) && !empty($terms)) {
        return $terms[0] instanceof \WP_Term ? $terms[0] : null;
    }

    return null;
}

/**
 * BreadcrumbList JSON-LD for the current view.
 *
 * Emits only where the visual trail renders, and only when no SEO plugin
 * is active — Yoast/Rank Math/SEOPress ship their own BreadcrumbList on
 * singular views, and duplicating it confuses rich-result parsing.
 *
 * Home is included here (position 1) even though the visual trail omits
 * it: schema describes the full hierarchy; the logo covers Home for users.
 */
function render_schema(): void {
    if (\Standard\Seo\seo_plugin_active()) {
        return;
    }

    $trail = for_current_post();
    if ($trail === null) {
        return;
    }

    $position = 1;
    $elements = [[
        '@type'    => 'ListItem',
        'position' => $position++,
        'name'     => __('Home', 'standard'),
        'item'     => home_url('/'),
    ]];

    foreach ($trail['items'] as $item) {
        $elements[] = [
            '@type'    => 'ListItem',
            'position' => $position++,
            'name'     => $item['label'],
            'item'     => $item['url'],
        ];
    }

    $elements[] = [
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => $trail['current'],
        'item'     => (string) get_permalink(),
    ];

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $elements,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, \Standard\Seo\SCHEMA_JSON_FLAGS) . '</script>' . "\n";
}

add_action('wp_head', __NAMESPACE__ . '\\render_schema', 6);
