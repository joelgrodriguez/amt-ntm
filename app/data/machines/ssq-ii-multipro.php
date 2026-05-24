<?php
/**
 * SSQ II MultiPro Roof & Wall Panel Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

return [
    'category' => __('Roof & Wall Panel Machines', 'standard'),
    'slogan'   => __('Versatility meets precision.', 'standard'),
    'hero' => [
        'headline'   => __('16 Quick-Change Profiles. One Proven Machine.', 'standard'),
        'subtitle'   => __('The best-selling, most advanced multi-profile machine. Contractors worldwide trust this machine with their business.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2025/12/starting-SSQ-on-job-site-1024x576-1.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_SSQ-II_1000x1000.png',
        'video'      => null,
    ],
    'stats' => [
        ['value' => '16',          'label' => __('Panel Profiles', 'standard')],
        ['value' => '45 min',      'label' => __('Tooling Changeover', 'standard')],
        ['value' => '75 ft/min',   'label' => __('Max Speed', 'standard')],
        ['value' => '24 ga',       'label' => __('Material Capacity', 'standard')],
    ],
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$115K – $130K', 'standard'),
        'note'          => __('Depending on profile; notching option not included', 'standard'),
        'apr'           => '5.49%',
        'months'        => '72',
    ],
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Proven Precision, Profile After Profile', 'standard'),
            'copy'     => __('16 roller stations with hardened tool steel shear dies. Change tooling in 45 minutes or less with one person and one wrench.', 'standard'),
            'specs'    => [
                __('16 polyurethane drive rollers', 'standard'),
                __('Hydraulically powered shear with hardened tool steel blades', 'standard'),
                __('Panel recognition safety system', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Rugged and Road-Ready', 'standard'),
            'copy'     => __('Welded tubular steel frame with powder-coated aluminum covers. Built to endure demanding job sites.', 'standard'),
            'specs'    => [
                __('2830 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Powder-coated aluminum covers', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'power-pack',
            'title'    => __('The Power Pack', 'standard'),
            'headline' => __('Gas or Electric. Field-Swappable.', 'standard'),
            'copy'     => __('Quick-Change Power-Pack lets you switch between gas and electric power on-site.', 'standard'),
            'specs'    => [
                __('16 HP Briggs & Stratton gas engine', 'standard'),
                __('5 HP or 7.5 HP electric motor options', 'standard'),
                __('Up to 75 ft/min production speed', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'brain',
            'title'    => __('The Brain', 'standard'),
            'headline' => __('Manual or Automatic Control', 'standard'),
            'copy'     => __('Choose manual push-button controls or upgrade to the UNIQ® Automatic Control System.', 'standard'),
            'specs'    => [
                __('UNIQ® Automatic Control System upgrade available', 'standard'),
                __('Push-button RUN/JOG at entry & exit ends', 'standard'),
                __('PLC or AMS controller options', 'standard'),
            ],
            'image'    => '',
        ],
    ],
    'fit' => [
        'is_for' => [
            __('High-volume commercial and residential roofing contractors', 'standard'),
            __('Crews running multiple panel profiles on the same job', 'standard'),
            __('Businesses that need gas and electric flexibility on-site', 'standard'),
            __('Contractors processing steel, aluminum, copper, or terne-coat stainless', 'standard'),
            __('Budget-conscious buyers who want SSQ capability at a lower price', 'standard'),
        ],
        'is_not_for' => [
            ['text' => __('Contractors who want the fastest tooling changeovers', 'standard'), 'machine' => 'ssq3-multipro'],
            ['text' => __('Residential-only gutter installers', 'standard'), 'machine' => 'mach-ii-5-gutter'],
            ['text' => __('Shops that only need exposed-fastener panels', 'standard'), 'machine' => '5vc-5v-crimp'],
            ['text' => __('Budget-conscious startups looking for entry-level rollforming', 'standard'), 'machine' => 'ssr-multipro-jr'],
        ],
    ],
    'blueprint' => [
        'svg' => 'ssq-ii-machine',
    ],
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],
    'profiles' => [
        'tag_slugs' => ['ssq-ii-multipro-roof-panel-machine'],
    ],
    'accessories' => [
        'product_tag' => 'SSQII',
    ],
    'testimonials' => [],
    'comparison' => [
        'compare_with' => ['ssq3-multipro', 'ssh-multipro'],
        'best_for'     => __('High-volume commercial & residential', 'standard'),
    ],
    'specs' => [
        'standard_features' => [
            __('16 Polyurethane Drive Rollers', 'standard'),
            __('Roller System with Shear Dies', 'standard'),
            __('Hydraulic Drive & Shear', 'standard'),
            __('Powder-Coated Aluminum Covers', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('Push Button RUN/JOG Controls at Entry & Exit Ends', 'standard'),
            __('Power Interruption Safety Circuit', 'standard'),
            __('UL Rated Panels', 'standard'),
            __('Industry\'s Best Warranty', 'standard'),
            __('Quick-Change™ Power-Pack (Gas or Electric)', 'standard'),
            __('One Pair of Bead, Pencil, Striation or V-Rib Rollers (choice of)', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "14'4\" (4.4m)",
                'length_slitter' => "15'4\" (4.7m)",
                'width'          => "5'2\" (1.57m)",
                'height'         => "4'3\" (1.3m)",
                'height_no_rack' => "2'6\" (0.8m)",
                'weight'         => '2,830 lbs. (1,280 kg)',
            ],
            'on_trailer' => [
                'length' => "18'11\" (5.8m)",
                'width'  => "7'2½\" (2.2m)",
                'height' => "6'3\" (1.9m)",
                'weight' => '5,090 lbs (2,300 kg)',
            ],
        ],

        'performance' => [
            'shear' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('Hydraulically Powered', 'standard'),
                    __('Infinitely Adjustable', 'standard'),
                    __('Hardened Tool Steel Blades & Shear Dies', 'standard'),
                    __('Panel Recognition Safety System', 'standard'),
                ],
            ],
            'drive' => [
                'type'    => __('Hydraulic', 'standard'),
                'details' => [
                    __('16 Polyurethane Drive Rollers', 'standard'),
                    __('Hydraulically Driven via Chain Sprocket & Gear', 'standard'),
                ],
            ],
            'speed' => [
                ['source' => __('Gas Engine', 'standard'),       'rate' => __('75 ft./min (23m/min)', 'standard')],
                ['source' => __('3PH 60Hz Motor', 'standard'),   'rate' => __('72 ft./min (22m/min)', 'standard')],
                ['source' => __('1PH 60Hz Motor', 'standard'),   'rate' => __('50 ft./min (15m/min)', 'standard')],
                ['source' => __('3PH 50Hz Motor', 'standard'),   'rate' => __('58 ft./min (18m/min)', 'standard')],
                ['source' => __('1PH 50Hz Motor', 'standard'),   'rate' => __('40 ft./min (12m/min)', 'standard')],
            ],
        ],

        'materials' => [
            [
                'name'      => __('Painted Steel', 'standard'),
                'gauge'     => __('28 ga. to 22 ga. (0.4mm to 0.8mm)', 'standard'),
                'note'      => __('50 ksi maximum for 24 to 22 ga. Includes painted, Galvalume, coated galvanized.', 'standard'),
            ],
            [
                'name'      => __('Painted Aluminum', 'standard'),
                'gauge'     => __('.027" to .040" (0.7mm to 1.0mm)', 'standard'),
                'note'      => null,
            ],
            [
                'name'      => __('Copper', 'standard'),
                'gauge'     => __('16 oz. to 20 oz. 3/4 Hard (0.5mm to 0.7mm)', 'standard'),
                'note'      => __('Notching not recommended with copper.', 'standard'),
            ],
            [
                'name'      => __('Terne Coat Stainless', 'standard'),
                'gauge'     => __('26 ga. (0.5mm)', 'standard'),
                'note'      => __('Notching not recommended.', 'standard'),
            ],
        ],

        'coil' => [
            'widths'              => __('15" to 30" (381mm to 762mm)', 'standard'),
            'finished_widths'     => __('12" to 24" (305mm to 610mm)', 'standard'),
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
            ['item' => __('Trailer', 'standard'),                                'weight' => __('2260 lbs. (1020 kg)', 'standard')],
            ['item' => __('Overhead Reel Rack', 'standard'),                     'weight' => __('336 lbs. (152 kg)', 'standard')],
            ['item' => __('Expandable Arbor (each)', 'standard'),                'weight' => __('80 lbs. (36 kg)', 'standard')],
            ['item' => __('10-foot Run-out Table (each)', 'standard'),           'weight' => __('62 lbs. (28 kg)', 'standard')],
            ['item' => __('Perforator', 'standard'),                             'weight' => __('150 lbs. (68 kg)', 'standard')],
            ['item' => __('Notching System', 'standard'),                        'weight' => __('220 lbs. (100 kg)', 'standard')],
            ['item' => __('Angled Slitter', 'standard'),                         'weight' => __('250 lbs. (113 kg)', 'standard')],
            ['item' => __('Strippable Protective Film Applicator', 'standard'),  'weight' => __('60 lbs. (27 kg)', 'standard')],
        ],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => ['U.S. PATENT NO. 6,772,616'],
        ],
    ],
    'resources' => [
        'manual'               => 'https://newtechmachinery.com/learning-center/manual/ssq-ii-roof-panel-machine-manual/',
        'brochure'             => 'https://newtechmachinery.com/learning-center/literature/ssq-ii-multipro-roof-panel-machine-brochure/',
        'service_training_url' => '/service-training',
    ],
    'faq' => [
        [
            'question' => __('How does the SSQ II compare to the SSQ3?', 'standard'),
            'answer'   => __('The SSQ II and SSQ3 produce the same 16 profiles. The SSQ3 adds QWIKSwap™ tooling (25-minute changeover vs 45), cover inspection windows, RFID sensors, shear strobe, and interior LEDs. The SSQ II is the proven workhorse at a lower price point.', 'standard'),
        ],
        [
            'question' => __('How long does tooling changeover take?', 'standard'),
            'answer'   => __('45 minutes or less with one person and one wrench. No special tools needed.', 'standard'),
        ],
        [
            'question' => __('What materials can the SSQ II process?', 'standard'),
            'answer'   => __('Painted steel (28–22 ga.), Galvalume, aluminum (.027"–.040"), copper (16–20 oz.), and terne-coated stainless (26 ga.).', 'standard'),
        ],
        [
            'question' => __('Can I upgrade to the UNIQ control system later?', 'standard'),
            'answer'   => __('Yes. The UNIQ® Automatic Control System is an available upgrade with touchscreen batch and length control, cut list upload, and built-in troubleshooting.', 'standard'),
        ],
        [
            'question' => __('What warranty is included?', 'standard'),
            'answer'   => __('Limited three-year part and NTM in-house labor warranty — the industry\'s best.', 'standard'),
        ],
    ],
    'schema' => [
        'low_price'    => '115000',
        'high_price'   => '130000',
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
