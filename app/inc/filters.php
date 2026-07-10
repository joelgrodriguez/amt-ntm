<?php
/**
 * Filter sidebar primitives.
 *
 * The PHP side of templates/parts/filter-sidebar.php. Two jobs:
 *
 *   1. Normalize "group" shapes into a stable record the template can
 *      render without conditionals (build_*_group helpers).
 *   2. Cheap, request-scoped count caches (post type + term) so the
 *      sidebar never hits the DB N times for the same number.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Filters;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Published-post counts keyed by post type, computed once per request.
 *
 * @return array<string, int>
 */
function get_post_type_counts(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    foreach (\get_post_types(['public' => true], 'names') as $post_type) {
        $counts = \wp_count_posts((string) $post_type);
        $cache[(string) $post_type] = is_object($counts) && isset($counts->publish)
            ? (int) $counts->publish
            : 0;
    }

    return $cache;
}

function get_post_type_count(string $post_type): int {
    $counts = get_post_type_counts();

    return $counts[$post_type] ?? 0;
}

/**
 * Build a checkbox/radio group from a (slug => label) map.
 *
 * @param array<string, string> $options    [value => display label]
 * @param string[]              $active     selected values
 * @param array<string, int>    $counts     optional [value => count]
 * @return array<string, mixed>
 */
function build_choice_group(
    string $id,
    string $title,
    string $name,
    array $options,
    array $active = [],
    array $counts = [],
    string $icon = '',
    string $mode = 'checkbox'
): array {
    $records = [];

    foreach ($options as $value => $label) {
        $value = (string) $value;
        $records[] = [
            'value'  => $value,
            'label'  => (string) $label,
            'count'  => array_key_exists($value, $counts) ? (int) $counts[$value] : null,
            'active' => in_array($value, $active, true),
        ];
    }

    return [
        'id'      => $id,
        'title'   => $title,
        'icon'    => $icon,
        'mode'    => $mode === 'radio' ? 'radio' : 'checkbox',
        'name'    => $name,
        'options' => $records,
    ];
}

/**
 * Build a checkbox/radio group from WP_Term objects.
 *
 * @param \WP_Term[] $terms
 * @param string[]   $active_slugs
 * @return array<string, mixed>
 */
function build_term_choice_group(
    string $id,
    string $title,
    string $name,
    array $terms,
    array $active_slugs = [],
    string $icon = '',
    string $mode = 'checkbox'
): array {
    $options = [];
    $counts = [];

    foreach ($terms as $term) {
        if (!$term instanceof \WP_Term) {
            continue;
        }

        $options[$term->slug] = $term->name;
        $counts[$term->slug] = (int) $term->count;
    }

    return build_choice_group($id, $title, $name, $options, $active_slugs, $counts, $icon, $mode);
}

/**
 * Build a pure-link group (taxonomy archive nav). Each option supplies
 * its own URL; no form submit.
 *
 * @param \WP_Term[] $terms
 * @param int[]      $active_ids
 * @return array<string, mixed>
 */
function build_term_link_group(
    string $id,
    string $title,
    array $terms,
    array $active_ids = [],
    string $icon = '',
    string $post_type = ''
): array {
    $options = [];

    foreach ($terms as $term) {
        if (!$term instanceof \WP_Term) {
            continue;
        }

        $url = \get_term_link($term);
        if (\is_wp_error($url)) {
            continue;
        }

        if ($post_type !== '') {
            $url = \add_query_arg(['post_type' => $post_type], (string) $url);
        }

        $options[] = [
            'value'  => $term->slug,
            'label'  => $term->name,
            // Term ->count is the GLOBAL count across all post types — misleading in the
            // scoped contexts this builder serves (e.g. a manual-only catalog). Suppress;
            // callers with accurate numbers use build_choice_group's $counts instead.
            'count'  => null,
            'active' => in_array((int) $term->term_id, $active_ids, true),
            'url'    => (string) $url,
        ];
    }

    return [
        'id'      => $id,
        'title'   => $title,
        'icon'    => $icon,
        'mode'    => 'link',
        'name'    => null,
        'options' => $options,
    ];
}

/**
 * Pull the active labels off a normalized group, for the chip strip.
 *
 * @param array<string, mixed> $group
 * @return string[]
 */
function get_active_labels(array $group): array {
    $labels = [];

    foreach ($group['options'] ?? [] as $option) {
        if (!empty($option['active']) && !empty($option['label'])) {
            $labels[] = (string) $option['label'];
        }
    }

    return $labels;
}
