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
const CACHE_GROUP = 'standard_search';
const CANONICAL_MACHINE_PRODUCT_IDS_CACHE_KEY = 'canonical_machine_product_ids_v1';
const REST_SUGGESTION_CACHE_TTL = 120;
const REST_SUGGESTION_CACHE_PREFIX = 'standard_search_suggestions_v1_';
const REST_SUGGESTION_CACHE_VERSION_OPTION = 'standard_search_suggestion_cache_version';
const MACHINE_PRODUCT_CATEGORIES = [
    'roof-wall-panel-machines',
    'gutter-machines',
];
const MACHINE_SEARCH_CATALOG = [
    'roof-wall-panel-machines' => [
        [
            'key'     => 'ssq3-multipro',
            'title'   => 'SSQ3 MultiPro',
            'slug'    => 'ssq3-roof-panel-machine',
        ],
        [
            'key'     => 'ssq-ii-multipro',
            'title'   => 'SSQ II MultiPro',
            'slug'    => 'ssq-roof-panel-machine',
            'active'  => false,
        ],
        [
            'key'     => 'ssh-multipro',
            'title'   => 'SSH MultiPro',
            'slug'    => 'ssh-roof-panel-machine',
        ],
        [
            'key'     => 'ssr-multipro-jr',
            'title'   => 'SSR MultiPro Jr.',
            'slug'    => 'ssr-roof-panel-machine',
        ],
        [
            'key'     => '5vc-5v-crimp',
            'title'   => '5VC-5V Crimp',
            'slug'    => '5vc-5v-crimp-roof-panel-machine',
        ],
        [
            'key'     => 'wav-wall-panel',
            'title'   => 'WAV Wall Panel Machine',
            'slug'    => 'wav-wall-panel-machine',
        ],
    ],
    'gutter-machines' => [
        [
            'key'     => 'mach-ii-combo-gutter',
            'title'   => 'MACH II 5"/6" Combo Gutter Machine',
            'slug'    => 'mach-ii-5-6-combo-gutter-machine',
        ],
        [
            'key'     => 'mach-ii-5-gutter',
            'title'   => 'MACH II 5" Gutter Machine',
            'slug'    => 'mach-ii-5-gutter-machine',
        ],
        [
            'key'     => 'mach-ii-6-gutter',
            'title'   => 'MACH II 6" Gutter Machine',
            'slug'    => 'mach-ii-6-gutter-machine',
        ],
        [
            'key'     => 'bg7-box-gutter',
            'title'   => 'BG7 Box Gutter Machine',
            'slug'    => 'bg7-box-gutter-machine',
        ],
    ],
];
const MACHINE_EXACT_INTENT_GROUPS = [
    [
        'keys'     => ['mach-ii-combo-gutter'],
        'patterns' => [
            '\\bgm\\s*5\\s*6\\b(?!\\s*\\d)',
            '\\bmach\\s*(?:ii|2)\\s*5\\s*(?:/|\\s)?\\s*6\\b',
            '\\bmach\\s*(?:ii|2)\\s*combo\\b',
        ],
    ],
    [
        'keys'     => ['mach-ii-5-gutter'],
        'patterns' => [
            '\\bgm\\s*5\\b(?!\\s*\\d)',
            '\\bmach\\s*(?:ii|2)\\s*5\\b(?!\\s*\\d)',
        ],
    ],
    [
        'keys'     => ['mach-ii-6-gutter'],
        'patterns' => [
            '\\bgm\\s*6\\b(?!\\s*\\d)',
            '\\bmach\\s*(?:ii|2)\\s*6\\b(?!\\s*\\d)',
        ],
    ],
    [
        'keys'     => ['ssq3-multipro'],
        'patterns' => ['\\bssq\\b(?!\\s*(?:ii|2|3|[0-9][a-z0-9]*))'],
    ],
    [
        'keys'     => ['ssq3-multipro'],
        'patterns' => ['\\bssq\\s*3\\b', '\\bq\\s*3\\b'],
    ],
    [
        'keys'     => ['ssq-ii-multipro'],
        'patterns' => ['\\bssq\\s*(?:ii|2)\\b'],
    ],
    [
        'keys'     => ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter'],
        'patterns' => ['\\bmach\\s*(?:ii|2)\\b(?!\\s*(?:5|6|combo))'],
        'family'   => true,
    ],
    [
        'keys'     => ['bg7-box-gutter'],
        'patterns' => ['\\bbg\\s*7\\b'],
    ],
    [
        'keys'     => ['wav-wall-panel'],
        'patterns' => ['\\bwav\\b'],
    ],
    [
        'keys'     => ['ssh-multipro'],
        'patterns' => ['\\bssh\\b'],
    ],
    [
        'keys'     => ['ssr-multipro-jr'],
        'patterns' => ['\\bssr\\b'],
    ],
    [
        'keys'     => ['5vc-5v-crimp'],
        'patterns' => ['\\b5\\s*vc\\b', '\\b5v\\s*crimp\\b'],
    ],
];
const MACHINE_CATEGORY_INTENT_GROUPS = [
    [
        'category' => 'gutter-machines',
        'phrases'  => ['gutter machine', 'seamless gutter', 'box gutter machine', 'k style gutter'],
    ],
    [
        'category' => 'roof-wall-panel-machines',
        'phrases'  => ['roof panel machine', 'roof wall panel machine', 'roof and wall panel machine', 'standing seam machine', 'wall panel machine'],
    ],
];
const MACHINE_MODIFIER_INTENT_GROUPS = [
    [
        'group'   => 'manual',
        'phrases' => ['manual', 'manuals', 'guide', 'guides', 'pdf', 'operation', 'operator', 'operators', 'owner', 'owners'],
    ],
    [
        'group'   => 'service',
        'phrases' => ['service', 'services', 'troubleshoot', 'troubleshooting', 'repair', 'repairs', 'maintenance'],
    ],
    [
        'group'   => 'accessory',
        'phrases' => ['part', 'parts', 'cover', 'covers', 'cart', 'carts', 'controller', 'controllers', 'accessory', 'accessories'],
    ],
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

/**
 * Collapse simple English plurals ("machines" → "machine") so intent
 * phrases written in the singular also match plural queries. Only words
 * of four or more letters ending in a single "s" lose it; short words
 * ("gas") and "ss" endings ("stainless") stay untouched.
 */
function singularize_search_text(string $normalized): string {
    $words = array_map(
        static fn(string $word): string =>
            preg_match('/^[a-z]{3,}s$/', $word) === 1 && !str_ends_with($word, 'ss')
                ? substr($word, 0, -1)
                : $word,
        explode(' ', $normalized)
    );

    return implode(' ', $words);
}

function normalized_contains_phrase(string $normalized, string $phrase): bool {
    $phrase = normalize_search_text($phrase);

    return $phrase !== '' && preg_match('/(?:^|\s)' . preg_quote($phrase, '/') . '(?:\s|$)/', $normalized) === 1;
}

/**
 * @param string[] $phrases
 */
function normalized_contains_any_phrase(string $normalized, array $phrases): bool {
    foreach ($phrases as $phrase) {
        if (normalized_contains_phrase($normalized, (string) $phrase)) {
            return true;
        }
    }

    return false;
}

/**
 * @param string[] $patterns
 */
function normalized_matches_any_pattern(string $normalized, array $patterns): bool {
    foreach ($patterns as $pattern) {
        $pattern = (string) $pattern;
        if ($pattern !== '' && preg_match('~' . $pattern . '~', $normalized) === 1) {
            return true;
        }
    }

    return false;
}

/**
 * @return array<int, array{keys:string[],patterns:string[],family?:bool}>
 */
function get_machine_exact_intent_groups(): array {
    return MACHINE_EXACT_INTENT_GROUPS;
}

/**
 * @return array<int, array{category:string,phrases:string[]}>
 */
function get_machine_category_intent_groups(): array {
    return MACHINE_CATEGORY_INTENT_GROUPS;
}

/**
 * @return array<int, array{group:string,phrases:string[]}>
 */
function get_machine_modifier_intent_groups(): array {
    return MACHINE_MODIFIER_INTENT_GROUPS;
}

/**
 * @return array<string, array<int, array{key:string,title:string,slug:string,active?:bool}>>
 */
function get_machine_search_catalog(): array {
    return MACHINE_SEARCH_CATALOG;
}

/**
 * @return array<int, array{key:string,title:string,slug:string,active?:bool,category:string}>
 */
function get_machine_search_catalog_items(): array {
    $items = [];

    foreach (get_machine_search_catalog() as $category => $machines) {
        foreach ($machines as $machine) {
            $machine['category'] = $category;
            $items[] = $machine;
        }
    }

    return $items;
}

/**
 * @return string[]
 */
function get_machine_data_keys(): array {
    return array_values(array_unique(array_map(
        static fn(array $machine): string => $machine['key'],
        get_machine_search_catalog_items()
    )));
}

/**
 * @return string[]
 */
function get_machine_search_category_keys(string $category): array {
    $catalog = get_machine_search_catalog();
    if (!isset($catalog[$category])) {
        return [];
    }

    $keys = [];
    foreach ($catalog[$category] as $machine) {
        if (array_key_exists('active', $machine) && $machine['active'] === false) {
            continue;
        }
        $keys[] = $machine['key'];
    }

    return $keys;
}

/**
 * Small, git-owned manifest for instant modal suggestions.
 *
 * This intentionally does not resolve post IDs or call WooCommerce. The REST
 * reconciliation pass supplies fresh titles, IDs, and click-tracked URLs.
 *
 * @return array{
 *   limit:int,
 *   machines:array<int, array{key:string,title:string,url:string,subtype:string,category:string,active:bool}>,
 *   categories:array<string, string[]>,
 *   exactGroups:array<int, array{keys:string[],patterns:string[],family?:bool}>,
 *   categoryGroups:array<int, array{category:string,phrases:string[],keys:string[]}>,
 *   modifierGroups:array<int, array{phrases:string[]}>
 * }
 */
function get_machine_suggestion_manifest(): array {
    $machines = [];
    $categories = get_active_machine_keys_by_product_category();

    foreach (get_machine_search_catalog_items() as $machine) {
        $machines[] = [
            'key'      => $machine['key'],
            'title'    => $machine['title'],
            'url'      => \Standard\Url\internal('/machines/' . $machine['category'] . '/' . $machine['slug'] . '/'),
            'subtype'  => 'product',
            'category' => $machine['category'],
            'active'   => !array_key_exists('active', $machine) || $machine['active'] !== false,
        ];
    }

    $category_groups = [];
    foreach (get_machine_category_intent_groups() as $group) {
        $category_groups[] = [
            'category' => $group['category'],
            'phrases' => $group['phrases'],
            'keys'    => $categories[$group['category']] ?? [],
        ];
    }

    $modifier_groups = [];
    foreach (get_machine_modifier_intent_groups() as $group) {
        $modifier_groups[] = [
            'phrases' => $group['phrases'],
        ];
    }

    return [
        'limit'          => 5,
        'machines'       => $machines,
        'categories'     => $categories,
        'exactGroups'    => get_machine_exact_intent_groups(),
        'categoryGroups' => $category_groups,
        'modifierGroups' => $modifier_groups,
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
    foreach (get_machine_search_catalog_items() as $machine) {
        $cache[$machine['key']] = array_values(array_unique([
            $machine['key'],
            $machine['slug'],
        ]));
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
        'roof-wall-panel-machines' => get_machine_search_category_keys('roof-wall-panel-machines'),
        'gutter-machines'          => get_machine_search_category_keys('gutter-machines'),
    ];

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
function canonical_machine_product_ids_request_cache(?array $value = null, bool $set = false): ?array {
    static $cache = null;

    if ($set) {
        $cache = $value;
    }

    return $cache;
}

/**
 * @param mixed $value
 * @return array<string, int>|null
 */
function normalize_cached_canonical_machine_product_ids($value): ?array {
    if (!is_array($value)) {
        return null;
    }

    $ids = [];
    foreach ($value as $key => $post_id) {
        $key = \sanitize_key((string) $key);
        $post_id = (int) $post_id;
        if ($key !== '' && $post_id > 0) {
            $ids[$key] = $post_id;
        }
    }

    return $ids;
}

/**
 * @return array<string, int>
 */
function resolve_canonical_machine_product_ids(): array {
    $ids = [];
    if (!\post_type_exists('product')) {
        return $ids;
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

            $ids[$key] = (int) $post->ID;
            break;
        }
    }

    return $ids;
}

/**
 * @return array<string, int>
 */
function get_canonical_machine_product_ids(): array {
    $request_cache = canonical_machine_product_ids_request_cache();
    if ($request_cache !== null) {
        return $request_cache;
    }

    $found = false;
    $cached = \wp_cache_get(CANONICAL_MACHINE_PRODUCT_IDS_CACHE_KEY, CACHE_GROUP, false, $found);
    $ids = $found ? normalize_cached_canonical_machine_product_ids($cached) : null;

    if ($ids === null) {
        $ids = resolve_canonical_machine_product_ids();
        \wp_cache_set(CANONICAL_MACHINE_PRODUCT_IDS_CACHE_KEY, $ids, CACHE_GROUP);
    }

    canonical_machine_product_ids_request_cache($ids, true);

    return $ids;
}

function flush_canonical_machine_product_id_cache(): void {
    canonical_machine_product_ids_request_cache(null, true);
    \wp_cache_delete(CANONICAL_MACHINE_PRODUCT_IDS_CACHE_KEY, CACHE_GROUP);
}

function flush_canonical_machine_product_id_cache_for_product_save(int $post_id, \WP_Post $post, bool $update): void {
    unset($update);

    if ($post->post_type !== 'product' || \wp_is_post_autosave($post_id) || \wp_is_post_revision($post_id)) {
        return;
    }

    flush_canonical_machine_product_id_cache();
}

function flush_canonical_machine_product_id_cache_for_deleted_post(int $post_id, \WP_Post $post): void {
    unset($post_id);

    if ($post->post_type === 'product') {
        flush_canonical_machine_product_id_cache();
    }
}

function flush_canonical_machine_product_id_cache_for_product_slug_change(int $post_id, \WP_Post $post_after, \WP_Post $post_before): void {
    unset($post_id);

    if ($post_after->post_type !== 'product') {
        return;
    }

    if ((string) $post_after->post_name !== (string) $post_before->post_name) {
        flush_canonical_machine_product_id_cache();
    }
}

/**
 * @return array<int, string>
 */
function get_canonical_machine_keys_by_product_id(): array {
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
    $singular = singularize_search_text($normalized);
    $compact = compact_search_text($query);

    // Phrase lists are written in the singular; match the query both as
    // typed and singularized so "gutter machines" carries the same intent
    // as "gutter machine".
    $contains_any_phrase = static function (array $phrases) use ($normalized, $singular): bool {
        return normalized_contains_any_phrase($normalized, $phrases)
            || ($singular !== $normalized && normalized_contains_any_phrase($singular, $phrases));
    };
    $exact_keys = [];
    $family_order = [];
    $active_by_category = get_active_machine_keys_by_product_category();

    $add_key = static function (string $key) use (&$exact_keys): void {
        if (!in_array($key, $exact_keys, true)) {
            $exact_keys[] = $key;
        }
    };

    foreach (get_machine_exact_intent_groups() as $group) {
        if (!normalized_matches_any_pattern($normalized, $group['patterns'])) {
            continue;
        }

        $keys = $group['keys'];
        if (!empty($group['family'])) {
            $family_order = array_values(array_filter(
                $active_by_category['gutter-machines'] ?? [],
                static fn(string $key): bool => str_starts_with($key, 'mach-ii-')
            ));
            $keys = $family_order !== [] ? $family_order : $keys;
        }

        foreach ($keys as $key) {
            $add_key($key);
        }
    }

    $category_keys = [];
    foreach (get_machine_category_intent_groups() as $group) {
        if ($contains_any_phrase($group['phrases'])) {
            $category_keys = array_merge($category_keys, $active_by_category[$group['category']] ?? []);
        }
    }

    $modifier_groups = [];
    foreach (get_machine_modifier_intent_groups() as $group) {
        if ($contains_any_phrase($group['phrases'])) {
            $modifier_groups[] = $group['group'];
        }
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

    if (!is_string($url) || $url === '') {
        return '#';
    }

    // Relevanssi's click-tracking cache can capture the product permalink
    // before WooCommerce substitutes %product_cat% (both filter
    // post_type_link at priority 10, Relevanssi first), then serve the
    // cached placeholder URL through relevanssi_get_permalink(). A literal
    // %product_cat% in an href is invalid percent-encoding — Cloudflare
    // rejects the click with a 400 — so re-run Woo's substitution here.
    if (strpos($url, '%product_cat%') !== false && \function_exists('wc_product_post_type_link')) {
        $url = \wc_product_post_type_link($url, $post);
    }

    return $url;
}

function encode_relevanssi_click_tracking_value(string $value): string {
    if (\function_exists('relevanssi_base64url_encode')) {
        return \relevanssi_base64url_encode($value);
    }

    return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
}

/**
 * Mirror Relevanssi Premium 2.29 click-tracking payloads for modal REST
 * results. Relevanssi decorates loop permalinks via relevanssi_get_permalink(),
 * but JSON search results bypass that path; the smoke script decodes this
 * intentional coupling so plugin drift fails loudly.
 */
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
 * @return array{id:int,title:string,url:string,subtype:string,machineKey:string}
 */
function format_rest_search_result(\WP_Post $post, string $search = '', int $rank = 0): array {
    $machine_keys = $post->post_type === 'product' ? get_canonical_machine_keys_by_product_id() : [];

    return [
        'id'         => (int) $post->ID,
        'title'      => (string) \get_the_title($post),
        'url'        => get_rest_search_result_permalink($post, $search, $rank),
        'subtype'    => (string) $post->post_type,
        'machineKey' => $machine_keys[(int) $post->ID] ?? '',
    ];
}

/**
 * @param string[] $post_types
 * @return string[]
 */
function normalize_rest_suggestion_cache_post_types(array $post_types): array {
    $normalized = array_values(array_filter(array_map(
        static fn($post_type): string => \sanitize_key((string) $post_type),
        $post_types
    )));
    sort($normalized);

    return array_values(array_unique($normalized));
}

function get_rest_suggestion_cache_version(): string {
    $version = \get_option(REST_SUGGESTION_CACHE_VERSION_OPTION, '1');

    return is_scalar($version) && (string) $version !== '' ? (string) $version : '1';
}

/**
 * @param string[] $post_types
 */
function get_rest_suggestion_cache_key(string $normalized_query, array $post_types, int $limit): string {
    $payload = \wp_json_encode([
        'v'       => get_rest_suggestion_cache_version(),
        'query'   => $normalized_query,
        'subtype' => normalize_rest_suggestion_cache_post_types($post_types),
        'limit'   => $limit,
    ]);

    if (!is_string($payload)) {
        $payload = $normalized_query . '|' . implode(',', normalize_rest_suggestion_cache_post_types($post_types)) . '|' . $limit;
    }

    return REST_SUGGESTION_CACHE_PREFIX . md5($payload);
}

/**
 * @param mixed $value
 * @return int[]|null
 */
function normalize_cached_rest_suggestion_ids($value): ?array {
    if (!is_array($value)) {
        return null;
    }

    $ids = [];
    foreach ($value as $post_id) {
        $post_id = (int) $post_id;
        if ($post_id > 0) {
            $ids[] = $post_id;
        }
    }

    return array_values(array_unique($ids));
}

/**
 * @return int[]|null
 */
function get_cached_rest_suggestion_ids(string $cache_key): ?array {
    $cached = \get_transient($cache_key);

    return normalize_cached_rest_suggestion_ids($cached);
}

/**
 * @param int[] $ids
 */
function set_cached_rest_suggestion_ids(string $cache_key, array $ids): void {
    \set_transient($cache_key, array_values(array_unique(array_map('intval', $ids))), REST_SUGGESTION_CACHE_TTL);
}

function flush_rest_suggestion_cache(): void {
    \update_option(REST_SUGGESTION_CACHE_VERSION_OPTION, sprintf('%.6F', microtime(true)), false);
}

function post_type_affects_rest_suggestion_cache(string $post_type): bool {
    return in_array(\sanitize_key($post_type), get_searchable_post_types(), true);
}

function flush_rest_suggestion_cache_for_post_save(int $post_id, \WP_Post $post, bool $update): void {
    unset($update);

    if (\wp_is_post_autosave($post_id) || \wp_is_post_revision($post_id)) {
        return;
    }

    if (post_type_affects_rest_suggestion_cache($post->post_type)) {
        flush_rest_suggestion_cache();
    }
}

function flush_rest_suggestion_cache_for_deleted_post(int $post_id, \WP_Post $post): void {
    unset($post_id);

    if (post_type_affects_rest_suggestion_cache($post->post_type)) {
        flush_rest_suggestion_cache();
    }
}

function flush_rest_suggestion_cache_for_status_change(string $new_status, string $old_status, \WP_Post $post): void {
    if ($new_status === $old_status || !post_type_affects_rest_suggestion_cache($post->post_type)) {
        return;
    }

    if ($new_status === 'publish' || $old_status === 'publish') {
        flush_rest_suggestion_cache();
    }
}

/**
 * @param mixed $terms
 * @param mixed $tt_ids
 * @param mixed $old_tt_ids
 */
function flush_search_caches_for_term_change(int $object_id, $terms, $tt_ids, string $taxonomy, bool $append, $old_tt_ids): void {
    unset($terms, $tt_ids, $append, $old_tt_ids);

    if (!in_array($taxonomy, ['category', 'post_tag', 'machine', 'content_department', 'product_cat', 'product_tag'], true)) {
        return;
    }

    $post = \get_post($object_id);
    if (!$post instanceof \WP_Post || !post_type_affects_rest_suggestion_cache($post->post_type)) {
        return;
    }

    if ($taxonomy === 'product_cat' && $post->post_type === 'product') {
        flush_canonical_machine_product_id_cache();
    }

    flush_rest_suggestion_cache();
}

/**
 * @param string[] $post_types
 * @return int[]
 */
function query_rest_suggestion_result_ids(string $search, array $post_types, int $per_page): array {
    if ($post_types === []) {
        return [];
    }

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
    $ids = [];

    foreach (array_values($query->posts) as $post) {
        if ($post instanceof \WP_Post) {
            $ids[] = (int) $post->ID;
        }
    }

    return array_values(array_unique($ids));
}

/**
 * @param int[]    $ids
 * @param string[] $post_types
 * @return array<int, array{id:int,title:string,url:string,subtype:string,machineKey:string}>
 */
function format_rest_search_results_from_ids(array $ids, string $search, array $post_types, int $limit): array {
    $items = [];
    $allowed = array_fill_keys($post_types, true);

    foreach ($ids as $post_id) {
        if (count($items) >= $limit) {
            break;
        }

        $post = \get_post((int) $post_id);
        if (!$post instanceof \WP_Post || $post->post_status !== 'publish') {
            continue;
        }

        if (!isset($allowed[$post->post_type]) || is_excluded_post_type((string) $post->post_type)) {
            continue;
        }

        $items[] = format_rest_search_result($post, $search, count($items) + 1);
    }

    return $items;
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
    $normalized_query = normalize_search_text($search);
    if ($normalized_query === '') {
        return \rest_ensure_response([]);
    }

    $cache_key = get_rest_suggestion_cache_key($normalized_query, $post_types, $per_page);
    $ids = get_cached_rest_suggestion_ids($cache_key);

    if ($ids === null) {
        $ids = query_rest_suggestion_result_ids($search, $post_types, $per_page);
        set_cached_rest_suggestion_ids($cache_key, $ids);
    }

    $items = format_rest_search_results_from_ids($ids, $search, $post_types, $per_page);

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
\add_action('save_post', __NAMESPACE__ . '\\flush_rest_suggestion_cache_for_post_save', 10, 3);
\add_action('save_post_product', __NAMESPACE__ . '\\flush_canonical_machine_product_id_cache_for_product_save', 10, 3);
\add_action('deleted_post', __NAMESPACE__ . '\\flush_canonical_machine_product_id_cache_for_deleted_post', 10, 2);
\add_action('deleted_post', __NAMESPACE__ . '\\flush_rest_suggestion_cache_for_deleted_post', 10, 2);
\add_action('post_updated', __NAMESPACE__ . '\\flush_canonical_machine_product_id_cache_for_product_slug_change', 10, 3);
\add_action('transition_post_status', __NAMESPACE__ . '\\flush_rest_suggestion_cache_for_status_change', 10, 3);
\add_action('set_object_terms', __NAMESPACE__ . '\\flush_search_caches_for_term_change', 10, 6);
\add_filter('rest_post_search_query', __NAMESPACE__ . '\\configure_rest_post_search_query', 10, 2);
\add_filter('option_relevanssi_post_type_weights', __NAMESPACE__ . '\\tune_relevanssi_post_type_weights');
\add_filter('option_relevanssi_index_post_types', __NAMESPACE__ . '\\sanitize_relevanssi_index_post_types');
\add_filter('option_relevanssi_title_boost', __NAMESPACE__ . '\\tune_relevanssi_title_boost');
\add_filter('option_relevanssi_content_boost', __NAMESPACE__ . '\\tune_relevanssi_content_boost');
\add_filter('relevanssi_hits_filter', __NAMESPACE__ . '\\apply_machine_relevance_contract', 20, 2);
\add_filter('relevanssi_do_not_index', __NAMESPACE__ . '\\exclude_relevanssi_indexed_post_types', 10, 3);
\add_filter('relevanssi_post_ok', __NAMESPACE__ . '\\exclude_relevanssi_result_post_types', 10, 2);
