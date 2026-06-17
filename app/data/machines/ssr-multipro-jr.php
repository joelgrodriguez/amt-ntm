<?php
/**
 * SSR MultiPro Jr. Roof Panel Machine – Product Data
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

return [
    'category' => __('Roof & Wall Panel Machines', 'standard'),
    'slogan'   => __('Compact power, professional results.', 'standard'),
    'hero' => [
        'headline'   => __('Affordable Entry Into Portable Rollforming.', 'standard'),
        'subtitle'   => __('The state-of-the-art SSR™ MultiPro Junior produces up to seven different panel profiles.', 'standard'),
        'hero_image' => 'https://newtechmachinery.com/wp-content/uploads/2023/05/5V-on-site.jpg',
        'image'      => 'https://newtechmachinery.com/wp-content/uploads/2025/09/20250911_NTM_SSR_1000x1000.png',
        'video'      => null,
    ],
    'stats' => [
        ['value' => '7',         'label' => __('Panel Profiles', 'standard')],
        ['value' => '30 ft/min', 'label' => __('Max Speed', 'standard')],
        ['value' => 'Electric',  'label' => __('Power', 'standard')],
        ['value' => 'Polyurethane', 'label' => __('Drive Rollers', 'standard')],
    ],
    'finance' => [
        'monthly_price' => null,
        'price_range'   => __('$44,900+', 'standard'),
        'note'          => __('Depending on machine configuration; includes choice of one profile', 'standard'),
        'apr'           => '',
        'months'        => '',
    ],
    'breakdown' => [
        [
            'id'       => 'forming-system',
            'title'    => __('The Forming System', 'standard'),
            'headline' => __('Simple, Effective, Reliable', 'standard'),
            'copy'     => __('8 polyurethane drive rollers with EZE-Change™ profile roller system and shear dies.', 'standard'),
            'specs'    => [
                __('8 polyurethane drive rollers', 'standard'),
                __('EZE-Change™ Profile Roller System with Shear Dies', 'standard'),
                __('Forward pulling easy cut manual shear', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'frame',
            'title'    => __('The Frame', 'standard'),
            'headline' => __('Lightweight and Portable', 'standard'),
            'copy'     => __('Welded tubular steel frame. The lightest and most compact panel machine in the NTM lineup.', 'standard'),
            'specs'    => [
                __('1715 lbs base weight', 'standard'),
                __('Welded tubular steel frame', 'standard'),
                __('Dual overhead reel rack included', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'power',
            'title'    => __('The Power', 'standard'),
            'headline' => __('Electric Simplicity', 'standard'),
            'copy'     => __('1.5 HP electric motor with waterproof NEMA 4 electrical system. Simple, reliable, clean.', 'standard'),
            'specs'    => [
                __('1.5 HP 120V 60 Hz 1PH 18 amp motor', 'standard'),
                __('Waterproof NEMA 4 electrical system', 'standard'),
                __('100-foot 10 gauge 20 amp extension cord included', 'standard'),
            ],
            'image'    => '',
        ],
        [
            'id'       => 'controls',
            'title'    => __('The Controls', 'standard'),
            'headline' => __('Push-Button Operation', 'standard'),
            'copy'     => __('Push-button RUN/JOG controls at entry and exit ends with length control limit switch.', 'standard'),
            'specs'    => [
                __('Push-button RUN/JOG at entry & exit ends', 'standard'),
                __('Length control limit switch', 'standard'),
                __('Two 10-foot run-out table sections included', 'standard'),
            ],
            'image'    => '',
        ],
    ],
    'fit' => [
        'is_for' => [
            __('Residential roofing contractors entering portable rollforming', 'standard'),
            __('Budget-conscious businesses looking for an affordable entry point', 'standard'),
            __('Crews who prefer simple electric-only operation', 'standard'),
            __('Contractors processing steel, aluminum, or copper up to 24 gauge', 'standard'),
        ],
        'is_not_for' => [
            ['text' => __('High-volume commercial operations needing maximum speed', 'standard'), 'machine' => 'ssq3-multipro'],
            ['text' => __('Contractors who need gas power or hydraulic shear', 'standard'), 'machine' => 'ssh-multipro'],
            ['text' => __('Gutter installers', 'standard'), 'machine' => 'mach-ii-5-gutter'],
            ['text' => __('Shops focused on wall panel or WAV profiles', 'standard'), 'machine' => 'wav-wall-panel'],
        ],
    ],
    'blueprint' => [
        'svg' => 'ssr-machine',
    ],
    'gallery' => [
        'images'  => [],
        'rotator' => [],
    ],
    'profiles' => [
        'tag_slugs' => ['ssr-multipro-roof-panel-machine'],
    ],
    'accessories' => [
        'product_tag' => 'SSR',
    ],
    'testimonials' => [],
    'comparison' => [
        'compare_with' => ['ssh-multipro', '5vc-5v-crimp'],
        'best_for'     => __('Entry-level / Residential', 'standard'),
    ],
    'specs' => [
        'standard_features' => [
            __('8 Polyurethane Drive Rollers', 'standard'),
            __('Powder-Coated Covers', 'standard'),
            __('Length Control Limit Switch', 'standard'),
            __('100-Foot, 10 Gauge, 20 Amp Extension Cord', 'standard'),
            __('Runs up to 24 Gauge Steel', 'standard'),
            __('Waterproof NEMA 4 Electrical System', 'standard'),
            __('(2) Run-Out Tables (10-Foot Sections Each)', 'standard'),
            __('Forward Pulling, Easy Cut Manual Shear', 'standard'),
            __('One Expandable Arbor / Reel', 'standard'),
            __('Push Button RUN/JOG Controls at Entry & Exit Ends', 'standard'),
            __('Welded Tubular Steel Frame', 'standard'),
            __('Dual Overhead Reel Rack', 'standard'),
            __('UL Rated Panels', 'standard'),
            __('Industry\'s Best Warranty', 'standard'),
            __('EZE-Change™ Profile Roller System with Shear Dies', 'standard'),
            __('One Pair of Bead, Pencil, Striation or V-Rib Rollers', 'standard'),
        ],

        'dimensions' => [
            'machine' => [
                'length'         => "12'6\" (3.9m)",
                'length_slitter' => null,
                'width'          => "3'10\" (1.2m)",
                'height'         => "4'3\" (1.3m)",
                'height_no_rack' => "1'10\" (0.6m)",
                'weight'         => '1,715 lbs. (780 kg)',
            ],
            'on_trailer' => [
                'length' => "18'11\" (5.8m)",
                'width'  => "7'2½\" (2.2m)",
                'height' => "6'3\" (1.9m)",
                'weight' => '3,975 lbs. (1,800 kg)',
            ],
        ],

        'performance' => [
            'shear' => [
                'type'    => __('Manual', 'standard'),
                'details' => [
                    __('Manually Powered', 'standard'),
                    __('Infinitely Adjustable', 'standard'),
                    __('Hardened Tool Steel Blades & Shear Dies', 'standard'),
                ],
            ],
            'drive' => [
                'type'    => __('Electric', 'standard'),
                'details' => [
                    __('Electrically Driven Polyurethane Rollers via Chain & Sprocket', 'standard'),
                ],
            ],
            'speed' => [
                ['source' => __('Electric Motor', 'standard'), 'rate' => __('30 ft./min (9m/min)', 'standard')],
            ],
        ],

        'materials' => [
            [
                'name'      => __('Painted Steel', 'standard'),
                'gauge'     => __('28 ga. to 24 ga. (0.4mm to 0.6mm)', 'standard'),
                'note'      => __('50 ksi maximum for 24 ga. Painted, Galvalume, coated galvanized.', 'standard'),
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
            'widths'              => __('15" to 20" (380mm to 508mm)', 'standard'),
            'finished_widths'     => __('12" to 16" (254mm to 406mm)', 'standard'),
            'max_diameter_rack'   => __('32" (812mm)', 'standard'),
            'max_diameter_decoil' => __('45" (1,143mm)', 'standard'),
            'max_weight_reel'     => null,
            'max_weight_cradle'   => null,
        ],

        'power_options' => [
            __('1.5 HP, 120V, 60 Hz, 1PH, 18 amps (220V option available)', 'standard'),
        ],

        'add_on_weights' => [
            ['item' => __('Trailer', 'standard'),                               'weight' => __('2260 lbs. (1020 kg)', 'standard')],
            ['item' => __('Overhead Reel Rack', 'standard'),                    'weight' => __('316 lbs. (143 kg)', 'standard')],
            ['item' => __('Expandable Arbor (each)', 'standard'),               'weight' => __('80 lbs. (36 kg)', 'standard')],
            ['item' => __('10-foot Run-out Table (each)', 'standard'),          'weight' => __('29 lbs. (13 kg)', 'standard')],
            ['item' => __('PVC Strippable Film Applicator', 'standard'),        'weight' => __('60 lbs. (27 kg)', 'standard')],
        ],

        'warranty' => [
            'description' => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
            'patents'     => ['U.S. PATENT NO. 6,772,616'],
        ],
    ],
    'resources' => [
        'manual'               => 'https://newtechmachinery.com/learning-center/manual/ssr-roof-panel-machine-manual/',
        'brochure'             => 'https://newtechmachinery.com/learning-center/literature/ssr-multipro-jr-roof-panel-machine-brochure/',
        'service_training_url' => '/service-training',
    ],
    'faq' => [
        [
            'question' => __('Is the SSR good for beginners?', 'standard'),
            'answer'   => __('Yes — the SSR MultiPro Jr. is the most affordable entry point into portable rollforming. It\'s electric-powered, easy to operate, and produces up to 7 panel profiles.', 'standard'),
        ],
        [
            'question' => __('Why is the SSR less expensive than the SSH or SSQ?', 'standard'),
            'answer'   => __('The SSR uses electric power only (no hydraulic drive), has a manual shear, and a smaller footprint. It\'s designed for residential contractors getting started with on-site panel production.', 'standard'),
        ],
        [
            'question' => __('What profiles can the SSR produce?', 'standard'),
            'answer'   => __('Up to 7 profiles including mechanical seam, snap-lock, and snap-lock with slotted flange options.', 'standard'),
        ],
        [
            'question' => __('What warranty is included?', 'standard'),
            'answer'   => __('Limited three-year part and NTM in-house labor warranty.', 'standard'),
        ],
    ],
    'schema' => [
        'low_price'    => '44900',
        'high_price'   => null,
        'availability' => 'InStock',
        'brand'        => 'New Tech Machinery',
        'manufacturer' => 'New Tech Machinery',
        'category'     => __('Roof & Wall Panel Machines', 'standard'),
    ],
];
