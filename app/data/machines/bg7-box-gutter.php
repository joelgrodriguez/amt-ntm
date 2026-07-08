<?php
/**
 * BG7™ Box Gutter Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

return [
    'category' => __('Seamless Gutter Machines', 'standard'),
    'slogan'   => __('Commercial-grade, built to last.', 'standard'),
    'hero' => [
        'headline'   => __('Two Profiles. One Commercial-Grade Machine.', 'standard'),
        'subtitle'   => __('7" commercial box gutter machine designed for industrial buildings. Two profiles in one machine.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2023/09/BG7-forming-gutter-scaled.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_BG7_1000x1000.png',
        'video'      => null,
    ],
    'stats' => [
        ['value' => '2',          'label' => __('Box Gutter Profiles', 'standard')],
        ['value' => '60 ft/min',  'label' => __('Max Speed', 'standard')],
        ['value' => 'Hydraulic',  'label' => __('Drive & Shear', 'standard')],
        ['value' => '7"',         'label' => __('Box Gutter Size', 'standard')],
    ],
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$71,600+', 'standard'),
        'note'          => __('Depending on machine configuration', 'standard'),
        'apr'           => '4.99%',
        'months'        => '72',
    ],
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Built for Commercial Gutters', 'standard'),
            'copy'     => __('Polyurethane drive rollers with hydraulic drive and shear for commercial-grade box gutter production.', 'standard'),
            'specs'    => [
                __('Polyurethane drive rollers', 'standard'),
                __('Hydraulic drive & shear', 'standard'),
                __('Gutter recognition safety system', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Heavy-Duty and Portable', 'standard'),
            'copy'     => __('Welded tubular steel frame built for demanding commercial job sites.', 'standard'),
            'specs'    => [
                __('2600 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Quick-Change Power-Pack', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'power-pack',
            'title'    => __('The Power Pack', 'standard'),
            'headline' => __('Gas or Electric Power', 'standard'),
            'copy'     => __('Quick-Change Power-Pack lets you switch power sources in the field.', 'standard'),
            'specs'    => [
                __('16 HP Briggs & Stratton gas engine option', 'standard'),
                __('Electric motor option', 'standard'),
                __('Up to 60 ft/min production speed', 'standard'),
            ],
            'image'    => '',
        ],
    ],
    'fit' => [
        'is_for' => [
            __('Commercial and industrial gutter contractors running 7" box gutters', 'standard'),
            __('Operations needing hydraulic drive and shear for heavy-gauge material', 'standard'),
            __('Contractors processing Grade 50 steel (22–26 ga.) or .040" aluminum', 'standard'),
            __('Businesses serving commercial building markets', 'standard'),
        ],
        'is_not_for' => [
            ['text' => __('Residential K-style gutter installers', 'standard'), 'machine' => 'mach-ii-combo-gutter'],
            ['text' => __('Contractors needing 5" or 6" residential gutters', 'standard'), 'machine' => 'mach-ii-5-gutter'],
            ['text' => __('Roofing contractors needing roof or wall panels', 'standard'), 'machine' => 'ssq3-multipro'],
            ['text' => __('Copper gutter specialists — BG7 does not process copper', 'standard')],
        ],
    ],
    'blueprint' => [
        'svg' => 'bg7-machine',
    ],
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],
    'profiles' => [
        'tag_slugs' => ['bg7-box-gutter-machine'],
    ],
    'accessories' => [
        'product_tag' => 'BG7',
    ],
    'testimonials' => [],
    'comparison' => [
        'compare_with' => ['mach-ii-combo-gutter', 'mach-ii-6-gutter'],
        'best_for'     => __('Commercial box gutter production', 'standard'),
    ],
    'specs' => [
        'standard_features' => [
            __('Capable of running aluminum or steel (option specified prior to purchase)', 'standard'),
            __('Polyurethane Drive Rollers', 'standard'),
            __('Length Control Limit Switch', 'standard'),
            __('Hydraulic Drive & Shear', 'standard'),
            __('Power Interruption Safety Circuit', 'standard'),
            __('Push Button RUN/JOG Controls at Entry & Exit Ends', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('Runs up to 22-gauge material', 'standard'),
            __('Gutter Recognition Safety System', 'standard'),
            __('Quick-Change Power-Pack™ (Gas or Electric)', 'standard'),
            __('Industry\'s Best Warranty', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "17'10\" (5.4m)",
                'length_slitter' => null,
                'width'          => "4'10½\" (1.5m)",
                'height'         => "4'3\" (1.3m)",
                'height_no_rack' => "2'8\" (0.8m)",
                'weight'         => '2,600 lbs. (1,180 kg)',
            ],
            'on_trailer' => [
                'length' => "21' (6.4m)",
                'width'  => "7' (2.1m)",
                'height' => "6'3\" (1.9m)",
                'weight' => '4,800 lbs. (2,180 kg)',
            ],
        ],

        'performance' => [
            'shear' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('Hydraulically Powered', 'standard'),
                    __('Hardened Tool Steel Blades & Shear Dies', 'standard'),
                    __('Gutter Recognition Safety System', 'standard'),
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
                'name'      => __('Grade 50 Steel', 'standard'),
                'gauge'     => __('26 ga. to 22 ga. (0.4mm to 0.8mm)', 'standard'),
                'note'      => __('Option specified prior to purchase.', 'standard'),
            ],
            [
                'name'      => __('Painted Aluminum', 'standard'),
                'gauge'     => __('.040" (1.0mm)', 'standard'),
                'note'      => __('Option specified prior to purchase.', 'standard'),
            ],
        ],

        'coil' => [
            'widths'              => __('20" (508mm)', 'standard'),
            'finished_widths'     => __('Straight Back or HK7 Hook', 'standard'),
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
            ['item' => __('Trailer', 'standard'),                          'weight' => __('2200 lbs. (1000 kg)', 'standard')],
            ['item' => __('Overhead Reel Rack', 'standard'),               'weight' => __('336 lbs. (152 kg)', 'standard')],
            ['item' => __('Expandable Arbor (each)', 'standard'),          'weight' => __('80 lbs. (36 kg)', 'standard')],
            ['item' => __('10-foot Run-out Table (each)', 'standard'),     'weight' => __('62 lbs. (28 kg)', 'standard')],
            ['item' => __('PVC Strippable Film Applicator', 'standard'),   'weight' => __('60 lbs. (27 kg)', 'standard')],
        ],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => ['U.S. PATENT NO. 5,394,722'],
        ],
    ],
    'resources' => [
        'manual'               => '/learning-center/manual/bg7-box-gutter-machine-with-plc-controller-manual/',
        'brochure'             => '/learning-center/literature/bg7-box-gutter-machine-brochure/',
        'service_training_url' => '/service-training',
    ],
    'faq' => [
        [
            'question' => __('What profiles does the BG7 produce?', 'standard'),
            'answer'   => __('Two 7" commercial box gutter profiles: BG7-HK7 (with hook) and BG7-SB7 (straight back).', 'standard'),
        ],
        [
            'question' => __('Can the BG7 run both steel and aluminum?', 'standard'),
            'answer'   => __('The BG7 can run either aluminum or steel, but the option must be specified prior to purchase. It handles Grade 50 steel from 26 to 22 gauge or .040" aluminum.', 'standard'),
        ],
        [
            'question' => __('What warranty is included?', 'standard'),
            'answer'   => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
        ],
    ],
    'schema' => [
        'low_price'    => '71600',
        'high_price'   => null,
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Seamless Gutter Machines', 'standard'),
    ],
];
