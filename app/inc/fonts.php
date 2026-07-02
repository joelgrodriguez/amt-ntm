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

/*
 * Weight audit (2026-07, #54): the theme uses Noto Sans 400/500/600/700
 * (body, font-medium, font-semibold, prose h2/strong/bolder) and
 * Noto Sans Mono 400/500 (labels/eyebrows are font-mono font-medium).
 * Noto Serif is defined as a token but never used anywhere — dropped
 * (falls back to Georgia if editor content ever selects it). Mono 600
 * dropped too: nothing renders mono above 500.
 */
const FONTS_URL = 'https://fonts.bunny.net/css?family=Noto+Sans:400,500,600,700|Noto+Sans+Mono:400,500&display=swap';

add_action('wp_head', function (): void {
    echo '<link rel="preconnect" href="https://fonts.bunny.net" crossorigin>' . "\n";
}, 1);

add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style('theme-fonts', FONTS_URL, [], null);
});

add_action('enqueue_block_editor_assets', function (): void {
    wp_enqueue_style('theme-fonts', FONTS_URL, [], null);
});
