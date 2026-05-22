<?php
/**
 * WP-CLI smoke checks for the theme search contract.
 *
 * Run from a loaded WordPress install:
 * wp eval-file wp-content/themes/amt-ntm/scripts/search-smoke.php --allow-root
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run this through WP-CLI after WordPress has loaded.\n");
    exit(1);
}

/**
 * @param array<string, string> $get
 * @param array<string, mixed>  $query_args
 */
function standard_search_smoke_query(array $get, array $query_args): \WP_Query {
    $previous_get = $_GET;
    $_GET = $get;

    $query = new \WP_Query();
    $previous_main_query = $GLOBALS['wp_the_query'] ?? null;
    $GLOBALS['wp_the_query'] = $query;

    $query->query($query_args);

    if ($previous_main_query instanceof \WP_Query) {
        $GLOBALS['wp_the_query'] = $previous_main_query;
    } else {
        unset($GLOBALS['wp_the_query']);
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
