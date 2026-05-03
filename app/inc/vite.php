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
        $file = THEME_DIR . '/.vite-dev-server';
        $url = file_exists($file) ? trim(file_get_contents($file)) : null;
    }
    return $url;
}

function get_vite_manifest(): ?array {
    static $manifest = false;
    if ($manifest === false) {
        $path = THEME_DIR . '/dist/.vite/manifest.json';
        $manifest = file_exists($path) ? json_decode(file_get_contents($path), true) : null;
    }
    return $manifest;
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
