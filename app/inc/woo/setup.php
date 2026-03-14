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
