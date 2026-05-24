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
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_image_size('card-thumbnail', 640, 360, true);  // 16:9, post thumbnails (cropped)
    add_image_size('product-card', 640, 360, true);    // 16:9, product/profile letterboxed via object-contain
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
    add_theme_support('editor-styles');
    add_editor_style(get_editor_style_url());
    register_nav_menus([
        'primary' => __('Primary Menu', 'standard'),
        'footer'  => __('Footer Menu', 'standard'),
    ]);
    if (!isset($GLOBALS['content_width'])) {
        $GLOBALS['content_width'] = 1440;
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
 * Preconnect to the production CDN where shared marketing assets live
 * (customer portraits, etc). Front page only, since that's where they're
 * referenced first; other surfaces that need it can extend the condition.
 */
function preconnect_marketing_cdn(array $urls, string $relation_type): array {
    if ($relation_type === 'preconnect' && is_front_page()) {
        $urls[] = [
            'href'        => 'https://newtechmachinery.com',
            'crossorigin' => 'anonymous',
        ];
    }
    return $urls;
}
add_filter('wp_resource_hints', __NAMESPACE__ . '\\preconnect_marketing_cdn', 10, 2);

/**
 * Get editor style URL from Vite manifest or dev server.
 */
function get_editor_style_url(): string {
    $dev_server = get_vite_dev_server();
    if ($dev_server) {
        return $dev_server . '/app/resources/css/editor.css';
    }
    $manifest = get_vite_manifest();
    if ($manifest !== null && isset($manifest['app/resources/css/editor.css'])) {
        return THEME_URI . '/dist/' . $manifest['app/resources/css/editor.css']['file'];
    }

    return '';
}
