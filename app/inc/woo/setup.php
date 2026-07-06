<?php
/**
 * WooCommerce Theme Integration
 *
 * Catalog-first foundation. Declares theme support and manages
 * WooCommerce's default assets. Commerce-specific hooks (cart,
 * checkout, account) should be added here when needed.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo;

if (!defined('ABSPATH')) {
    exit;
}

const PRODUCT_CATEGORY_ARCHIVE_REDIRECTS = [
    'roof-wall-panel-machines' => '/roof-wall-panel-machines/',
    'gutter-machines'          => '/seamless-gutter-machines/',
];

/**
 * Declare WooCommerce theme support.
 *
 * Prevents admin notices, enables WC to respect theme image sizes,
 * and signals plugin compatibility.
 */
add_action('after_setup_theme', function (): void {
    add_theme_support('woocommerce');
});

/**
 * Disable default WooCommerce stylesheets.
 *
 * Theme provides its own styling via resources/css/woocommerce.css.
 */
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Keep public product category archives out of unstyled WooCommerce markup.
 */
add_action('template_redirect', function (): void {
    if (!\is_tax('product_cat')) {
        return;
    }

    $term = \get_queried_object();
    $path = '/machines/';

    if ($term instanceof \WP_Term && isset(PRODUCT_CATEGORY_ARCHIVE_REDIRECTS[$term->slug])) {
        $path = PRODUCT_CATEGORY_ARCHIVE_REDIRECTS[$term->slug];
    }

    \wp_safe_redirect(\home_url($path), 301);
    exit;
});
