<?php
/**
 * Products Configuration
 *
 * Product data for the Explore Machines section.
 * Uses WooCommerce products if available, otherwise falls back to sample data.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Products;

/**
 * Get product categories for the Explore Machines section.
 *
 * @return array<string, string> Category slug => label pairs
 */
function get_product_categories(): array {
    return [
        'all'                         => \__('All', 'standard'),
        'roof-wall-panel-machines'    => \__('Roof & Wall Panel Machines', 'standard'),
        'gutter-machines'             => \__('Seamless Gutter Machines', 'standard'),
        'accessories-add-on-equipment' => \__('Accessories & Upgrades', 'standard'),
    ];
}

/**
 * Get products by category.
 *
 * Uses WooCommerce if available, otherwise returns sample data.
 *
 * @param string $category_slug The category slug
 * @return array<int, array> Array of product data
 */
function get_products_by_category(string $category_slug): array {
    // Check if WooCommerce is active
    if (function_exists('wc_get_products')) {
        return get_woocommerce_products($category_slug);
    }

    // Fallback to sample data
    return get_sample_products($category_slug);
}

/**
 * Get products from WooCommerce.
 *
 * @param string $category_slug The category slug
 * @return array<int, array> Array of product data
 */
function get_woocommerce_products(string $category_slug): array {
    $current_year = date('Y');

    // Handle "all" category - query each category separately to maintain order
    if ($category_slug === 'all') {
        $all_formatted = [];

        // Get roof & wall panel machines first
        $all_formatted = array_merge($all_formatted, get_woocommerce_products('roof-wall-panel-machines'));

        // Then gutter machines
        $all_formatted = array_merge($all_formatted, get_woocommerce_products('gutter-machines'));

        // Then accessories (limit to 7)
        $accessories = get_woocommerce_products('accessories-add-on-equipment');
        $all_formatted = array_merge($all_formatted, array_slice($accessories, 0, 7));

        return $all_formatted;
    }

    $is_accessory = $category_slug === 'accessories-add-on-equipment';

    $products = \wc_get_products([
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
            'year'        => $is_accessory ? '' : $current_year,
            'image'       => \wp_get_attachment_url($product->get_image_id()),
            'price'       => $is_accessory ? '' : '$87,245',
            'price_label' => \__('Starting at', 'standard'),
            'explore_url' => $product->get_permalink(),
            'build_url'   => '/build-finance/?machine=' . $product->get_slug(),
            'badge'       => '',
        ];
    }

    return $formatted;
}

/**
 * Get sample products for development/fallback.
 *
 * @param string $category_slug The category slug
 * @return array<int, array> Array of product data
 */
function get_sample_products(string $category_slug): array {
    $uploads_url = 'https://newtechmachinery.com/wp-content/uploads';
    $current_year = date('Y');

    $products = [
        'roof-wall-panel-machines' => [
            [
                'id'          => 'ssq3-multipro',
                'title'       => 'SSQ3™ MultiPro',
                'tagline'     => 'The future of portable roll forming.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2026/01/Screenshot-2026-01-07-at-9.37.43-AM.png',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/ssq3-multipro/',
                'build_url'   => '/build-finance/?machine=ssq3-multipro',
                'badge'       => 'Best Seller',
            ],
            [
                'id'          => 'ssq-ii-multipro',
                'title'       => 'SSQ II™ MultiPro',
                'tagline'     => 'Versatility meets precision.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2025/12/starting-SSQ-on-job-site-1024x576-1.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/ssq-ii-multipro/',
                'build_url'   => '/build-finance/?machine=ssq-ii-multipro',
                'badge'       => '',
            ],
            [
                'id'          => 'ssh-multipro',
                'title'       => 'SSH™ MultiPro',
                'tagline'     => 'Built for standing seam perfection.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/ssh-multipro/',
                'build_url'   => '/build-finance/?machine=ssh-multipro',
                'badge'       => '',
            ],
            [
                'id'          => 'ssr-multipro-jr',
                'title'       => 'SSR™ MultiPro Jr.',
                'tagline'     => 'Compact power, professional results.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2023/05/5V-on-site.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/ssr-multipro-jr/',
                'build_url'   => '/build-finance/?machine=ssr-multipro-jr',
                'badge'       => '',
            ],
            [
                'id'          => '5vc-5v-crimp',
                'title'       => '5VC-5V CRIMP™',
                'tagline'     => 'Classic profiles, modern efficiency.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2023/05/5V-on-site.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/5vc-5v-crimp/',
                'build_url'   => '/build-finance/?machine=5vc-5v-crimp',
                'badge'       => '',
            ],
        ],
        'gutter-machines' => [
            [
                'id'          => 'mach-ii-5',
                'title'       => 'MACH II™ 5"',
                'tagline'     => 'Speed and precision, job after job.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2024/07/20240612_NTM_CS-Rain-Gutters-Interview_V1.00_03_30_06.Still002.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/mach-ii-5/',
                'build_url'   => '/build-finance/?machine=mach-ii-5',
                'badge'       => 'Popular',
            ],
            [
                'id'          => 'mach-ii-6',
                'title'       => 'MACH II™ 6"',
                'tagline'     => 'Larger capacity for bigger jobs.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2024/07/20240612_NTM_CS-Rain-Gutters-Interview_V1.00_03_30_06.Still002.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/mach-ii-6/',
                'build_url'   => '/build-finance/?machine=mach-ii-6',
                'badge'       => '',
            ],
            [
                'id'          => 'mach-ii-combo',
                'title'       => 'MACH II™ 5"/6" Combo',
                'tagline'     => 'Two sizes, one machine.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2024/07/20240612_NTM_CS-Rain-Gutters-Interview_V1.00_03_30_06.Still002.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/mach-ii-combo/',
                'build_url'   => '/build-finance/?machine=mach-ii-combo',
                'badge'       => '',
            ],
            [
                'id'          => 'bg7-box-gutter',
                'title'       => 'BG7™',
                'tagline'     => 'Commercial-grade, built to last.',
                'year'        => $current_year,
                'image'       => $uploads_url . '/2023/09/BG7-forming-gutter-scaled.jpg',
                'price'       => '$87,245',
                'price_label' => \__('Starting at', 'standard'),
                'explore_url' => '/machines/bg7-box-gutter/',
                'build_url'   => '/build-finance/?machine=bg7-box-gutter',
                'badge'       => '',
            ],
        ],
        'accessories-add-on-equipment' => [
            [
                'id'          => 'coil-reel',
                'title'       => 'Coil Reel',
                'tagline'     => 'Smooth, consistent coil feeding.',
                'year'        => '',
                'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                'price'       => '',
                'price_label' => '',
                'explore_url' => '/accessories/coil-reel/',
                'build_url'   => '/build-finance/?accessory=coil-reel',
                'badge'       => '',
            ],
            [
                'id'          => 'run-out-stand',
                'title'       => 'Run-Out Stand',
                'tagline'     => 'Support for longer panel runs.',
                'year'        => '',
                'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                'price'       => '',
                'price_label' => '',
                'explore_url' => '/accessories/run-out-stand/',
                'build_url'   => '/build-finance/?accessory=run-out-stand',
                'badge'       => '',
            ],
            [
                'id'          => 'slitter',
                'title'       => 'Slitter Attachment',
                'tagline'     => 'Precision cutting on the job site.',
                'year'        => '',
                'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                'price'       => '',
                'price_label' => '',
                'explore_url' => '/accessories/slitter/',
                'build_url'   => '/build-finance/?accessory=slitter',
                'badge'       => 'New',
            ],
            [
                'id'          => 'notcher',
                'title'       => 'Notcher',
                'tagline'     => 'Clean notches for seamless seams.',
                'year'        => '',
                'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                'price'       => '',
                'price_label' => '',
                'explore_url' => '/accessories/notcher/',
                'build_url'   => '/build-finance/?accessory=notcher',
                'badge'       => '',
            ],
            [
                'id'          => 'hemmer',
                'title'       => 'Hemmer',
                'tagline'     => 'Professional edge finishing.',
                'year'        => '',
                'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
                'price'       => '',
                'price_label' => '',
                'explore_url' => '/accessories/hemmer/',
                'build_url'   => '/build-finance/?accessory=hemmer',
                'badge'       => '',
            ],
        ],
    ];

    // Handle "all" category - combine all products
    if ($category_slug === 'all') {
        $all_products = [];

        // Add all machines
        foreach ($products['roof-wall-panel-machines'] as $product) {
            $all_products[] = $product;
        }
        foreach ($products['gutter-machines'] as $product) {
            $all_products[] = $product;
        }

        // Add accessories (limit to 7)
        $accessories = array_slice($products['accessories-add-on-equipment'], 0, 7);
        foreach ($accessories as $product) {
            $all_products[] = $product;
        }

        return $all_products;
    }

    return $products[$category_slug] ?? [];
}
