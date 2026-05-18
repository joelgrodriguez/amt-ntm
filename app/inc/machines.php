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
    // Hero slider features 3 machines: roof flagship + gutter flagship +
    // most-shopped roof. The full catalog (8+ machines) lives on /machines/.
    // Keeps the loop short (24s @ 8s/slide) and the marquee focused.
    //
    // Each entry pairs the data-file slug (key, matches data/machines/*.php)
    // with the title and the WooCommerce product slug used to resolve the
    // machine page permalink. The two slug namespaces don't match because
    // the data files are by model line and the WC products are by SKU.
    $slider_machines = [
        // NOTE: SSQ II while SSQ3's WooCommerce product is being prepared.
        // When the SSQ3 product page ships, swap data slug to 'ssq3-multipro'
        // and wp_slug to the new product permalink.
        'ssq-ii-multipro' => [
            'title'   => 'SSQ II™ MultiPro',
            'wp_slug' => 'ssq-roof-panel-machine',
        ],
        'mach-ii-5-gutter' => [
            'title'   => 'MACH II™',
            'wp_slug' => 'mach-ii-5-gutter-machine',
        ],
        '5vc-5v-crimp' => [
            'title'   => '5VC-5V CRIMP™',
            'wp_slug' => '5vc-5v-crimp-roof-panel-machine',
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
