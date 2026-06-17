<?php
/**
 * Service Hub taxonomy, queries, and import tooling.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ServiceHub;

if (!defined('ABSPATH')) {
    exit;
}

const TAXONOMY = 'content_department';
const SERVICE_TERM_SLUG = 'service-repair';
const CACHE_GROUP = 'standard_service_hub';
const CACHE_TTL = 15 * MINUTE_IN_SECONDS;

/**
 * @return string[]
 */
function get_post_types(): array {
    return [
        'post',
        'knowledgebase',
        'video',
        'resource',
        'download',
        'manual',
        'literature',
        'footprint',
        'cutlist',
        'page',
    ];
}

/**
 * @return array<string, string>
 */
function get_department_terms(): array {
    return [
        SERVICE_TERM_SLUG => \__('Service & Repair', 'standard'),
        'sales'           => \__('Sales', 'standard'),
        'training'        => \__('Training', 'standard'),
    ];
}

/**
 * @return array<string, array{label: string, icon: string}>
 */
function get_post_type_options(): array {
    return [
        'post'          => ['label' => \__('Articles', 'standard'), 'icon' => 'file-text'],
        'knowledgebase' => ['label' => \__('Troubleshooting', 'standard'), 'icon' => 'life-buoy'],
        'video'      => ['label' => \__('Videos', 'standard'), 'icon' => 'play'],
        'resource'   => ['label' => \__('Resources', 'standard'), 'icon' => 'folder'],
        'download'   => ['label' => \__('Downloads', 'standard'), 'icon' => 'download'],
        'manual'     => ['label' => \__('Manuals', 'standard'), 'icon' => 'file-text'],
        'literature' => ['label' => \__('Literature', 'standard'), 'icon' => 'folder'],
        'footprint'  => ['label' => \__('Footprints', 'standard'), 'icon' => 'settings'],
        'cutlist'    => ['label' => \__('Cutlists', 'standard'), 'icon' => 'file-text'],
        'page'       => ['label' => \__('Pages', 'standard'), 'icon' => 'link'],
    ];
}

function get_post_type_label(string $post_type): string {
    $options = get_post_type_options();

    return $options[$post_type]['label'] ?? $post_type;
}

function register_department_taxonomy(): void {
    \register_taxonomy(TAXONOMY, get_post_types(), [
        'labels'             => [
            'name'                       => \__('Departments', 'standard'),
            'singular_name'              => \__('Department', 'standard'),
            'search_items'               => \__('Search Departments', 'standard'),
            'all_items'                  => \__('All Departments', 'standard'),
            'edit_item'                  => \__('Edit Department', 'standard'),
            'update_item'                => \__('Update Department', 'standard'),
            'add_new_item'               => \__('Add New Department', 'standard'),
            'new_item_name'              => \__('New Department Name', 'standard'),
            'menu_name'                  => \__('Departments', 'standard'),
            'popular_items'              => \__('Popular Departments', 'standard'),
            'separate_items_with_commas' => \__('Separate departments with commas', 'standard'),
            'add_or_remove_items'        => \__('Add or remove departments', 'standard'),
            'choose_from_most_used'      => \__('Choose from the most used departments', 'standard'),
            'not_found'                  => \__('No departments found.', 'standard'),
        ],
        'public'             => true,
        'hierarchical'       => true,
        'show_ui'            => true,
        'show_admin_column'  => true,
        'show_in_quick_edit' => true,
        'show_in_rest'      => true,
        'query_var'          => true,
        'rewrite'            => [
            'slug'       => 'department',
            'with_front' => false,
        ],
    ]);
}
add_action('init', __NAMESPACE__ . '\\register_department_taxonomy', 20);

function ensure_department_terms(): void {
    if (!\taxonomy_exists(TAXONOMY)) {
        return;
    }

    foreach (get_department_terms() as $slug => $name) {
        if (\term_exists($slug, TAXONOMY)) {
            continue;
        }

        \wp_insert_term($name, TAXONOMY, ['slug' => $slug]);
    }
}
add_action('init', __NAMESPACE__ . '\\ensure_department_terms', 30);

/**
 * @return array<int, array{taxonomy: string, field: string, terms: string[]}>
 */
function get_service_tax_query(): array {
    return [[
        'taxonomy' => TAXONOMY,
        'field'    => 'slug',
        'terms'    => [SERVICE_TERM_SLUG],
    ]];
}

/**
 * @return array<string, array{label: string, orderby: string, order: string}>
 */
function get_sort_options(): array {
    return [
        'newest' => ['label' => \__('Newest first', 'standard'),    'orderby' => 'date',  'order' => 'DESC'],
        'oldest' => ['label' => \__('Oldest first', 'standard'),    'orderby' => 'date',  'order' => 'ASC'],
        'az'     => ['label' => \__('Title A to Z', 'standard'),    'orderby' => 'title', 'order' => 'ASC'],
        'za'     => ['label' => \__('Title Z to A', 'standard'),    'orderby' => 'title', 'order' => 'DESC'],
    ];
}

/**
 * @return array{search: string, type: string, category: string, machine: string, sort: string}
 */
function get_active_filters(): array {
    $type = get_query_value('service_type');
    $sort = get_query_value('service_sort');

    return [
        'search'   => get_query_value('service_search', 'text'),
        'type'     => \in_array($type, get_post_types(), true) ? $type : '',
        'category' => get_query_value('service_category'),
        'machine'  => get_query_value('service_machine'),
        'sort'     => \array_key_exists($sort, get_sort_options()) ? $sort : '',
    ];
}

function get_query_value(string $key, string $sanitize = 'key'): string {
    if (!isset($_GET[$key])) {
        return '';
    }

    $value = \wp_unslash($_GET[$key]);
    if (!\is_string($value)) {
        return '';
    }

    return $sanitize === 'text'
        ? \sanitize_text_field($value)
        : \sanitize_key($value);
}

/**
 * @param array{search?: string, type?: string, category?: string, machine?: string} $filters
 * @return string|string[]
 */
function get_filtered_post_type(array $filters) {
    $type = (string) ($filters['type'] ?? '');

    return \in_array($type, get_post_types(), true) ? $type : get_post_types();
}

/**
 * @param array{search?: string, type?: string, category?: string, machine?: string} $filters
 * @return array<string, mixed>
 */
function get_query_args(array $filters, int $paged = 1, int $per_page = 12): array {
    $tax_query = [
        'relation' => 'AND',
        get_service_tax_query()[0],
    ];

    if (!empty($filters['category'])) {
        $tax_query[] = [
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => [(string) $filters['category']],
        ];
    }

    if (!empty($filters['machine'])) {
        $tax_query[] = [
            'taxonomy' => 'post_tag',
            'field'    => 'slug',
            'terms'    => [(string) $filters['machine']],
        ];
    }

    $args = [
        'post_type'           => get_filtered_post_type($filters),
        'post_status'         => 'publish',
        'posts_per_page'      => $per_page,
        'paged'               => \max(1, $paged),
        'ignore_sticky_posts' => true,
        'tax_query'           => $tax_query,
    ];

    if (!empty($filters['search'])) {
        $args['s'] = (string) $filters['search'];
        // Relevanssi (the site search engine) intercepts any WP_Query with `s`
        // set. On a SECONDARY query it half-hooks and returns nothing unless we
        // opt in explicitly — so without this flag, keyword searches here return
        // 0 results even when matches exist. `relevanssi => true` runs the query
        // through Relevanssi properly and still honors our tax_query (department
        // + machine + category). Only set it when there's actually a keyword;
        // filter-only queries use plain WP_Query.
        if (\function_exists('relevanssi_do_query')) {
            $args['relevanssi'] = true;
        }
    }

    $sort_options = get_sort_options();
    $sort_key = (string) ($filters['sort'] ?? '');
    if ($sort_key !== '' && isset($sort_options[$sort_key])) {
        $args['orderby'] = $sort_options[$sort_key]['orderby'];
        $args['order'] = $sort_options[$sort_key]['order'];
    } elseif (empty($filters['search'])) {
        $args['orderby'] = 'date';
        $args['order'] = 'DESC';
    }

    return $args;
}

/**
 * @param array{search?: string, type?: string, category?: string, machine?: string} $filters
 */
function get_results_query(array $filters, int $paged = 1, int $per_page = 12): \WP_Query {
    return new \WP_Query(get_query_args($filters, $paged, $per_page));
}

function get_service_count(string $post_type = ''): int {
    $cache_key = 'count:' . ($post_type !== '' ? $post_type : '_all');
    $cached = \wp_cache_get($cache_key, CACHE_GROUP);
    if ($cached !== false) {
        return (int) $cached;
    }

    $query = new \WP_Query([
        'post_type'           => $post_type !== '' ? $post_type : get_post_types(),
        'post_status'         => 'publish',
        'posts_per_page'      => 1,
        'fields'              => 'ids',
        'ignore_sticky_posts' => true,
        'tax_query'           => get_service_tax_query(),
    ]);

    $count = (int) $query->found_posts;
    \wp_cache_set($cache_key, $count, CACHE_GROUP, CACHE_TTL);

    return $count;
}

/**
 * @return array<string, int>
 */
function get_post_type_counts(): array {
    $cached = \wp_cache_get('post_type_counts', CACHE_GROUP);
    if (\is_array($cached)) {
        return $cached;
    }

    $counts = [];
    foreach (get_post_types() as $post_type) {
        $counts[$post_type] = get_service_count($post_type);
    }

    \wp_cache_set('post_type_counts', $counts, CACHE_GROUP, CACHE_TTL);

    return $counts;
}

/**
 * @return int[]
 */
function get_service_post_ids(int $limit = 1000): array {
    $cache_key = 'post_ids:' . $limit;
    $cached = \wp_cache_get($cache_key, CACHE_GROUP);
    if (\is_array($cached)) {
        return $cached;
    }

    $ids = \get_posts([
        'post_type'              => get_post_types(),
        'post_status'            => 'publish',
        'posts_per_page'         => $limit,
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'ignore_sticky_posts'    => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'tax_query'              => get_service_tax_query(),
    ]);

    $ids = \array_map('intval', $ids);
    \wp_cache_set($cache_key, $ids, CACHE_GROUP, CACHE_TTL);

    return $ids;
}

/**
 * @return \WP_Term[]
 */
function get_terms_for_service_content(string $taxonomy, int $limit = 30): array {
    if (!\taxonomy_exists($taxonomy)) {
        return [];
    }

    $cache_key = 'terms:' . $taxonomy . ':' . $limit;
    $cached = \wp_cache_get($cache_key, CACHE_GROUP);
    if (\is_array($cached)) {
        return $cached;
    }

    $post_ids = get_service_post_ids();
    if ($post_ids === []) {
        \wp_cache_set($cache_key, [], CACHE_GROUP, CACHE_TTL);
        return [];
    }

    $terms = \get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => true,
        'object_ids' => $post_ids,
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => $limit,
    ]);

    $terms = \is_array($terms) ? $terms : [];
    \wp_cache_set($cache_key, $terms, CACHE_GROUP, CACHE_TTL);

    return $terms;
}

/**
 * Machine tags that actually have service-department content, in the canonical
 * machines-data order. Intersects the machine post-tags with the post_tag terms
 * attached to service posts — so the Machine filter never lists a machine that
 * returns zero results, and each option carries its service-only count.
 *
 * @return \WP_Term[]
 */
function get_machine_terms_for_service(): array {
    if (!\function_exists('Standard\\MachinesData\\get_machine_post_tags')) {
        return [];
    }

    // Service-scoped post_tag terms, keyed by slug, carry the service count.
    $service_counts = [];
    foreach (get_terms_for_service_content('post_tag', 200) as $term) {
        if ($term instanceof \WP_Term) {
            $service_counts[$term->slug] = (int) $term->count;
        }
    }

    if ($service_counts === []) {
        return [];
    }

    // Walk machine tags in canonical order; keep only those with service
    // content, and overwrite their count with the service-only count.
    $machines = [];
    foreach (\Standard\MachinesData\get_machine_post_tags() as $tag) {
        if (!$tag instanceof \WP_Term || !isset($service_counts[$tag->slug])) {
            continue;
        }

        $clone = clone $tag;
        $clone->count = $service_counts[$tag->slug];
        $machines[] = $clone;
    }

    return $machines;
}

/**
 * Categories that have service-department content, with service-only counts.
 *
 * @return \WP_Term[]
 */
function get_category_terms_for_service(): array {
    return get_terms_for_service_content('category', 50);
}

/**
 * Build the Service Hub filter sidebar groups: Machine first (the owner's
 * mental model), then Resource Type, then Category. Every option is scoped to
 * content that lives in the service department, so no filter ever returns zero
 * results. Field names match get_active_filters() so the GET form round-trips.
 *
 * @param array{search?: string, type?: string, category?: string, machine?: string} $filters
 * @param array<string, string> $type_options [post_type => label], pre-gated by count
 * @return array<int, array<string, mixed>>
 */
function get_filter_groups(array $filters, array $type_options): array {
    if (!\function_exists('Standard\\Filters\\build_choice_group')) {
        require_once \get_template_directory() . '/inc/filters.php';
    }

    $groups = [];

    $machine_terms = get_machine_terms_for_service();
    if ($machine_terms !== []) {
        // Machine first — the owner's mental model. An empty-value option acts
        // as "All machines" (the sidebar treats an empty radio value that way).
        $machine_options = ['' => \__('All machines', 'standard')];
        $machine_counts = [];
        foreach ($machine_terms as $term) {
            if ($term instanceof \WP_Term) {
                $machine_options[$term->slug] = $term->name;
                $machine_counts[$term->slug] = (int) $term->count;
            }
        }

        $groups[] = \Standard\Filters\build_choice_group(
            'service-machine',
            \__('Machine', 'standard'),
            'service_machine',
            $machine_options,
            [(string) ($filters['machine'] ?? '')],
            $machine_counts,
            'settings',
            'radio'
        );
    }

    $groups[] = \Standard\Filters\build_choice_group(
        'service-type',
        \__('Resource Type', 'standard'),
        'service_type',
        $type_options,
        [(string) ($filters['type'] ?? '')],
        [],
        'file-text',
        'radio'
    );

    $category_terms = get_category_terms_for_service();
    if ($category_terms !== []) {
        // Prepend an "All categories" sentinel via a plain choice group so the
        // radio can be cleared; term groups don't carry an empty option.
        $category_options = ['' => \__('All categories', 'standard')];
        $category_counts = [];
        foreach ($category_terms as $term) {
            if ($term instanceof \WP_Term) {
                $category_options[$term->slug] = $term->name;
                $category_counts[$term->slug] = (int) $term->count;
            }
        }

        $groups[] = \Standard\Filters\build_choice_group(
            'service-category',
            \__('Category', 'standard'),
            'service_category',
            $category_options,
            [(string) ($filters['category'] ?? '')],
            $category_counts,
            'folder',
            'radio'
        );
    }

    return $groups;
}

/**
 * Invalidate every service-hub cache key. Cheap; called rarely.
 *
 * Without an explicit key registry, wp_cache_flush_group() is the
 * surgical move when available, falling back to the brand-blunt
 * wp_cache_flush() for object caches that don't support group flushes
 * (the default in-request cache included).
 */
function flush_caches(): void {
    if (\function_exists('wp_cache_flush_group')) {
        \wp_cache_flush_group(CACHE_GROUP);
        return;
    }

    \wp_cache_flush();
}

function maybe_flush_on_save(int $post_id): void {
    if (\wp_is_post_revision($post_id) || \wp_is_post_autosave($post_id)) {
        return;
    }

    $post = \get_post($post_id);
    if (!$post instanceof \WP_Post) {
        return;
    }

    if (!\in_array($post->post_type, get_post_types(), true)) {
        return;
    }

    flush_caches();
}
\add_action('save_post', __NAMESPACE__ . '\\maybe_flush_on_save');
\add_action('deleted_post', __NAMESPACE__ . '\\maybe_flush_on_save');

/**
 * Flush when department / category / machine assignments shift, which
 * is what actually invalidates filter counts and term lists.
 *
 * @param int      $object_id
 * @param int[]    $terms
 * @param int[]    $tt_ids
 * @param string   $taxonomy
 */
function maybe_flush_on_term_change(int $object_id, array $terms, array $tt_ids, string $taxonomy): void {
    if (!\in_array($taxonomy, [TAXONOMY, 'category', 'post_tag'], true)) {
        return;
    }

    flush_caches();
}
\add_action('set_object_terms', __NAMESPACE__ . '\\maybe_flush_on_term_change', 10, 4);

/**
 * @return string[]
 */
function normalize_department_slugs(string $department): array {
    $department = \html_entity_decode(\trim($department), ENT_QUOTES | ENT_HTML5, 'UTF-8');

    if ($department === '' || \strtoupper($department) === 'NULL') {
        return [];
    }

    $map = [
        'service & repair'  => SERVICE_TERM_SLUG,
        'service and repair' => SERVICE_TERM_SLUG,
        'sales'             => 'sales',
        'training'          => 'training',
    ];

    $slugs = [];
    foreach (\array_map('trim', \explode(',', $department)) as $part) {
        $key = \strtolower($part);
        if (isset($map[$key])) {
            $slugs[] = $map[$key];
        }
    }

    return \array_values(\array_unique($slugs));
}

/**
 * @param string[] $args
 * @param array<string, mixed> $assoc_args
 */
function import_csv_command(array $args, array $assoc_args): void {
    if (!defined('WP_CLI') || !WP_CLI) {
        return;
    }

    $path = (string) ($args[0] ?? '');
    if ($path === '') {
        \WP_CLI::error('Usage: wp ntm service-hub import-csv /path/to/content.csv [--dry-run]');
    }

    if (!\is_readable($path)) {
        \WP_CLI::error("CSV is not readable: {$path}");
    }

    if (!\taxonomy_exists(TAXONOMY)) {
        register_department_taxonomy();
    }
    ensure_department_terms();

    $dry_run = (bool) \WP_CLI\Utils\get_flag_value($assoc_args, 'dry-run', false);
    $handle = \fopen($path, 'r');
    if (!$handle) {
        \WP_CLI::error("Could not open CSV: {$path}");
    }

    $header = \fgetcsv($handle);
    if (!\is_array($header)) {
        \fclose($handle);
        \WP_CLI::error('CSV is empty.');
    }

    $columns = \array_flip($header);
    foreach (['Post ID', 'Department'] as $required_column) {
        if (!isset($columns[$required_column])) {
            \fclose($handle);
            \WP_CLI::error("CSV is missing required column: {$required_column}");
        }
    }

    $synced = 0;
    $missing = 0;
    $failed = 0;
    $row_number = 1;

    while (($row = \fgetcsv($handle)) !== false) {
        $row_number++;
        $row = \array_pad($row, \count($header), '');

        $post_id = (int) ($row[$columns['Post ID']] ?? 0);
        if ($post_id <= 0) {
            $failed++;
            \WP_CLI::warning("Row {$row_number}: invalid Post ID.");
            continue;
        }

        $post = \get_post($post_id);
        if (!$post instanceof \WP_Post) {
            $missing++;
            continue;
        }

        $slugs = normalize_department_slugs((string) ($row[$columns['Department']] ?? ''));
        if (!$dry_run) {
            $result = \wp_set_object_terms($post_id, $slugs, TAXONOMY, false);
            if (\is_wp_error($result)) {
                $failed++;
                \WP_CLI::warning("Post {$post_id}: " . $result->get_error_message());
                continue;
            }
        }

        $synced++;
    }

    \fclose($handle);

    $mode = $dry_run ? 'Dry run complete' : 'Import complete';
    \WP_CLI::success("{$mode}: {$synced} synced, {$missing} missing, {$failed} failed.");
}

if (defined('WP_CLI') && WP_CLI) {
    \WP_CLI::add_command('ntm service-hub import-csv', __NAMESPACE__ . '\\import_csv_command');
}
