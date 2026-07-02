<?php
/**
 * Vite integration for asset loading.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

function get_vite_dev_server(): ?string {
    static $url = false;
    if ($url === false) {
        if (!use_vite_dev_server()) {
            $url = null;
            return $url;
        }

        $file = THEME_DIR . '/.vite-dev-server';
        $candidate = file_exists($file)
            ? normalize_vite_dev_server((string) file_get_contents($file))
            : null;

        // A stale .vite-dev-server file (dev server crashed, or wrote a
        // LAN IP that no longer answers) would enqueue dead asset URLs
        // and render the site with no CSS/JS. Only trust the file if
        // the server actually answers; otherwise fall back to dist/.
        $url = ($candidate !== null && is_vite_dev_server_reachable($candidate))
            ? $candidate
            : null;
    }
    return $url;
}

/**
 * Cheap TCP reachability check for the Vite dev server.
 *
 * Memoized per request via the static in get_vite_dev_server(), and
 * only runs in local/development environments, so the ~150ms worst
 * case is a one-time local cost.
 */
function is_vite_dev_server_reachable(string $url): bool {
    static $reachable = null;
    if ($reachable !== null) {
        return $reachable;
    }

    $parts = wp_parse_url($url);
    $host = $parts['host'] ?? '';
    $port = (int) ($parts['port'] ?? ((($parts['scheme'] ?? '') === 'https') ? 443 : 80));

    if ($host === '') {
        $reachable = false;
        return $reachable;
    }

    $errno = 0;
    $errstr = '';
    $socket = @fsockopen($host, $port, $errno, $errstr, 0.15);

    if ($socket === false) {
        $reachable = false;
        return $reachable;
    }

    fclose($socket);
    $reachable = true;
    return $reachable;
}

function use_vite_dev_server(): bool {
    if (defined('WP_ENVIRONMENT_TYPE') && function_exists('wp_get_environment_type')) {
        return in_array(wp_get_environment_type(), ['local', 'development'], true);
    }

    return defined('WP_DEBUG') && WP_DEBUG;
}

function get_vite_manifest(): ?array {
    static $manifest = false;
    if ($manifest === false) {
        $path = THEME_DIR . '/dist/.vite/manifest.json';
        $decoded = file_exists($path)
            ? json_decode((string) file_get_contents($path), true)
            : null;

        $manifest = is_array($decoded) ? $decoded : null;
    }
    return $manifest;
}

function normalize_vite_dev_server(string $url): ?string {
    $url = trim($url);

    if ($url === '') {
        return null;
    }

    $parts = wp_parse_url($url);
    $scheme = $parts['scheme'] ?? '';
    $host = $parts['host'] ?? '';

    if (!in_array($scheme, ['http', 'https'], true) || $host === '') {
        return null;
    }

    return untrailingslashit($url);
}

function enqueue_vite_dev_client(string $dev_server): void {
    static $enqueued = false;

    if ($enqueued) {
        return;
    }

    wp_enqueue_script('theme-vite-client', $dev_server . '/@vite/client', [], null, false);
    $enqueued = true;
}

function enqueue_vite_entry(string $handle, string $entry_path): void {
    $dev_server = get_vite_dev_server();

    if ($dev_server) {
        enqueue_vite_dev_client($dev_server);

        $url = $dev_server . '/' . ltrim($entry_path, '/');
        if (str_ends_with($entry_path, '.css')) {
            wp_enqueue_style($handle, $url, [], null);
        } else {
            wp_enqueue_script($handle, $url, [], null, true);
        }

        return;
    }

    $manifest = get_vite_manifest();
    if (!$manifest || !isset($manifest[$entry_path])) {
        return;
    }

    $entry = $manifest[$entry_path];
    if (!is_array($entry)) {
        return;
    }

    $file  = $entry['file'] ?? '';

    if ($file !== '' && str_ends_with($file, '.css')) {
        wp_enqueue_style($handle, THEME_URI . '/dist/' . $file, [], THEME_VERSION);
    } elseif ($file !== '') {
        wp_enqueue_script($handle, THEME_URI . '/dist/' . $file, [], THEME_VERSION, true);
    }

    foreach ($entry['css'] ?? [] as $i => $css) {
        wp_enqueue_style($handle . '-css-' . $i, THEME_URI . '/dist/' . $css, [], THEME_VERSION);
    }
}

function is_machine_product_page(): bool {
    return is_singular('product')
        && has_term(['roof-wall-panel-machines', 'gutter-machines'], 'product_cat');
}

function is_woocommerce_theme_screen(): bool {
    if (is_singular('product')) {
        return !is_machine_product_page();
    }

    return (function_exists('is_woocommerce') && is_woocommerce())
        || (function_exists('is_cart') && is_cart())
        || (function_exists('is_checkout') && is_checkout())
        || (function_exists('is_account_page') && is_account_page());
}

add_action('wp_enqueue_scripts', function (): void {
    enqueue_vite_entry('theme-app', 'app/resources/js/_app.js');

    if (is_front_page()) {
        enqueue_vite_entry('theme-front-page', 'app/resources/js/front-page.js');
    }

    if (is_machine_product_page()) {
        enqueue_vite_entry('theme-machine-product', 'app/resources/js/machine-product.js');
    }

    if (is_woocommerce_theme_screen()) {
        enqueue_vite_entry('theme-woocommerce', 'app/resources/css/woo.css');
    }
});

add_filter('script_loader_tag', function (string $tag, string $handle, string $src): string {
    return str_starts_with($handle, 'theme-')
        ? '<script type="module" src="' . esc_url($src) . '"></script>'
        : $tag;
}, 10, 3);
