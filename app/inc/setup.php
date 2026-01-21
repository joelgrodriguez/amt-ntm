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

    // Custom image sizes
    add_image_size('card-thumbnail', 400, 225, true); // 16:9 aspect ratio, hard crop
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
 * Remove archive title prefixes (Archives:, Category:, Tag:, etc.)
 */
function clean_archive_title(string $title): string {
    if (is_category()) {
        return single_cat_title('', false);
    } elseif (is_tag()) {
        return single_tag_title('', false);
    } elseif (is_post_type_archive()) {
        return post_type_archive_title('', false);
    } elseif (is_author()) {
        return get_the_author();
    }
    return $title;
}
add_filter('get_the_archive_title', __NAMESPACE__ . '\\clean_archive_title');

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
