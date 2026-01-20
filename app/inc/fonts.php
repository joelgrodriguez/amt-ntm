<?php
/**
 * Bunny Fonts registration.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

const FONTS_URL = 'https://fonts.bunny.net/css?family=IBM+Plex+Sans:400,500,600,700|IBM+Plex+Serif:400,500,600,700|IBM+Plex+Mono:400,500,600&display=swap';

add_action('wp_head', function (): void {
    echo '<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>' . "\n";
}, 1);

add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style('theme-fonts', FONTS_URL, [], null);
});

add_action('enqueue_block_editor_assets', function (): void {
    wp_enqueue_style('theme-fonts', FONTS_URL, [], null);
});
