<?php
/**
 * MACH II™ 6" Gutter Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

return [
    'category' => __('Seamless Gutter Machines', 'standard'),
    'slogan'   => __('Speed and precision, job after job.', 'standard'),
    'hero' => [
        'headline'   => __('6" K-Style Gutters. The Industry Standard.', 'standard'),
        'subtitle'   => __('6" seamless gutter machine with the original polyurethane drive system. Over 30 years of proven performance.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-mach2-gutter-install-abel-003.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_MACH-II-6_1000x1000.png',
        'video'      => null,
    ],
    'stats' => [
        ['value' => '50 ft/min', 'label' => __('Max Speed', 'standard')],
        ['value' => '1,250 lbs', 'label' => __('Machine Weight', 'standard')],
        ['value' => '30+',       'label' => __('Years Proven', 'standard')],
        ['value' => '6" K-style', 'label' => __('Gutter Profile', 'standard')],
    ],
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$10,500+', 'standard'),
        'note'          => __('Depending on machine configuration', 'standard'),
        'apr'           => '5.99%',
        'months'        => '60',
    ],
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Polyurethane Drive, Stainless Forming', 'standard'),
            'copy'     => __('Polyurethane drive rollers with separate stainless steel forming rollers. The design that changed the industry.', 'standard'),
            'specs'    => [
                __('Polyurethane drive rollers', 'standard'),
                __('Stainless steel forming rollers', 'standard'),
                __('Forward pulling easy cut shear', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Compact and Light', 'standard'),
            'copy'     => __('Welded tubular steel frame. Slightly larger than the 5" model for 6" gutter production.', 'standard'),
            'specs'    => [
                __('1250 lbs total weight', 'standard'),
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
    'fit' => [
        'is_for' => [
            __('Residential and light commercial gutter contractors running 6" K-style', 'standard'),
            __('Markets where 6" gutters are the standard for larger homes', 'standard'),
            __('Crews who prefer a dedicated single-size machine', 'standard'),
            __('Contractors processing steel, aluminum, or copper gutter material', 'standard'),
        ],
        'is_not_for' => [
            ['text' => __('Contractors who also need 5" gutters', 'standard'), 'machine' => 'mach-ii-combo-gutter'],
            ['text' => __('Commercial box gutter operations', 'standard'), 'machine' => 'bg7-box-gutter'],
            ['text' => __('Roofing contractors needing roof or wall panels', 'standard'), 'machine' => 'ssr-multipro-jr'],
            ['text' => __('High-volume operations needing hydraulic drive', 'standard')],
        ],
    ],
    'blueprint' => [
        'svg' => 'mach-ii-6-machine',
    ],
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],
    'profiles' => [
        'tag_slugs' => ['mach-ii-6-gutter-machine'],
    ],
    'accessories' => [
        'product_tag' => 'MACHII',
    ],
    'testimonials' => [],
    'comparison' => [
        'compare_with' => ['mach-ii-5-gutter', 'mach-ii-combo-gutter'],
        'best_for'     => __('Residential 6" K-style gutter production', 'standard'),
    ],
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
                'weight'         => '1,250 lbs. (570 kg)',
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
            'widths'              => __('15" (380mm)', 'standard'),
            'finished_widths'     => __('6" K-style gutter', 'standard'),
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
    'resources' => [
        'manual'               => '/learning-center/manual/mach-ii-gutter-machines-manual/',
        'brochure'             => '/learning-center/literature/mach-ii-5-6-5-6-gutter-machines-brochure/',
        'service_training_url' => '/service-training',
    ],
    'faq' => [
        [
            'question' => __('Why choose the 6" over the 5" model?', 'standard'),
            'answer'   => __('6" gutters handle more water flow and are increasingly popular for larger homes and commercial buildings. If your market demands 6", this is the dedicated machine.', 'standard'),
        ],
        [
            'question' => __('Can I upgrade to the combo later?', 'standard'),
            'answer'   => __('The 5" and 6" are separate machines. If you need both sizes, consider the MACH II 5"/6" Combo from the start.', 'standard'),
        ],
        [
            'question' => __('What profiles are available?', 'standard'),
            'answer'   => __('Three 6" K-style profiles: with straight back (MG6-SB6), with Alcoa hook (MG6-HK6), and with bottom bead (MG6-BB6).', 'standard'),
        ],
        [
            'question' => __('What warranty is included?', 'standard'),
            'answer'   => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
        ],
    ],
    'schema' => [
        'low_price'    => '10500',
        'high_price'   => null,
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Seamless Gutter Machines', 'standard'),
    ],
];
