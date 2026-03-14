<?php
/**
 * Machine Product Template Router
 *
 * Routes WooCommerce single product pages for machine categories
 * to a custom landing page template. Accessories and other products
 * use the default WooCommerce template.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\MachineTemplate;

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
