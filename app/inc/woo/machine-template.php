<?php
/**
 * Product Template Router
 *
 * Routes WooCommerce single product pages to custom templates
 * based on product category. Machines get a full landing page,
 * accessories get a branded product page.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\MachineTemplate;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Machine product category slugs that get the custom template.
 */
const MACHINE_CATEGORIES = [
    'roof-wall-panel-machines',
    'gutter-machines',
];

/**
 * Swap the template for machine products.
 *
 * Runs at priority 99 to fire after WooCommerce's own template_include (priority 10).
 */
add_filter('template_include', function (string $template): string {
    if (!is_singular('product')) {
        return $template;
    }

    if (!has_term(MACHINE_CATEGORIES, 'product_cat')) {
        return $template;
    }

    $custom = get_theme_file_path('templates/woo/product/single-machine.php');

    return file_exists($custom) ? $custom : $template;
}, 99);

/**
 * Accessory product category slugs that get the branded template.
 */
const ACCESSORY_CATEGORIES = [
    'accessories-add-on-equipment',
];

/**
 * Swap the template for accessory products.
 */
add_filter('template_include', function (string $template): string {
    if (!is_singular('product')) {
        return $template;
    }

    if (!has_term(ACCESSORY_CATEGORIES, 'product_cat')) {
        return $template;
    }

    $custom = get_theme_file_path('templates/woo/product/single-accessory.php');

    return file_exists($custom) ? $custom : $template;
}, 98);
