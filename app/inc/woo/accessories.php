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
 * Return compatible machines in the data shape expected by card-product.php.
 *
 * Used by the accessory page Compatibility section so machine cards in that
 * carousel render through the canonical card. Dormant machines are stripped.
 *
 * @return array<int, array<string, mixed>>
 */
function get_compatible_machine_product_cards(int $limit = 8): array {
    $machine_posts = \get_posts([
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => $limit + 4, // overfetch in case dormant rows are filtered
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

    $dormant_slugs = function_exists('Standard\\MachinesData\\get_dormant_wc_slugs')
        ? \Standard\MachinesData\get_dormant_wc_slugs()
        : [];

    $cards = [];
    foreach ($machine_posts as $post_id) {
        $product = \wc_get_product($post_id);
        if (!$product instanceof \WC_Product) {
            continue;
        }
        if (!empty($dormant_slugs) && in_array($product->get_slug(), $dormant_slugs, true)) {
            continue;
        }

        $woo_slug = $product->get_slug();
        $is_gutter = false;
        foreach ($product->get_category_ids() as $cat_id) {
            $term = \get_term((int) $cat_id, 'product_cat');
            if ($term instanceof \WP_Term && $term->slug === 'gutter-machines') {
                $is_gutter = true;
                break;
            }
        }

        $description = function_exists('Standard\\MachinesData\\get_machine_description')
            ? \Standard\MachinesData\get_machine_description($woo_slug)
            : '';

        $raw_price = $product->get_price();
        $price = ($raw_price === '' || $raw_price === null)
            ? \Standard\Woo\Catalog\FALLBACK_MACHINE_PRICE
            : '$' . \number_format((float) $raw_price);

        $cards[] = [
            'id'             => $product->get_id(),
            'title'          => \Standard\Woo\Catalog\get_short_title($product->get_name()),
            'category_label' => $is_gutter
                ? \__('Seamless Gutter Machine', 'standard')
                : \__('Roof & Wall Panel Machine', 'standard'),
            'description'    => $description,
            'image'          => \wp_get_attachment_url($product->get_image_id()),
            'price'          => $price,
            'price_label'    => \__('Starting at', 'standard'),
            'explore_url'    => $product->get_permalink(),
            'build_url'      => \Standard\Woo\Catalog\get_configurator_url($woo_slug),
            'badge'          => '',
        ];

        if (count($cards) >= $limit) {
            break;
        }
    }

    return $cards;
}

/**
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function get_related_accessory_cards(\WC_Product $product, int $limit = 4): array {
    // Random related products bypass the cache in Standard\Woo\Cache.
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
