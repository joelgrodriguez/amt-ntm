<?php
/**
 * Classic theme head cleanup.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

function remove_default_head_assets(): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_oembed_add_host_js');
}
add_action('init', __NAMESPACE__ . '\\remove_default_head_assets');

function deregister_embed_script(): void {
    wp_dequeue_script('wp-embed');
    wp_deregister_script('wp-embed');
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\deregister_embed_script', 100);

/*
 * Leave global-styles and classic-theme-styles enqueued.
 *
 * Those inline styles can affect block editor content, alignments, colors,
 * and spacing on database-authored pages. Without browser verification of a
 * Learning Center post and a block-content page, dequeuing them would be a
 * guess. The task explicitly says not to guess.
 */
