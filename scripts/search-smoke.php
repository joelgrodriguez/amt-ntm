<?php
/**
 * WP-CLI smoke checks for the theme search contract.
 *
 * Run from a loaded WordPress install:
 * wp eval-file wp-content/themes/amt-ntm/scripts/search-smoke.php --use-include --allow-root
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run this through WP-CLI after WordPress has loaded.\n");
    exit(1);
}

function standard_search_smoke_load_search_contract(): void {
    if (function_exists('Standard\\Search\\get_searchable_post_types')) {
        return;
    }

    $root = dirname(__DIR__);
    foreach ([
        'app/inc/urls.php',
        'app/inc/woo/cache.php',
        'app/inc/machine-product-data.php',
        'app/inc/machines-data.php',
        'app/inc/search.php',
    ] as $file) {
        $path = $root . '/' . $file;
        if (file_exists($path)) {
            require_once $path;
        }
    }
}

standard_search_smoke_load_search_contract();

/**
 * @param array<string, string> $get
 * @param array<string, mixed>  $query_args
 */
function standard_search_smoke_query(array $get, array $query_args): \WP_Query {
    $previous_get = $_GET;
    $_GET = $get;

    $query = new \WP_Query();
    $previous_main_query = $GLOBALS['wp_the_query'] ?? null;
    $previous_query = $GLOBALS['wp_query'] ?? null;
    $GLOBALS['wp_the_query'] = $query;
    $GLOBALS['wp_query'] = $query;

    $query->query($query_args);

    if ($previous_main_query instanceof \WP_Query) {
        $GLOBALS['wp_the_query'] = $previous_main_query;
    } else {
        unset($GLOBALS['wp_the_query']);
    }
    if ($previous_query instanceof \WP_Query) {
        $GLOBALS['wp_query'] = $previous_query;
    } else {
        unset($GLOBALS['wp_query']);
    }

    $_GET = $previous_get;

    return $query;
}

function standard_search_smoke_fail(string $message): void {
    if (class_exists('WP_CLI')) {
        \WP_CLI::error($message);
    }

    fwrite(STDERR, "FAIL: {$message}\n");
    exit(1);
}

function standard_search_smoke_pass(string $message): void {
    if (class_exists('WP_CLI')) {
        \WP_CLI::log("PASS: {$message}");
        return;
    }

    echo "PASS: {$message}\n";
}

/**
 * @param mixed $actual
 * @param mixed $expected
 */
function standard_search_smoke_assert_same($actual, $expected, string $message): void {
    if ($actual !== $expected) {
        standard_search_smoke_fail($message . ' Expected ' . wp_json_encode($expected) . ', got ' . wp_json_encode($actual) . '.');
    }

    standard_search_smoke_pass($message);
}

function standard_search_smoke_assert_true(bool $condition, string $message): void {
    if (!$condition) {
        standard_search_smoke_fail($message);
    }

    standard_search_smoke_pass($message);
}

/**
 * @param string[] $blocked
 */
function standard_search_smoke_assert_no_post_types(\WP_Query $query, array $blocked, string $message): void {
    foreach ($query->posts as $post) {
        if ($post instanceof \WP_Post && in_array($post->post_type, $blocked, true)) {
            standard_search_smoke_fail($message . ' Found blocked post type "' . $post->post_type . '" in "' . $post->post_title . '".');
        }
    }

    standard_search_smoke_pass($message);
}

/**
 * @return mixed
 */
function standard_search_smoke_with_query_globals(\WP_Query $query, callable $callback) {
    $previous_main_query = $GLOBALS['wp_the_query'] ?? null;
    $previous_query = $GLOBALS['wp_query'] ?? null;
    $GLOBALS['wp_the_query'] = $query;
    $GLOBALS['wp_query'] = $query;

    try {
        return $callback();
    } finally {
        if ($previous_main_query instanceof \WP_Query) {
            $GLOBALS['wp_the_query'] = $previous_main_query;
        } else {
            unset($GLOBALS['wp_the_query']);
        }
        if ($previous_query instanceof \WP_Query) {
            $GLOBALS['wp_query'] = $previous_query;
        } else {
            unset($GLOBALS['wp_query']);
        }
    }
}

/**
 * @return string[]
 */
function standard_search_smoke_machine_slugs(string $machine_key): array {
    if (function_exists('Standard\\Search\\get_machine_product_slug_candidates_by_key')) {
        $map = \Standard\Search\get_machine_product_slug_candidates_by_key();

        return $map[$machine_key] ?? [$machine_key];
    }

    return [$machine_key];
}

/**
 * @param string[] $categories
 */
function standard_search_smoke_product_id_by_slug_category(array $slugs, array $categories, string $label): int {
    foreach ($slugs as $slug) {
        $post = get_page_by_path($slug, OBJECT, 'product');
        if (!$post instanceof \WP_Post || $post->post_status !== 'publish') {
            continue;
        }

        if ($categories !== [] && taxonomy_exists('product_cat') && !has_term($categories, 'product_cat', $post->ID)) {
            continue;
        }

        return (int) $post->ID;
    }

    standard_search_smoke_fail("Could not resolve {$label} product by slug/category.");
}

function standard_search_smoke_machine_product_id(string $machine_key): int {
    return standard_search_smoke_product_id_by_slug_category(
        standard_search_smoke_machine_slugs($machine_key),
        ['roof-wall-panel-machines', 'gutter-machines'],
        $machine_key
    );
}

/**
 * @param string[] $machine_keys
 * @return int[]
 */
function standard_search_smoke_machine_product_ids(array $machine_keys): array {
    return array_map('standard_search_smoke_machine_product_id', $machine_keys);
}

function standard_search_smoke_first_post(\WP_Query $query, string $message): \WP_Post {
    $post = $query->posts[0] ?? null;
    if (!$post instanceof \WP_Post) {
        standard_search_smoke_fail($message . ' Search returned no first result.');
    }

    return $post;
}

function standard_search_smoke_rank(\WP_Query $query, int $post_id): ?int {
    foreach (array_values($query->posts) as $index => $post) {
        if ($post instanceof \WP_Post && (int) $post->ID === $post_id) {
            return $index + 1;
        }
    }

    return null;
}

function standard_search_smoke_result_text(\WP_Post $post): string {
    $value = $post->post_title . ' ' . $post->post_name . ' ' . $post->post_type;
    $value = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    $value = strtolower((string) remove_accents($value));
    $value = preg_replace('/[^a-z0-9]+/', ' ', $value) ?? $value;

    return trim($value);
}

function standard_search_smoke_assert_url_has_tracking(string $url, string $message): void {
    if ((string) get_option('relevanssi_click_tracking', 'off') !== 'on' || !function_exists('relevanssi_log_click')) {
        standard_search_smoke_pass($message . ' Click tracking is unavailable/off, so URL decoration is skipped.');
        return;
    }

    standard_search_smoke_assert_true(
        str_contains($url, '_rt=') && str_contains($url, '_rt_nonce='),
        $message . ' URL was: ' . $url
    );
}

function standard_search_smoke_assert_machine_intent(string $search, array $expected_exact_keys, string $message): void {
    if (!function_exists('Standard\\Search\\get_machine_search_intent')) {
        standard_search_smoke_fail('Machine intent parser is unavailable.');
    }

    $intent = \Standard\Search\get_machine_search_intent($search);
    standard_search_smoke_assert_same($intent['exact_keys'], $expected_exact_keys, $message);
}

function standard_search_smoke_assert_source_contracts(): void {
    $root = dirname(__DIR__);
    $search_source = file_get_contents($root . '/app/inc/search.php') ?: '';
    $content_source = file_get_contents($root . '/app/templates/parts/content-search.php') ?: '';
    $modal_source = file_get_contents($root . '/app/resources/js/modules/SearchModal.js') ?: '';

    if (preg_match('/function\s+get_relevanssi_index_post_types\b.*?^}/ms', $search_source, $match) !== 1) {
        standard_search_smoke_fail('Could not inspect get_relevanssi_index_post_types source.');
    }

    standard_search_smoke_assert_true(
        !str_contains($match[0], 'post_type_exists'),
        'Relevanssi index post-type contract is not filtered by registration timing.'
    );
    standard_search_smoke_assert_true(
        str_contains($search_source, "add_filter('option_relevanssi_index_post_types'"),
        'Relevanssi index post-type cleanup is persisted in git-owned theme code.'
    );
    standard_search_smoke_assert_true(
        str_contains($modal_source, '/wp-json/standard/v1/search')
        && !str_contains($modal_source, '/wp-json/wp/v2/search'),
        'Search modal uses the theme REST endpoint instead of native WP search.'
    );
    standard_search_smoke_assert_true(
        str_contains($content_source, "get_template_part('templates/parts/card-post', null")
        && str_contains($content_source, 'get_search_result_permalink'),
        'Generic card-post search results receive the tracked search URL override.'
    );
}

/**
 * @param callable(\WP_Post): bool $predicate
 */
function standard_search_smoke_first_matching_rank(\WP_Query $query, callable $predicate): ?int {
    foreach (array_values($query->posts) as $index => $post) {
        if ($post instanceof \WP_Post && $predicate($post)) {
            return $index + 1;
        }
    }

    return null;
}

function standard_search_smoke_assert_top_machine(string $search, string $machine_key): void {
    $expected_id = standard_search_smoke_machine_product_id($machine_key);
    $query = standard_search_smoke_query([], ['s' => $search]);
    $first = standard_search_smoke_first_post($query, "{$search} leads with {$machine_key}.");

    standard_search_smoke_assert_same(
        (int) $first->ID,
        $expected_id,
        "{$search} leads with canonical {$machine_key} machine product."
    );
}

/**
 * @param string[] $machine_keys
 */
function standard_search_smoke_assert_top_in_machine_set(string $search, array $machine_keys, string $message): void {
    $expected_ids = standard_search_smoke_machine_product_ids($machine_keys);
    $query = standard_search_smoke_query([], ['s' => $search]);
    $first = standard_search_smoke_first_post($query, $message);

    standard_search_smoke_assert_true(
        in_array((int) $first->ID, $expected_ids, true),
        $message . ' First result was "' . $first->post_title . '".'
    );
}

/**
 * @param callable(\WP_Post): bool $modifier_predicate
 * @param string[] $machine_keys
 */
function standard_search_smoke_assert_modifier_beats_machine(
    string $search,
    callable $modifier_predicate,
    array $machine_keys,
    string $message
): void {
    $query = standard_search_smoke_query([], ['s' => $search]);
    $modifier_rank = standard_search_smoke_first_matching_rank($query, $modifier_predicate);
    if ($modifier_rank === null) {
        standard_search_smoke_fail($message . ' No matching modifier result found.');
    }

    $machine_rank = null;
    foreach (standard_search_smoke_machine_product_ids($machine_keys) as $machine_id) {
        $rank = standard_search_smoke_rank($query, $machine_id);
        if ($rank !== null) {
            $machine_rank = $machine_rank === null ? $rank : min($machine_rank, $rank);
        }
    }

    standard_search_smoke_assert_true(
        $machine_rank === null || $modifier_rank < $machine_rank,
        $message . " Modifier rank {$modifier_rank}, machine rank " . ($machine_rank ?? 'not present') . '.'
    );
}

/**
 * @return int[]
 */
function standard_search_smoke_query_ids(\WP_Query $query, int $limit = 5): array {
    $ids = [];
    foreach (array_slice($query->posts, 0, $limit) as $post) {
        if ($post instanceof \WP_Post) {
            $ids[] = (int) $post->ID;
        }
    }

    return $ids;
}

/**
 * @return int[]
 */
function standard_search_smoke_rest_ids(string $search, string $subtype = '', int $limit = 5): array {
    return array_map(
        static fn(array $item): int => (int) ($item['id'] ?? 0),
        standard_search_smoke_rest_items($search, $subtype, $limit)
    );
}

/**
 * @return array<int, array<string, mixed>>
 */
function standard_search_smoke_rest_items(string $search, string $subtype = '', int $limit = 5): array {
    if (!function_exists('Standard\\Search\\handle_rest_search_request')) {
        standard_search_smoke_fail('REST search handler is unavailable.');
    }

    $request = new \WP_REST_Request('GET', '/standard/v1/search');
    $request->set_param('search', $search);
    $request->set_param('per_page', $limit);
    if ($subtype !== '') {
        $request->set_param('subtype', $subtype);
    }

    $response = \Standard\Search\handle_rest_search_request($request);
    $data = $response instanceof \WP_REST_Response ? $response->get_data() : [];

    if (!is_array($data)) {
        standard_search_smoke_fail('REST search handler returned a non-array payload.');
    }

    return array_values(array_filter($data, 'is_array'));
}

function standard_search_smoke_assert_rest_parity(string $search, string $subtype = ''): void {
    $get = $subtype !== '' ? ['post_type' => $subtype] : [];
    $query = standard_search_smoke_query($get, ['s' => $search]);
    $page_ids = standard_search_smoke_query_ids($query);
    $rest_ids = standard_search_smoke_rest_ids($search, $subtype);

    standard_search_smoke_assert_same(
        $rest_ids,
        $page_ids,
        "REST/modal top results match full-page search for {$search}" . ($subtype !== '' ? " ({$subtype})" : '') . '.'
    );
}

$blocked_post_types = ['pricesheet', 'cutlist', 'attachment'];
$blocked_index_post_types = ['pricesheet', 'cutlist'];

$default = standard_search_smoke_query([], ['s' => 'brochure']);
standard_search_smoke_assert_true((int) $default->found_posts > 0, 'Keyword search returns results.');
standard_search_smoke_assert_no_post_types($default, $blocked_post_types, 'Keyword search excludes blocked post types.');

$literature_found = false;
foreach ($default->posts as $post) {
    if ($post instanceof \WP_Post && $post->post_type === 'literature') {
        $literature_found = true;
        break;
    }
}
standard_search_smoke_assert_true($literature_found, 'Keyword search includes literature results.');

$empty = standard_search_smoke_query([], ['s' => '']);
standard_search_smoke_assert_same((int) $empty->found_posts, 0, 'Empty search returns zero results.');

$invalid_type = standard_search_smoke_query(['type' => 'not-real'], ['s' => 'gutter']);
standard_search_smoke_assert_same((int) $invalid_type->found_posts, 0, 'Invalid post type filter returns zero results.');

$pricesheet = standard_search_smoke_query(['post_type' => 'pricesheet'], ['s' => 'pricing']);
standard_search_smoke_assert_same((int) $pricesheet->found_posts, 0, 'Pricesheet filter returns zero results.');

$manuals = standard_search_smoke_query(['type' => 'manual'], ['s' => 'manual']);
standard_search_smoke_assert_true((int) $manuals->found_posts > 0, 'Manual type filter returns results.');
foreach ($manuals->posts as $post) {
    if ($post instanceof \WP_Post && $post->post_type !== 'manual') {
        standard_search_smoke_fail('Manual type filter returned "' . $post->post_type . '" result "' . $post->post_title . '".');
    }
}
standard_search_smoke_pass('Manual type filter returns only manuals.');

$legacy_category = standard_search_smoke_query(['_sft_category' => 'testimonials'], ['s' => '']);
standard_search_smoke_assert_true((int) $legacy_category->found_posts > 0, 'Legacy _sft_category filter still resolves.');
standard_search_smoke_assert_no_post_types($legacy_category, $blocked_post_types, 'Legacy filter excludes blocked post types.');

$lc_category = standard_search_smoke_query(['lc_category' => 'seamless-gutter-rollforming-machines'], ['s' => '']);
standard_search_smoke_assert_true((int) $lc_category->found_posts > 0, 'Learning Center category filter-only search returns results.');
standard_search_smoke_assert_no_post_types($lc_category, $blocked_post_types, 'Learning Center category filter excludes blocked post types.');
standard_search_smoke_assert_same(
    apply_filters('relevanssi_prevent_default_request', true, $lc_category),
    false,
    'Learning Center filter-only search allows the default WP request.'
);

if (function_exists('relevanssi_do_query')) {
    standard_search_smoke_assert_top_machine('SSQ3', 'ssq3-multipro');
    standard_search_smoke_assert_top_machine('SSQ 3', 'ssq3-multipro');
    standard_search_smoke_assert_top_machine('SSQ2', 'ssq-ii-multipro');
    standard_search_smoke_assert_top_machine('MACH II', 'mach-ii-combo-gutter');
    standard_search_smoke_assert_top_machine('Mach 2', 'mach-ii-combo-gutter');
    standard_search_smoke_assert_top_machine('BG7', 'bg7-box-gutter');
    standard_search_smoke_assert_top_machine('WAV', 'wav-wall-panel');

    standard_search_smoke_assert_top_in_machine_set(
        'gutter machine',
        ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter', 'bg7-box-gutter'],
        'Generic gutter machine query leads with an active canonical gutter machine.'
    );
    standard_search_smoke_assert_top_in_machine_set(
        'roof panel machine',
        ['ssq3-multipro', 'ssh-multipro', 'ssr-multipro-jr', '5vc-5v-crimp', 'wav-wall-panel'],
        'Generic roof panel machine query leads with an active canonical roof/wall machine.'
    );

    standard_search_smoke_assert_modifier_beats_machine(
        'SSQ3 manual',
        static fn(\WP_Post $post): bool => $post->post_type === 'manual' && str_contains(standard_search_smoke_result_text($post), 'ssq3'),
        ['ssq3-multipro'],
        'SSQ3 manual query lets the manual outrank the machine.'
    );
    standard_search_smoke_assert_modifier_beats_machine(
        'BG7 manual',
        static fn(\WP_Post $post): bool => $post->post_type === 'manual' && str_contains(standard_search_smoke_result_text($post), 'bg7'),
        ['bg7-box-gutter'],
        'BG7 manual query lets the manual outrank the machine.'
    );
    standard_search_smoke_assert_modifier_beats_machine(
        'SSQ3 cover',
        static fn(\WP_Post $post): bool => $post->post_type === 'product' && str_contains(standard_search_smoke_result_text($post), 'cover'),
        ['ssq3-multipro'],
        'SSQ3 cover query lets the requested accessory outrank the machine.'
    );
    standard_search_smoke_assert_modifier_beats_machine(
        'MACH II cart',
        static fn(\WP_Post $post): bool => $post->post_type === 'product' && str_contains(standard_search_smoke_result_text($post), 'cart'),
        ['mach-ii-combo-gutter', 'mach-ii-5-gutter', 'mach-ii-6-gutter'],
        'MACH II cart query lets the requested accessory outrank MACH II machines.'
    );

    standard_search_smoke_assert_rest_parity('SSQ3');
    standard_search_smoke_assert_rest_parity('MACH II', 'product');
    standard_search_smoke_assert_rest_parity('SSQ3 manual');

    standard_search_smoke_assert_machine_intent('SSQ2', ['ssq-ii-multipro'], 'SSQ2 safely maps to SSQ II.');
    standard_search_smoke_assert_machine_intent('SSQ200 profile', [], 'SSQ200 profile does not map to SSQ II.');
    standard_search_smoke_assert_machine_intent('SSQ210A profile', [], 'SSQ210A profile does not map to SSQ II.');
    standard_search_smoke_assert_machine_intent('SSQ275 profile', [], 'SSQ275 profile does not map to SSQ II.');

    $rest_items = standard_search_smoke_rest_items('SSQ3');
    $first_rest_url = (string) ($rest_items[0]['url'] ?? '');
    standard_search_smoke_assert_url_has_tracking($first_rest_url, 'REST/modal result URLs preserve Relevanssi click tracking.');

    $post_only = standard_search_smoke_query(['post_type' => 'post'], ['s' => 'gutter']);
    $first_post = standard_search_smoke_first_post($post_only, 'Post-only search has a generic card-post result.');
    $tracked_post_url = standard_search_smoke_with_query_globals(
        $post_only,
        static fn(): string => \Standard\Search\get_search_result_permalink($first_post)
    );
    standard_search_smoke_assert_url_has_tracking($tracked_post_url, 'Generic card-post search URLs preserve Relevanssi click tracking.');
} else {
    standard_search_smoke_pass('Relevanssi is unavailable; machine ranking checks skipped without fatal errors.');
}

standard_search_smoke_assert_source_contracts();

foreach ($blocked_index_post_types as $blocked_post_type) {
    $posts = get_posts([
        'post_type'              => $blocked_post_type,
        'post_status'            => 'any',
        'posts_per_page'         => 1,
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    if ($posts === []) {
        standard_search_smoke_pass("No {$blocked_post_type} post exists to test index exclusion.");
        continue;
    }

    $post = $posts[0];
    $do_not_index = apply_filters('relevanssi_do_not_index', false, $post->ID, $post);
    standard_search_smoke_assert_true($do_not_index !== false, "{$blocked_post_type} is blocked from Relevanssi indexing.");

    $post_ok = apply_filters('relevanssi_post_ok', true, $post->ID);
    standard_search_smoke_assert_same($post_ok, false, "{$blocked_post_type} is blocked from Relevanssi results.");
}

if (class_exists('WP_CLI')) {
    \WP_CLI::success('Search smoke checks passed.');
}
