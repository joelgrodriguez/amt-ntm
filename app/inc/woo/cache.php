<?php
/**
 * WooCommerce Query Cache
 *
 * Wraps wc_get_products() with a transient layer so hot pages
 * (front page hero, machines listing, product carousels) don't
 * hit the WC product query on every request.
 *
 * Cached arrays hold product IDs only — callers re-hydrate via
 * wc_get_product() so prices/stock stay live without invalidation.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Cache;

if (!defined('ABSPATH')) {
    exit;
}

const TTL = 6 * HOUR_IN_SECONDS;
const PREFIX = 'std_wc_q_';

/**
 * Run wc_get_products() with a transient cache.
 *
 * @param array $args Same shape as wc_get_products().
 * @return \WC_Product[]
 */
function get_products(array $args): array {
    if (!function_exists('wc_get_products')) {
        return [];
    }

    if (($args['orderby'] ?? '') === 'rand') {
        $products = \wc_get_products($args);

        return is_array($products)
            ? array_values(array_filter($products, fn($product): bool => $product instanceof \WC_Product))
            : [];
    }

    $key = PREFIX . md5(wp_json_encode($args));
    $ids = get_transient($key);

    if ($ids === false) {
        $query_args = array_merge($args, ['return' => 'ids']);
        $ids = \wc_get_products($query_args);
        if (!is_array($ids)) {
            $ids = [];
        }
        set_transient($key, $ids, TTL);
    }

    $products = [];
    foreach ($ids as $id) {
        $product = \wc_get_product($id);
        if ($product) {
            $products[] = $product;
        }
    }

    return $products;
}

/**
 * Flush all cached product queries.
 *
 * Hooked to product save/delete and term changes so editors
 * see fresh data immediately after publishing.
 */
function flush(): void {
    global $wpdb;
    $like = $wpdb->esc_like('_transient_' . PREFIX) . '%';
    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like)
    );
    $like_timeout = $wpdb->esc_like('_transient_timeout_' . PREFIX) . '%';
    $wpdb->query(
        $wpdb->prepare("DELETE FROM {$wpdb->options} WHERE option_name LIKE %s", $like_timeout)
    );
}

/**
 * Flush on delete, but only for products. 'deleted_post' fires for
 * every post type; without the guard, deleting any page/post wipes
 * the whole product-query cache.
 *
 * @param int      $post_id Deleted post ID.
 * @param \WP_Post $post    Deleted post object (passed since WP 5.5).
 */
function flush_on_deleted_post(int $post_id, \WP_Post $post): void {
    if ($post->post_type === 'product') {
        flush();
    }
}

add_action('save_post_product', __NAMESPACE__ . '\\flush');
add_action('deleted_post', __NAMESPACE__ . '\\flush_on_deleted_post', 10, 2);
add_action('woocommerce_update_product', __NAMESPACE__ . '\\flush');
add_action('woocommerce_delete_product', __NAMESPACE__ . '\\flush');
add_action('edited_product_cat', __NAMESPACE__ . '\\flush');
add_action('edited_product_tag', __NAMESPACE__ . '\\flush');
