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
    // Hero slide content shape per category:
    //   - eyebrow / category: short label, named explicitly
    //   - title: the BENEFIT the buyer gets from this category. Big
    //     display type; what makes them lean in.
    //   - slogan: ~155-character meta-description voice. Names what
    //     the category does, lists concrete proof points, ends short
    //     of 160 chars so it works as both on-page subhead and as
    //     SEO description for crawlers.
    //   - cta: explicit per-slide, names the destination.
    //   - cta_url: routes to category landing, not the deep SKU.
    $machine_slides = [
        'ssq3-multipro' => [
            'title'            => __('Elevate Your Output', 'standard'),
            'slogan'           => __('Build standing seam, flush wall, and board-and-batten panels on-site with NTM portable rollformers. 16 profiles, gas or electric power, owner-grade.', 'standard'),
            // Slider-only image. Keeps data/machines/ssq3-multipro.php's
            // hero.hero_image free to drive the single-machine page.
            'background_image' => content_url('/uploads/2026/05/ntm-q3-hero-placeholder-2.png'),
            // Image bias: this shot has the machine sitting low in the
            // frame, so anchor to the bottom edge when cover-cropped.
            'focal_point'      => 'center bottom',
            'cta_url'          => '/roof-wall-panel-machines/',
            'cta_label'        => __('Explore Panel Machines', 'standard'),
        ],
        'mach-ii-combo-gutter' => [
            'title'     => __('Do More<br class="hidden lg:inline"> in Less Time', 'standard'),
            'slogan'    => __('Run 5- and 6-inch K-style seamless gutters on every job, single setup, no machine swap. NTM portable gutter machines keep the full ticket in-house.', 'standard'),
            'cta_url'   => '/seamless-gutter-machines/',
            'cta_label' => __('Explore Gutter Machines', 'standard'),
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
            'slogan'           => $meta['slogan'],
            'background_image' => $meta['background_image'] ?? $data['hero']['hero_image'] ?? $data['hero']['image'] ?? '',
            'background_video' => $data['hero']['video'] ?? '',
            'learn_more_url'   => $meta['cta_url'],
            'cta_label'        => $meta['cta_label'],
            'focal_point'      => $meta['focal_point'] ?? '',
        ];
    }

    // Non-machine slide. Same shape as above. Accessories doesn't
    // have a data file; everything hand-rolled.
    $slides[] = [
        'id'               => 'upgrades',
        'category'         => __('Accessories & Upgrades', 'standard'),
        'title'            => __('Expand Your Capabilities', 'standard'),
        'slogan'           => __('Extend what your NTM machine already does. Trailers, decoilers, control upgrades, and tooling that add profiles, capacity, and faster job turnaround.', 'standard'),
        'background_image' => 'https://newtechmachinery.com/wp-content/uploads/2026/04/Jim-adjusting-his-machine-scaled.jpg',
        'background_video' => '',
        'learn_more_url'   => '/machines/upgrades/',
        'cta_label'        => __('Explore Accessories', 'standard'),
    ];

    return $slides;
}
