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
 * Builds a slug→URL map on first call, then serves from cache. Accepts
 * either a WooCommerce product slug (e.g. ssq-roof-panel-machine) or a
 * machines-data slug (e.g. ssq-ii-multipro). When the data slug doesn't
 * match a WC product directly, the alias map in machine-product-data.php
 * is consulted in reverse to find the corresponding WC slug.
 *
 * @param string $slug Product slug.
 * @return string Product permalink or '#'.
 */
function get_product_url(string $slug): string {
    static $urls = null;

    if ($urls === null) {
        $urls = [];
        if (function_exists('wc_get_products')) {
            $products = \Standard\Woo\Cache\get_products([
                'limit'  => -1,
                'status' => 'publish',
                'type'   => 'simple',
            ]);
            foreach ($products as $product) {
                $urls[$product->get_slug()] = $product->get_permalink();
            }
        }
    }

    if (isset($urls[$slug])) {
        return $urls[$slug];
    }

    // Reverse-lookup the alias map: callers may pass a data-file slug
    // (e.g. ssq-ii-multipro) while WC stores the product under a
    // different slug (e.g. ssq-roof-panel-machine).
    if (function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        $wc_slug = array_search($slug, \Standard\MachineProductData\get_slug_aliases(), true);
        if ($wc_slug !== false && isset($urls[$wc_slug])) {
            return $urls[$wc_slug];
        }
    }

    return '#';
}

/**
 * Get a WooCommerce product's starting price for a machine slug.
 *
 * Uses the same slug → product lookup (with alias fallback) as
 * get_product_url(). Returns a formatted dollar string (e.g. "$87,245"),
 * or null when the product can't be resolved or has no price set.
 *
 * @param string $slug Machine slug (data slug or WC slug).
 */
function get_product_price(string $slug): ?string {
    static $prices = null;

    if ($prices === null) {
        $prices = [];
        if (function_exists('wc_get_products')) {
            $products = \Standard\Woo\Cache\get_products([
                'limit'  => -1,
                'status' => 'publish',
                'type'   => 'simple',
            ]);
            foreach ($products as $product) {
                $raw = $product->get_price();
                if ($raw === '' || $raw === null) {
                    continue;
                }
                $prices[$product->get_slug()] = '$' . \number_format((float) $raw);
            }
        }
    }

    if (isset($prices[$slug])) {
        return $prices[$slug];
    }

    if (function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        $wc_slug = array_search($slug, \Standard\MachineProductData\get_slug_aliases(), true);
        if ($wc_slug !== false && isset($prices[$wc_slug])) {
            return $prices[$wc_slug];
        }
    }

    return null;
}

/**
 * Get all machines organized by category.
 *
 * Dormant machines (e.g. SSQ II, superseded by SSQ3) are filtered out
 * by default. Pass true to include them — used by Woo product templates
 * that still need to resolve the dormant machine's metadata when its
 * historical product page renders.
 *
 * @return array<string, array{label: string, machines: array}>
 */
function get_machine_categories(bool $include_dormant = false): array {
    $base = 'https://newtechmachinery.com/wp-content/uploads/';

    $categories = [
        'roof-wall' => [
            'label' => 'Roof & Wall Panel Machines',
            'url'   => '/roof-wall-panel-machines/',
            'machines' => [
                [
                    'slug'              => 'ssq3-multipro',
                    'configurator_slug' => 'ssq3-multi-pro',
                    'name'              => 'SSQ3™ MultiPro',
                    'short_name' => 'SSQ3™',
                    'descriptor' => 'The most advanced portable roll former ever built',
                    'description' => 'Runs 16 panel profiles on a high-speed hydraulic drive — NTM\'s flagship roof and wall panel machine for commercial crews.',
                    'image'      => $base . '2026/03/SSQ3_OL_0226-hero.png',
                    'url'        => get_product_url('ssq3-multipro'),
                    'badge'      => 'Flagship',
                    'highlights' => [
                        'Up to 16 panel profiles: standing seam, flush wall, and board & batten siding',
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
                    'slug'              => 'ssq-ii-multipro',
                    'configurator_slug' => 'ssqii',
                    'name'              => 'SSQ II™ MultiPro',
                    'short_name' => 'SSQ II™',
                    'descriptor' => 'The proven multi-profile workhorse',
                    'description' => 'Proven 16-profile workhorse with hydraulic drive and shear — gas or electric power for commercial roof and wall panel work.',
                    'image'      => $base . '2025/09/20250911_NTM_SSQ-II_1000x1000.png',
                    'url'        => get_product_url('ssq-ii-multipro'),
                    'badge'      => '',
                    // Superseded by SSQ3. Hidden from /machines and
                    // /roof-wall-panel-machines listings, but the Woo
                    // product page stays live for historical reasons.
                    'dormant'    => true,
                    'highlights' => [
                        'Up to 16 profile options: standing seam roof, wall panels, and board & batten',
                        'Quick-Change Power Pack (QCPP) switches between gas and electric in the field',
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
                    'slug'              => 'ssh-multipro',
                    'configurator_slug' => 'ssh',
                    'name'              => 'SSH™ MultiPro',
                    'short_name' => 'SSH™',
                    'descriptor' => 'Residential & light commercial machine',
                    'description' => 'Runs 7 panel profiles up to ~60 feet per minute — sized and priced for residential and light commercial roof crews.',
                    'image'      => $base . '2025/09/20250911_NTM_SSH_1000x1000.png.webp',
                    'url'        => get_product_url('ssh-multipro'),
                    'badge'      => '',
                    'highlights' => [
                        '7 panel profiles for residential and light commercial roofing',
                        'Hydraulic drive and shear, up to ~60 feet per minute',
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
                    'slug'              => 'ssr-multipro-jr',
                    'configurator_slug' => 'ssr',
                    'name'              => 'SSR™ MultiPro Jr.',
                    'short_name' => 'SSR™',
                    'descriptor' => 'Affordable entry into portable rollforming',
                    'description' => 'The most affordable entry point into portable rollforming — electric-powered with NTM\'s EZE CHANGE quick-swap profile system.',
                    'image'      => $base . '2025/09/20250911_NTM_SSR_1000x1000.png',
                    'url'        => get_product_url('ssr-multipro-jr'),
                    'badge'      => '',
                    'highlights' => [
                        'Most affordable entry point into portable rollforming',
                        'Polyurethane drive rollers with EZE CHANGE profile roller system',
                        'Up to ~30 feet per minute, electric powered',
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
                    'slug'              => '5vc-5v-crimp',
                    'configurator_slug' => '5vc',
                    'name'              => '5V Crimp',
                    'short_name' => '5V Crimp',
                    'descriptor' => 'The industry\'s only portable 5V crimp machine',
                    'description' => 'The industry\'s only portable 5V crimp roof panel machine — hydraulic drive and shear, built for exposed-fastener jobs.',
                    'image'      => $base . '2025/09/20250911_NTM_5VC_1000x1000.png',
                    'url'        => get_product_url('5vc-5v-crimp'),
                    'badge'      => '',
                    'highlights' => [
                        'NTM\'s only exposed fastener roof panel machine',
                        'Hydraulically driven polyurethane rollers with hydraulic shear',
                        'Easy to transport, built for durability and efficiency',
                    ],
                    'specs'      => [
                        'profiles' => '5V Crimp',
                        'speed'    => '',
                        'power'    => '',
                        'shear'    => 'Hydraulic',
                        'best_for' => 'Exposed fastener roofing',
                    ],
                ],
                [
                    'slug'              => 'wav-wall-panel',
                    'configurator_slug' => 'wav',
                    'name'              => 'WAV™',
                    'short_name' => 'WAV™',
                    'descriptor' => 'The industry\'s only portable WAV profile machine',
                    'description' => 'Purpose-built for heavy commercial wall panel work — 4 profiles on 25 polyurethane drive rollers with UNIQ® standard.',
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
                        'speed'    => '',
                        'power'    => '',
                        'shear'    => '',
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
                    'slug'              => 'mach-ii-5-gutter',
                    'configurator_slug' => 'machii',
                    'name'              => 'MACH II™ 5" Gutter Machine',
                    'short_name' => 'MACH II™ 5"',
                    'descriptor' => 'The most trusted 5" gutter machine since 1994',
                    'description' => 'Produces 5" K-style seamless gutters from raw coil at up to ~50 feet per minute — the residential gutter industry standard.',
                    'image'      => $base . '2025/09/20250911_NTM_MACH-II-5_1000x1000.png',
                    'url'        => get_product_url('mach-ii-5-gutter'),
                    'badge'      => '',
                    'highlights' => [
                        '5" K-style gutters from raw coil',
                        'Up to 50 feet per minute with polyurethane drive rollers',
                        'Industry standard for 30+ years, 1–2 week lead time',
                    ],
                    'specs'      => [
                        'profiles'  => 'K-style',
                        'size'      => '5"',
                        'speed'     => '~50 FPM',
                        'drive'     => 'Polyurethane rollers',
                        'lead_time' => '1–2 weeks',
                        'best_for'  => 'Residential gutter',
                    ],
                ],
                [
                    'slug'              => 'mach-ii-6-gutter',
                    'configurator_slug' => 'machii',
                    'name'              => 'MACH II™ 6" Gutter Machine',
                    'short_name' => 'MACH II™ 6"',
                    'descriptor' => 'Dedicated 6" K-style for larger homes',
                    'description' => 'Produces 6" K-style seamless gutters from raw coil at up to ~50 feet per minute — sized for larger homes and light commercial.',
                    'image'      => $base . '2025/09/20250911_NTM_MACH-II-6_1000x1000.png',
                    'url'        => get_product_url('mach-ii-6-gutter'),
                    'badge'      => '',
                    'highlights' => [
                        '6" K-style gutters from raw coil',
                        'Up to 50 feet per minute with polyurethane drive rollers',
                        'Industry standard for 30+ years, 1–2 week lead time',
                    ],
                    'specs'      => [
                        'profiles'  => 'K-style',
                        'size'      => '6"',
                        'speed'     => '~50 FPM',
                        'drive'     => 'Polyurethane rollers',
                        'lead_time' => '1–2 weeks',
                        'best_for'  => 'Larger homes / light commercial',
                    ],
                ],
                [
                    'slug'              => 'mach-ii-combo-gutter',
                    'configurator_slug' => 'machii',
                    'name'              => 'MACH II™ 5"/6" Combo Gutter Machine',
                    'short_name' => 'MACH II™ 5"/6"',
                    'descriptor' => 'Two gutter sizes, one machine',
                    'description' => 'Switches between 5" and 6" K-style gutters from a single setup — no machine swap, no second trip to the truck.',
                    'image'      => $base . '2025/09/20250911_NTM_MACH-II-5-6-Combo_1000x1000.png',
                    'url'        => get_product_url('mach-ii-combo-gutter'),
                    'badge'      => '',
                    'featured'   => true,
                    'highlights' => [
                        '5"/6" combo K-style gutters from raw coil',
                        'Up to 50 feet per minute with polyurethane drive rollers',
                        'Industry standard for 30+ years, 1–2 week lead time',
                    ],
                    'specs'      => [
                        'profiles'  => 'K-style',
                        'size'      => '5" / 6"',
                        'speed'     => '~50 FPM',
                        'drive'     => 'Polyurethane rollers',
                        'lead_time' => '1–2 weeks',
                        'best_for'  => 'Crews running both sizes',
                    ],
                ],
                [
                    'slug'       => 'bg7-box-gutter',
                    'name'       => 'BG7™',
                    'short_name' => 'BG7™',
                    'descriptor' => 'Commercial-grade 7" box gutter machine',
                    'description' => 'Commercial-grade portable 7" box gutter machine — built for durability on demanding jobsites, designed to travel with the crew.',
                    'image'      => $base . '2025/09/20250911_NTM_BG7_1000x1000.png',
                    'url'        => get_product_url('bg7-box-gutter'),
                    'badge'      => '',
                    'highlights' => [
                        'Commercial-grade box gutter machine',
                        'Built for durability on demanding jobsites',
                        'Portable design goes where the job takes you',
                    ],
                    'specs'      => [
                        'profiles'  => 'Box gutter',
                        'size'      => '7"',
                        'speed'     => '',
                        'drive'     => 'Polyurethane rollers',
                        'lead_time' => 'Configurable',
                        'best_for'  => 'Commercial box gutter',
                    ],
                ],
            ],
        ],
    ];

    if ($include_dormant) {
        return $categories;
    }

    foreach ($categories as $key => $category) {
        $categories[$key]['machines'] = array_values(array_filter(
            $category['machines'],
            static fn(array $m): bool => empty($m['dormant'])
        ));
    }

    return $categories;
}

/**
 * Map a raw machine data array (from get_machine_categories()) into the
 * data shape expected by templates/parts/card-product.php.
 *
 * Used by /machines, /roof-wall-panel-machines, and /seamless-gutter-machines
 * to render every machine through the single canonical card-product partial.
 *
 * @param array<string, mixed> $machine     Raw machine data row.
 * @param string               $category_key 'roof-wall' or 'gutter'.
 * @return array<string, mixed>
 */
function to_card_product(array $machine, string $category_key): array {
    $is_gutter = $category_key === 'gutter';
    $slug      = (string) ($machine['slug'] ?? '');

    $configurator_slug = (string) ($machine['configurator_slug'] ?? '');
    $build_url         = $configurator_slug !== ''
        ? \Standard\Url\internal('/configurator/' . $configurator_slug . '/')
        : '';

    $url = (string) ($machine['url'] ?? '');

    return [
        'id'             => $slug,
        'title'          => $machine['short_name'] ?? $machine['name'] ?? '',
        'category_label' => $is_gutter
            ? \__('Seamless Gutter Machine', 'standard')
            : \__('Roof & Wall Panel Machine', 'standard'),
        'description'    => $machine['description'] ?? '',
        'image'          => $machine['image'] ?? '',
        'price'          => !empty($machine['price'])
            ? $machine['price']
            : (get_product_price($slug) ?? ''),
        'price_label'    => $machine['price_label'] ?? \__('Starting at', 'standard'),
        'explore_url'    => $url !== '' && $url !== '#' ? $url : get_product_url($slug),
        'build_url'      => $build_url,
        'badge'          => !empty($machine['featured']) && empty($machine['badge'])
            ? \__('Featured', 'standard')
            : ($machine['badge'] ?? ''),
    ];
}

/**
 * Get a machine's editorial card description by slug.
 *
 * Resolves a WooCommerce product slug to the matching machine in
 * get_machine_categories() (including dormant) and returns its
 * `description` field — the canonical one-sentence body copy used by
 * card-product. Falls back to '' when no match.
 *
 * Accepts either a data slug (e.g. ssq3-multipro) or a Woo product slug
 * (e.g. ssq3-roof-panel-machine), via the alias map in
 * machine-product-data.php.
 */
function get_machine_description(string $slug): string {
    $data_slug = $slug;
    if (function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        $aliases = \Standard\MachineProductData\get_slug_aliases();
        if (isset($aliases[$slug])) {
            $data_slug = $aliases[$slug];
        }
    }

    foreach (get_machine_categories(true) as $category) {
        foreach ($category['machines'] as $machine) {
            if (($machine['slug'] ?? '') === $data_slug && !empty($machine['description'])) {
                return (string) $machine['description'];
            }
        }
    }

    return '';
}

/**
 * Get all machines as a flat array.
 *
 * Dormant machines are excluded by default; pass true to include them.
 *
 * @return array
 */
function get_all_machines(bool $include_dormant = false): array {
    $all = [];
    foreach (get_machine_categories($include_dormant) as $category) {
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
function get_roof_wall_machines(bool $include_dormant = false): array {
    $categories = get_machine_categories($include_dormant);
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
            'answer'   => 'Lead times vary by model, but most NTM roof and wall panel machines ship within 4–8 weeks. The SSQ3 MultiPro and SSQ II MultiPro are our highest-demand models; contact your specialist for current availability.',
        ],
        [
            'question' => 'How much does a portable roof panel machine cost?',
            'answer'   => 'Pricing depends on the model and configuration. Entry-level machines like the SSR MultiPro Jr. start at a lower investment, while flagship models like the SSQ3 MultiPro are priced for high-volume commercial contractors. Flexible financing options (including lease-to-own and seasonal plans) make it easy to get started.',
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
        [
            'question' => 'Does New Tech Machinery offer financing?',
            'answer'   => 'Yes. NTM partners with equipment finance lenders to offer lease-to-own, equipment loans, and seasonal payment plans. Most contractors structure financing so the machine pays for itself within the first year from increased panel revenue. Contact our sales team to build a custom financing package.',
        ],
        [
            'question' => 'How do I purchase an NTM roof panel machine?',
            'answer'   => 'You can buy directly from NTM through our sales team, or through an authorized dealer in your region. Start by building a quote in the online configurator or by talking with a machine specialist who will walk you through pricing, options, and lead time.',
        ],
        [
            'question' => 'What do I do if I need help with my machine?',
            'answer'   => 'NTM provides phone, email, and online support, plus a network of service centers across the country. Every purchase includes hands-on operator training, and machines running the UNIQ controller have built-in troubleshooting videos and diagnostics for in-field problem solving.',
        ],
        [
            'question' => 'What panel profiles can NTM machines produce?',
            'answer'   => 'NTM roof and wall panel machines produce standing seam roof panels, flush wall panels, board and batten siding, trapezoidal profiles, and the 5V crimp exposed-fastener profile. The SSQ3 and SSQ II MultiPro support up to 16 profiles from a single machine.',
        ],
    ];
}

/**
 * Get seamless gutter machines as a flat array.
 *
 * @return array
 */
function get_gutter_machines(bool $include_dormant = false): array {
    $categories = get_machine_categories($include_dormant);
    return $categories['gutter']['machines'] ?? [];
}

/**
 * WooCommerce product slugs that should be hidden from listings.
 *
 * Built from the dormant flag in get_machine_categories(true) and the
 * alias map in machine-product-data.php. WC queries (front page
 * carousel, etc.) feed through this so dormant machines don't leak
 * into category listings.
 *
 * @return array<int, string>
 */
function get_dormant_wc_slugs(): array {
    $dormant_data_slugs = [];
    foreach (get_machine_categories(true) as $category) {
        foreach ($category['machines'] as $machine) {
            if (!empty($machine['dormant']) && !empty($machine['slug'])) {
                $dormant_data_slugs[] = (string) $machine['slug'];
            }
        }
    }

    if (empty($dormant_data_slugs)) {
        return [];
    }

    $wc_slugs = [];
    if (function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        foreach (\Standard\MachineProductData\get_slug_aliases() as $wc_slug => $data_slug) {
            if (in_array($data_slug, $dormant_data_slugs, true)) {
                $wc_slugs[] = $wc_slug;
            }
        }
    }

    // Also catch the case where a WC slug matches a data slug directly.
    return array_values(array_unique(array_merge($wc_slugs, $dormant_data_slugs)));
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
            'answer'   => 'NTM seamless gutter machines start at $9,800 for the MACH II 5" model, $10,500 for the MACH II 6", and $12,300 for the 5"/6" combo. The BG7 box gutter machine starts at $71,600. Flexible financing (including lease-to-own and seasonal plans) makes it easy to get started.',
        ],
        [
            'question' => 'How long does delivery take after ordering?',
            'answer'   => 'Most NTM gutter machines ship within 1–2 weeks thanks to streamlined production. The MACH II line is our highest-volume product; current lead times are among the shortest in the industry. Contact your specialist for exact availability.',
        ],
        [
            'question' => 'What warranty comes with NTM gutter machines?',
            'answer'   => 'Every NTM gutter machine includes a 3-year limited warranty covering manufacturing defects and workmanship. Drive rollers (the heart of the machine) carry a lifetime warranty. Your account specialist can walk you through the full coverage details.',
        ],
        [
            'question' => 'Can I purchase a gutter machine online?',
            'answer'   => 'Select MACH II models are available for online purchase directly from NTM. Custom configurations, combo machines, and the BG7 are handled through our sales team to ensure you get the right setup for your business.',
        ],
        [
            'question' => 'What kind of support does NTM provide?',
            'answer'   => 'NTM provides phone and email technical support, an online service portal, and access to service centers across the country. Every machine purchase includes hands-on operator training. Replacement parts and consumables are stocked for fast shipping.',
        ],
        [
            'question' => 'Does New Tech Machinery offer financing?',
            'answer'   => 'Yes. NTM partners with equipment finance lenders to offer lease-to-own, equipment loans, and seasonal payment plans. Most gutter contractors structure financing so the MACH II machine pays for itself within the first year of use. Contact our sales team to build a custom financing package.',
        ],
        [
            'question' => 'How long will it take to get my machine?',
            'answer'   => 'Most NTM gutter machines ship within 1–2 weeks because the MACH II line is built in volume. Custom configurations and the BG7 box gutter machine may take longer depending on options. Your account specialist will give you an exact lead time at order confirmation.',
        ],
        [
            'question' => 'What do I do if I need help with my gutter machine?',
            'answer'   => 'NTM provides phone, email, and online portal support, plus a network of service centers and field technicians across the country. Every purchase includes hands-on operator training. Replacement parts and consumables ship fast so your crews stay on the job.',
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
            'text'  => 'Upload cut lists directly, integrated with AppliCad software.',
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
 * Detailed UNIQ feature spec for the standalone UNIQ landing page.
 *
 * Longer-form copy than get_uniq_features(); used by the four-up
 * blueprint grid on page-uniq-control-system.php. Each item maps 1:1
 * to the legacy page's feature blocks, rewritten in the
 * engineered-showroom voice.
 *
 * @return array<int, array{spec: string, title: string, text: string}>
 */
function get_uniq_detailed_features(): array {
    return [
        [
            'spec'  => '01 / DISPLAY',
            'title' => '7″ Touchscreen',
            'text'  => 'Lockable metal-covered touchscreen with troubleshooting screens, coil calculator, job and part entry, and full machine settings — all in one panel.',
        ],
        [
            'spec'  => '02 / OPERATION',
            'title' => 'Automatic or Manual',
            'text'  => 'Run the machine and shear from the touchscreen, or fall back to the manual push-button control panel. The operator chooses.',
        ],
        [
            'spec'  => '03 / NOTCHING',
            'title' => 'Automatic Notching',
            'text'  => 'Program notch types once; the machine punches them as material runs, including angled notches for hips and valleys. Requires a notch-equipped SSQ II™ MultiPro.',
        ],
        [
            'spec'  => '04 / DATA',
            'title' => 'Cutlist Import / Export',
            'text'  => 'USB port for program updates, 600-panel cutlist import, and export of final project specifications back to the office.',
        ],
    ];
}

/**
 * Resource library for the UNIQ landing page.
 *
 * Two columns: instructional documentation (PDFs / manuals) and video
 * tutorials. Each entry: label, url, kind (used for the mono affix and
 * the type label in the resource row).
 *
 * @return array{docs: list<array{label: string, url: string, kind: string}>, videos: list<array{label: string, url: string, kind: string}>}
 */
function get_uniq_resources(): array {
    return [
        'docs' => [
            [
                'label' => 'Instructions for Field-Updating UNIQ Software',
                'url'   => '/wp-content/uploads/2022/09/09012022_Instructions-for-updating-from-Uniq-V1-to-V2.pdf',
                'kind'  => 'PDF',
            ],
            [
                'label' => 'Copying UNIQ / NTM200 Programs to SD & USB',
                'url'   => '/wp-content/uploads/2022/01/How-to-copy-the-Uniq-or-NTM200-programs-onto-SD-USB-drives.pdf',
                'kind'  => 'PDF',
            ],
            [
                'label' => 'UNIQ Automatic Control System Supplement Manual',
                'url'   => '/learning-center/manual/ssq2-supplement-uniq-v1-1-9/',
                'kind'  => 'MANUAL',
            ],
            [
                'label' => 'How to Import a Cutlist into UNIQ',
                'url'   => '/wp-content/uploads/2022/01/Instructions-for-field-updating-the-UNIQ-program-1.pdf',
                'kind'  => 'PDF',
            ],
        ],
        'videos' => [
            [
                'label' => 'New Software Tutorial',
                'url'   => '/learning-center/video/uniq-control-system-new-software-tutorial-video/',
                'kind'  => 'VIDEO',
            ],
            [
                'label' => 'Software Update Tutorial',
                'url'   => '/learning-center/video/uniq-software-update-tutorial-video/',
                'kind'  => 'VIDEO',
            ],
            [
                'label' => 'Programming Panel Lengths',
                'url'   => '/learning-center/video/how-to-program-panel-lengths-using-uniq-automatic-control-system-video/',
                'kind'  => 'VIDEO',
            ],
            [
                'label' => 'Using the Push-Button Panel',
                'url'   => '/learning-center/video/how-to-use-push-button-panel-on-uniq-automatic-controller/',
                'kind'  => 'VIDEO',
            ],
            [
                'label' => 'Feeding & Running Material with Notching',
                'url'   => '/learning-center/video/run-material-ssq-with-notching-video/',
                'kind'  => 'VIDEO',
            ],
            [
                'label' => 'Upgrading UNIQ Manual to Automatic',
                'url'   => '/learning-center/video/upgrade-the-ntm-uniq-manual-to-automatic-video/',
                'kind'  => 'VIDEO',
            ],
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
            'text'  => 'Standing seam, flush wall, and board & batten siding: maximum versatility from a single machine.',
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
            'answer'   => 'Portable rollforming machines allow contractors to fabricate metal roof and wall panels directly on the jobsite, from raw coil stock into finished panels, ready to install. No factory orders, no shipping delays, no panel damage in transit.',
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
            'answer'   => 'Yes. Many NTM innovations can be retrofitted to existing equipment, protecting your original investment. Contact your account specialist to learn which upgrades are available for your machine.',
        ],
        [
            'question' => 'What materials can NTM machines process?',
            'answer'   => 'NTM machines work with painted steel, Galvalume, aluminum, copper, zinc, and terne-coated stainless steel. Gauge capacity varies by machine: the SSQ3 and SSQ II handle up to 24 gauge steel, while gutter machines process standard gutter coil stock.',
        ],
        [
            'question' => 'How do I finance an NTM machine?',
            'answer'   => 'NTM offers flexible financing options to fit your business. Choose from lease-to-own, equipment loans, or seasonal payment plans. Many contractors pay off their machine within the first year from increased revenue alone. Contact our team to build a custom financing package.',
        ],
        [
            'question' => 'How long will it take to get my machine?',
            'answer'   => 'Lead times vary by model. Most NTM gutter machines ship within 1–2 weeks; roof and wall panel machines typically ship within 4–8 weeks. The SSQ3 MultiPro and SSQ II MultiPro are our highest-demand roof panel models. Your account specialist will confirm exact lead time at order.',
        ],
        [
            'question' => 'How do I purchase an NTM machine?',
            'answer'   => 'Purchase directly from NTM through our sales team or through an authorized dealer in your region. Start by building a quote in the online configurator at /build-finance/ or by talking with a machine specialist who will walk you through pricing, configuration, and lead time.',
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
