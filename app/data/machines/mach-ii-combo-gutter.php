<?php
/**
 * MACH II™ 5"/6" Combo Gutter Machine – Product Data
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

    // ── Hero ──
    'hero' => [
        'headline' => __('5" and 6" K-Style in One Machine.', 'standard'),
        'subtitle' => __('The combo runs both 5" and 6" K-style seamless gutters. Maximum versatility for gutter contractors.', 'standard'),
        'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_MACH-II-5-6-Combo_1000x1000.png',
        'video'    => null,
    ],

    // ── Stats ──
    'stats' => [
        ['value' => '2',         'label' => __('Gutter Sizes', 'standard')],
        ['value' => '50 ft/min', 'label' => __('Max Speed', 'standard')],
        ['value' => '1,350 lbs', 'label' => __('Machine Weight', 'standard')],
        ['value' => '$15,500+',  'label' => __('Starting At', 'standard')],
    ],

    // ── Finance ──
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$15,500+', 'standard'),
        'note'          => __('Depending on machine configuration', 'standard'),
    ],

    // ── Breakdown ──
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Polyurethane Drive, Stainless Forming', 'standard'),
            'copy'     => __('Polyurethane drive rollers with stainless steel forming rollers. Run both 5" and 6" gutters from one machine.', 'standard'),
            'specs'    => [
                __('Polyurethane drive rollers', 'standard'),
                __('Stainless steel forming rollers', 'standard'),
                __('Forward pulling easy cut shear with extra shear', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Compact and Light', 'standard'),
            'copy'     => __('Welded tubular steel frame. Same footprint as the 6" model with added versatility.', 'standard'),
            'specs'    => [
                __('1350 lbs total weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Two 360° rotatable reel stands', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'power',
            'title'    => __('The Power', 'standard'),
            'headline' => __('Simple Electric Drive', 'standard'),
            'copy'     => __('3/4 HP 110V motor. Plug into any standard outlet.', 'standard'),
            'specs'    => [
                __('3/4 HP 110 VAC 60 Hz 1 Phase 11 AMPs', 'standard'),
                __('Push-button RUN/JOG at entry & exit ends', 'standard'),
                __('Power interruption safety circuit', 'standard'),
            ],
            'image'    => '',
        ],
    ],

    // ── Blueprint ──
    'blueprint' => [
        'svg' => 'mach-ii-combo-machine',
    ],

    // ── Gallery ──
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],

    // ── Profiles ──
    'profiles' => [
        'tag_slugs' => ['mach-ii-5-gutter-machine', 'mach-ii-5-6-gutter-machine', 'mach-ii-6-gutter-machine'],
    ],

    // ── Accessories ──
    'accessories' => [
        'product_tag' => 'MACHII',
    ],

    // ── Testimonials ──
    'testimonials' => [],

    // ── Comparison ──
    'comparison' => [
        'compare_with' => ['mach-ii-5-gutter', 'mach-ii-6-gutter'],
        'best_for'     => __('5" & 6" K-style gutter production', 'standard'),
    ],

    // ── Specs ──
    'specs' => [
        'standard_features' => [
            __('Polyurethane Drive Rollers', 'standard'),
            __('Two 360° Rotatable Reel Stands for Easy Coil Loading', 'standard'),
            __('Stainless Steel Forming Rollers', 'standard'),
            __('Electric Motor: 3/4 HP, 110 VAC, 60 Hz, 1 Phase, 11 AMPs', 'standard'),
            __('Push Button RUN/JOG Controls at Entry & Exit Ends', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('Forward Pulling, Easy Cut Shear for Accuracy', 'standard'),
            __('Industry\'s Best Warranty', 'standard'),
            __('Power Interruption Safety Circuit', 'standard'),
            __('Easy Lift Reels', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "10' (3.0m)",
                'length_slitter' => null,
                'width'          => "2' (0.6m)",
                'height'         => "4' (1.2m)",
                'height_no_rack' => null,
                'weight'         => '1,350 lbs. (610 kg)',
            ],
            'on_trailer' => [],
        ],

        'performance' => [
            'shear' => [
                'type'    => __('Manual', 'standard'),
                'details' => [
                    __('Manually Powered', 'standard'),
                    __('Hardened Tool Steel Blades & Shear Dies', 'standard'),
                ],
            ],
            'drive' => [
                'type'    => __('Electric', 'standard'),
                'details' => [
                    __('Electrically Driven Polyurethane Rollers', 'standard'),
                ],
            ],
            'speed' => [
                ['source' => __('Electric Motor', 'standard'), 'rate' => __('50 ft./min (15m/min)', 'standard')],
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
                'gauge'     => __('.019" to .032" (0.5mm to 0.8mm)', 'standard'),
                'note'      => null,
            ],
            [
                'name'      => __('Copper', 'standard'),
                'gauge'     => __('16 oz. to 20 oz. 3/4 Hard (0.5mm to 0.7mm)', 'standard'),
                'note'      => null,
            ],
        ],

        'coil' => [
            'widths'              => __('11¾" to 15" (300mm to 380mm)', 'standard'),
            'finished_widths'     => __('5" or 6" K-style gutter', 'standard'),
            'max_diameter_rack'   => __('30" (762mm)', 'standard'),
            'max_diameter_decoil' => null,
            'max_weight_reel'     => __('1,000 lbs (recommended)', 'standard'),
            'max_weight_cradle'   => __('400 lbs (recommended)', 'standard'),
        ],

        'power_options' => [
            __('3/4 HP, 110 VAC, 60 Hz, 1 Phase, 11 AMPs', 'standard'),
        ],

        'add_on_weights' => [],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => ['U.S. PATENT NO. 5,394,722', 'U.S. PATENT NO. 5,740,687'],
        ],
    ],

    // ── Resources ──
    'resources' => [
        'manual'               => 'https://newtechmachinery.com/learning-center/manual/mach-ii-gutter-machines-manual/',
        'brochure'             => 'https://newtechmachinery.com/learning-center/literature/mach-ii-5-6-5-6-gutter-machines-brochure/',
        'service_training_url' => '/service-training',
    ],

    // ── FAQ ──
    'faq' => [
        [
            'question' => __('Why choose the combo over separate 5" and 6" machines?', 'standard'),
            'answer'   => __('The combo lets you run both 5" and 6" K-style gutters from a single machine. It costs less than buying two separate machines and takes up less space on the truck.', 'standard'),
        ],
        [
            'question' => __('Is switching between 5" and 6" difficult?', 'standard'),
            'answer'   => __('No — switching between gutter sizes is straightforward with the combo\'s dual forming system.', 'standard'),
        ],
        [
            'question' => __('What profiles are available?', 'standard'),
            'answer'   => __('All 5" and 6" K-style profiles: bottom bead, Alcoa hook, and straight back for both sizes.', 'standard'),
        ],
        [
            'question' => __('What warranty is included?', 'standard'),
            'answer'   => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
        ],
    ],

    // ── Schema ──
    'schema' => [
        'low_price'    => '15500',
        'high_price'   => null,
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Seamless Gutter Machines', 'standard'),
    ],
];
