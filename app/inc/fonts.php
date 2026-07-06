<?php
/**
 * Self-hosted font preloads.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

/*
 * Weight audit (2026-07, #54): the theme uses Noto Sans 400/500/600/700
 * (body, font-medium, font-semibold, prose h2/strong/bolder) and
 * Noto Sans Mono 400/500 (labels/eyebrows are font-mono font-medium).
 * Noto Serif is defined as a token but never used anywhere — dropped
 * (falls back to Georgia if editor content ever selects it). Mono 600
 * dropped too: nothing renders mono above 500.
 */
const FONT_PRELOAD_FILES = [
    'noto-sans/noto-sans-latin-400-normal.woff2',
    'noto-sans-mono/noto-sans-mono-latin-500-normal.woff2',
];

function get_theme_font_url(string $file): ?string {
    $file = ltrim($file, '/');
    $dev_server = get_vite_dev_server();

    if ($dev_server !== null) {
        return $dev_server . '/app/resources/fonts/' . $file;
    }

    $manifest = get_vite_manifest();
    $entry = $manifest['app/resources/fonts/' . $file] ?? null;

    if (!is_array($entry)) {
        return null;
    }

    $dist_file = $entry['file'] ?? '';

    if (!is_string($dist_file) || $dist_file === '') {
        return null;
    }

    return THEME_URI . '/dist/' . $dist_file;
}

function preload_theme_fonts(): void {
    foreach (FONT_PRELOAD_FILES as $file) {
        $url = get_theme_font_url($file);

        if ($url === null) {
            continue;
        }

        printf(
            '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
            esc_url($url)
        );
    }
}
add_action('wp_head', __NAMESPACE__ . '\\preload_theme_fonts', 1);
