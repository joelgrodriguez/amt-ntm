<?php
/**
 * Accessory product hook surgery.
 *
 * Customizes WooCommerce's native single-product hooks for the
 * accessory template: swaps add-to-cart for a quote CTA, tunes
 * related-products count, and tweaks tab content.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Accessories;

if (!defined('ABSPATH')) {
    exit;
}

const ACCESSORY_CATEGORY_SLUG = 'accessories-add-on-equipment';

function is_accessory_product(): bool {
    return is_singular('product') && has_term(ACCESSORY_CATEGORY_SLUG, 'product_cat');
}

/**
 * Replace add-to-cart with our quote CTA inside the Woo summary stack.
 */
add_action('wp', function (): void {
    if (!is_accessory_product()) {
        return;
    }

    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);

    add_action('woocommerce_single_product_summary', __NAMESPACE__ . '\\render_quote_cta', 30);
    add_action('woocommerce_single_product_summary', __NAMESPACE__ . '\\render_eyebrow', 4);
});

function render_eyebrow(): void {
    echo '<p class="section-eyebrow mb-2">' . esc_html__('Accessories', 'standard') . '</p>';
}

function render_quote_cta(): void {
    global $product;
    if (!$product instanceof \WC_Product) {
        return;
    }

    $slug      = $product->get_slug();
    $quote_url = \Standard\Url\with_query('/contact/', ['product' => $slug]);
    ?>
    <div class="flex flex-wrap gap-3 pt-2">
        <a href="<?php echo esc_url($quote_url); ?>" class="btn btn-primary">
            <?php esc_html_e('Request a Quote', 'standard'); ?>
        </a>
        <a href="tel:+13032943553" class="btn btn-outline-dark">
            <?php icon('phone', ['class' => 'w-4 h-4']); ?>
            <?php esc_html_e('Call Us', 'standard'); ?>
        </a>
    </div>
    <?php
}

/**
 * Related products: 4 per row, 4 total, only accessories.
 */
add_filter('woocommerce_output_related_products_args', function (array $args): array {
    if (!is_accessory_product()) {
        return $args;
    }

    return array_merge($args, [
        'posts_per_page' => 4,
        'columns'        => 4,
    ]);
});
