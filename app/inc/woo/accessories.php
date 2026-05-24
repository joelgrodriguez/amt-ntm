<?php
/**
 * Woo accessory product card data.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Accessories;

if (!defined('ABSPATH')) {
    exit;
}

const MACHINE_CATEGORY_SLUGS = ['roof-wall-panel-machines', 'gutter-machines'];
const ACCESSORY_CATEGORY_SLUG = 'accessories-add-on-equipment';

/**
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function get_compatible_machine_cards(int $limit = 4): array {
    $machine_posts = \get_posts([
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => $limit,
        'orderby'                => 'menu_order title',
        'order'                  => 'ASC',
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'tax_query'              => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => MACHINE_CATEGORY_SLUGS,
                'operator' => 'IN',
            ],
        ],
    ]);

    return product_cards(array_filter(array_map('\wc_get_product', $machine_posts)));
}

/**
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function get_related_accessory_cards(\WC_Product $product, int $limit = 4): array {
    $products = \Standard\Woo\Cache\get_products([
        'category' => [ACCESSORY_CATEGORY_SLUG],
        'exclude'  => [$product->get_id()],
        'limit'    => $limit,
        'status'   => 'publish',
        'orderby'  => 'rand',
    ]);

    return product_cards($products);
}

/**
 * @param \WC_Product[] $products
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function product_cards(array $products): array {
    $cards = [];

    foreach ($products as $product) {
        if (!$product instanceof \WC_Product) {
            continue;
        }

        $cards[] = [
            'url'      => $product->get_permalink(),
            'image_id' => (int) $product->get_image_id(),
            'title'    => $product->get_name(),
            'subtitle' => $product->get_price_html() ?: null,
        ];
    }

    return $cards;
}
