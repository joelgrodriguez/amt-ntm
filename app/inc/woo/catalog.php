<?php
/**
 * Product Catalog Data
 *
 * WooCommerce product queries for theme templates.
 * Uses WooCommerce as the catalog source when available and falls back to
 * machine data for local development without WooCommerce.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Catalog;

if (!defined('ABSPATH')) {
    exit;
}

const FALLBACK_MACHINE_PRICE = '$87,245';

/**
 * Get product categories for the Explore Machines section.
 *
 * @return array<string, string> Category slug => label pairs
 */
function get_product_categories(): array {
    return [
        'all'                          => \__('All', 'standard'),
        'roof-wall-panel-machines'     => \__('Roof & Wall Panel Machines', 'standard'),
        'gutter-machines'              => \__('Seamless Gutter Machines', 'standard'),
        'accessories-add-on-equipment' => \__('Accessories & Upgrades', 'standard'),
    ];
}

/**
 * Get products by category.
 *
 * @return array<int, array>
 */
function get_products_by_category(string $category_slug): array {
    if (function_exists('wc_get_products')) {
        return get_woocommerce_products($category_slug);
    }

    return get_sample_products($category_slug);
}

/**
 * Get products from WooCommerce.
 *
 * @return array<int, array>
 */
function get_woocommerce_products(string $category_slug): array {
    if ($category_slug === 'all') {
        return array_merge(
            get_woocommerce_products('roof-wall-panel-machines'),
            get_woocommerce_products('gutter-machines'),
            array_slice(get_woocommerce_products('accessories-add-on-equipment'), 0, 7)
        );
    }

    $is_accessory = $category_slug === 'accessories-add-on-equipment';

    $products = \Standard\Woo\Cache\get_products([
        'category' => [$category_slug],
        'limit'    => $is_accessory ? 7 : 10,
        'status'   => 'publish',
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ]);

    $formatted = [];
    foreach ($products as $product) {
        $formatted[] = [
            'id'          => $product->get_id(),
            'title'       => $product->get_name(),
            'tagline'     => $product->get_short_description(),
            'descriptor'  => $is_accessory ? '' : \wp_strip_all_tags($product->get_short_description()),
            'image'       => \wp_get_attachment_url($product->get_image_id()),
            'price'       => $is_accessory ? '' : FALLBACK_MACHINE_PRICE,
            'price_label' => \__('Starting at', 'standard'),
            'explore_url' => $product->get_permalink(),
            'build_url'   => \Standard\Url\with_query('/build-finance/', ['machine' => $product->get_slug()]),
            'badge'       => '',
        ];
    }

    return $formatted;
}

/**
 * Get sample products for development/fallback.
 *
 * @return array<int, array>
 */
function get_sample_products(string $category_slug): array {
    if ($category_slug === 'all') {
        return array_merge(
            get_sample_products('roof-wall-panel-machines'),
            get_sample_products('gutter-machines'),
            array_slice(get_sample_products('accessories-add-on-equipment'), 0, 7)
        );
    }

    if ($category_slug === 'roof-wall-panel-machines' || $category_slug === 'gutter-machines') {
        return get_sample_machine_products($category_slug);
    }

    if ($category_slug === 'accessories-add-on-equipment') {
        return get_sample_accessory_products();
    }

    return [];
}

/**
 * @return array<int, array>
 */
function get_sample_machine_products(string $category_slug): array {
    $machines = $category_slug === 'roof-wall-panel-machines'
        ? \Standard\MachinesData\get_roof_wall_machines()
        : \Standard\MachinesData\get_gutter_machines();

    return array_map(
        fn(array $machine): array => format_sample_machine_product($machine, $category_slug),
        $machines
    );
}

/**
 * @param array<string, mixed> $machine
 * @return array<string, mixed>
 */
function format_sample_machine_product(array $machine, string $category_slug): array {
    $slug       = (string) ($machine['slug'] ?? '');
    $public_slug = get_public_machine_slug($slug);
    $is_gutter  = $category_slug === 'gutter-machines';

    return [
        'id'          => $public_slug,
        'title'       => $is_gutter ? ($machine['short_name'] ?? $machine['name'] ?? '') : ($machine['name'] ?? ''),
        'tagline'     => $machine['descriptor'] ?? '',
        'descriptor'  => $machine['descriptor'] ?? '',
        'image'       => $machine['image'] ?? '',
        'price'       => !empty($machine['price']) ? $machine['price'] : FALLBACK_MACHINE_PRICE,
        'price_label' => !empty($machine['price_label']) ? $machine['price_label'] : \__('Starting at', 'standard'),
        'explore_url' => get_sample_machine_url($machine, $category_slug, $public_slug),
        'build_url'   => \Standard\Url\with_query('/build-finance/', ['machine' => $public_slug]),
        'badge'       => get_sample_machine_badge($slug),
    ];
}

/**
 * Match legacy fallback URLs used before machine data became canonical.
 */
function get_public_machine_slug(string $slug): string {
    $map = [
        'mach-ii-5-gutter'     => 'mach-ii-5',
        'mach-ii-6-gutter'     => 'mach-ii-6',
        'mach-ii-combo-gutter' => 'mach-ii-combo',
    ];

    return $map[$slug] ?? $slug;
}

/**
 * @param array<string, mixed> $machine
 */
function get_sample_machine_url(array $machine, string $category_slug, string $public_slug): string {
    $url = (string) ($machine['url'] ?? '');
    if ($url !== '' && $url !== '#') {
        return \Standard\Url\internal($url);
    }

    return \Standard\Url\internal('/machines/' . $category_slug . '/' . $public_slug . '/');
}

function get_sample_machine_badge(string $slug): string {
    $map = [
        'ssq3-multipro'    => 'Best Seller',
        'mach-ii-5-gutter' => 'Popular',
    ];

    return $map[$slug] ?? '';
}

/**
 * @return array<int, array<string, string>>
 */
function get_sample_accessory_products(): array {
    $uploads_url = 'https://newtechmachinery.com/wp-content/uploads';

    return [
        [
            'id'          => 'coil-reel',
            'title'       => 'Coil Reel',
            'tagline'     => 'Smooth, consistent coil feeding.',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/coil-reel/'),
            'build_url'   => \Standard\Url\with_query('/build-finance/', ['accessory' => 'coil-reel']),
            'badge'       => '',
        ],
        [
            'id'          => 'run-out-stand',
            'title'       => 'Run-Out Stand',
            'tagline'     => 'Support for longer panel runs.',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/run-out-stand/'),
            'build_url'   => \Standard\Url\with_query('/build-finance/', ['accessory' => 'run-out-stand']),
            'badge'       => '',
        ],
        [
            'id'          => 'slitter',
            'title'       => 'Slitter Attachment',
            'tagline'     => 'Precision cutting on the job site.',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/slitter/'),
            'build_url'   => \Standard\Url\with_query('/build-finance/', ['accessory' => 'slitter']),
            'badge'       => 'New',
        ],
        [
            'id'          => 'notcher',
            'title'       => 'Notcher',
            'tagline'     => 'Clean notches for seamless seams.',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/notcher/'),
            'build_url'   => \Standard\Url\with_query('/build-finance/', ['accessory' => 'notcher']),
            'badge'       => '',
        ],
        [
            'id'          => 'hemmer',
            'title'       => 'Hemmer',
            'tagline'     => 'Professional edge finishing.',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/hemmer/'),
            'build_url'   => \Standard\Url\with_query('/build-finance/', ['accessory' => 'hemmer']),
            'badge'       => '',
        ],
    ];
}
