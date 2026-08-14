<?php
/**
 * Regression checks for public sales and configurator paths.
 *
 * Run with: php scripts/tests/conversion-paths.test.php
 */

declare(strict_types=1);

namespace {
    define('ABSPATH', __DIR__);

    /** @var array<string, callable|string> */
    $ntm_actions = [];

    function add_action(string $hook, callable|string $callback, int $priority = 10): bool
    {
        $GLOBALS['ntm_actions'][$hook] = $callback;
        return true;
    }

    function __(string $text, string $domain = 'default'): string
    {
        return $text;
    }

    function wp_http_validate_url(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    function home_url(string $path = ''): string
    {
        return 'https://newtechmachinery.com' . $path;
    }

    function content_url(string $path = ''): string
    {
        return 'https://newtechmachinery.com/wp-content' . $path;
    }

    function sanitize_key(string $key): string
    {
        return strtolower((string) preg_replace('/[^a-z0-9_\-]/', '', $key));
    }

    function wp_parse_url(string $url, int $component = -1): mixed
    {
        return parse_url($url, $component);
    }

    function untrailingslashit(string $value): string
    {
        return rtrim($value, '/\\');
    }

    function ntm_assert(bool $condition, string $message): void
    {
        if (!$condition) {
            throw new \RuntimeException($message);
        }
    }
}

namespace Standard\Url {
    function internal(string $path): string
    {
        return \home_url($path);
    }

    function canonical(string $url): string
    {
        return $url;
    }
}

namespace {
    require __DIR__ . '/../../app/inc/machine-product-data.php';
    require __DIR__ . '/../../app/inc/machines-data.php';
    require __DIR__ . '/../../app/inc/legacy-build-finance.php';

    ntm_assert(
        \Standard\MachinesData\get_configurator_url('mach-ii-combo-gutter')
            === 'https://newtechmachinery.com/configurator/machii/',
        'The featured MACH II CTA must resolve to the canonical configurator.'
    );
    ntm_assert(
        \Standard\MachinesData\get_configurator_url('mach-ii-5-6-combo-gutter-machine')
            === 'https://newtechmachinery.com/configurator/machii/',
        'A known WooCommerce alias must resolve to the canonical configurator.'
    );
    ntm_assert(
        \Standard\LegacyBuildFinance\get_redirect_path('ssr-multipro-jr-roof-panel-machine')
            === '/configurator/ssr/',
        'Known legacy machine requests must redirect to their machine configurator.'
    );
    ntm_assert(
        \Standard\LegacyBuildFinance\get_redirect_path('unknown-machine')
            === '/configurator/',
        'Unknown legacy machine requests must fall back to the configurator index.'
    );

    $active_theme_files = [
        __DIR__ . '/../../app/inc/machines-data.php',
        __DIR__ . '/../../app/templates/pages/machines/lineup-flagship.php',
        __DIR__ . '/../../app/templates/pages/machii/variant-matrix.php',
        __DIR__ . '/../../app/templates/parts/front-page/flagships.php',
    ];
    foreach ($active_theme_files as $file) {
        ntm_assert(
            !str_contains((string) file_get_contents($file), '/build-finance/'),
            basename($file) . ' must not emit the retired build-finance route.'
        );
    }

    echo "Conversion path tests passed.\n";
}
