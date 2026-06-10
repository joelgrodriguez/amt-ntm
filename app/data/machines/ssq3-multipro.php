<?php
/**
 * SSQ3 MultiPro Roof & Wall Panel Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

return [
    'category' => __('Roof & Wall Panel Machines', 'standard'),
    'slogan'   => __('The future of portable roll forming.', 'standard'),
    'hero' => [
        'headline'   => __('16 Panel Profiles.<br class="hidden lg:inline"> One Machine.', 'standard'),
        'subtitle'   => __('The most advanced portable roof and wall panel machine we\'ve ever built. Smarter, safer, and more efficient than ever.', 'standard'),
        // TODO(asset): Alex to deliver Q3 on trailer with Unique screen visible (Monday pre-demo).
        // Drop file into uploads and update hero_image path below.
        'hero_image' => content_url('/uploads/2026/05/ntm-q3-hero-placeholder.png'),
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-ssq3-manual-controller-050.jpg',
        'video'      => null,
    ],
    'stats' => [
        ['value' => '16',          'label' => __('Panel Profiles', 'standard')],
        ['value' => '25 min',      'label' => __('Tooling Changeover', 'standard')],
        ['value' => '75 ft/min',   'label' => __('Max Speed', 'standard')],
        ['value' => '$2.25/sq ft', 'label' => __('Avg. Savings', 'standard')],
    ],
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$121K – $137K', 'standard'),
        'note'          => __('Depending on profile; notching option not included', 'standard'),
        'apr'           => '4.99%',
        'months'        => '84',
    ],
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Precision Forming Panel After Panel', 'standard'),
            'copy'     => __('16 roller stations with hardened tool steel shear dies deliver consistent, accurate panel profiles at production speed.', 'standard'),
            'specs'    => [
                __('16 polyurethane drive rollers', 'standard'),
                __('Hydraulically powered shear with hardened tool steel blades', 'standard'),
                __('Panel recognition safety system', 'standard'),
            ],
            'image'    => content_url('/uploads/2026/05/ntm-ssq3-manual-controller-121.jpg'),
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Built to Take a Beating', 'standard'),
            'copy'     => __('Welded tubular steel frame with powder-coated aluminum covers and inspection windows. Built for the job site, not the showroom.', 'standard'),
            'specs'    => [
                __('2830 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Powder-coated aluminum covers with windows', 'standard'),
            ],
            'image'    => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-ssq3-overhead-drone-001.jpg',
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
            'image'    => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-mach2-electric-power-pack-001.jpg',
        ],
        [
            'id'       => 'brain',
            'title'    => __('The Brain', 'standard'),
            'headline' => __('Smart Controls Simple Operation', 'standard'),
            'copy'     => __('Choose manual push-button controls or the UNIQ Automatic Control System with touchscreen diagnostics.', 'standard'),
            'specs'    => [
                __('UNIQ® Automatic Control System option', 'standard'),
                __('Push-button RUN/JOG at entry & exit ends', 'standard'),
                __('RFID cover sensors and on-controller diagnostics', 'standard'),
            ],
            'image'    => 'https://newtechmachinery.com/wp-content/uploads/2026/05/ntm-ssq3-rfid-sensors-001.jpg',
        ],
    ],
    'fit' => [
        'is_for' => [
            __('High-volume commercial and residential roofing contractors', 'standard'),
            __('Crews running multiple panel profiles on the same job', 'standard'),
            __('Businesses that need gas and electric flexibility on-site', 'standard'),
            __('Contractors processing steel, aluminum, copper, or terne-coat stainless', 'standard'),
            __('Operations that value fast 25-minute tooling changeovers', 'standard'),
        ],
        'is_not_for' => [
            ['text' => __('Residential-only gutter installers', 'standard'), 'machine' => 'mach-ii-5-gutter'],
            ['text' => __('Contractors who only need exposed-fastener 5V crimp panels', 'standard'), 'machine' => '5vc-5v-crimp'],
            ['text' => __('Budget-conscious startups looking for entry-level rollforming', 'standard'), 'machine' => 'ssr-multipro-jr'],
            ['text' => __('Shops that only run wall panels without roof profiles', 'standard'), 'machine' => 'wav-wall-panel'],
        ],
    ],
    'blueprint' => [
        'svg' => 'ssq3-machine',
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
    'case_study' => [
        'image_position' => 'right',
        'background'     => 'bg-blue-50',
        'content' => [
            'eyebrow'  => __('Customer Story', 'standard'),
            'quote'    => __('All you gotta do is come run one more panel out. You\'re not bound to what\'s in a crate. If you\'re the roofing contractor, you completely control your own destiny.', 'standard'),
            'name'     => 'Joe Keene',
            'company'  => 'Integrity Metals',
            'machine'  => 'SSQ3 MultiPro',
            'image'    => content_url('/uploads/2026/05/ntm-customer-onsite-002.jpg'),
            'cta_text' => __('Watch the Full Story', 'standard'),
            'cta_url'  => '/learning-center/video/is-ntm-good-for-contractors-and-manufacturers-video/',
            'cta_icon' => 'play',
        ],
        'stats' => [],
    ],
    'comparison' => [
        'compare_with' => ['ssq-ii-multipro', 'ssh-multipro'],
        'best_for'     => __('High-volume commercial & residential', 'standard'),
    ],
    'specs' => [
        'standard_features' => [
            __('16 Polyurethane Drive Rollers', 'standard'),
            __('Roller System with Shear Dies', 'standard'),
            __('Hydraulic Drive & Shear', 'standard'),
            __('Powder-Coated Aluminum Covers with Windows', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('Push Button RUN/JOG Controls at Entry & Exit Ends', 'standard'),
            __('Power Interruption Safety Circuit', 'standard'),
            __('UL Rated Panels', 'standard'),
            __('Industry\'s Best Warranty', 'standard'),
            __('Quick-Change™ Power-Pack (Gas or Electric options)', 'standard'),
            __('One Pair of Bead, Pencil, Striation or V-Rib Rollers (choice of)', 'standard'),
            __('QWIKSwap™ tooling for faster changeovers', 'standard'),
            __('Cover windows for visual diagnostics', 'standard'),
            __('Shear warning strobe on the controller', 'standard'),
            __('RFID cover sensors and on-controller diagnostics', 'standard'),
            __('Zerk fittings for easy lubrication', 'standard'),
            __('Interior LED lights for increased visibility', 'standard'),
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
            'widths'            => __('15" to 30" (381mm to 762mm)', 'standard'),
            'finished_widths'   => __('12" to 24" (305mm to 610mm)', 'standard'),
            'max_diameter_rack' => __('32" (812mm)', 'standard'),
            'max_diameter_decoil' => __('45" (1,143mm)', 'standard'),
            'max_weight_reel'   => null,
            'max_weight_cradle' => null,
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
        'manual'              => '/learning-center/manual/ssq3-roof-panel-machine-manual/',
        'brochure'            => '/learning-center/literature/ssq3-multipro-roof-panel-machine-brochure/',
        'service_training_url' => '/service-training',
    ],
    'faq' => [
        [
            'question' => __('How long does it take to get an SSQ3 after ordering?', 'standard'),
            'answer'   => __('Lead times vary, but the SSQ3 MultiPro typically ships within 4–8 weeks. Contact your specialist for current availability.', 'standard'),
        ],
        [
            'question' => __('What\'s the difference between the SSQ3 and SSQ II?', 'standard'),
            'answer'   => __('The SSQ3 is the latest iteration with QWIKSwap™ tooling (25-minute changeover vs 45 minutes), sealed drive gear covers with inspection windows, RFID cover sensors, shear warning strobe, and interior LED lighting. Both produce the same 16 profiles.', 'standard'),
        ],
        [
            'question' => __('What materials can the SSQ3 process?', 'standard'),
            'answer'   => __('Painted steel (28–22 ga.), Galvalume, aluminum (.027"–.040"), copper (16–20 oz.), and terne-coated stainless steel (26 ga.).', 'standard'),
        ],
        [
            'question' => __('Can I switch between gas and electric power?', 'standard'),
            'answer'   => __('Yes. The Quick-Change Power-Pack (QCPP) lets you swap between a 16 HP Briggs & Stratton gas engine and 5 HP or 7.5 HP electric motors in the field — no tools required.', 'standard'),
        ],
        [
            'question' => __('What warranty does the SSQ3 come with?', 'standard'),
            'answer'   => __('The SSQ3 includes a limited three-year part and NTM in-house labor warranty — the industry\'s best.', 'standard'),
        ],
    ],
    'schema' => [
        'low_price'    => '121000',
        'high_price'   => '137000',
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
