<?php
/**
 * Search query contract.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Search;

if (!defined('ABSPATH')) {
    exit;
}

const REST_NAMESPACE = 'standard/v1';
const REST_SEARCH_ROUTE = '/search';
const SEARCH_CONTEXT_QUERY_VAR = 'standard_search_context';
const SEARCH_CONTEXT_SITE = 'site';
const MACHINE_PRODUCT_CATEGORIES = [
    'roof-wall-panel-machines',
    'gutter-machines',
];

/**
 * @return string[]
 */
function get_default_post_types(): array {
    return [
        'post',
        'page',
        'video',
        'literature',
        'resource',
        'download',
        'manual',
        'profile',
        'product',
        'footprint',
    ];
}

/**
 * @return string[]
 */
function get_excluded_post_types(): array {
    $post_types = \apply_filters('standard_search_excluded_post_types', [
        'pricesheet',
        'cutlist',
        'attachment',
    ]);

    if (!is_array($post_types)) {
        return ['pricesheet'];
    }

    return array_values(array_unique(array_filter(
        array_map(fn($post_type): string => \sanitize_key((string) $post_type), $post_types)
    )));
}

/**
 * @return string[]
 */
function get_excluded_index_post_types(): array {
    $post_types = \apply_filters('standard_search_excluded_index_post_types', [
        'pricesheet',
        'cutlist',
    ]);

    if (!is_array($post_types)) {
        return ['pricesheet'];
    }

    return array_values(array_unique(array_filter(
        array_map(fn($post_type): string => \sanitize_key((string) $post_type), $post_types)
    )));
}

/**
 * @return string[]
 */
function get_relevanssi_index_post_types(): array {
    $post_types = array_merge(get_default_post_types(), ['attachment']);

    return array_values(array_diff(
        array_unique(array_map(static fn(string $post_type): string => \sanitize_key($post_type), $post_types)),
        get_excluded_index_post_types()
    ));
}

/**
 * @return string[]
 */
function get_searchable_post_types(): array {
    $post_types = \apply_filters('standard_search_post_types', get_default_post_types());

    if (!is_array($post_types)) {
        return ['post', 'page'];
    }

    return array_values(array_filter(
        array_map(fn($post_type): string => \sanitize_key((string) $post_type), $post_types),
        fn(string $post_type): bool => \post_type_exists($post_type) && !in_array($post_type, get_excluded_post_types(), true)
    ));
}

/**
 * @return string[]
 */
function get_requested_post_types(): array {
    $requested = get_request_values(get_post_type_filter_keys(), 'post_type');

    if ($requested === []) {
        return get_searchable_post_types();
    }

    $allowed = get_searchable_post_types();

    return array_values(array_intersect($requested, $allowed));
}

/**
 * @return string[]
 */
function get_post_type_filter_keys(): array {
    return ['post_type', 'type', 'lc_type'];
}

/**
 * @return array<string, string>
 */
function get_post_type_filter_options(): array {
    $options = [];
    $preferred_labels = \apply_filters('standard_search_post_type_filter_labels', [
        'product'    => \__('Machines', 'standard'),
        'profile'    => \__('Profiles', 'standard'),
        'manual'     => \__('Manuals', 'standard'),
        'post'       => \__('Articles', 'standard'),
        'video'      => \__('Videos', 'standard'),
        'resource'   => \__('Resources', 'standard'),
        'download'   => \__('Downloads', 'standard'),
        'literature' => \__('Literature', 'standard'),
        'page'       => \__('Pages', 'standard'),
        'footprint'  => \__('Footprints', 'standard'),
    ]);
    $searchable_post_types = get_searchable_post_types();

    if (is_array($preferred_labels)) {
        foreach ($preferred_labels as $post_type => $label) {
            $post_type = \sanitize_key((string) $post_type);

            if (in_array($post_type, $searchable_post_types, true)) {
                $options[$post_type] = (string) $label;
            }
        }
    }

    foreach ($searchable_post_types as $post_type) {
        if (isset($options[$post_type])) {
            continue;
        }

        $post_type_object = \get_post_type_object($post_type);
        $options[$post_type] = $post_type_object
            ? (string) $post_type_object->labels->name
            : $post_type;
    }

    return $options;
}

/**
 * Curated suggestions shown beneath the search input. Editorial, not
 * derived from query logs; reflects what NTM wants users to discover.
 *
 * @return array<int, array{label:string, query:string, post_type?:string}>
 */
function get_popular_searches(): array {
    $defaults = [
        ['label' => 'SSQ II',           'query' => 'SSQ II',           'post_type' => 'product'],
        ['label' => 'MACH II',          'query' => 'MACH II',          'post_type' => 'product'],
        ['label' => 'Color visualizer', 'query' => 'color visualizer'],
        ['label' => 'Service & parts',  'query' => 'service',          'post_type' => 'manual'],
    ];

    $items = \apply_filters('standard_search_popular_searches', $defaults);

    if (!is_array($items)) {
        return $defaults;
    }

    $clean = [];
    foreach ($items as $item) {
        if (!is_array($item)) {
            continue;
        }
        $label = isset($item['label']) ? trim((string) $item['label']) : '';
        $query = isset($item['query']) ? trim((string) $item['query']) : '';
        if ($label === '' || $query === '') {
            continue;
        }
        $entry = ['label' => $label, 'query' => $query];
        if (!empty($item['post_type'])) {
            $entry['post_type'] = \sanitize_key((string) $item['post_type']);
        }
        $clean[] = $entry;
    }

    return $clean;
}

/**
 * @return array<string|int, mixed>
 */
function get_requested_tax_query(): array {
    $filters = \apply_filters('standard_search_taxonomy_filters', [
        'category'           => ['category', 'lc_category', '_sft_category'],
        'post_tag'           => ['tag', 'post_tag', 'lc_machine', '_sft_post_tag'],
        'machine'            => ['machine'],
        'content_department' => ['department', 'content_department'],
        'product_cat'        => ['product_cat'],
        'product_tag'        => ['product_tag'],
    ]);

    if (!is_array($filters)) {
        return [];
    }

    $tax_query = [];

    foreach ($filters as $taxonomy => $keys) {
        $taxonomy = \sanitize_key((string) $taxonomy);

        if (!\taxonomy_exists($taxonomy) || !is_array($keys)) {
            continue;
        }

        $terms = get_request_values($keys, 'term', $taxonomy);

        if ($terms === []) {
            continue;
        }

        $tax_query[] = [
            'taxonomy' => $taxonomy,
            'field'    => 'slug',
            'terms'    => $terms,
            'operator' => 'IN',
        ];
    }

    if (count($tax_query) > 1) {
        return array_merge(['relation' => 'AND'], $tax_query);
    }

    return $tax_query;
}

/**
 * @param string[] $keys
 * @return string[]
 */
function get_request_values(array $keys, string $context, string $taxonomy = ''): array {
    $values = [];

    foreach ($keys as $key) {
        if (!isset($_GET[$key])) {
            continue;
        }

        $raw = \wp_unslash($_GET[$key]);
        $raw_values = is_array($raw) ? $raw : [$raw];

        foreach ($raw_values as $raw_value) {
            if (!is_scalar($raw_value)) {
                continue;
            }

            $parts = preg_split('/[\s,+]+/', (string) $raw_value) ?: [];

            foreach ($parts as $part) {
                $part = trim($part);

                if ($part === '') {
                    continue;
                }

                $value = $context === 'post_type'
                    ? \sanitize_key($part)
                    : normalize_term_slug($part, $taxonomy);

                if ($value !== '') {
                    $values[] = $value;
                }
            }
        }
    }

    return array_values(array_unique($values));
}

function normalize_term_slug(string $value, string $taxonomy): string {
    if (ctype_digit($value)) {
        $term = \get_term((int) $value, $taxonomy);

        if ($term instanceof \WP_Term) {
            return $term->slug;
        }
    }

    return \sanitize_title($value);
}

/**
 * Relevanssi does not reliably honor post__in=[0] on empty searches,
 * so use an impossible post type too.
 *
 * @param array<string, mixed> $query_args
 */
function force_no_results(array &$query_args): void {
    $query_args['post_type'] = ['__standard_no_results'];
    $query_args['post__in'] = [0];
}

function is_excluded_post_type(string $post_type): bool {
    return in_array(\sanitize_key($post_type), get_excluded_post_types(), true);
}

function is_excluded_index_post_type(string $post_type): bool {
    return in_array(\sanitize_key($post_type), get_excluded_index_post_types(), true);
}

/**
 * @param bool|string $do_not_index
 * @param int         $post_id
 * @param \WP_Post|null $post
 * @return bool|string
 */
function exclude_relevanssi_indexed_post_types($do_not_index, int $post_id, ?\WP_Post $post = null) {
    $post = $post ?: \get_post($post_id);

    if ($post instanceof \WP_Post && is_excluded_index_post_type($post->post_type)) {
        return 'Standard search excludes this post type';
    }

    return $do_not_index;
}

function exclude_relevanssi_result_post_types(bool $post_ok, int $post_id): bool {
    if (!$post_ok) {
        return false;
    }

    $post_type = \get_post_type($post_id);

    return is_string($post_type) ? !is_excluded_post_type($post_type) : $post_ok;
}

/**
 * These weights make commercial objects win product-name searches without
 * burying manuals and downloads when those are the better match.
 *
 * @param mixed $weights
 * @return array<string, int>
 */
function tune_relevanssi_post_type_weights($weights): array {
    $weights = is_array($weights) ? $weights : [];
    $overrides = \apply_filters('standard_search_relevanssi_post_type_weights', [
        'product'    => 1,
        'manual'     => 4,
        'page'       => 2,
        'profile'    => 2,
        'literature' => 2,
        'resource'   => 2,
        'download'   => 2,
        'footprint'  => 2,
        'post'       => 1,
        'video'      => 1,
    ]);

    if (!is_array($overrides)) {
        return $weights;
    }

    foreach ($overrides as $post_type => $weight) {
        $post_type = \sanitize_key((string) $post_type);
        $weight = (int) $weight;

        if ($post_type !== '' && $weight > 0) {
            $weights[$post_type] = $weight;
        }
    }

    return $weights;
}

/**
 * Keep Relevanssi's indexed post-type option aligned with the theme contract.
 * Attachment indexing intentionally remains allowed until PDF-parent result
 * behavior is proven; direct attachment results are still blocked elsewhere.
 *
 * @param mixed $post_types
 * @return string[]
 */
function sanitize_relevanssi_index_post_types($post_types): array {
    unset($post_types);

    return get_relevanssi_index_post_types();
}

function tune_relevanssi_title_boost($boost): int {
    return (int) \apply_filters('standard_search_relevanssi_title_boost', 40, $boost);
}

function tune_relevanssi_content_boost($boost): int {
    return (int) \apply_filters('standard_search_relevanssi_content_boost', 5, $boost);
}

function normalize_search_text(string $value): string {
    $value = \html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    $value = str_replace(["\u{2122}", "\u{00AE}"], '', $value);
    $value = \function_exists('remove_accents') ? \remove_accents($value) : $value;
    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', ' ', $value) ?? $value;
    $value = preg_replace('/\s+/', ' ', $value) ?? $value;

    return trim($value);
}

function compact_search_text(string $value): string {
    return str_replace(' ', '', normalize_search_text($value));
}

function normalized_contains_phrase(string $normalized, string $phrase): bool {
    $phrase = normalize_search_text($phrase);

    return $phrase !== '' && preg_match('/(?:^|\s)' . preg_quote($phrase, '/') . '(?:\s|$)/', $normalized) === 1;
}

/**
 * @return string[]
 */
function get_machine_data_keys(): array {
    if (\function_exists('Standard\\MachineProductData\\get_machine_data_keys')) {
        return \Standard\MachineProductData\get_machine_data_keys();
    }

    return [
        'ssq3-multipro',
        'ssq-ii-multipro',
        'ssh-multipro',
        'ssr-multipro-jr',
        '5vc-5v-crimp',
        'wav-wall-panel',
        'mach-ii-5-gutter',
        'mach-ii-6-gutter',
        'mach-ii-combo-gutter',
        'bg7-box-gutter',
    ];
}

/**
 * @return array<string, string[]>
 */
function get_machine_product_slug_candidates_by_key(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    foreach (get_machine_data_keys() as $key) {
        $cache[$key] = [$key];
    }

    if (\function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        foreach (\Standard\MachineProductData\get_slug_aliases() as $woo_slug => $key) {
            $key = (string) $key;
            if (!isset($cache[$key])) {
                $cache[$key] = [$key];
            }
            $cache[$key][] = (string) $woo_slug;
        }
    }

    foreach ($cache as $key => $slugs) {
        $cache[$key] = array_values(array_unique(array_filter(
            array_map(static fn($slug): string => \sanitize_title((string) $slug), $slugs)
        )));
    }

    return $cache;
}

/**
 * @return array<string, string[]>
 */
function get_active_machine_keys_by_product_category(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [
        'roof-wall-panel-machines' => ['ssq3-multipro', 'ssh-multipro', 'ssr-multipro-jr', '5vc-5v-crimp', 'wav-wall-panel'],
        'gutter-machines'          => ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter', 'bg7-box-gutter'],
    ];

    if (\function_exists('Standard\\MachinesData\\get_machine_categories')) {
        $categories = \Standard\MachinesData\get_machine_categories(false);
        $from_data = [
            'roof-wall-panel-machines' => [],
            'gutter-machines'          => [],
        ];

        foreach (($categories['roof-wall']['machines'] ?? []) as $machine) {
            $slug = (string) ($machine['slug'] ?? '');
            if ($slug !== '') {
                $from_data['roof-wall-panel-machines'][] = $slug;
            }
        }

        foreach (($categories['gutter']['machines'] ?? []) as $machine) {
            $slug = (string) ($machine['slug'] ?? '');
            if ($slug !== '') {
                $from_data['gutter-machines'][] = $slug;
            }
        }

        foreach ($from_data as $category => $keys) {
            if ($keys !== []) {
                $cache[$category] = array_values(array_unique($keys));
            }
        }
    }

    return $cache;
}

/**
 * @return array<string, int>
 */
function get_machine_catalog_order(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    $position = 0;
    foreach (get_active_machine_keys_by_product_category() as $keys) {
        foreach ($keys as $key) {
            if (!isset($cache[$key])) {
                $cache[$key] = $position++;
            }
        }
    }

    foreach (get_machine_data_keys() as $key) {
        if (!isset($cache[$key])) {
            $cache[$key] = $position++;
        }
    }

    return $cache;
}

function product_is_in_machine_category(int $post_id): bool {
    return \taxonomy_exists('product_cat')
        && \has_term(MACHINE_PRODUCT_CATEGORIES, 'product_cat', $post_id);
}

/**
 * @return array<string, int>
 */
function get_canonical_machine_product_ids(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    if (!\post_type_exists('product')) {
        return $cache;
    }

    foreach (get_machine_product_slug_candidates_by_key() as $key => $slugs) {
        foreach ($slugs as $slug) {
            $post = \get_page_by_path($slug, OBJECT, 'product');
            if (!$post instanceof \WP_Post || $post->post_status !== 'publish') {
                continue;
            }
            if (!product_is_in_machine_category((int) $post->ID)) {
                continue;
            }

            $cache[$key] = (int) $post->ID;
            break;
        }
    }

    return $cache;
}

/**
 * @return array<int, string>
 */
function get_canonical_machine_keys_by_product_id(): array {
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    foreach (get_canonical_machine_product_ids() as $key => $post_id) {
        $cache[(int) $post_id] = (string) $key;
    }

    return $cache;
}

/**
 * @param string[] $keys
 * @return int[]
 */
function get_machine_product_ids_for_keys(array $keys): array {
    $ids = [];
    $products = get_canonical_machine_product_ids();

    foreach ($keys as $key) {
        if (isset($products[$key])) {
            $ids[] = (int) $products[$key];
        }
    }

    return array_values(array_unique($ids));
}

/**
 * @return array{
 *     normalized:string,
 *     compact:string,
 *     exact_keys:string[],
 *     category_keys:string[],
 *     family_order:string[],
 *     modifier_groups:string[],
 *     has_machine_intent:bool,
 *     has_modifier_intent:bool
 * }
 */
function get_machine_search_intent(string $query): array {
    $normalized = normalize_search_text($query);
    $compact = compact_search_text($query);
    $exact_keys = [];
    $family_order = [];
    $active_by_category = get_active_machine_keys_by_product_category();

    $add_key = static function (string $key) use (&$exact_keys): void {
        if (!in_array($key, $exact_keys, true)) {
            $exact_keys[] = $key;
        }
    };

    if (preg_match('/\bgm\s*5\s*6\b/', $normalized) === 1) {
        $add_key('mach-ii-combo-gutter');
    }
    if (preg_match('/\bgm\s*5\b/', $normalized) === 1) {
        $add_key('mach-ii-5-gutter');
    }
    if (preg_match('/\bgm\s*6\b/', $normalized) === 1) {
        $add_key('mach-ii-6-gutter');
    }

    if (preg_match('/\bssq\s*3\b/', $normalized) === 1 || preg_match('/\bq\s*3\b/', $normalized) === 1) {
        $add_key('ssq3-multipro');
    }
    if (preg_match('/\bssq\s*(?:ii|2)\b/', $normalized) === 1) {
        $add_key('ssq-ii-multipro');
    }
    if (preg_match('/\bmach\s*(?:ii|2)\b/', $normalized) === 1) {
        $family_order = array_values(array_filter(
            $active_by_category['gutter-machines'] ?? [],
            static fn(string $key): bool => str_starts_with($key, 'mach-ii-')
        ));
        if ($family_order === []) {
            $family_order = ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter'];
        }
        foreach ($family_order as $key) {
            $add_key($key);
        }
    }
    if (preg_match('/\bbg\s*7\b/', $normalized) === 1) {
        $add_key('bg7-box-gutter');
    }
    if (preg_match('/\bwav\b/', $normalized) === 1) {
        $add_key('wav-wall-panel');
    }
    if (preg_match('/\bssh\b/', $normalized) === 1) {
        $add_key('ssh-multipro');
    }
    if (preg_match('/\bssr\b/', $normalized) === 1) {
        $add_key('ssr-multipro-jr');
    }
    if (preg_match('/\b5\s*vc\b/', $normalized) === 1 || preg_match('/\b5v\s*crimp\b/', $normalized) === 1) {
        $add_key('5vc-5v-crimp');
    }

    $category_keys = [];
    if (
        normalized_contains_phrase($normalized, 'gutter machine')
        || normalized_contains_phrase($normalized, 'seamless gutter')
        || normalized_contains_phrase($normalized, 'box gutter machine')
        || normalized_contains_phrase($normalized, 'k style gutter')
    ) {
        $category_keys = array_merge($category_keys, $active_by_category['gutter-machines'] ?? []);
    }
    if (
        normalized_contains_phrase($normalized, 'roof panel machine')
        || normalized_contains_phrase($normalized, 'roof wall panel machine')
        || normalized_contains_phrase($normalized, 'roof and wall panel machine')
        || normalized_contains_phrase($normalized, 'standing seam machine')
        || normalized_contains_phrase($normalized, 'wall panel machine')
    ) {
        $category_keys = array_merge($category_keys, $active_by_category['roof-wall-panel-machines'] ?? []);
    }

    $modifier_groups = [];
    if (preg_match('/\b(manual|manuals|guide|guides|pdf|operation|operators?|owners?)\b/', $normalized) === 1) {
        $modifier_groups[] = 'manual';
    }
    if (preg_match('/\b(service|services|troubleshoot|troubleshooting|repair|repairs|maintenance)\b/', $normalized) === 1) {
        $modifier_groups[] = 'service';
    }
    if (preg_match('/\b(parts?|covers?|carts?|controllers?|accessor(?:y|ies))\b/', $normalized) === 1) {
        $modifier_groups[] = 'accessory';
    }

    $category_keys = array_values(array_unique($category_keys));
    $modifier_groups = array_values(array_unique($modifier_groups));

    return [
        'normalized'          => $normalized,
        'compact'             => $compact,
        'exact_keys'          => $exact_keys,
        'category_keys'       => $category_keys,
        'family_order'        => $family_order,
        'modifier_groups'     => $modifier_groups,
        'has_machine_intent'  => $exact_keys !== [] || $category_keys !== [],
        'has_modifier_intent' => $modifier_groups !== [],
    ];
}

function query_allows_product_results(\WP_Query $query): bool {
    $post_types = $query->get('post_type');
    $post_types = is_array($post_types) ? $post_types : ($post_types !== '' ? [$post_types] : []);
    $post_types = array_values(array_filter(array_map(static fn($post_type): string => \sanitize_key((string) $post_type), $post_types)));

    if ($post_types === [] || in_array('any', $post_types, true)) {
        return in_array('product', get_searchable_post_types(), true);
    }

    return in_array('product', $post_types, true);
}

function get_hit_post_id($hit): int {
    if ($hit instanceof \WP_Post) {
        return (int) $hit->ID;
    }

    if (is_numeric($hit)) {
        return (int) $hit;
    }

    if (is_object($hit)) {
        foreach (['ID', 'id', 'doc', 'post_id'] as $property) {
            if (isset($hit->{$property}) && is_numeric($hit->{$property})) {
                return (int) $hit->{$property};
            }
        }
    }

    if (is_array($hit)) {
        foreach (['ID', 'id', 'doc', 'post_id'] as $key) {
            if (isset($hit[$key]) && is_numeric($hit[$key])) {
                return (int) $hit[$key];
            }
        }
    }

    return 0;
}

/**
 * @param mixed[] $hits
 * @param array<string, mixed> $intent
 * @return mixed[]
 */
function append_curated_machine_hits(array $hits, array $intent, \WP_Query $query): array {
    if (!query_allows_product_results($query)) {
        return $hits;
    }

    $keys = (array) ($intent['exact_keys'] ?? []);
    if (empty($intent['has_modifier_intent'])) {
        $keys = array_merge($keys, (array) ($intent['category_keys'] ?? []));
    }

    $ids = get_machine_product_ids_for_keys($keys);
    if ($ids === []) {
        return $hits;
    }

    $seen = [];
    foreach ($hits as $hit) {
        $post_id = get_hit_post_id($hit);
        if ($post_id > 0) {
            $seen[$post_id] = true;
        }
    }

    foreach ($ids as $post_id) {
        if (isset($seen[$post_id])) {
            continue;
        }

        $post = \get_post($post_id);
        if (!$post instanceof \WP_Post) {
            continue;
        }

        $post->relevance_score = -1;
        $hits[] = $post;
        $seen[$post_id] = true;
    }

    return $hits;
}

/**
 * @param array<string, mixed> $intent
 */
function result_matches_modifier_intent(int $post_id, array $intent): bool {
    if (empty($intent['has_modifier_intent'])) {
        return false;
    }

    $post = \get_post($post_id);
    if (!$post instanceof \WP_Post) {
        return false;
    }

    $post_type = (string) $post->post_type;
    $haystack = normalize_search_text((string) $post->post_title . ' ' . (string) $post->post_name);
    $groups = (array) ($intent['modifier_groups'] ?? []);

    if (in_array('manual', $groups, true)) {
        if ($post_type === 'manual') {
            return true;
        }
        if (in_array($post_type, ['download', 'resource', 'literature'], true)
            && preg_match('/\b(manual|guide|pdf|operators?|owners?)\b/', $haystack) === 1) {
            return true;
        }
    }

    if (in_array('service', $groups, true)) {
        if (in_array($post_type, ['post', 'video', 'resource', 'download', 'manual', 'knowledgebase'], true)
            && preg_match('/\b(service|troubleshoot|troubleshooting|repair|maintenance)\b/', $haystack) === 1) {
            return true;
        }
    }

    if (in_array('accessory', $groups, true) && $post_type === 'product') {
        $is_machine = isset(get_canonical_machine_keys_by_product_id()[$post_id]);
        if (!$is_machine && preg_match('/\b(parts?|covers?|carts?|controllers?|accessor(?:y|ies))\b/', $haystack) === 1) {
            return true;
        }
        if (!$is_machine && \taxonomy_exists('product_cat') && \has_term('accessories-add-on-equipment', 'product_cat', $post_id)) {
            return true;
        }
    }

    return false;
}

/**
 * @param array<string, mixed> $intent
 * @return array{0:int,1:int}
 */
function get_machine_result_bucket(int $post_id, array $intent): array {
    $machine_keys_by_id = get_canonical_machine_keys_by_product_id();
    $machine_key = $machine_keys_by_id[$post_id] ?? '';
    $exact_keys = (array) ($intent['exact_keys'] ?? []);
    $category_keys = (array) ($intent['category_keys'] ?? []);
    $target_keys = array_values(array_unique(array_merge($exact_keys, $category_keys)));
    $machine_order = get_machine_catalog_order();

    if (!empty($intent['has_modifier_intent'])) {
        if (result_matches_modifier_intent($post_id, $intent)) {
            return [0, PHP_INT_MAX];
        }

        if ($machine_key !== '' && in_array($machine_key, $target_keys, true)) {
            return [20, $machine_order[$machine_key] ?? PHP_INT_MAX];
        }

        return [40, PHP_INT_MAX];
    }

    if ($machine_key !== '' && in_array($machine_key, $exact_keys, true)) {
        $family_order = (array) ($intent['family_order'] ?? []);
        $family_position = array_search($machine_key, $family_order, true);

        return [0, $family_position === false ? ($machine_order[$machine_key] ?? PHP_INT_MAX) : (int) $family_position];
    }

    if ($machine_key !== '' && in_array($machine_key, $category_keys, true)) {
        return [10, PHP_INT_MAX];
    }

    if ($machine_key !== '') {
        return [30, $machine_order[$machine_key] ?? PHP_INT_MAX];
    }

    return [40, PHP_INT_MAX];
}

/**
 * Reorder only theme-owned site search queries. Relevanssi has already sorted
 * by relevance; this adds deterministic machine-intent buckets before the
 * plugin slices results for pagination.
 *
 * @param array{0:mixed[],1:string} $filter_data
 * @return array{0:mixed[],1:string}
 */
function apply_machine_relevance_contract(array $filter_data, \WP_Query $query): array {
    if ((string) $query->get(SEARCH_CONTEXT_QUERY_VAR) !== SEARCH_CONTEXT_SITE) {
        return $filter_data;
    }

    $search = (string) ($query->get('s') ?: ($filter_data[1] ?? ''));
    $intent = get_machine_search_intent($search);

    if (empty($intent['has_machine_intent'])) {
        return $filter_data;
    }

    $hits = isset($filter_data[0]) && is_array($filter_data[0]) ? $filter_data[0] : [];
    $hits = append_curated_machine_hits($hits, $intent, $query);

    $ranked = [];
    foreach ($hits as $index => $hit) {
        $post_id = get_hit_post_id($hit);
        [$bucket, $machine_order] = $post_id > 0
            ? get_machine_result_bucket($post_id, $intent)
            : [40, PHP_INT_MAX];

        $ranked[] = [
            'hit'           => $hit,
            'bucket'        => $bucket,
            'machine_order' => $machine_order,
            'index'         => $index,
        ];
    }

    usort($ranked, static function (array $a, array $b): int {
        $bucket = $a['bucket'] <=> $b['bucket'];
        if ($bucket !== 0) {
            return $bucket;
        }

        $machine_order = $a['machine_order'] <=> $b['machine_order'];
        if ($machine_order !== 0 && ($a['machine_order'] !== PHP_INT_MAX || $b['machine_order'] !== PHP_INT_MAX)) {
            return $machine_order;
        }

        return $a['index'] <=> $b['index'];
    });

    $filter_data[0] = array_map(static fn(array $row) => $row['hit'], $ranked);

    return $filter_data;
}

/**
 * Preserve Relevanssi highlight/click-tracking parameters for card data
 * produced specifically inside the search results loop.
 */
function get_search_result_permalink(?\WP_Post $post = null): string {
    $post = $post ?: \get_post();
    if (!$post instanceof \WP_Post) {
        return '#';
    }

    if (\function_exists('relevanssi_get_permalink')) {
        $url = \relevanssi_get_permalink($post);
    } else {
        $url = \get_permalink($post);
    }

    return is_string($url) && $url !== '' ? $url : '#';
}

function encode_relevanssi_click_tracking_value(string $value): string {
    if (\function_exists('relevanssi_base64url_encode')) {
        return \relevanssi_base64url_encode($value);
    }

    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

function get_rest_search_result_permalink(\WP_Post $post, string $search, int $rank): string {
    $url = \get_permalink($post);
    $url = is_string($url) && $url !== '' ? $url : '#';

    if ((string) \get_option('relevanssi_click_tracking', 'off') !== 'on') {
        return $url;
    }

    if ($search === '' || $rank < 1 || !\function_exists('relevanssi_log_click')) {
        return $url;
    }

    if (\function_exists('relevanssi_is_ok_to_log') && !\relevanssi_is_ok_to_log()) {
        return $url;
    }

    if (\function_exists('relevanssi_is_front_page_id') && \relevanssi_is_front_page_id((int) $post->ID)) {
        return $url;
    }

    $query = str_replace('|', ' ', $search);
    $query = \function_exists('relevanssi_strtolower')
        ? \relevanssi_strtolower($query)
        : strtolower($query);
    $value = $rank . '|1|' . $query . '|' . time();

    return \add_query_arg([
        '_rt'       => encode_relevanssi_click_tracking_value($value),
        '_rt_nonce' => \wp_create_nonce('relevanssi_click_tracking_' . (int) $post->ID),
    ], $url);
}

/**
 * @return string[]
 */
function get_rest_requested_post_types(\WP_REST_Request $request): array {
    $raw = $request->get_param('subtype');
    $values = is_array($raw) ? $raw : ($raw !== null && $raw !== '' ? [$raw] : []);

    if ($values === []) {
        return get_searchable_post_types();
    }

    $requested = array_values(array_filter(array_map(static fn($value): string => \sanitize_key((string) $value), $values)));

    return array_values(array_intersect($requested, get_searchable_post_types()));
}

/**
 * @return array{id:int,title:string,url:string,subtype:string}
 */
function format_rest_search_result(\WP_Post $post, string $search = '', int $rank = 0): array {
    return [
        'id'      => (int) $post->ID,
        'title'   => (string) \get_the_title($post),
        'url'     => get_rest_search_result_permalink($post, $search, $rank),
        'subtype' => (string) $post->post_type,
    ];
}

function handle_rest_search_request(\WP_REST_Request $request): \WP_REST_Response {
    $search = \sanitize_text_field((string) $request->get_param('search'));
    $search = trim($search);

    if ($search === '') {
        return \rest_ensure_response([]);
    }

    $post_types = get_rest_requested_post_types($request);
    if ($post_types === []) {
        return \rest_ensure_response([]);
    }

    $per_page = (int) $request->get_param('per_page');
    $per_page = max(1, min(20, $per_page > 0 ? $per_page : 5));

    $query_args = [
        's'                    => $search,
        'post_type'            => $post_types,
        'post_status'          => 'publish',
        'posts_per_page'       => $per_page,
        'ignore_sticky_posts'  => true,
        'suppress_filters'     => false,
        SEARCH_CONTEXT_QUERY_VAR => SEARCH_CONTEXT_SITE,
        'rlv_source'           => 'standard-rest-search',
    ];

    if (\function_exists('relevanssi_do_query')) {
        $query_args['relevanssi'] = true;
    }

    $query = new \WP_Query($query_args);
    $items = [];

    foreach (array_values($query->posts) as $index => $post) {
        if ($post instanceof \WP_Post) {
            $items[] = format_rest_search_result($post, $search, $index + 1);
        }
    }

    return \rest_ensure_response($items);
}

function register_rest_routes(): void {
    \register_rest_route(REST_NAMESPACE, REST_SEARCH_ROUTE, [
        'methods'             => \WP_REST_Server::READABLE,
        'callback'            => __NAMESPACE__ . '\\handle_rest_search_request',
        'permission_callback' => '__return_true',
        'args'                => [
            'search' => [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
            'subtype' => [
                'type'              => ['string', 'array'],
                'sanitize_callback' => static function ($value) {
                    if (is_array($value)) {
                        return array_values(array_filter(array_map(
                            static fn($item): string => is_scalar($item) ? \sanitize_key((string) $item) : '',
                            $value
                        )));
                    }

                    return \sanitize_key((string) $value);
                },
            ],
            'per_page' => [
                'type'              => 'integer',
                'default'           => 5,
                'minimum'           => 1,
                'maximum'           => 20,
                'sanitize_callback' => 'absint',
            ],
        ],
    ]);
}

/**
 * @return array<string, mixed>
 */
function get_product_card_data(int $post_id): array {
    $fallback = [
        'id'             => $post_id,
        'title'          => \get_the_title($post_id),
        'category_label' => '',
        'descriptor'     => '',
        'image'          => \get_the_post_thumbnail_url($post_id, 'product-card') ?: '',
        'price'          => '',
        'price_label'    => \__('Starting at', 'standard'),
        'explore_url'    => \get_permalink($post_id) ?: '#',
        'build_url'      => '',
        'badge'          => '',
    ];

    if (!\function_exists('wc_get_product')) {
        return $fallback;
    }

    $product = \wc_get_product($post_id);
    if (!$product instanceof \WC_Product) {
        return $fallback;
    }

    $price = $product->get_price();
    $image = \wp_get_attachment_url((int) $product->get_image_id());
    $build_url = \function_exists('Standard\\Woo\\Catalog\\get_configurator_url')
        ? \Standard\Woo\Catalog\get_configurator_url($product->get_slug())
        : '';

    return [
        'id'             => $product->get_id(),
        'title'          => \function_exists('Standard\\Woo\\Catalog\\get_short_title')
            ? \Standard\Woo\Catalog\get_short_title($product->get_name())
            : $product->get_name(),
        'category_label' => \function_exists('Standard\\Woo\\Catalog\\get_primary_category_label')
            ? \Standard\Woo\Catalog\get_primary_category_label($product)
            : '',
        'descriptor'     => \wp_strip_all_tags($product->get_short_description()),
        'image'          => is_string($image) ? $image : '',
        'price'          => $price !== '' ? '$' . \number_format((float) $price) : '',
        'price_label'    => \__('Starting at', 'standard'),
        'explore_url'    => $product->get_permalink(),
        'build_url'      => $build_url,
        'badge'          => '',
    ];
}

function configure_main_query(\WP_Query $query): void {
    if (\is_admin() || !$query->is_main_query() || !$query->is_search()) {
        return;
    }

    $requested_post_types = get_request_values(get_post_type_filter_keys(), 'post_type');
    $post_types = $requested_post_types === []
        ? get_searchable_post_types()
        : get_requested_post_types();
    $tax_query = get_requested_tax_query();

    $query_args = \apply_filters('standard_search_query_args', [
        'post_type'           => $post_types !== [] ? $post_types : get_searchable_post_types(),
        'post_status'         => 'publish',
        'posts_per_page'      => 12,
        'ignore_sticky_posts' => true,
        'suppress_filters'    => false,
        SEARCH_CONTEXT_QUERY_VAR => SEARCH_CONTEXT_SITE,
    ], $query);

    if (!is_array($query_args)) {
        return;
    }

    if ($tax_query !== []) {
        $query_args['tax_query'] = $tax_query;
    }

    if ($requested_post_types !== [] && $post_types === []) {
        force_no_results($query_args);
    }

    // "?s=" with no value still trips both WP_Query::parse_search() (which
    // emits a useless LIKE '%&#32;%' clause) and Relevanssi. When the user
    // submitted no keyword but did pick a filter, run the normal WP tax query:
    // remove WP's blank search SQL and opt out of both Relevanssi gates.
    $raw_s         = (string) $query->get('s');
    $decoded_s     = html_entity_decode($raw_s, ENT_QUOTES, 'UTF-8');
    $keyword_blank = trim($decoded_s) === '';
    $filters_set   = $tax_query !== [] || $requested_post_types !== [];

    if ($keyword_blank && $filters_set) {
        \add_filter('posts_search', static function (string $search, \WP_Query $q) use ($query): string {
            return $q === $query ? '' : $search;
        }, 10, 2);
        \add_filter('relevanssi_search_ok', static function (bool $ok, \WP_Query $q) use ($query): bool {
            return $q === $query ? false : $ok;
        }, 10, 2);
        \add_filter('relevanssi_prevent_default_request', static function (bool $prevent, \WP_Query $q) use ($query): bool {
            return $q === $query ? false : $prevent;
        }, 10, 2);
    }

    if ($keyword_blank && !$filters_set) {
        force_no_results($query_args);
    }

    foreach ($query_args as $key => $value) {
        $query->set((string) $key, $value);
    }
}

/**
 * @param array<string, mixed> $query_args
 * @return array<string, mixed>
 */
function configure_rest_post_search_query(array $query_args, \WP_REST_Request $request): array {
    if (empty($query_args['s'])) {
        return $query_args;
    }

    $requested_post_types = isset($query_args['post_type']) ? (array) $query_args['post_type'] : [];
    $post_types = array_values(array_filter(
        array_map(static fn($post_type): string => \sanitize_key((string) $post_type), $requested_post_types)
    ));

    if ($post_types === [] || in_array('any', $post_types, true)) {
        $post_types = get_searchable_post_types();
    } else {
        $post_types = array_values(array_intersect($post_types, get_searchable_post_types()));
    }

    if ($post_types === []) {
        force_no_results($query_args);
    } else {
        $query_args['post_type'] = $post_types;
    }

    $query_args['post_status'] = 'publish';
    $query_args['suppress_filters'] = false;
    return $query_args;
}

function configure_taxonomy_archive_query(\WP_Query $query): void {
    if (\is_admin() || !$query->is_main_query() || $query->is_search()) {
        return;
    }

    if (!($query->is_category() || $query->is_tag() || $query->is_tax())) {
        return;
    }

    $requested_post_types = get_request_values(get_post_type_filter_keys(), 'post_type');
    $post_types = $requested_post_types === []
        ? get_searchable_post_types()
        : array_values(array_intersect($requested_post_types, get_searchable_post_types()));

    if ($requested_post_types !== [] && $post_types === []) {
        $query_args = [];
        force_no_results($query_args);

        foreach ($query_args as $key => $value) {
            $query->set((string) $key, $value);
        }

        return;
    }

    $query->set('post_type', $post_types);
    $query->set('post_status', 'publish');
    $query->set('posts_per_page', 12);
    $query->set('ignore_sticky_posts', true);
}

\add_action('pre_get_posts', __NAMESPACE__ . '\\configure_main_query');
\add_action('pre_get_posts', __NAMESPACE__ . '\\configure_taxonomy_archive_query');
\add_action('rest_api_init', __NAMESPACE__ . '\\register_rest_routes');
\add_filter('rest_post_search_query', __NAMESPACE__ . '\\configure_rest_post_search_query', 10, 2);
\add_filter('option_relevanssi_post_type_weights', __NAMESPACE__ . '\\tune_relevanssi_post_type_weights');
\add_filter('option_relevanssi_index_post_types', __NAMESPACE__ . '\\sanitize_relevanssi_index_post_types');
\add_filter('option_relevanssi_title_boost', __NAMESPACE__ . '\\tune_relevanssi_title_boost');
\add_filter('option_relevanssi_content_boost', __NAMESPACE__ . '\\tune_relevanssi_content_boost');
\add_filter('relevanssi_hits_filter', __NAMESPACE__ . '\\apply_machine_relevance_contract', 20, 2);
\add_filter('relevanssi_do_not_index', __NAMESPACE__ . '\\exclude_relevanssi_indexed_post_types', 10, 3);
\add_filter('relevanssi_post_ok', __NAMESPACE__ . '\\exclude_relevanssi_result_post_types', 10, 2);
