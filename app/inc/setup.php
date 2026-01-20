<?php
/**
 * Theme setup: theme supports, menus, content width.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme setup.
 */
function theme_setup(): void {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('align-wide');
    add_theme_support('wp-block-styles');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('responsive-embeds');

    // Gutenberg editor supports
    // Note: editor-color-palette and editor-font-sizes are now defined in theme.json
    add_theme_support('editor-styles');
    add_editor_style(get_editor_style_url());

    // Register navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'standard-press'),
        'mobile'  => __('Mobile Menu', 'standard-press'),
        'footer'  => __('Footer Menu', 'standard-press'),
    ]);

    // Set content width
    if (!isset($GLOBALS['content_width'])) {
        $GLOBALS['content_width'] = 1200;
    }
}
add_action('after_setup_theme', __NAMESPACE__ . '\\theme_setup');

/**
 * Get editor style URL from Vite manifest or dev server.
 */
function get_editor_style_url(): string {
    // Development: return dev server URL
    $dev_server = get_vite_dev_server();
    if ($dev_server) {
        return $dev_server . '/app/resources/css/editor.css';
    }

    // Production: get from manifest (uses cached manifest from vite.php)
    $manifest = get_vite_manifest();
    if ($manifest !== null && isset($manifest['app/resources/css/editor.css'])) {
        return THEME_URI . '/dist/' . $manifest['app/resources/css/editor.css']['file'];
    }

    return '';
}
