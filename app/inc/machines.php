<?php
/**
 * Front-Page Hero Slider Configuration
 *
 * Returns slide data for the front-page hero slider. Most slides are
 * derived from machine data files; arbitrary category slides (e.g.
 * Accessories) can also be appended.
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
 * Get the front-page hero slides.
 *
 * Slide shape (what hero-slide.php consumes):
 *   id, category, title, slogan, background_image, background_video,
 *   learn_more_url, cta_label
 *
 * Machine-derived slides pull image/category/slogan from data/machines/*.php
 * and resolve the CTA URL from WooCommerce. Extra slides (accessories,
 * etc.) hand-roll the same shape inline at the bottom.
 *
 * @return array<int, array>
 */
function get_hero_slides(): array {
    // Machine slides: SSQ3 (roof) + MACH II Combo (gutter). The full catalog
    // (8+ machines) lives on /machines/.
    //
    // Each entry pairs the data-file slug (key, matches data/machines/*.php)
    // with the title and the WooCommerce product slug used to resolve the
    // machine page permalink. The two slug namespaces don't match because
    // the data files are by model line and the WC products are by SKU.
    $machine_slides = [
        'ssq3-multipro' => [
            'title'            => 'SSQ3™ MultiPro',
            'wp_slug'          => 'ssq3-multipro',
            // Slider-only image. Keeps data/machines/ssq3-multipro.php's
            // hero.hero_image free to drive the single-machine page.
            'background_image' => content_url('/uploads/2026/05/ntm-q3-hero-placeholder-2.png'),
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
    foreach ($machine_slides as $meta) {
        $wp_slug = $meta['wp_slug'];
        $post = get_page_by_path($wp_slug, OBJECT, 'product');
        if ($post && $post->post_status === 'publish') {
            $permalinks[$wp_slug] = get_permalink($post);
        }
    }

    $slides = [];
    foreach ($machine_slides as $slug => $meta) {
        $data = get_machine_product_data($slug);
        if (!$data) {
            continue;
        }

        $slides[] = [
            'id'               => $slug,
            'category'         => $data['category'] ?? '',
            'title'            => $meta['title'],
            'slogan'           => $data['slogan'] ?? '',
            'background_image' => $meta['background_image'] ?? $data['hero']['hero_image'] ?? $data['hero']['image'] ?? '',
            'background_video' => $data['hero']['video'] ?? '',
            'learn_more_url'   => $permalinks[$meta['wp_slug']] ?? '#',
            'cta_label'        => __('View Machine', 'standard'),
        ];
    }

    // Non-machine slides. Same shape, hand-rolled because there's no
    // data file behind them.
    $slides[] = [
        'id'               => 'ntm-accessories',
        'category'         => __('Accessories & Upgrades', 'standard'),
        'title'            => __('Built for the Job.', 'standard'),
        'slogan'           => __('Plug in an upgrade. Run more profiles, more coil, more jobs from the machine you own.', 'standard'),
        'background_image' => 'https://newtechmachinery.com/wp-content/uploads/2026/04/Jim-adjusting-his-machine-scaled.jpg',
        'background_video' => '',
        'learn_more_url'   => '/machines/ntm-accessories/',
        'cta_label'        => __('Shop Accessories', 'standard'),
    ];

    return $slides;
}
