<?php
/**
 * Learning Center query helpers.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\LearningCenter;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return string[]
 */
function get_post_types(): array {
    return ['post', 'video', 'resource', 'download'];
}

/**
 * @param string|string[] $post_type
 */
function get_latest_query(int $count = 4, string|array $post_type = [], string $category_slug = ''): \WP_Query {
    $query_args = [
        'post_type'      => $post_type ?: get_post_types(),
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
        'no_found_rows'  => true,
    ];

    if ($category_slug !== '') {
        $query_args['category_name'] = $category_slug;
    }

    return new \WP_Query($query_args);
}

function get_featured_query(): \WP_Query {
    return get_latest_query(1);
}

function get_recent_query(int $exclude_id = 0, int $count = 4): \WP_Query {
    $query_args = [
        'post_type'      => get_post_types(),
        'posts_per_page' => $count,
        'post_status'    => 'publish',
        'no_found_rows'  => true,
    ];

    if ($exclude_id > 0) {
        $query_args['post__not_in'] = [$exclude_id];
    }

    return new \WP_Query($query_args);
}

function get_section_query(string $post_type, int $count = 4): \WP_Query {
    return get_latest_query($count, $post_type);
}

/**
 * @return array<int, array{title: string, post_type: string, icon: string, link: string|false, link_text: string}>
 */
function get_content_sections(): array {
    return [
        [
            'title'     => \__('Latest Articles', 'standard'),
            'post_type' => 'post',
            'icon'      => 'file-text',
            'link'      => \home_url('/learning-center/articles/'),
            'link_text' => \__('View All Articles', 'standard'),
        ],
        [
            'title'     => \__('Latest Videos', 'standard'),
            'post_type' => 'video',
            'icon'      => 'play',
            'link'      => \get_post_type_archive_link('video'),
            'link_text' => \__('View All Videos', 'standard'),
        ],
        [
            'title'     => \__('Latest Resources', 'standard'),
            'post_type' => 'resource',
            'icon'      => 'folder',
            'link'      => \get_post_type_archive_link('resource'),
            'link_text' => \__('View All Resources', 'standard'),
        ],
        [
            'title'     => \__('Latest Downloads', 'standard'),
            'post_type' => 'download',
            'icon'      => 'download',
            'link'      => \get_post_type_archive_link('download'),
            'link_text' => \__('View All Downloads', 'standard'),
        ],
    ];
}

/**
 * @return array<string, array{icon: string, label: string, cta: string}>
 */
function get_type_config(): array {
    return [
        'post'     => ['icon' => 'file-text', 'label' => \__('Article', 'standard'), 'cta' => \__('Read Article', 'standard')],
        'video'    => ['icon' => 'play', 'label' => \__('Video', 'standard'), 'cta' => \__('Watch Video', 'standard')],
        'resource' => ['icon' => 'folder', 'label' => \__('Resource', 'standard'), 'cta' => \__('View Resource', 'standard')],
        'download' => ['icon' => 'download', 'label' => \__('Download', 'standard'), 'cta' => \__('View Download', 'standard')],
    ];
}

function get_type_icon(string $post_type): string {
    $config = get_type_config();
    return $config[$post_type]['icon'] ?? 'file-text';
}

function get_type_label(string $post_type): string {
    $config = get_type_config();
    return $config[$post_type]['label'] ?? '';
}

function get_type_cta(string $post_type): string {
    $config = get_type_config();
    return $config[$post_type]['cta'] ?? \__('Read More', 'standard');
}
