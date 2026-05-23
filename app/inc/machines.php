<?php
/**
 * Featured Machines Configuration
 *
 * Defines which machines appear in the front-page hero slider
 * and builds slide data from the machine data files.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Machines;

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;

/**
 * Get featured machines for the hero slider.
 *
 * Machine data (images, finance, category, slogan) comes from data/machines/*.php.
 * Product URLs come from WooCommerce. Only the slug list and display titles live here.
 *
 * @return array<int, array>
 */
function get_featured_machines(): array {
    // Hero slider features the two flagships: SSQ3 (roof) + MACH II Combo
    // (gutter). The full catalog (8+ machines) lives on /machines/.
    // Two-slide loop @ 8s/slide keeps the marquee focused on the flagships.
    //
    // Each entry pairs the data-file slug (key, matches data/machines/*.php)
    // with the title and the WooCommerce product slug used to resolve the
    // machine page permalink. The two slug namespaces don't match because
    // the data files are by model line and the WC products are by SKU.
    $slider_machines = [
        'ssq3-multipro' => [
            'title'   => 'SSQ3™ MultiPro',
            'wp_slug' => 'ssq3-multipro',
        ],
        'mach-ii-combo-gutter' => [
            'title'   => 'MACH II™ Combo',
            'wp_slug' => 'mach-ii-5-6-combo-gutter-machine',
        ],
    ];

    // Build wp_slug → permalink map.
    // get_page_by_path() does an exact slug match; wc_get_products()'s
    // 'slug' arg does a LIKE match and can return adjacent products,
    // so we resolve each slug individually for correctness.
    $permalinks = [];
    foreach ($slider_machines as $meta) {
        $wp_slug = $meta['wp_slug'];
        $post = get_page_by_path($wp_slug, OBJECT, 'product');
        if ($post && $post->post_status === 'publish') {
            $permalinks[$wp_slug] = get_permalink($post);
        }
    }

    $machines = [];
    foreach ($slider_machines as $slug => $meta) {
        $data = get_machine_product_data($slug);
        if (!$data) {
            continue;
        }

        $machines[] = [
            'id'               => $slug,
            'category'         => $data['category'] ?? '',
            'title'            => $meta['title'],
            'slogan'           => $data['slogan'] ?? '',
            'background_image' => $data['hero']['hero_image'] ?? $data['hero']['image'] ?? '',
            'background_video' => $data['hero']['video'] ?? '',
            'stats'            => $data['stats'] ?? [],
            'finance_apr'      => $data['finance']['apr'] ?? '',
            'finance_months'   => $data['finance']['months'] ?? '',
            'finance_url'      => \Standard\Url\with_query('/build-finance/', ['machine' => $slug]),
            'learn_more_url'   => $permalinks[$meta['wp_slug']] ?? '#',
        ];
    }

    return $machines;
}
