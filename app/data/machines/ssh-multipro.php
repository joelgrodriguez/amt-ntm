<?php
/**
 * SSH MultiPro Roof Panel Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

return [
    'category' => __('Roof & Wall Panel Machines', 'standard'),
    'slogan'   => __('Built for standing seam perfection.', 'standard'),
    'hero' => [
        'headline'   => __('8 Profiles. Hydraulic Power. Residential Precision.', 'standard'),
        'subtitle'   => __('The high-performance solution to your residential and light commercial needs.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_SSH_1000x1000.png',
        'video'      => null,
    ],
    'stats' => [
        ['value' => '8',          'label' => __('Panel Profiles', 'standard')],
        ['value' => '60 ft/min',  'label' => __('Max Speed', 'standard')],
        ['value' => 'Hydraulic',  'label' => __('Shear & Drive', 'standard')],
        ['value' => '24 ga',      'label' => __('Material Capacity', 'standard')],
    ],
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$71,600', 'standard'),
        'note'          => __('Depending on machine configuration; includes choice of one profile', 'standard'),
        'apr'           => '5.49%',
        'months'        => '72',
    ],
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Precision for Residential Roofing', 'standard'),
            'copy'     => __('8 polyurethane drive rollers with EZE-Change™ profile roller system for quick profile swaps.', 'standard'),
            'specs'    => [
                __('8 polyurethane drive rollers', 'standard'),
                __('Hydraulically powered shear with hardened tool steel blades', 'standard'),
                __('Panel recognition safety system', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Compact and Job-Site Tough', 'standard'),
            'copy'     => __('Welded tubular steel frame with powder-coated covers. Lighter than the SSQ line for easier transport.', 'standard'),
            'specs'    => [
                __('2360 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Powder-coated covers', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'power-pack',
            'title'    => __('The Power Pack', 'standard'),
            'headline' => __('Gas or Electric. Your Choice.', 'standard'),
            'copy'     => __('Quick-Change Power-Pack for field-swappable gas or electric power.', 'standard'),
            'specs'    => [
                __('16 HP Briggs & Stratton gas engine', 'standard'),
                __('5 HP or 7.5 HP electric motor options', 'standard'),
                __('Up to 60 ft/min production speed', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'controls',
            'title'    => __('The Controls', 'standard'),
            'headline' => __('PLC Controller Included', 'standard'),
            'copy'     => __('PLC batch and length computerized controller comes standard.', 'standard'),
            'specs'    => [
                __('PLC Batch / Length Computerized Controller included', 'standard'),
                __('Push-button RUN/JOG at entry & exit ends', 'standard'),
                __('Length control limit switch', 'standard'),
            ],
            'image'    => '',
        ],
    ],
    'fit' => [
        'is_for' => [
            __('Residential and light commercial roofing contractors', 'standard'),
            __('Contractors who need hydraulic shear and drive at a mid-range price', 'standard'),
            __('Businesses running 8 residential and light-commercial profiles', 'standard'),
            __('Crews processing steel, aluminum, copper, or terne-coat stainless', 'standard'),
        ],
        'is_not_for' => [
            ['text' => __('High-volume commercial operations needing 16+ profiles', 'standard'), 'machine' => 'ssq3-multipro'],
            ['text' => __('Gutter-only installers', 'standard'), 'machine' => 'mach-ii-5-gutter'],
            ['text' => __('Contractors who only need exposed-fastener 5V crimp', 'standard'), 'machine' => '5vc-5v-crimp'],
            ['text' => __('Shops focused exclusively on wall panel production', 'standard'), 'machine' => 'wav-wall-panel'],
        ],
    ],
    'blueprint' => [
        'svg' => 'ssh-machine',
    ],
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],
    'profiles' => [
        'tag_slugs' => ['ssh-multipro-roof-panel-machine'],
    ],
    'accessories' => [
        'product_tag' => 'SSH',
    ],
    'testimonials' => [],
    'comparison' => [
        'compare_with' => ['ssq-ii-multipro', 'ssr-multipro-jr'],
        'best_for'     => __('Residential & light commercial', 'standard'),
    ],
    'specs' => [
        'standard_features' => [
            __('8 Polyurethane Drive Rollers', 'standard'),
            __('Powder-Coated Covers', 'standard'),
            __('Power Interruption Safety Circuit', 'standard'),
            __('Runs Up to 24 Gauge Steel', 'standard'),
            __('Hydraulic Drive & Shear', 'standard'),
            __('Length Control Limit Switch', 'standard'),
            __('PLC Batch / Length Computerized Controller', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('EZE-Change™ Profile Roller System', 'standard'),
            __('Push Button RUN/JOG Controls', 'standard'),
            __('Quick-Change™ Power-Pack (Gas or Electric)', 'standard'),
            __('UL-Rated Panels', 'standard'),
            __('One Pair of Bead, Pencil, Striation or V-Rib Rollers (choice of)', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "12'10\" (3.9m)",
                'length_slitter' => null,
                'width'          => "4'10½\" (1.2m)",
                'height'         => "4'3\" (1.3m)",
                'height_no_rack' => "2'0\" (0.6m)",
                'weight'         => '2,360 lbs. (1,070 kg)',
            ],
            'on_trailer' => [
                'length' => "18'11\" (5.8m)",
                'width'  => "7'2½\" (2.2m)",
                'height' => "6'3\" (1.9m)",
                'weight' => '4,620 lbs. (2,090 kg)',
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
                    __('Hydraulically Driven via Chain, Sprocket & Gear', 'standard'),
                ],
            ],
            'speed' => [
                ['source' => __('All Power Sources', 'standard'), 'rate' => __('60 ft./min (18.3m/min)', 'standard')],
            ],
        ],

        'materials' => [
            [
                'name'      => __('Painted Steel', 'standard'),
                'gauge'     => __('28 ga. to 24 ga. (0.4mm to 0.6mm)', 'standard'),
                'note'      => __('Painted, galvanized, aluminized.', 'standard'),
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
            [
                'name'      => __('Terne-Coat Stainless Steel', 'standard'),
                'gauge'     => __('26 ga. (0.6mm)', 'standard'),
                'note'      => null,
            ],
        ],

        'coil' => [
            'widths'              => __('14" to 24" (355mm to 610mm)', 'standard'),
            'finished_widths'     => __('12" to 20" (254mm to 508mm)', 'standard'),
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
            ['item' => __('Trailer', 'standard'),                               'weight' => __('2260 lbs. (1020 kg)', 'standard')],
            ['item' => __('Overhead Reel Rack', 'standard'),                    'weight' => __('336 lbs. (152 kg)', 'standard')],
            ['item' => __('Expandable Arbor (each)', 'standard'),               'weight' => __('80 lbs. (36 kg)', 'standard')],
            ['item' => __('10-foot Run-out Table (each)', 'standard'),          'weight' => __('62 lbs. (28 kg)', 'standard')],
            ['item' => __('PVC Strippable Film Applicator', 'standard'),        'weight' => __('60 lbs. (27 kg)', 'standard')],
        ],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => ['U.S. PATENT NO. 6,772,616'],
        ],
    ],
    'resources' => [
        'manual'               => '/learning-center/manual/ssh-roof-panel-machine-with-plc-controller-manual/',
        'brochure'             => '/learning-center/literature/ssh-brochure/',
        'service_training_url' => '/service-training',
        // "How to change a profile" is one of the most common owner questions.
        // SSH has both a Learning Center video and a step-by-step article.
        'profile_change'       => [
            [
                'label' => __('How to Change a Profile (Video)', 'standard'),
                'url'   => '/learning-center/video/how-to-change-a-profile-new-tech-machinerys-ssh-multipro-video/',
            ],
            [
                'label' => __('How to Change a Profile: Step-by-Step', 'standard'),
                'url'   => '/learning-center/how-to-change-a-profile-in-an-ssh-multipro/',
            ],
        ],
    ],
    'faq' => [
        [
            'question' => __('What profiles can the SSH produce?', 'standard'),
            'answer'   => __('The SSH produces 8 panel profiles including mechanical seam, snap-lock, snap-lock with slotted flange, T-Panel, and Clip Relief options for residential and light commercial roofing.', 'standard'),
        ],
        [
            'question' => __('What\'s the difference between the SSH and SSQ II?', 'standard'),
            'answer'   => __('The SSQ II produces 16 profiles (including wall panels) vs 8 for the SSH. The SSQ II is built for high-volume commercial + residential work, while the SSH is focused on residential and light commercial.', 'standard'),
        ],
        [
            'question' => __('Does the SSH include a controller?', 'standard'),
            'answer'   => __('Yes — the SSH includes a PLC Batch/Length Computerized Controller as standard equipment.', 'standard'),
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
        'category'     => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
