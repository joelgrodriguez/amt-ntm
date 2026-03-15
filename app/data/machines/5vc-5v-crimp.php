<?php
/**
 * 5VC-5V CRIMP™ Roof Panel Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

// ── Hero ──
// ── Stats ──
// ── Finance ──
// ── Breakdown ──
// ── Blueprint ──
// ── Gallery ──
// ── Profiles ──
// ── Accessories ──
// ── Testimonials ──
// ── Comparison ──
// ── Specs ──
// ── Resources ──
// ── FAQ ──
// ── Schema ──

return [

    // ── Identity ──
    'category' => __('Roof & Wall Panel Machines', 'standard'),
    'slogan'   => __('Classic profiles, modern efficiency.', 'standard'),

    // ── Hero ──
    'hero' => [
        'headline'   => __('The Portable Solution to Your 5V Crimp Needs.', 'standard'),
        'subtitle'   => __('NTM\'s only exposed fastener roof panel machine. Profiles available in 21" and 24" widths.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2023/05/5V-on-site.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_5VC_1000x1000.png',
        'video'      => null,
    ],

    // ── Stats ──
    'stats' => [
        ['value' => '3',          'label' => __('Panel Profiles', 'standard')],
        ['value' => '60 ft/min',  'label' => __('Max Speed', 'standard')],
        ['value' => 'Hydraulic',  'label' => __('Drive & Shear', 'standard')],
        ['value' => '$70,800+',   'label' => __('Starting At', 'standard')],
    ],

    // ── Finance ──
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$70,800+', 'standard'),
        'note'          => __('Depending on machine configuration', 'standard'),
        'apr'           => '5.99%',
        'months'        => '60',
    ],

    // ── Breakdown ──
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Hardened Stainless Steel Precision', 'standard'),
            'copy'     => __('Hardened stainless steel forming roller system with shear dies for consistent 5V crimp profiles.', 'standard'),
            'specs'    => [
                __('Polyurethane drive rollers', 'standard'),
                __('Hardened stainless steel forming rollers', 'standard'),
                __('Hydraulically powered shear with hardened tool steel blades', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Compact and Durable', 'standard'),
            'copy'     => __('Welded tubular steel frame. Compact footprint for easy transport.', 'standard'),
            'specs'    => [
                __('2200 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Powder-coated finish', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'power-pack',
            'title'    => __('The Power Pack', 'standard'),
            'headline' => __('Gas or Electric. Quick-Change.', 'standard'),
            'copy'     => __('Quick-Change Power-Pack for field-swappable power.', 'standard'),
            'specs'    => [
                __('16 HP Briggs & Stratton gas engine', 'standard'),
                __('5 HP or 7.5 HP electric motor options', 'standard'),
                __('Hydraulic drive system', 'standard'),
            ],
            'image'    => '',
        ],
    ],

    // ── Blueprint ──
    'blueprint' => [
        'svg' => '5vc-machine',
    ],

    // ── Gallery ──
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],

    // ── Profiles ──
    'profiles' => [
        'tag_slugs' => ['5vc-5v-crimp-roof-panel-machine'],
    ],

    // ── Accessories ──
    'accessories' => [
        'product_tag' => '5VC',
    ],

    // ── Testimonials ──
    'testimonials' => [],

    // ── Comparison ──
    'comparison' => [
        'compare_with' => ['ssh-multipro', 'ssr-multipro-jr'],
        'best_for'     => __('Exposed fastener roofing', 'standard'),
    ],

    // ── Specs ──
    'specs' => [
        'standard_features' => [
            __('Polyurethane Drive Rollers', 'standard'),
            __('Push Button RUN/JOG Controls at Entry & Exit Ends', 'standard'),
            __('Hardened Stainless Steel Forming Roller System with Shear Dies', 'standard'),
            __('Hydraulic Drive & Shear', 'standard'),
            __('Length Control Limit Switch', 'standard'),
            __('Panel Recognition Safety Proximity Switch', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('Power Interruption Safety Circuit', 'standard'),
            __('Industry\'s Best Warranty', 'standard'),
            __('Quick-Change Power-Pack™ (Gas or Electric)', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "10'5\" (3.2m)",
                'length_slitter' => null,
                'width'          => "4'10½\" (1.2m)",
                'height'         => "4'3\" (1.3m)",
                'height_no_rack' => "2'4\" (0.75m)",
                'weight'         => '2,200 lbs. (1,000 kg)',
            ],
            'on_trailer' => [
                'length' => "18'11\" (5.8m)",
                'width'  => "7'2½\" (2.2m)",
                'height' => "6'3\" (1.9m)",
                'weight' => '4,460 lbs. (2,020 kg)',
            ],
        ],

        'performance' => [
            'shear' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('Hydraulically Powered', 'standard'),
                    __('Hardened Tool Steel Blades & Shear Dies', 'standard'),
                ],
            ],
            'drive' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('Hydraulically Driven Polyurethane Rollers', 'standard'),
                ],
            ],
            'speed' => [
                ['source' => __('All Power Sources', 'standard'), 'rate' => __('60 ft./min (18m/min)', 'standard')],
            ],
        ],

        'materials' => [
            [
                'name'      => __('Painted Steel', 'standard'),
                'gauge'     => __('30 ga. to 24 ga. (0.3mm to 0.6mm)', 'standard'),
                'note'      => __('Painted, Galvalume, coated galvanized.', 'standard'),
            ],
            [
                'name'      => __('Painted Aluminum', 'standard'),
                'gauge'     => __('.027" to .040" (0.7mm to 1.0mm)', 'standard'),
                'note'      => null,
            ],
            [
                'name'      => __('Copper', 'standard'),
                'gauge'     => __('16 oz. to 20 oz. 3/4 Hard (0.5mm to 0.7mm)', 'standard'),
                'note'      => null,
            ],
        ],

        'coil' => [
            'widths'              => __('24" to 27½" (610mm to 699mm)', 'standard'),
            'finished_widths'     => __('21" or 24" (533mm or 610mm)', 'standard'),
            'max_diameter_rack'   => __('32" (812mm)', 'standard'),
            'max_diameter_decoil' => __('45" (1,143mm)', 'standard'),
            'max_weight_reel'     => null,
            'max_weight_cradle'   => null,
        ],

        'power_options' => [
            __('7.5 HP, 220V, 60 Hz, 3PH, 18 amps', 'standard'),
            __('5 HP, 220V, 60 Hz, 1PH, 20 amps', 'standard'),
            __('Gas Power (16 HP Briggs & Stratton Engine)', 'standard'),
            __('5 HP, 380V, 50 Hz, 3PH, 8 amps', 'standard'),
            __('7.5 HP, 460V, 60 Hz, 3PH, 9 amps', 'standard'),
            __('5 HP, 220V, 50 Hz, 1PH, 21 amp', 'standard'),
        ],

        'add_on_weights' => [
            ['item' => __('Trailer', 'standard'),                          'weight' => __('2260 lbs. (1020 kg)', 'standard')],
            ['item' => __('Overhead Reel Rack', 'standard'),               'weight' => __('336 lbs. (152 kg)', 'standard')],
            ['item' => __('Expandable Arbor (each)', 'standard'),          'weight' => __('80 lbs. (36 kg)', 'standard')],
            ['item' => __('10-foot Run-out Table (each)', 'standard'),     'weight' => __('62 lbs. (28 kg)', 'standard')],
            ['item' => __('PVC Strippable Film Applicator', 'standard'),   'weight' => __('60 lbs. (27 kg)', 'standard')],
        ],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => ['U.S. PATENT NO. 6,772,616'],
        ],
    ],

    // ── Resources ──
    'resources' => [
        'manual'               => 'https://newtechmachinery.com/learning-center/manual/5vc-5v-roof-panel-machine-with-ams-controller-manual/',
        'brochure'             => 'https://newtechmachinery.com/learning-center/literature/5vc-5v-crimp-roof-panel-machine-brochure/',
        'service_training_url' => '/service-training',
    ],

    // ── FAQ ──
    'faq' => [
        [
            'question' => __('What makes the 5VC different from other NTM machines?', 'standard'),
            'answer'   => __('The 5VC is NTM\'s only exposed fastener roof panel machine, producing 5V crimp profiles in 21" and 24" widths. All other NTM panel machines produce concealed-fastener standing seam or snap-lock profiles.', 'standard'),
        ],
        [
            'question' => __('What profiles can the 5VC produce?', 'standard'),
            'answer'   => __('Three 5V crimp profiles: 5VC-210P, 5VC-240P, and 5VC-245P.', 'standard'),
        ],
        [
            'question' => __('What warranty is included?', 'standard'),
            'answer'   => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
        ],
    ],

    // ── Schema ──
    'schema' => [
        'low_price'    => '70800',
        'high_price'   => null,
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
