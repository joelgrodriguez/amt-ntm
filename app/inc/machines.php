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
    // with the title and the category landing page the CTA routes to. The
    // hero slider does the "category door" job for the front page — the
    // slide photo + title spotlights the flagship machine in each category,
    // but the CTA delivers the buyer to the category landing (not the deep
    // product page) so they see the full lineup with category context.
    $machine_slides = [
        'ssq3-multipro' => [
            'title'            => 'SSQ3™ MultiPro',
            // Slider-only image. Keeps data/machines/ssq3-multipro.php's
            // hero.hero_image free to drive the single-machine page.
            'background_image' => content_url('/uploads/2026/05/ntm-q3-hero-placeholder-2.png'),
            // Image bias: this shot has the machine sitting low in the
            // frame, so anchor to the bottom edge when cover-cropped.
            'focal_point'      => 'center bottom',
            'cta_url'          => '/machines/roof-wall-panel-machines/',
            'cta_label'        => __('Elevate Your Output', 'standard'),
        ],
        'mach-ii-combo-gutter' => [
            'title'     => 'MACH II™ Combo',
            'cta_url'   => '/machines/gutter-machines/',
            'cta_label' => __('Do More in Less Time', 'standard'),
        ],
    ];

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
            'learn_more_url'   => $meta['cta_url'],
            'cta_label'        => $meta['cta_label'],
            'focal_point'      => $meta['focal_point'] ?? '',
        ];
    }

    // Non-machine slides. Same shape, hand-rolled because there's no
    // data file behind them. Accessories slide already routed to the
    // category landing (the model for the two machine slides above).
    $slides[] = [
        'id'               => 'ntm-accessories',
        'category'         => __('Accessories & Upgrades', 'standard'),
        'title'            => __('Built for the Job.', 'standard'),
        'slogan'           => __('Plug in an upgrade. Run more profiles, more coil, more jobs.', 'standard'),
        'background_image' => 'https://newtechmachinery.com/wp-content/uploads/2026/04/Jim-adjusting-his-machine-scaled.jpg',
        'background_video' => '',
        'learn_more_url'   => '/machines/ntm-accessories/',
        'cta_label'        => __('Expand Your Capabilities', 'standard'),
    ];

    return $slides;
}
