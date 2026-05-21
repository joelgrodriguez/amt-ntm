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

use function Standard\MachineProductData\get_machine_product_data;
use function Standard\MachineProductData\resolve_machine_key;

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
 * Machine data keys that get the rich flagship landing page.
 *
 * Every machine with an entry in app/data/machines/ can be referenced
 * elsewhere (hero slider, schema, related queries) without forcing the
 * full landing template. Only the keys listed here route to
 * single-machine.php; everything else falls back to
 * single-machine-default.php.
 */
const FLAGSHIP_DATA_KEYS = [
    'ssq3-multipro',
    'mach-ii-combo-gutter',
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
        return is_flagship_machine()
            ? 'templates/woo/product/single-machine.php'
            : 'templates/woo/product/single-machine-default.php';
    }

    if (has_term(ACCESSORY_CATEGORIES, 'product_cat')) {
        return 'templates/woo/product/single-accessory.php';
    }

    return null;
}

/**
 * Does the current product map to a flagship machine data key?
 *
 * Flagship products get the full single-machine.php landing page.
 * Non-flagship machines fall back to single-machine-default.php even
 * if they have machine data registered for use elsewhere (hero, schema).
 */
function is_flagship_machine(): bool {
    if (!function_exists('wc_get_product')) {
        return false;
    }

    $product = \wc_get_product(get_queried_object_id());
    if (!$product instanceof \WC_Product) {
        return false;
    }

    $key = resolve_machine_key($product->get_slug());

    return $key !== null && in_array($key, FLAGSHIP_DATA_KEYS, true);
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
