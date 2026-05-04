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
    $slider_slugs = [
        'ssq3-multipro'    => 'SSQ3™ MultiPro',
        'ssq-ii-multipro'  => 'SSQ II™ MultiPro',
        'ssh-multipro'     => 'SSH™ MultiPro',
        'ssr-multipro-jr'  => 'SSR™ MultiPro Jr.',
        '5vc-5v-crimp'     => '5VC-5V CRIMP™',
        'wav-wall-panel'   => 'WAV™',
        'mach-ii-5-gutter' => 'MACH II™',
        'bg7-box-gutter'   => 'BG7™',
    ];

    // Build slug → permalink map from WooCommerce
    $permalinks = [];
    if (function_exists('wc_get_products')) {
        $products = \Standard\Woo\Cache\get_products([
            'slug'   => array_keys($slider_slugs),
            'limit'  => count($slider_slugs),
            'status' => 'publish',
        ]);
        foreach ($products as $product) {
            $permalinks[$product->get_slug()] = $product->get_permalink();
        }
    }

    $machines = [];
    foreach ($slider_slugs as $slug => $title) {
        $data = get_machine_product_data($slug);
        if (!$data) {
            continue;
        }

        $machines[] = [
            'id'               => $slug,
            'category'         => $data['category'] ?? '',
            'title'            => $title,
            'slogan'           => $data['slogan'] ?? '',
            'background_image' => $data['hero']['hero_image'] ?? $data['hero']['image'] ?? '',
            'background_video' => $data['hero']['video'] ?? '',
            'finance_apr'      => $data['finance']['apr'] ?? '',
            'finance_months'   => $data['finance']['months'] ?? '',
            'finance_url'      => \Standard\Url\with_query('/build-finance/', ['machine' => $slug]),
            'learn_more_url'   => $permalinks[$slug] ?? '#',
        ];
    }

    return $machines;
}
