<?php
/**
 * Learning Center request filters.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\LearningCenter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return array{category: string, machine: string, type: string}
 */
function get_active_filters(): array {
    $type = get_filter_query_value('lc_type');

    return [
        'category' => get_filter_query_value('lc_category'),
        'machine'  => get_filter_query_value('lc_machine'),
        'type'     => in_array($type, get_post_types(), true) ? $type : '',
    ];
}

function get_learning_center_url(): string {
    $posts_page_id = (int) \get_option('page_for_posts');

    if ($posts_page_id > 0) {
        return \get_permalink($posts_page_id) ?: \Standard\Url\internal('/');
    }

    return \Standard\Url\internal('/');
}

function get_filter_query_value(string $key): string {
    if (!isset($_GET[$key])) {
        return '';
    }

    $value = \wp_unslash($_GET[$key]);

    return is_string($value) ? \sanitize_key($value) : '';
}

/**
 * @param array<int, array{title: string, post_type: string, icon: string, link: string|false, link_text: string}> $sections
 * @param array{type?: string} $filters
 * @return array<int, array{title: string, post_type: string, icon: string, link: string|false, link_text: string}>
 */
function filter_content_sections(array $sections, array $filters): array {
    $type = (string) ($filters['type'] ?? '');

    if ($type === '') {
        return $sections;
    }

    return array_values(array_filter(
        $sections,
        fn(array $section): bool => $section['post_type'] === $type
    ));
}
