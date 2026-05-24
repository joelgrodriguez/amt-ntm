<?php
/**
 * Accessories landing page — content + classification data.
 *
 * Wraps the 66-product "Accessories & Add-On Equipment" Woo category in
 * editorial buckets, fitment data, and copy that the landing page's section
 * parts read from. WordPress query work is kept in this file so the
 * template parts stay presentational.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\AccessoriesData;

if (!defined('ABSPATH')) {
    exit;
}

const CATEGORY_SLUG = 'accessories-add-on-equipment';

/**
 * Bucket map for the catalog grid + category nav.
 *
 * Each bucket has a stable id (used as anchor + grid section id), a mono
 * label, a one-line editorial description, and a classifier callback that
 * decides if a given Woo product belongs in this bucket. The first bucket
 * whose classifier returns true wins; products that match none fall into
 * the "more" bucket.
 *
 * @return array<int, array{
 *   id: string,
 *   label: string,
 *   description: string,
 *   classify: callable(\WC_Product): bool,
 * }>
 */
function get_buckets(): array {
    return [
        [
            'id'          => 'reels-stands',
            'label'       => __('Reels & Stands', 'standard'),
            'description' => __('De-coilers, turnstiles, single/dual/triple racks. Keep the coil moving.', 'standard'),
            'classify'    => static fn(\WC_Product $p): bool => matches_any($p, [
                '/\bDR0?\d/i', '/\bRL\d/i', '/\bTRN\b/i', '/\bFSD\d/i',
            ], [
                'reel', 'de-coiler', 'decoiler', 'turnstile',
            ]),
        ],
        [
            'id'          => 'runout-cutting',
            'label'       => __('Runout & Cutting', 'standard'),
            'description' => __('Runout tables, hand seamers, shears, the Z-Cutter. The panel\'s life after the machine.', 'standard'),
            'classify'    => static fn(\WC_Product $p): bool => matches_any($p, [
                '/\bROS[GR]?\d?\b/i', '/\bRSG\d/i', '/\bRSM\d/i',
                '/\bSH[56]\b/i', '/\bBF.+SH\b/i', '/\bHS\d/i',
                '/\bRAC\b/i', '/Z-?Cutter/i',
            ], [
                'run out', 'runout', 'shear', 'seamer', 'transfer rack', 'z-closure', 'z-cutter',
            ]),
        ],
        [
            'id'          => 'power-controls',
            'label'       => __('Power & Controls', 'standard'),
            'description' => __('UNIQ, EZ-Counter, PLC, motor and voltage options, 12V systems, remotes. What fires the line.', 'standard'),
            'classify'    => static fn(\WC_Product $p): bool => matches_any($p, [
                '/\bUNQ\b/i', '/\bUNIQ\b/i', '/\bPLC\d/i', '/\bELEC\d/i',
                '/\bMG?12V\b/i', '/\bM78-\d/i', '/\bREM-?\d/i', '/\bGAS\s?\d/i',
                '/\bHMT-/i', '/EZ.?Counter/i',
            ], [
                'control system', 'remote', 'motor', 'volt', 'hot melt', 'engine',
            ]),
        ],
        [
            'id'          => 'carts-covers-transport',
            'label'       => __('Carts, Covers & Transport', 'standard'),
            'description' => __('Machine carts, trailers, covers, riser pads. Own it, move it, protect it.', 'standard'),
            'classify'    => static fn(\WC_Product $p): bool => matches_any($p, [
                '/\bCRT-/i', '/\bMG-CART\b/i', '/\bCVR-/i',
                '/\bTR\d{2}/i', '/\bRISER/i',
            ], [
                'cart', 'cover', 'trailer', 'riser',
            ]),
        ],
        [
            'id'          => 'roll-tooling',
            'label'       => __('Roll Tooling', 'standard'),
            'description' => __('Profile rollers, bottom beads, back flanges, striations, panel notching, Alcoa hook. Extend what the machine can make.', 'standard'),
            'classify'    => static fn(\WC_Product $p): bool => matches_any($p, [
                '/\bRR[1-7]/i', '/\bBB[56]\b/i', '/\bBF\d/i',
                '/\bRRF/i', '/\bFWQ\b/i', '/\bHK[56]/i', '/\bASL\b/i',
            ], [
                'back flange', 'bottom bead', 'striation', 'notching',
                'alcoa hook', 'angle slitter', 'frame assembly', 'pencil, bead',
                'soffit', 'underdeck',
            ]),
        ],
        [
            'id'          => 'workstation-fabrication',
            'label'       => __('Workstation & Fabrication', 'standard'),
            'description' => __('Gutter workstation, miter saw, integrated tool holders, perforator, film applicator. Shop-floor extensions.', 'standard'),
            'classify'    => static fn(\WC_Product $p): bool => matches_any($p, [
                '/\bNMP-/i', '/\bPR3/i',
            ], [
                'workstation', 'work station', 'perforator', 'miter saw',
                'film applicator', 'tool holder',
            ]),
        ],
    ];
}

/**
 * Machines surfaced in the "fits which machine" quick-reference matrix.
 *
 * Order matters: this is the column order in the table. `tag` is the Woo
 * product_tag that accessories use to declare compatibility (see
 * inc/woo/accessory-tag-map.php).
 *
 * @return array<int, array{slug: string, label: string, tag: string, url: string}>
 */
function get_fitment_machines(): array {
    return [
        ['slug' => 'ssq3',    'label' => 'SSQ3',      'tag' => 'SSQIII',     'url' => '/ssq3-multipro/'],
        ['slug' => 'ssq2',    'label' => 'SSQ II',    'tag' => 'SSQII',      'url' => '/ssq-roof-panel-machine/'],
        ['slug' => 'ssh',     'label' => 'SSH',       'tag' => 'SSH',        'url' => '/ssh-roof-panel-machine/'],
        ['slug' => 'ssr',     'label' => 'SSR',       'tag' => 'SSR',        'url' => '/ssr-roof-panel-machine/'],
        ['slug' => '5vc',     'label' => '5VC',       'tag' => '5VC',        'url' => '/5vc-5v-crimp-roof-panel-machine/'],
        ['slug' => 'wav',     'label' => 'WAV',       'tag' => 'WAV',        'url' => '/wav-wall-panel-machine/'],
        ['slug' => 'machii',  'label' => 'MACH II',   'tag' => 'MACHII',     'url' => '/mach-ii-5-6-5-6-gutter-machines/'],
        ['slug' => 'bg7',     'label' => 'BG7',       'tag' => 'BG7',        'url' => '/bg7-box-gutter-machine/'],
    ];
}

/**
 * Rows in the fitment matrix. Each row is one bucket (matches a get_buckets() id).
 *
 * @return array<int, array{id: string, label: string}>
 */
function get_fitment_rows(): array {
    return [
        ['id' => 'reels-stands',          'label' => __('Reel & Stand', 'standard')],
        ['id' => 'runout-cutting',        'label' => __('Runout & Cutting', 'standard')],
        ['id' => 'power-controls',        'label' => __('Controller / Power', 'standard')],
        ['id' => 'carts-covers-transport', 'label' => __('Cart, Cover, Trailer', 'standard')],
        ['id' => 'roll-tooling',          'label' => __('Roll Tooling', 'standard')],
    ];
}

/**
 * Owner resources strip (manuals / install videos / parts).
 *
 * @return array<int, array{eyebrow: string, label: string, description: string, url: string}>
 */
function get_owner_resources(): array {
    return [
        [
            'eyebrow'     => __('Reference', 'standard'),
            'label'       => __('Manuals & Spec Sheets', 'standard'),
            'description' => __('Operator manuals, parts diagrams, and spec sheets for every machine in the lineup.', 'standard'),
            'url'         => '/learning-center/resource/manuals/',
        ],
        [
            'eyebrow'     => __('Install', 'standard'),
            'label'       => __('Riser Kit Install, On Video', 'standard'),
            'description' => __('Field install walkthrough for the SSQ3 riser kit. Step by step, no narration.', 'standard'),
            'url'         => '/learning-center/video/ntm-riser-kit-install-made-easy-video/',
        ],
        [
            'eyebrow'     => __('Support', 'standard'),
            'label'       => __('Talk to Service', 'standard'),
            'description' => __('Rollforming specialists in Aurora, CO. Calls answered by people who service the machines.', 'standard'),
            'url'         => '/contact/',
        ],
    ];
}

/**
 * Run all accessory products through the bucket classifiers.
 *
 * Excludes anything whose title still ends with a known machine suffix
 * (a handful of machines are mis-tagged into category 272). Falls back to
 * a final "More & Specialty" bucket for products that didn't match a rule.
 *
 * @return array<string, array{
 *   id: string,
 *   label: string,
 *   description: string,
 *   products: array<int, array<string, mixed>>
 * }>
 */
function get_bucketed_products(): array {
    $buckets = get_buckets();
    $result = [];
    foreach ($buckets as $b) {
        $result[$b['id']] = [
            'id'          => $b['id'],
            'label'       => $b['label'],
            'description' => $b['description'],
            'products'    => [],
        ];
    }
    $result['more'] = [
        'id'          => 'more',
        'label'       => __('Specialty', 'standard'),
        'description' => __('One-off parts and specialty add-ons that don\'t fit a tidy category.', 'standard'),
        'products'    => [],
    ];

    if (!function_exists('wc_get_products')) {
        return $result;
    }

    $products = \Standard\Woo\Cache\get_products([
        'category' => [CATEGORY_SLUG],
        'limit'    => 200,
        'status'   => 'publish',
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ]);

    $machine_suffixes = [
        ' Roof and Wall Panel Machine',
        ' Roof Panel Machine',
        ' Wall Panel Machine',
        ' Seamless Gutter Machine',
        ' Gutter Machine',
    ];

    foreach ($products as $product) {
        if (!$product instanceof \WC_Product) {
            continue;
        }
        if (title_ends_with_any($product->get_name(), $machine_suffixes)) {
            continue;
        }

        $card = format_card($product);

        $placed = false;
        foreach ($buckets as $b) {
            if (($b['classify'])($product)) {
                $result[$b['id']]['products'][] = $card;
                $placed = true;
                break;
            }
        }
        if (!$placed) {
            $result['more']['products'][] = $card;
        }
    }

    return $result;
}

/**
 * Return the count of bucketed accessories. Used by the hero stats.
 */
function get_accessory_count(): int {
    $bucketed = get_bucketed_products();
    $count = 0;
    foreach ($bucketed as $b) {
        $count += count($b['products']);
    }
    return $count;
}

/**
 * Build the fitment matrix: rows × machines, each cell = product count.
 *
 * @return array<string, array<string, int>>  matrix[row_id][machine_slug] = count
 */
function get_fitment_matrix(): array {
    $matrix = [];
    foreach (get_fitment_rows() as $row) {
        foreach (get_fitment_machines() as $machine) {
            $matrix[$row['id']][$machine['slug']] = 0;
        }
    }

    if (!function_exists('wc_get_products')) {
        return $matrix;
    }

    $bucketed = get_bucketed_products();
    $machines = get_fitment_machines();

    foreach (get_fitment_rows() as $row) {
        $bucket = $bucketed[$row['id']] ?? null;
        if (!$bucket) {
            continue;
        }
        foreach ($bucket['products'] as $card) {
            $product_id = (int) ($card['id'] ?? 0);
            if ($product_id <= 0) {
                continue;
            }
            $tags = wp_get_post_terms($product_id, 'product_tag', ['fields' => 'names']);
            if (is_wp_error($tags) || !is_array($tags)) {
                continue;
            }
            foreach ($machines as $machine) {
                if (in_array($machine['tag'], $tags, true)) {
                    $matrix[$row['id']][$machine['slug']]++;
                }
            }
        }
    }

    return $matrix;
}

/**
 * Format a single Woo product as the shape expected by card-accessory.php.
 *
 * Mirrors Standard\Woo\Accessories\product_cards() so the same partial
 * renders accessories whether they're sourced from this data layer or
 * from the machine-page compatible-accessories query.
 *
 * @return array{id: int, url: string, image_id: int, title: string, subtitle: string|null}
 */
function format_card(\WC_Product $product): array {
    return [
        'id'       => $product->get_id(),
        'url'      => $product->get_permalink(),
        'image_id' => (int) $product->get_image_id(),
        'title'    => $product->get_name(),
        'subtitle' => $product->get_price_html() ?: null,
    ];
}

/**
 * True if any regex OR any case-insensitive title needle matches the product.
 *
 * Regex patterns are tested against title + sku (concatenated).
 * Plain needles are case-insensitive substring tests against the title.
 *
 * @param array<int, string> $patterns
 * @param array<int, string> $needles
 */
function matches_any(\WC_Product $product, array $patterns, array $needles): bool {
    $title    = $product->get_name();
    $sku      = (string) $product->get_sku();
    $haystack = $title . ' ' . $sku;

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $haystack) === 1) {
            return true;
        }
    }
    foreach ($needles as $needle) {
        if (stripos($title, $needle) !== false) {
            return true;
        }
    }
    return false;
}

/**
 * @param array<int, string> $suffixes
 */
function title_ends_with_any(string $title, array $suffixes): bool {
    foreach ($suffixes as $suffix) {
        $len = strlen($suffix);
        if ($len > 0 && substr($title, -$len) === $suffix) {
            return true;
        }
    }
    return false;
}
