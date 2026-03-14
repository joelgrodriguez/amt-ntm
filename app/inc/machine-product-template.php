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

namespace Standard\MachineProduct;

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

    // DEBUG: show category info on product pages for admins
    if (current_user_can('manage_options')) {
        $terms = wp_get_post_terms(get_the_ID(), 'product_cat', ['fields' => 'slugs']);
        $match = has_term(MACHINE_CATEGORIES, 'product_cat');
        $custom_path = get_theme_file_path('templates/pages/product/single-machine.php');
        add_action('wp_head', function () use ($terms, $match, $custom_path) {
            echo '<!-- MACHINE TPL DEBUG: cats=[' . implode(', ', $terms) . '] match=' . ($match ? 'YES' : 'NO') . ' file_exists=' . (file_exists($custom_path) ? 'YES' : 'NO') . ' -->';
        });
    }

    if (!has_term(MACHINE_CATEGORIES, 'product_cat')) {
        return $template;
    }

    $custom = get_theme_file_path('templates/pages/product/single-machine.php');

    return file_exists($custom) ? $custom : $template;
}, 99);
