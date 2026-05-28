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
        'product'    => 20,
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

function tune_relevanssi_title_boost($boost): int {
    return (int) \apply_filters('standard_search_relevanssi_title_boost', 40, $boost);
}

function tune_relevanssi_content_boost($boost): int {
    return (int) \apply_filters('standard_search_relevanssi_content_boost', 5, $boost);
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
    // emits a useless LIKE '%&#32;%' clause) and Relevanssi (which runs an
    // empty-keyword search and overwrites $query->posts with nothing). When
    // the user submitted no keyword but did pick a filter, neutralize WP's
    // search SQL (posts_search → '') and tell Relevanssi to bail
    // (relevanssi_search_ok → false). is_search stays true so search.php
    // is still selected as the template.
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
    if ($requested_post_types === []) {
        return;
    }

    $post_types = array_values(array_intersect($requested_post_types, get_searchable_post_types()));

    if ($post_types === []) {
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
\add_filter('rest_post_search_query', __NAMESPACE__ . '\\configure_rest_post_search_query', 10, 2);
\add_filter('option_relevanssi_post_type_weights', __NAMESPACE__ . '\\tune_relevanssi_post_type_weights');
\add_filter('option_relevanssi_title_boost', __NAMESPACE__ . '\\tune_relevanssi_title_boost');
\add_filter('option_relevanssi_content_boost', __NAMESPACE__ . '\\tune_relevanssi_content_boost');
\add_filter('relevanssi_do_not_index', __NAMESPACE__ . '\\exclude_relevanssi_indexed_post_types', 10, 3);
\add_filter('relevanssi_post_ok', __NAMESPACE__ . '\\exclude_relevanssi_result_post_types', 10, 2);
