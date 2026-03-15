<?php
/**
 * WAV™ Wall Panel Machine – Product Data
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
    'slogan'   => __('Wave panels, endless possibilities.', 'standard'),

    // ── Hero ──
    'hero' => [
        'headline'   => __('The Industry\'s Only Portable WAV Profile Machine.', 'standard'),
        'subtitle'   => __('Purpose-built for heavy commercial and industrial wall panel work. UNIQ® Automatic Control System comes standard.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_WAV_1000x1000.png',
        'video'      => null,
    ],

    // ── Stats ──
    'stats' => [
        ['value' => '3',             'label' => __('WAV Profiles', 'standard')],
        ['value' => '150 ft/min',    'label' => __('Max Speed (Electric)', 'standard')],
        ['value' => 'UNIQ®',         'label' => __('Controller Standard', 'standard')],
        ['value' => '$232,000+',     'label' => __('Starting At', 'standard')],
    ],

    // ── Finance ──
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$232,000+', 'standard'),
        'note'          => __('Depending on configuration', 'standard'),
        'apr'           => '',
        'months'        => '',
    ],

    // ── Breakdown ──
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('25 Rollers. Maximum Precision.', 'standard'),
            'copy'     => __('25 polyurethane drive rollers with VFD for precise wall panel forming at up to 150 ft/min.', 'standard'),
            'specs'    => [
                __('25 polyurethane drive rollers', 'standard'),
                __('Hydraulic drive & shear', 'standard'),
                __('8" 12" and 16" WAV-style profiles', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Built for Heavy Commercial Work', 'standard'),
            'copy'     => __('The largest machine in the NTM lineup. Welded tubular steel frame.', 'standard'),
            'specs'    => [
                __('5000 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Fixed mount or trailer options', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'controls',
            'title'    => __('The Controls', 'standard'),
            'headline' => __('UNIQ® Comes Standard', 'standard'),
            'copy'     => __('UNIQ Automatic Control System with computer batch and length control included.', 'standard'),
            'specs'    => [
                __('UNIQ® Automatic Control System standard', 'standard'),
                __('Computer batch & length control', 'standard'),
                __('On-site setup and training included', 'standard'),
            ],
            'image'    => '',
        ],
    ],

    // ── Blueprint ──
    'blueprint' => [
        'svg' => 'wav-machine',
    ],

    // ── Gallery ──
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],

    // ── Profiles ──
    'profiles' => [
        'tag_slugs' => ['wav-wall-panel-machine'],
    ],

    // ── Accessories ──
    'accessories' => [
        'product_tag' => 'WAV',
    ],

    // ── Testimonials ──
    'testimonials' => [],

    // ── Comparison ──
    'comparison' => [
        'compare_with' => ['ssq3-multipro', 'ssq-ii-multipro'],
        'best_for'     => __('Heavy commercial/industrial walls', 'standard'),
    ],

    // ── Specs ──
    'specs' => [
        'standard_features' => [
            __('Can form 8", 12", and 16" WAV-style profiles with either fastener flange or clip-style attachment', 'standard'),
            __('UNIQ Automatic Control System', 'standard'),
            __('Hydraulic Shear', 'standard'),
            __('Hydraulic Drive', 'standard'),
            __('Equipped with 16 HP Gas Engine and/or 460V 3-Phase Electric Power', 'standard'),
            __('Minimum Flat Sheet Feed Length is 62 Inches (1,575mm)', 'standard'),
            __('Free on-site setup and training within the continental U.S. included', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "22'8\" (6.7m)",
                'length_slitter' => null,
                'width'          => "5'1\" (1.5m)",
                'height'         => "4'5\" (1.2m)",
                'height_no_rack' => "2'7\" (0.61m)",
                'weight'         => '5,000 lbs. (2,272.7 kg)',
            ],
            'on_trailer' => [
                'length' => "27'10\" (8.2m)",
                'width'  => "7'4\" (2.1m)",
                'height' => "6'7\" (1.83m)",
                'weight' => '8,700 lbs. (3,954.6 kg)',
            ],
        ],

        'performance' => [
            'shear' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('Hydraulically Powered', 'standard'),
                    __('Hardened Tool Steel Blades & Shear Dies', 'standard'),
                    __('Panel Recognition Proximity Sensor', 'standard'),
                ],
            ],
            'drive' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('Hydraulically Driven via Chain Sprocket & Gear Using 25 Polyurethane Drive Rollers', 'standard'),
                ],
            ],
            'speed' => [
                ['source' => __('Electric Power', 'standard'), 'rate' => __('Up to 150 ft./min (45.7m/min)', 'standard')],
                ['source' => __('Gas Power', 'standard'),      'rate' => __('Up to 75 ft./min (22.8m/min)', 'standard')],
            ],
        ],

        'materials' => [
            [
                'name'      => __('Painted Steel', 'standard'),
                'gauge'     => __('22 or 24 ga. (0.8mm to 0.6mm)', 'standard'),
                'note'      => __('Grade 50. Painted, Galvalume, coated galvanized.', 'standard'),
            ],
            [
                'name'      => __('Painted Aluminum', 'standard'),
                'gauge'     => __('.032" to .040" (0.8mm to 1.0mm)', 'standard'),
                'note'      => __('WAV-16-4 Profile only.', 'standard'),
            ],
        ],

        'coil' => [
            'widths'              => __('24" (610mm) standard for 16-4 profile', 'standard'),
            'finished_widths'     => __('8", 12", or 16" coverage', 'standard'),
            'max_diameter_rack'   => __('32" (812mm)', 'standard'),
            'max_diameter_decoil' => __('45" (1,143mm)', 'standard'),
            'max_weight_reel'     => null,
            'max_weight_cradle'   => null,
        ],

        'power_options' => [
            __('16 HP Gas Engine', 'standard'),
            __('460V 3-Phase Electric Power', 'standard'),
        ],

        'add_on_weights' => [],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => [],
        ],
    ],

    // ── Resources ──
    'resources' => [
        'manual'               => 'https://newtechmachinery.com/learning-center/manual/wav-wall-panel-machine-manual/',
        'brochure'             => 'https://newtechmachinery.com/learning-center/literature/wav-wall-panel-machine-brochure/',
        'service_training_url' => '/service-training',
    ],

    // ── FAQ ──
    'faq' => [
        [
            'question' => __('What profiles does the WAV produce?', 'standard'),
            'answer'   => __('Three WAV-style profiles in 16", 12", and 8" widths with either fastener flange or clip-style attachment for commercial wall panel applications.', 'standard'),
        ],
        [
            'question' => __('Does the WAV come with a controller?', 'standard'),
            'answer'   => __('Yes — the UNIQ® Automatic Control System comes standard with computer batch and length control.', 'standard'),
        ],
        [
            'question' => __('Is training included?', 'standard'),
            'answer'   => __('Yes — free on-site setup and training within the continental U.S. is included with every WAV purchase.', 'standard'),
        ],
    ],

    // ── Schema ──
    'schema' => [
        'low_price'    => '232000',
        'high_price'   => null,
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
