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

use function Standard\Filters\build_choice_group;

/**
 * @return array{category: string, machine: string, type: string}
 */
function get_active_filters(): array {
    $category = get_filter_query_value('lc_category') ?: get_filter_query_value('category');
    $machine = get_filter_query_value('lc_machine') ?: get_filter_query_value('post_tag') ?: get_filter_query_value('tag');
    $type = get_filter_query_value('lc_type') ?: get_filter_query_value('post_type');

    return [
        'category' => $category,
        'machine'  => $machine,
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
 * Learning Center's canonical type filter labels.
 *
 * This is intentionally plural/user-facing. get_type_config() is singular
 * card metadata; filters need the rail labels users scan.
 *
 * @return array<string, string>
 */
function get_type_filter_options(bool $include_all = true, string $all_label = ''): array {
    $options = $include_all
        ? ['' => $all_label !== '' ? $all_label : \__('All resources', 'standard')]
        : [];

    return $options + [
        'post'       => \__('Articles', 'standard'),
        'video'      => \__('Videos', 'standard'),
        'manual'     => \__('Manuals', 'standard'),
        'profile'    => \__('Profiles', 'standard'),
        'resource'   => \__('Resources', 'standard'),
        'download'   => \__('Downloads', 'standard'),
        'literature' => \__('Literature', 'standard'),
    ];
}

/**
 * @return array<string, string>
 */
function get_category_filter_options(bool $include_all = true): array {
    $options = $include_all ? ['' => \__('All categories', 'standard')] : [];

    foreach (get_allowed_categories() as $category) {
        if ($category instanceof \WP_Term) {
            $options[$category->slug] = $category->name;
        }
    }

    return $options;
}

/**
 * @return array<string, string>
 */
function get_machine_filter_options(bool $include_all = true): array {
    $options = $include_all ? ['' => \__('All machines', 'standard')] : [];

    if (!\function_exists('Standard\\MachinesData\\get_machine_post_tags')) {
        return $options;
    }

    foreach (\Standard\MachinesData\get_machine_post_tags() as $tag) {
        if ($tag instanceof \WP_Term) {
            $options[$tag->slug] = $tag->name;
        }
    }

    return $options;
}

/**
 * Build the canonical Learning Center filter groups for GET forms.
 *
 * @param array{category?: string, machine?: string, type?: string} $filters
 * @param array{
 *   names?: array{category?: string, type?: string, machine?: string},
 *   type_options?: array<string, string>,
 *   all_type_label?: string,
 * } $args
 * @return array<int, array<string, mixed>>
 */
function get_filter_groups(array $filters = [], array $args = []): array {
    if (!\function_exists('Standard\\Filters\\build_choice_group')) {
        require_once \get_template_directory() . '/inc/filters.php';
    }

    $names = \is_array($args['names'] ?? null) ? $args['names'] : [];
    $category_name = (string) ($names['category'] ?? 'lc_category');
    $type_name     = (string) ($names['type'] ?? 'lc_type');
    $machine_name  = (string) ($names['machine'] ?? 'lc_machine');

    $type_options = \is_array($args['type_options'] ?? null)
        ? $args['type_options']
        : get_type_filter_options(true, (string) ($args['all_type_label'] ?? ''));

    $groups = [
        build_choice_group(
            'lc-category',
            \__('Category', 'standard'),
            $category_name,
            get_category_filter_options(),
            [(string) ($filters['category'] ?? '')],
            [],
            'folder',
            'radio'
        ),
        build_choice_group(
            'lc-type',
            \__('Resource Type', 'standard'),
            $type_name,
            $type_options,
            [(string) ($filters['type'] ?? '')],
            [],
            'file-text',
            'radio'
        ),
    ];

    $machine_options = get_machine_filter_options();
    if (count($machine_options) > 1) {
        $groups[] = build_choice_group(
            'lc-machine',
            \__('Machine', 'standard'),
            $machine_name,
            $machine_options,
            [(string) ($filters['machine'] ?? '')],
            [],
            'settings',
            'radio'
        );
    }

    return $groups;
}

/**
 * Build canonical Learning Center link groups for non-form sidebars.
 *
 * Used on single content templates and archive rails where a click should
 * navigate immediately instead of checking an input and waiting for Apply.
 *
 * @param array{category?: string, machine?: string, type?: string} $active
 * @param array{category?: string, machine?: string, type?: string} $preserve
 * @return array<int, array<string, mixed>>
 */
function get_filter_link_groups(array $active = [], array $preserve = []): array {
    $build_url = static function (array $params) use ($preserve): string {
        $query = [];

        foreach (['category' => 'lc_category', 'type' => 'lc_type', 'machine' => 'lc_machine'] as $key => $param) {
            $value = (string) ($params[$key] ?? $preserve[$key] ?? '');
            if ($value !== '') {
                $query[$param] = $value;
            }
        }

        return \add_query_arg($query, get_learning_center_url());
    };

    $groups = [];

    $category_options = [];
    foreach (get_category_filter_options(false) as $slug => $label) {
        $category_options[] = [
            'value'  => $slug,
            'label'  => $label,
            'count'  => null,
            'active' => (string) ($active['category'] ?? '') === $slug,
            'url'    => $build_url(['category' => $slug]),
        ];
    }

    if ($category_options !== []) {
        $groups[] = [
            'id'      => 'lc-category',
            'title'   => \__('Category', 'standard'),
            'icon'    => 'folder',
            'mode'    => 'link',
            'name'    => null,
            'options' => $category_options,
        ];
    }

    $type_options = [];
    foreach (get_type_filter_options(false) as $slug => $label) {
        $type_options[] = [
            'value'  => $slug,
            'label'  => $label,
            'count'  => null,
            'active' => (string) ($active['type'] ?? '') === $slug,
            'url'    => $build_url(['type' => $slug]),
        ];
    }

    if ($type_options !== []) {
        $groups[] = [
            'id'      => 'lc-type',
            'title'   => \__('Resource Type', 'standard'),
            'icon'    => 'file-text',
            'mode'    => 'link',
            'name'    => null,
            'options' => $type_options,
        ];
    }

    $machine_options = [];
    foreach (get_machine_filter_options(false) as $slug => $label) {
        $machine_options[] = [
            'value'  => $slug,
            'label'  => $label,
            'count'  => null,
            'active' => (string) ($active['machine'] ?? '') === $slug,
            'url'    => $build_url(['machine' => $slug]),
        ];
    }

    if ($machine_options !== []) {
        $groups[] = [
            'id'      => 'lc-machine',
            'title'   => \__('Machine', 'standard'),
            'icon'    => 'settings',
            'mode'    => 'link',
            'name'    => null,
            'options' => $machine_options,
        ];
    }

    return $groups;
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
