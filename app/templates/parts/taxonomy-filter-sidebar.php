<?php
/**
 * Taxonomy filter sidebar (thin caller).
 *
 * Historical entry point used by archive.php, page-profiles.php,
 * page-manuals.php, single-manual.php and template-articles.php.
 * Translates the legacy `sections` shape into the normalized groups
 * schema accepted by templates/parts/filter-sidebar.php, then delegates.
 *
 * Args (legacy)
 * -------------
 *  sections   : array<int, array{title:string, icon:string, terms:WP_Term[], current_terms:WP_Term[]}>
 *  post_type  : string  scope term archives to this post_type via ?post_type=
 *  back_url   : string
 *  back_label : string
 *
 * @package Standard
 *
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Filters\build_term_link_group;

$sections   = isset($args['sections']) && is_array($args['sections']) ? $args['sections'] : [];
$post_type  = isset($args['post_type']) ? sanitize_key((string) $args['post_type']) : '';
$back_url   = isset($args['back_url']) ? (string) $args['back_url'] : '';
$back_label = isset($args['back_label']) ? (string) $args['back_label'] : '';

if (!function_exists('Standard\\Filters\\build_term_link_group')) {
    require_once get_template_directory() . '/inc/filters.php';
}

$groups = [];
$index = 0;

foreach ($sections as $section) {
    if (!is_array($section)) {
        continue;
    }

    $terms = isset($section['terms']) && is_array($section['terms']) ? $section['terms'] : [];
    if ($terms === []) {
        continue;
    }

    $current = isset($section['current_terms']) && is_array($section['current_terms']) ? $section['current_terms'] : [];
    $active_ids = [];
    foreach ($current as $current_term) {
        if ($current_term instanceof WP_Term) {
            $active_ids[] = (int) $current_term->term_id;
        }
    }

    $groups[] = build_term_link_group(
        'tax-' . $index++,
        (string) ($section['title'] ?? ''),
        $terms,
        $active_ids,
        (string) ($section['icon'] ?? ''),
        $post_type
    );
}

if ($groups === []) {
    return;
}

get_template_part('templates/parts/filter-sidebar', null, [
    'groups'       => $groups,
    'show_actions' => false,
    'back_url'     => $back_url,
    'back_label'   => $back_label,
    'drawer_label' => __('Filters', 'standard'),
    'aria_label'   => __('Filters', 'standard'),
]);
