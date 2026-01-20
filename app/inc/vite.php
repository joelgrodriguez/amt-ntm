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

add_action('wp_enqueue_scripts', function (): void {
    $dev_server = get_vite_dev_server();

    if ($dev_server) {
        add_action('wp_head', function () use ($dev_server): void {
            echo '<script type="module" src="' . esc_url($dev_server . '/@vite/client') . '"></script>';
            echo '<script type="module" src="' . esc_url($dev_server . '/app/resources/js/_app.js') . '"></script>';
        }, 999);
        return;
    }

    $manifest = get_vite_manifest();
    if (!$manifest || !isset($manifest['app/resources/js/_app.js'])) {
        return;
    }

    $entry = $manifest['app/resources/js/_app.js'];
    wp_enqueue_script('theme-main', THEME_URI . '/dist/' . $entry['file'], [], THEME_VERSION, true);

    foreach ($entry['css'] ?? [] as $i => $css) {
        wp_enqueue_style('theme-main-' . $i, THEME_URI . '/dist/' . $css, [], THEME_VERSION);
    }
});

add_filter('script_loader_tag', function (string $tag, string $handle, string $src): string {
    return str_contains($handle, 'theme-main')
        ? '<script type="module" src="' . esc_url($src) . '"></script>'
        : $tag;
}, 10, 3);
