<?php
/**
 * Machine Product Landing Page Data
 *
 * Per-machine content for the custom single product template.
 * WooCommerce handles price, SKU, gallery, and cart.
 * This file provides the landing page narrative content.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineProductData;

/**
 * Get landing page data for a specific machine by slug.
 *
 * @param string $slug WooCommerce product slug.
 * @return array|null Machine data or null if not found.
 */
function get_machine_product_data(string $slug): ?array {
    $machines = get_all_machine_product_data();

    // Exact match first
    if (isset($machines[$slug])) {
        return $machines[$slug];
    }

    // Prefix match: WooCommerce slugs often include the full name
    // e.g., 'ssq3-multipro-roof-panel-machine' should match key 'ssq3-multipro'
    // Sort by key length descending so longest prefix wins (prevents 'ssh' matching before 'ssh-multipro')
    $keys = array_keys($machines);
    usort($keys, fn($a, $b) => strlen($b) - strlen($a));
    foreach ($keys as $key) {
        if (str_starts_with($slug, $key)) {
            return $machines[$key];
        }
    }

    // Fallback: return default skeleton data so all machines render the custom template
    // TODO: remove once all machines have dedicated data entries
    return get_default_machine_data();
}

/**
 * Get all machine product landing page data.
 *
 * @return array<string, array> Keyed by product slug.
 */
function get_all_machine_product_data(): array {
    $base = 'https://newtechmachinery.com/wp-content/uploads/';

    return [
        'ssq3-multipro' => [
            // Hero
            'hero_headline' => __('Produce 16 Panel Profiles On-Site. One Machine.', 'standard'),
            'hero_subtitle' => __('The most advanced portable roof and wall panel machine we\'ve ever built.', 'standard'),
            'hero_image'    => $base . '2025/09/Machine-on-rooftop-scaled.jpg',
            'hero_video'    => null, // future: URL to mp4

            // Stats bar
            'stats' => [
                ['value' => '16',          'label' => __('Panel Profiles', 'standard')],
                ['value' => '25 min',      'label' => __('Tooling Changeover', 'standard')],
                ['value' => '75 ft/min',   'label' => __('Max Speed', 'standard')],
                ['value' => '$2.25/sq ft', 'label' => __('Avg. Savings', 'standard')],
            ],

            // Machine breakdown — subsystem sections
            'breakdown' => [
                [
                    'id'       => 'forming-system',
                    'title'    => __('The Forming System', 'standard'),
                    'headline' => __('Precision Forming, Panel After Panel', 'standard'),
                    'copy'     => __('16 roller stations with hardened tool steel shear dies deliver consistent, accurate panel profiles at production speed.', 'standard'),
                    'specs'    => [
                        __('16 polyurethane drive rollers', 'standard'),
                        __('Hydraulically powered shear with hardened tool steel blades', 'standard'),
                        __('Panel recognition safety system', 'standard'),
                    ],
                    'image'    => '', // TODO: contextual photo
                ],
                [
                    'id'       => 'frame',
                    'title'    => __('The Frame', 'standard'),
                    'headline' => __('Built to Take a Beating', 'standard'),
                    'copy'     => __('Welded tubular steel frame with powder-coated aluminum covers. Built for the job site, not the showroom.', 'standard'),
                    'specs'    => [
                        __('2,830 lbs base weight', 'standard'),
                        __('Welded tubular steel frame', 'standard'),
                        __('Powder-coated aluminum covers with windows', 'standard'),
                    ],
                    'image'    => '', // TODO: contextual photo
                ],
                [
                    'id'       => 'power-pack',
                    'title'    => __('The Power Pack', 'standard'),
                    'headline' => __('Gas or Electric. Your Call.', 'standard'),
                    'copy'     => __('Quick-Change Power-Pack swaps between gas and electric in the field. No tools, no downtime.', 'standard'),
                    'specs'    => [
                        __('16 HP Briggs & Stratton gas engine', 'standard'),
                        __('5 HP or 7.5 HP electric motor options', 'standard'),
                        __('Up to 75 ft/min production speed', 'standard'),
                    ],
                    'image'    => '', // TODO: contextual photo
                ],
                [
                    'id'       => 'brain',
                    'title'    => __('The Brain', 'standard'),
                    'headline' => __('Smart Controls, Simple Operation', 'standard'),
                    'copy'     => __('Choose manual push-button controls or the UNIQ Automatic Control System with touchscreen diagnostics.', 'standard'),
                    'specs'    => [
                        __('UNIQ® Automatic Control System option', 'standard'),
                        __('Push-button RUN/JOG at entry & exit ends', 'standard'),
                        __('RFID cover sensors and on-controller diagnostics', 'standard'),
                    ],
                    'image'    => '', // TODO: contextual photo
                ],
            ],

            // Blueprint
            'blueprint_svg' => 'ssq3-machine',
            'blueprint_dimensions' => [
                'length'         => "14'4\" (4.4m)",
                'length_slitter' => "15'4\" (4.7m)",
                'width'          => "5'2\" (1.57m)",
                'height'         => "4'3\" (1.3m)",
                'height_no_rack' => "2'6\" (0.8m)",
                'weight'         => '2,830 lbs (1,280 kg)',
            ],
            'blueprint_trailer' => [
                'length' => "18'11\" (5.8m)",
                'width'  => "7'2½\" (2.2m)",
                'height' => "6'3\" (1.9m)",
                'weight' => '5,090 lbs (2,300 kg)',
            ],

            // Gallery (v1: static multi-angle, v2: rotator frames)
            'gallery_images' => [], // TODO: multi-angle photos
            'rotator_images' => [], // TODO: 24-36 frame sequence

            // Social proof
            'testimonials' => [
                // TODO: source from NTM customer stories
            ],

            // Comparison
            'compare_with' => ['ssq-ii-multipro', 'ssh-multipro'],
            'best_for'     => __('High-volume commercial & residential', 'standard'),

            // Accessories (product slugs)
            'featured_accessories' => [],  // TODO: WooCommerce product slugs

            // Resources
            'resources' => [
                'manual'   => '', // TODO: URL
                'brochure' => '', // TODO: URL
            ],
        ],

        // Additional machines follow the same structure
    ];
}

/**
 * Default skeleton data for machines without dedicated entries.
 * Pulls headline from the WooCommerce product name.
 * TODO: remove once all machines have dedicated data.
 *
 * @return array
 */
function get_default_machine_data(): array {
    return [
        'hero_headline' => '', // falls back to product name in template
        'hero_subtitle' => '',
        'hero_image'    => '',
        'hero_video'    => null,
        'stats'         => [],
        'breakdown'     => [],
        'blueprint_svg' => '',
        'blueprint_dimensions' => [],
        'blueprint_trailer'    => [],
        'gallery_images'  => [],
        'rotator_images'  => [],
        'profiles'        => [],
        'testimonials'    => [],
        'compare_with'    => [],
        'best_for'        => '',
        'featured_accessories' => [],
        'resources'       => [],
    ];
}
