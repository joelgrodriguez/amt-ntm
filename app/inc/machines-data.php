<?php
/**
 * Machines Page Data
 *
 * Hardcoded machine data for the /machines landing page.
 * Sourced from docs/ntm-machines.md research brief.
 * This module is intentionally content-focused; presentation/layout helpers
 * live in inc/grid.php so content changes do not drag view logic with them.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachinesData;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get WooCommerce product permalink by slug.
 *
 * Builds a slug→URL map on first call, then serves from cache.
 *
 * @param string $slug Product slug.
 * @return string Product permalink or '#'.
 */
function get_product_url(string $slug): string {
    static $urls = null;

    if ($urls === null) {
        $urls = [];
        if (function_exists('wc_get_products')) {
            $products = wc_get_products([
                'limit'  => -1,
                'status' => 'publish',
                'type'   => 'simple',
            ]);
            foreach ($products as $product) {
                $urls[$product->get_slug()] = $product->get_permalink();
            }
        }
    }

    return $urls[$slug] ?? '#';
}

/**
 * Get all machines organized by category.
 *
 * @return array<string, array{label: string, machines: array}>
 */
function get_machine_categories(): array {
    $base = 'https://newtechmachinery.com/wp-content/uploads/';

    return [
        'roof-wall' => [
            'label' => 'Roof & Wall Panel Machines',
            'url'   => '/roof-wall-panel-machines/',
            'machines' => [
                [
                    'slug'       => 'ssq3-multipro',
                    'name'       => 'SSQ3™ MultiPro',
                    'short_name' => 'SSQ3™',
                    'descriptor' => 'The most advanced portable roll former ever built',
                    'image'      => $base . '2025/10/SSQ3_For-Render_Trailer_Flattened-SQUARE.png',
                    'url'        => get_product_url('ssq3-multipro'),
                    'badge'      => 'New — Flagship',
                    'highlights' => [
                        'Up to 16 panel profiles — standing seam, flush wall, and board & batten siding',
                        'High-speed hydraulic drive with advanced touchscreen controller',
                        'RFID cover sensors, shear warning strobe, and 8 interior LEDs for safety',
                    ],
                    'specs'      => [
                        'profiles' => '16',
                        'speed'    => 'High-speed',
                        'power'    => 'Gas/Electric (QCPP)',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Commercial + Residential',
                    ],
                ],
                [
                    'slug'       => 'ssq-ii-multipro',
                    'name'       => 'SSQ II™ MultiPro',
                    'short_name' => 'SSQ II™',
                    'descriptor' => 'The proven multi-profile workhorse',
                    'image'      => $base . '2025/09/20250911_NTM_SSQ-II_1000x1000.png',
                    'url'        => get_product_url('ssq-ii-multipro'),
                    'badge'      => '',
                    'highlights' => [
                        'Up to 16 profile options — standing seam roof, wall panels, and board & batten',
                        'Quick-Change Power Pack (QCPP) — switch between gas and electric in the field',
                        'Up to ~75 feet per minute with hydraulic shear',
                    ],
                    'specs'      => [
                        'profiles' => '16',
                        'speed'    => '~75 FPM',
                        'power'    => 'Gas/Electric (QCPP)',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Commercial + Residential',
                    ],
                ],
                [
                    'slug'       => 'ssh-multipro',
                    'name'       => 'SSH™ MultiPro',
                    'short_name' => 'SSH™',
                    'descriptor' => 'Residential & light commercial machine',
                    'image'      => $base . '2025/09/20250911_NTM_SSH_1000x1000.png',
                    'url'        => get_product_url('ssh-multipro'),
                    'badge'      => '',
                    'highlights' => [
                        '7 panel profiles for residential and light commercial roofing',
                        'Hydraulic drive and shear — up to ~60 feet per minute',
                        'Supports painted steel, Galvalume, copper, and terne-coated stainless',
                    ],
                    'specs'      => [
                        'profiles' => '7',
                        'speed'    => '~60 FPM',
                        'power'    => 'Gas/Electric (QCPP)',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Residential + Light Commercial',
                    ],
                ],
                [
                    'slug'       => 'ssr-multipro-jr',
                    'name'       => 'SSR™ MultiPro Jr.',
                    'short_name' => 'SSR™',
                    'descriptor' => 'Affordable entry into portable rollforming',
                    'image'      => $base . '2025/09/20250911_NTM_SSR_1000x1000.png',
                    'url'        => get_product_url('ssr-multipro-jr'),
                    'badge'      => '',
                    'highlights' => [
                        'Most affordable entry point into portable rollforming',
                        'Polyurethane drive rollers with EZE CHANGE profile roller system',
                        'Up to ~30 feet per minute — electric powered',
                    ],
                    'specs'      => [
                        'profiles' => 'Multiple',
                        'speed'    => '~30 FPM',
                        'power'    => 'Electric only',
                        'shear'    => 'Manual',
                        'best_for' => 'Entry-level / Residential',
                    ],
                ],
                [
                    'slug'       => '5vc-5v-crimp',
                    'name'       => '5V Crimp',
                    'short_name' => '5V Crimp',
                    'descriptor' => 'The industry\'s only portable 5V crimp machine',
                    'image'      => $base . '2025/09/20250911_NTM_5VC_1000x1000.png',
                    'url'        => get_product_url('5vc-5v-crimp'),
                    'badge'      => '',
                    'highlights' => [
                        'NTM\'s only exposed fastener roof panel machine',
                        'Hydraulically driven polyurethane rollers with hydraulic shear',
                        'Easy to transport — built for durability and efficiency',
                    ],
                    'specs'      => [
                        'profiles' => '5V Crimp',
                        'speed'    => '—',
                        'power'    => '—',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Exposed fastener roofing',
                    ],
                ],
                [
                    'slug'       => 'wav-wall-panel',
                    'name'       => 'WAV™',
                    'short_name' => 'WAV™',
                    'descriptor' => 'The industry\'s only portable WAV profile machine',
                    'image'      => $base . '2025/09/20250911_NTM_WAV_1000x1000.png',
                    'url'        => get_product_url('wav-wall-panel'),
                    'badge'      => '',
                    'highlights' => [
                        'Purpose-built for heavy commercial and industrial wall panel work',
                        '4 profiles on 25 polyurethane drive rollers with VFD',
                        'UNIQ® Automatic Control System comes standard',
                    ],
                    'specs'      => [
                        'profiles' => '4',
                        'speed'    => '—',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Heavy commercial/industrial walls',
                    ],
                ],
            ],
        ],
        'gutter' => [
            'label' => 'Seamless Gutter Machines',
            'url'   => '/seamless-gutter-machines/',
            'machines' => [
                [
                    'slug'       => 'mach-ii-5-gutter',
                    'name'       => 'MACH II™ 5" Gutter Machine',
                    'short_name' => 'MACH II™ 5"',
                    'descriptor' => 'The most trusted 5" gutter machine since 1994',
                    'image'      => $base . '2025/09/20250911_NTM_MACH-II-5_1000x1000.png',
                    'url'        => get_product_url('mach-ii-5-gutter'),
                    'badge'      => '',
                    'price'      => '$87,245',
                    'price_label' => 'Starting at',
                    'highlights' => [
                        '5" K-style gutters from raw coil',
                        'Up to 50 feet per minute with polyurethane drive rollers',
                        'Industry standard for 30+ years — 1–2 week lead time',
                    ],
                    'specs'      => [
                        'profiles' => 'K-style gutter',
                        'speed'    => '~50 FPM',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Seamless gutter production',
                    ],
                ],
                [
                    'slug'       => 'mach-ii-6-gutter',
                    'name'       => 'MACH II™ 6" Gutter Machine',
                    'short_name' => 'MACH II™ 6"',
                    'descriptor' => 'Dedicated 6" K-style for larger homes',
                    'image'      => $base . '2025/09/20250911_NTM_MACH-II-6_1000x1000.png',
                    'url'        => get_product_url('mach-ii-6-gutter'),
                    'badge'      => '',
                    'price'      => '$87,245',
                    'price_label' => 'Starting at',
                    'highlights' => [
                        '6" K-style gutters from raw coil',
                        'Up to 50 feet per minute with polyurethane drive rollers',
                        'Industry standard for 30+ years — 1–2 week lead time',
                    ],
                    'specs'      => [
                        'profiles' => 'K-style gutter',
                        'speed'    => '~50 FPM',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Seamless gutter production',
                    ],
                ],
                [
                    'slug'       => 'mach-ii-combo-gutter',
                    'name'       => 'MACH II™ 5"/6" Combo Gutter Machine',
                    'short_name' => 'MACH II™ 5"/6"',
                    'descriptor' => 'Two gutter sizes, one machine',
                    'image'      => $base . '2025/09/20250911_NTM_MACH-II-5-6-Combo_1000x1000.png',
                    'url'        => get_product_url('mach-ii-combo-gutter'),
                    'badge'      => '',
                    'price'      => '$87,245',
                    'price_label' => 'Starting at',
                    'highlights' => [
                        '5"/6" combo K-style gutters from raw coil',
                        'Up to 50 feet per minute with polyurethane drive rollers',
                        'Industry standard for 30+ years — 1–2 week lead time',
                    ],
                    'specs'      => [
                        'profiles' => 'K-style gutter',
                        'speed'    => '~50 FPM',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Seamless gutter production',
                    ],
                ],
                [
                    'slug'       => 'bg7-box-gutter',
                    'name'       => 'BG7™',
                    'short_name' => 'BG7™',
                    'descriptor' => 'Commercial-grade 7" box gutter machine',
                    'image'      => $base . '2025/09/20250911_NTM_BG7_1000x1000.png',
                    'url'        => get_product_url('bg7-box-gutter'),
                    'badge'      => '',
                    'price'      => '',
                    'price_label' => '',
                    'highlights' => [
                        'Commercial-grade box gutter machine',
                        'Built for durability on demanding jobsites',
                        'Portable design goes where the job takes you',
                    ],
                    'specs'      => [
                        'profiles' => 'Box gutter',
                        'speed'    => '—',
                        'power'    => '—',
                        'shear'    => '—',
                        'best_for' => 'Commercial box gutter production',
                    ],
                ],
            ],
        ],
    ];
}

/**
 * Get all machines as a flat array.
 *
 * @return array
 */
function get_all_machines(): array {
    $all = [];
    foreach (get_machine_categories() as $category) {
        foreach ($category['machines'] as $machine) {
            $all[] = $machine;
        }
    }
    return $all;
}

/**
 * Get roof & wall panel machines as a flat array.
 *
 * @return array
 */
function get_roof_wall_machines(): array {
    $categories = get_machine_categories();
    return $categories['roof-wall']['machines'] ?? [];
}

/**
 * Get FAQ items specific to the roof & wall panel machines category.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function get_roof_wall_faq_items(): array {
    return [
        [
            'question' => 'How long does it take to get a roof panel machine after ordering?',
            'answer'   => 'Lead times vary by model, but most NTM roof and wall panel machines ship within 4–8 weeks. The SSQ3 MultiPro and SSQ II MultiPro are our highest-demand models — contact your specialist for current availability.',
        ],
        [
            'question' => 'How much does a portable roof panel machine cost?',
            'answer'   => 'Pricing depends on the model and configuration. Entry-level machines like the SSR MultiPro Jr. start at a lower investment, while flagship models like the SSQ3 MultiPro are priced for high-volume commercial contractors. Flexible financing options — including lease-to-own and seasonal plans — make it easy to get started.',
        ],
        [
            'question' => 'What warranty comes with NTM roof and wall panel machines?',
            'answer'   => 'Every NTM machine is backed by a comprehensive warranty covering manufacturing defects and workmanship. Specific coverage terms vary by model. Your account specialist can walk you through the full warranty details for the machine you\'re considering.',
        ],
        [
            'question' => 'What kind of training and ongoing support do I get?',
            'answer'   => 'Every machine purchase includes hands-on operator training. NTM also provides ongoing technical support, troubleshooting assistance, and access to replacement parts. Machines with the UNIQ controller include built-in help pages and diagnostic videos for in-field problem solving.',
        ],
        [
            'question' => 'What materials can these machines process?',
            'answer'   => 'NTM roof and wall panel machines handle painted steel, Galvalume, aluminum, copper, zinc, and terne-coated stainless steel. The SSQ3 and SSQ II process up to 24 gauge steel. The WAV is purpose-built for heavy commercial wall panel work with 4 profiles on 25 polyurethane drive rollers.',
        ],
    ];
}

/**
 * Get seamless gutter machines as a flat array.
 *
 * @return array
 */
function get_gutter_machines(): array {
    $categories = get_machine_categories();
    return $categories['gutter']['machines'] ?? [];
}

/**
 * Get FAQ items specific to the seamless gutter machines category.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function get_gutter_faq_items(): array {
    return [
        [
            'question' => 'How much does a seamless gutter machine cost?',
            'answer'   => 'NTM seamless gutter machines start at $87,245 for the MACH II 5" and 6" models. The 5"/6" combo machine and BG7 box gutter machine are priced based on configuration. Flexible financing options — including lease-to-own and seasonal plans — make it easy to get started.',
        ],
        [
            'question' => 'How long does delivery take after ordering?',
            'answer'   => 'Most NTM gutter machines ship within 1–2 weeks thanks to streamlined production. The MACH II line is our highest-volume product — current lead times are among the shortest in the industry. Contact your specialist for exact availability.',
        ],
        [
            'question' => 'What warranty comes with NTM gutter machines?',
            'answer'   => 'Every NTM gutter machine includes a 3-year limited warranty covering manufacturing defects and workmanship. Drive rollers — the heart of the machine — carry a lifetime warranty. Your account specialist can walk you through the full coverage details.',
        ],
        [
            'question' => 'Can I purchase a gutter machine online?',
            'answer'   => 'Select MACH II models are available for online purchase directly from NTM. Custom configurations, combo machines, and the BG7 are handled through our sales team to ensure you get the right setup for your business.',
        ],
        [
            'question' => 'What kind of support does NTM provide?',
            'answer'   => 'NTM provides phone and email technical support, an online service portal, and access to service centers across the country. Every machine purchase includes hands-on operator training. Replacement parts and consumables are stocked for fast shipping.',
        ],
    ];
}

/**
 * Get SSQ3 feature improvements for the spotlight section.
 *
 * @return array<int, array{title: string, text: string}>
 */
function get_ssq3_features(): array {
    return [
        [
            'title' => 'Sealed Drive Gear Covers',
            'text'  => 'Inspection windows protect components against dust and debris.',
        ],
        [
            'title' => 'RFID Cover Sensors',
            'text'  => 'Controller identifies exactly which cover is open.',
        ],
        [
            'title' => 'Shear Warning Strobe',
            'text'  => 'Line-of-sight safety cue before and during shear cycles.',
        ],
        [
            'title' => 'Interior LED Lighting',
            'text'  => '8 strategically placed LEDs — work even when machine is off, LOTO-friendly.',
        ],
        [
            'title' => 'Improved Safety Guarding',
            'text'  => 'Bottom guarding limits hand access. Slug funnel controls scrap for cleaner floors.',
        ],
        [
            'title' => 'Simplified Maintenance',
            'text'  => 'Better visibility, faster adjustments, simpler service overall.',
        ],
    ];
}

/**
 * Get UNIQ control system features for the technology spotlight.
 *
 * @return array<int, array{title: string, text: string}>
 */
function get_uniq_features(): array {
    return [
        [
            'title' => 'Touchscreen Interface',
            'text'  => 'Batch and length control with intuitive touch operation.',
        ],
        [
            'title' => 'Cut List Upload',
            'text'  => 'Upload cut lists directly — integrated with AppliCad software.',
        ],
        [
            'title' => 'Built-in Troubleshooting',
            'text'  => 'Error messages with help pages and videos. Operators diagnose and fix problems independently.',
        ],
        [
            'title' => 'CE-Compliant Safety',
            'text'  => 'Controls start drive, notching, and shear functions with safety compliance.',
        ],
    ];
}

/**
 * Get key differentiators for the 3-card section.
 *
 * @return array<int, array{icon: string, title: string, text: string}>
 */
function get_differentiators(): array {
    return [
        [
            'icon'  => 'settings',
            'title' => 'On-Site Fabrication',
            'text'  => 'Eliminate factory delays, transportation costs, and panel damage risks. Produce exactly what you need, where you need it.',
        ],
        [
            'icon'  => 'trending-up',
            'title' => 'Up to 16 Profiles',
            'text'  => 'Standing seam, flush wall, and board & batten siding — maximum versatility from a single machine.',
        ],
        [
            'icon'  => 'link',
            'title' => 'Retrofit-Friendly',
            'text'  => 'New innovations can often be retrofitted to existing NTM equipment, protecting your investment.',
        ],
    ];
}

/**
 * Get FAQ items for the accordion section.
 *
 * @return array<int, array{question: string, answer: string}>
 */
function get_faq_items(): array {
    return [
        [
            'question' => 'What is portable rollforming?',
            'answer'   => 'Portable rollforming machines allow contractors to fabricate metal roof and wall panels directly on the jobsite — from raw coil stock into finished panels, ready to install. No factory orders, no shipping delays, no panel damage in transit.',
        ],
        [
            'question' => 'Which machine is right for my business?',
            'answer'   => 'It depends on the work you do. The SSQ3 and SSQ II are ideal for contractors doing both commercial and residential work with up to 16 profiles. The SSH is built for residential and light commercial. The SSR Jr. is the most affordable entry point. Talk to a specialist for a tailored recommendation.',
        ],
        [
            'question' => 'How are NTM machines powered?',
            'answer'   => 'Most NTM machines feature the Quick-Change Power Pack (QCPP), which lets you switch between gas and electric power in the field. The SSR Jr. is electric-only. This flexibility means you can work on any jobsite regardless of power availability.',
        ],
        [
            'question' => 'What kind of training and support is included?',
            'answer'   => 'Every machine purchase includes comprehensive operator training. NTM also provides ongoing technical support, troubleshooting assistance, and access to replacement parts. The UNIQ controller includes built-in help pages and diagnostic videos.',
        ],
        [
            'question' => 'Can I retrofit my existing NTM machine?',
            'answer'   => 'Yes — many NTM innovations can be retrofitted to existing equipment, protecting your original investment. Contact your account specialist to learn which upgrades are available for your machine.',
        ],
        [
            'question' => 'What materials can NTM machines process?',
            'answer'   => 'NTM machines work with painted steel, Galvalume, aluminum, copper, zinc, and terne-coated stainless steel. Gauge capacity varies by machine — the SSQ3 and SSQ II handle up to 24 gauge steel, while gutter machines process standard gutter coil stock.',
        ],
        [
            'question' => 'How do I finance an NTM machine?',
            'answer'   => 'NTM offers flexible financing options to fit your business. Choose from lease-to-own, equipment loans, or seasonal payment plans. Many contractors pay off their machine within the first year from increased revenue alone. Contact our team to build a custom financing package.',
        ],
    ];
}

/**
 * Get NTM journey milestones for social proof section.
 *
 * @return array<int, array{stat: string, label: string}>
 */
function get_journey_stats(): array {
    return [
        [
            'stat'  => '1991',
            'label' => 'Founded in Denver, CO',
        ],
        [
            'stat'  => '40+',
            'label' => 'Countries worldwide',
        ],
        [
            'stat'  => '7',
            'label' => 'Continents reached',
        ],
        [
            'stat'  => '30+',
            'label' => 'Years of innovation',
        ],
    ];
}

/**
 * Get ROI statistics for the ROI snapshot section.
 *
 * @return array<int, array{stat: string, label: string}>
 */
function get_roi_stats(): array {
    return [
        [
            'stat'  => '$2.25',
            'label' => 'Saved Per Sq Ft vs. Factory Panels',
        ],
        [
            'stat'  => '1–2 Yrs',
            'label' => 'Typical Machine Payback Period',
        ],
        [
            'stat'  => '1,000%',
            'label' => 'Business Growth Reported by Owners',
        ],
    ];
}
