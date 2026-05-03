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
 * Accessory product category slugs that get the branded template.
 */
const ACCESSORY_CATEGORIES = [
    'accessories-add-on-equipment',
];

/**
 * Resolve the custom single product template for the current product.
 *
 * Machine products intentionally win over accessories if a product is
 * assigned to both category groups. A machine landing page is the richer
 * sales surface; accessory layout is the fallback product treatment.
 */
function get_single_product_template(): ?string {
    if (!is_singular('product')) {
        return null;
    }

    if (has_term(MACHINE_CATEGORIES, 'product_cat')) {
        return 'templates/woo/product/single-machine.php';
    }

    if (has_term(ACCESSORY_CATEGORIES, 'product_cat')) {
        return 'templates/woo/product/single-accessory.php';
    }

    return null;
}

/**
 * Swap WooCommerce single product templates.
 *
 * Runs at priority 99 to fire after WooCommerce's own template_include
 * callback at priority 10.
 */
add_filter('template_include', function (string $template): string {
    $relative_template = get_single_product_template();

    if ($relative_template === null) {
        return $template;
    }

    $custom = get_theme_file_path($relative_template);
    return file_exists($custom) ? $custom : $template;
}, 99);
