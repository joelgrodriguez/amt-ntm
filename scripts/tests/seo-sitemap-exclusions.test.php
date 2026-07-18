<?php

declare(strict_types=1);

define('ABSPATH', __DIR__);

$GLOBALS['ntm_test_filters'] = [];
$GLOBALS['ntm_test_taxonomy_exists'] = true;
$GLOBALS['ntm_test_get_terms_calls'] = [];
$GLOBALS['ntm_test_get_terms_result'] = [78, 79, 272];

final class WP_Error
{
}

function add_action(string $hook_name, callable|string $callback, int $priority = 10, int $accepted_args = 1): bool
{
    return add_filter($hook_name, $callback, $priority, $accepted_args);
}

function add_filter(string $hook_name, callable|string $callback, int $priority = 10, int $accepted_args = 1): bool
{
    $GLOBALS['ntm_test_filters'][$hook_name][] = compact('callback', 'priority', 'accepted_args');

    return true;
}

function taxonomy_exists(string $taxonomy): bool
{
    return $taxonomy === 'product_cat' && $GLOBALS['ntm_test_taxonomy_exists'];
}

function get_terms(array $args): array|WP_Error
{
    $GLOBALS['ntm_test_get_terms_calls'][] = $args;

    return $GLOBALS['ntm_test_get_terms_result'];
}

function is_wp_error(mixed $value): bool
{
    return $value instanceof WP_Error;
}

require __DIR__ . '/../../app/inc/seo.php';

function ntm_test_assert_same(mixed $expected, mixed $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException($message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.');
    }
}

function ntm_test_assert_contains(mixed $needle, array $haystack, string $message): void
{
    if (!in_array($needle, $haystack, true)) {
        throw new RuntimeException($message . ' Missing ' . var_export($needle, true) . '.');
    }
}

$tests = [
    'registers Yoast term-ID exclusion hook' => function (): void {
        $callbacks = array_column($GLOBALS['ntm_test_filters']['wpseo_exclude_from_sitemap_by_term_ids'] ?? [], 'callback');

        ntm_test_assert_contains(
            'Standard\\Seo\\exclude_retired_product_category_terms_from_yoast_sitemaps',
            $callbacks,
            'SEO module should register the Yoast sitemap term exclusion callback'
        );
    },

    'resolves only retired product_cat slugs and preserves existing exclusions' => function (): void {
        $GLOBALS['ntm_test_taxonomy_exists'] = true;
        $GLOBALS['ntm_test_get_terms_calls'] = [];
        $GLOBALS['ntm_test_get_terms_result'] = [78, 79, 272];

        $actual = \Standard\Seo\exclude_retired_product_category_terms_from_yoast_sitemaps([5, '6', 78]);

        ntm_test_assert_same([5, 6, 78, 79, 272], $actual, 'Retired term IDs should merge with existing exclusions once');
        ntm_test_assert_same(1, count($GLOBALS['ntm_test_get_terms_calls']), 'Retired term lookup should run exactly once');
        ntm_test_assert_same([
            'taxonomy'   => 'product_cat',
            'slug'       => [
                'roof-wall-panel-machines',
                'gutter-machines',
                'accessories-add-on-equipment',
            ],
            'fields'     => 'ids',
            'hide_empty' => false,
        ], $GLOBALS['ntm_test_get_terms_calls'][0], 'Term lookup should use only stable retired product_cat slugs');
    },

    'leaves existing exclusions alone when product_cat is unavailable' => function (): void {
        $GLOBALS['ntm_test_taxonomy_exists'] = false;
        $GLOBALS['ntm_test_get_terms_calls'] = [];

        $actual = \Standard\Seo\exclude_retired_product_category_terms_from_yoast_sitemaps(['5', 6]);

        ntm_test_assert_same([5, 6], $actual, 'Missing Woo taxonomy should not erase existing exclusions');
        ntm_test_assert_same([], $GLOBALS['ntm_test_get_terms_calls'], 'Missing Woo taxonomy should not query terms');
    },

    'leaves existing exclusions alone when term lookup fails' => function (): void {
        $GLOBALS['ntm_test_taxonomy_exists'] = true;
        $GLOBALS['ntm_test_get_terms_result'] = new WP_Error();

        $actual = \Standard\Seo\exclude_retired_product_category_terms_from_yoast_sitemaps(['5', 6]);

        ntm_test_assert_same([5, 6], $actual, 'Failed term lookup should not erase existing exclusions');
    },
];

$failures = 0;

foreach ($tests as $name => $test) {
    try {
        $test();
        echo "PASS {$name}\n";
    } catch (Throwable $exception) {
        $failures++;
        echo "FAIL {$name}: {$exception->getMessage()}\n";
    }
}

if ($failures > 0) {
    exit(1);
}

echo 'SEO sitemap exclusion tests OK (' . count($tests) . ")\n";
