<?php
/**
 * Front-end performance policy for marketing and product pages.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Performance;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Product detail and machine/accessory landing pages use theme-owned UI.
 */
function is_optimized_catalog_screen(): bool {
    if (is_singular('product')) {
        return true;
    }

    return is_page([
        'machines',
        'seamless-gutter-machines',
        'roof-wall-panel-machines',
        'upgrades',
    ]);
}

/**
 * Replace the Clarity plugin's eager head loader with the theme's delayed
 * loader. The plugin continues to own its project setting and admin UI.
 */
function remove_eager_clarity_loader(): void {
    if (function_exists('clarity_add_script_to_header')) {
        remove_action('wp_head', 'clarity_add_script_to_header');
    }
}
add_action('after_setup_theme', __NAMESPACE__ . '\\remove_eager_clarity_loader', 20);

/**
 * Pass vendor IDs to the single client-side loading gate.
 */
function print_third_party_config(): void {
    if (is_admin()) {
        return;
    }

    $integrations = class_exists('\\Standard_Site_Integrations')
        ? \Standard_Site_Integrations::instance()
        : null;
    $clarity_owned = $integrations
        && $integrations->integration_is_effective('clarity');

    $config = [
        // Keep the legacy key shape while the Corbel assistant replaces chat.
        // HubSpot forms still load through HubspotForms.js when a form exists.
        'hubspotPortalId' => '',
        'clarityProjectId' => $clarity_owned
            ? ''
            : sanitize_key((string) get_option('clarity_project_id', '')),
    ];

    echo '<script>window.ntmThirdPartyConfig = '
        . wp_json_encode($config, JSON_UNESCAPED_SLASHES)
        . ';</script>';
}
add_action('wp_head', __NAMESPACE__ . '\\print_third_party_config', 2);

/**
 * Remove assets from plugins whose UI is not present on theme-owned catalog
 * pages. WooCommerce still owns product data; it just does not need cart or
 * single-product JavaScript on quote-only templates.
 */
function dequeue_unused_catalog_assets(): void {
    if (!is_optimized_catalog_screen()) {
        return;
    }

    foreach ([
        'wc-add-to-cart',
        'wc-add-to-cart-variation',
        'wc-cart-fragments',
        'wc-jquery-blockui',
        'wc-js-cookie',
        'wc-single-product',
        'woocommerce',
        'sourcebuster-js',
        'wc-order-attribution',
        'tec-user-agent',
    ] as $handle) {
        wp_dequeue_script($handle);
    }

    foreach ([
        'wc-blocks-style',
        'woocommerce-general',
        'woocommerce-layout',
        'woocommerce-smallscreen',
    ] as $handle) {
        wp_dequeue_style($handle);
    }

    // Logged-in visitors need Dashicons for the admin bar. Public catalog
    // pages use the tiny theme fallback below instead of the full font.
    if (!is_user_logged_in()) {
        wp_dequeue_style('dashicons');
    }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\dequeue_unused_catalog_assets', 999);
add_action('wp_print_styles', __NAMESPACE__ . '\\dequeue_unused_catalog_assets', 0);
add_action('wp_print_scripts', __NAMESPACE__ . '\\dequeue_unused_catalog_assets', 0);
add_action('wp_print_footer_scripts', __NAMESPACE__ . '\\dequeue_unused_catalog_assets', 0);
