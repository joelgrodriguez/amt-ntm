<?php
/**
 * Mega menu fragment cache.
 *
 * The desktop mega menu hydrates multiple WooCommerce/product/profile cards.
 * Cache the finished HTML so normal page views do not repeat that work.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MegaMenu;

if (!defined('ABSPATH')) {
    exit;
}

const CACHE_PREFIX = 'std_mega_menu_html_';
const CACHE_TTL    = 6 * 60 * 60;

/**
 * Render the cached desktop mega menu fragment.
 */
function render(): void {
    echo get_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get the cached mega menu HTML, generating it on a cold cache.
 */
function get_html(): string {
    $key = cache_key();
    $html = \get_transient($key);

    if (is_string($html)) {
        return $html;
    }

    \ob_start();
    \get_template_part('templates/parts/mega-menu');
    $html = (string) \ob_get_clean();

    \set_transient($key, $html, CACHE_TTL);

    return $html;
}

/**
 * Build a cache key that changes when the menu renderer changes in git.
 */
function cache_key(): string {
    $files = [
        'inc/desktop-nav.php',
        'templates/parts/mega-menu.php',
        'templates/parts/card-accessory.php',
        'templates/parts/card-product.php',
        'templates/parts/card-profile.php',
    ];

    $version_parts = [];
    foreach ($files as $file) {
        $path = \get_template_directory() . '/' . $file;
        $version_parts[] = $file . ':' . (\file_exists($path) ? (string) \filemtime($path) : 'missing');
    }

    return CACHE_PREFIX . \md5(\implode('|', $version_parts));
}

/**
 * Flush every mega menu transient variant.
 */
function flush(...$ignored): void {
    \delete_transient(cache_key());

    global $wpdb;

    $like = $wpdb->esc_like('_transient_' . CACHE_PREFIX) . '%';
    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like)
    );

    $like_timeout = $wpdb->esc_like('_transient_timeout_' . CACHE_PREFIX) . '%';
    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like_timeout)
    );
}

/**
 * Flush menu HTML when a rendered post type is deleted.
 */
function flush_on_deleted_post(int $post_id, \WP_Post $post): void {
    if (\in_array($post->post_type, ['product', 'profile'], true)) {
        flush();
    }
}

add_action('save_post_product', __NAMESPACE__ . '\\flush');
add_action('deleted_post', __NAMESPACE__ . '\\flush_on_deleted_post', 10, 2);
add_action('woocommerce_update_product', __NAMESPACE__ . '\\flush');
add_action('woocommerce_delete_product', __NAMESPACE__ . '\\flush');
add_action('edited_product_cat', __NAMESPACE__ . '\\flush');
add_action('edited_product_tag', __NAMESPACE__ . '\\flush');
add_action('save_post_profile', __NAMESPACE__ . '\\flush');
add_action('edited_category', __NAMESPACE__ . '\\flush');
