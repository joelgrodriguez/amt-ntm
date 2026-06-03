<?php
/**
 * Choose Your Machine — Data Assembler
 *
 * Single source of truth for the chooser page. Reads each machine's real
 * content from app/data/machines/*.php (category, price_range, stats) and
 * resolves its live WooCommerce permalink via get_machine_product_link(),
 * so prices, specs, and links never drift from the product pages.
 *
 * The per-row "best when" line and the two spec chips are curated here:
 * the data files' is_for/stats are written for the product page, not for a
 * one-line scannable chooser row. Copy is grounded in those files, not
 * invented. Row order is curated flagship → entry within each family,
 * because that order is the information a buyer is actually scanning for.
 *
 * Returns the two ordered family lists plus each family's entry price:
 *   roof / gutter  ordered row lists (below)
 *   roof_from / gutter_from  lowest "From" price string, drives the fork
 *
 * Each row:
 *   key        machine data key (app/data/machines/<key>.php)
 *   name       short model name for the ledger (mono)
 *   best_when  one-line qualifier: who this machine is for
 *   chips      two {label,value} spec facts pulled from the data file stats
 *   price      normalised "from" floor display string (e.g. "$43,400")
 *   price_num  numeric floor for min-finding (e.g. 43400), or null
 *   url        live product permalink, or null if the product is absent
 *   image      WC featured-image URL (woocommerce_thumbnail), '' if none
 *   image_alt  featured-image alt text, '' if none
 *
 * Rows whose product cannot be resolved locally still render (copy + chips);
 * they simply omit the link. Nothing 404s, nothing is faked.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;
use function Standard\MachineProductData\get_machine_product_link;

/**
 * Reduce any price_range string to its low-end "starting from" figure.
 *
 * The product data files store price two ways: simple machines as a floor
 * ("$43,400+") and configured-to-spec flagships as a range ("$121K - $137K").
 * The product pages want both shapes, but the chooser ledger is a single
 * "From X" column, so we take the low end of whichever shape we're given.
 * "$121K - $137K" -> ["$121,000", 121000]; "$43,400+" -> ["$43,400", 43400].
 *
 * @param string $range The finance.price_range string.
 * @return array{0: string, 1: int}|null [display, numeric] or null if unparseable.
 */
$ntm_price_floor = static function (string $range): ?array {
    // First currency token in the string is the low end (ranges read low-high).
    if (!preg_match('/\$\s*([\d,]+(?:\.\d+)?)\s*([KkMm])?/', $range, $m)) {
        return null;
    }

    $num = (float) str_replace(',', '', $m[1]);
    $mult = strtoupper($m[2] ?? '');
    if ($mult === 'K') {
        $num *= 1000;
    } elseif ($mult === 'M') {
        $num *= 1000000;
    }

    $num = (int) round($num);
    $display = '$' . number_format($num);

    return [$display, $num];
};

/**
 * Curated chooser rows. Specs reference real stat labels in each data file;
 * the assembler pulls the matching values so a spec change on a product page
 * flows through here. price comes straight from the data file price_range.
 */
$ntm_choose_catalog = [
    'roof' => [
        [
            'key'       => 'ssq3-multipro',
            'name'      => __('SSQ3™ MultiPro', 'standard'),
            'best_when' => __('You run high panel volume across many profiles and change tooling several times a day.', 'standard'),
            'chips'     => ['Panel Profiles', 'Tooling Changeover'],
        ],
        [
            'key'       => 'ssq-ii-multipro',
            'name'      => __('SSQ II™ MultiPro', 'standard'),
            'best_when' => __('You want all 16 profiles at a lower entry than the SSQ3 and don\'t need its 25-minute changeover.', 'standard'),
            'chips'     => ['Panel Profiles', 'Max Speed'],
        ],
        [
            'key'       => 'ssh-multipro',
            'name'      => __('SSH™ MultiPro', 'standard'),
            'best_when' => __('You run residential and light commercial standing seam and do not need all 16 profiles.', 'standard'),
            'chips'     => ['Panel Profiles', 'Max Speed'],
        ],
        [
            'key'       => 'ssr-multipro-jr',
            'name'      => __('SSR™ MultiPro Jr.', 'standard'),
            'best_when' => __('You are entering portable rollforming and want the lowest-cost electric machine to start fabricating.', 'standard'),
            'chips'     => ['Panel Profiles', 'Power'],
        ],
        [
            'key'       => '5vc-5v-crimp',
            'name'      => __('5V Crimp', 'standard'),
            'best_when' => __('Your work is exposed-fastener 5V crimp roofing, the classic ag and rural panel.', 'standard'),
            'chips'     => ['Roof Style', 'Max Speed'],
        ],
        [
            'key'       => 'wav-wall-panel',
            'name'      => __('WAV™ Wall Panel', 'standard'),
            'best_when' => __('You fabricate heavy commercial and industrial wave wall panels; the only portable WAV machine made.', 'standard'),
            'chips'     => ['WAV Profiles', 'Max Speed (Electric)'],
        ],
    ],
    'gutter' => [
        [
            'key'       => 'mach-ii-5-gutter',
            'name'      => __('MACH II™ 5"', 'standard'),
            'best_when' => __('You run residential 5" K-style gutter, the most common seamless gutter job.', 'standard'),
            'chips'     => ['Gutter Profile', 'Max Speed'],
        ],
        [
            'key'       => 'mach-ii-6-gutter',
            'name'      => __('MACH II™ 6"', 'standard'),
            'best_when' => __('Your work calls for larger 6" K-style gutter on residential and light commercial roofs.', 'standard'),
            'chips'     => ['Gutter Profile', 'Max Speed'],
        ],
        [
            'key'       => 'mach-ii-combo-gutter',
            'name'      => __('MACH II™ 5"/6" Combo', 'standard'),
            'best_when' => __('You switch between 5" and 6" K-style and want both sizes from one machine.', 'standard'),
            'chips'     => ['K-Style Sizes', 'Max Speed'],
        ],
        [
            'key'       => 'bg7-box-gutter',
            'name'      => __('BG7™ Box Gutter', 'standard'),
            'best_when' => __('The job is commercial 7" box gutter, not K-style.', 'standard'),
            'chips'     => ['Box Gutter Size', 'Max Speed'],
        ],
    ],
];

/**
 * Hydrate one curated row from its data file + live product link.
 *
 * @param array $row Curated row (key, name, best_when, chips).
 * @return array Row with price, url, and resolved {label,value} chips.
 */
$ntm_choose_hydrate = static function (array $row) use ($ntm_price_floor): array {
    // The data files key off the WooCommerce slug; the canonical slug for
    // each machine key is the first alias, but get_machine_product_data()
    // resolves the key directly too, so pass the key.
    $data  = get_machine_product_data($row['key']);
    $stats = is_array($data) && !empty($data['stats']) ? $data['stats'] : [];

    // Build a label => value map from the data file stats, then pick the two
    // chips this row asked for. Missing labels are dropped, not faked.
    $by_label = [];
    foreach ($stats as $stat) {
        if (isset($stat['label'], $stat['value'])) {
            $by_label[$stat['label']] = $stat['value'];
        }
    }

    $chips = [];
    foreach ($row['chips'] as $label) {
        if (isset($by_label[$label])) {
            $chips[] = ['label' => $label, 'value' => $by_label[$label]];
        }
    }

    // Normalise every row to a single "From X" floor so the ledger price
    // column is one consistent shape, then keep the numeric for min-finding.
    $range = is_array($data) && !empty($data['finance']['price_range'])
        ? $data['finance']['price_range']
        : '';
    $floor = $range !== '' ? $ntm_price_floor($range) : null;

    $link = get_machine_product_link($row['key']);

    return [
        'name'        => $row['name'],
        'best_when'   => $row['best_when'],
        'chips'       => $chips,
        'price'       => $floor[0] ?? '',   // display, e.g. "$43,400"
        'price_num'   => $floor[1] ?? null, // numeric, for family-min
        'url'         => $link['url'] ?? null,
        'image'       => $link['image'] ?? '',      // WC featured image, '' if none
        'image_alt'   => $link['image_alt'] ?? '',
    ];
};

$ntm_catalog = [
    'roof'   => array_map($ntm_choose_hydrate, $ntm_choose_catalog['roof']),
    'gutter' => array_map($ntm_choose_hydrate, $ntm_choose_catalog['gutter']),
];

/**
 * Lowest "From" price in a family, as a display string (e.g. "$9,800"), or ''.
 * Drives the fork's entry price so it can never desync from the catalog.
 *
 * @param array $rows Hydrated rows.
 * @return string
 */
$ntm_family_floor = static function (array $rows): string {
    $min = null;
    foreach ($rows as $r) {
        if ($r['price_num'] !== null && ($min === null || $r['price_num'] < $min['price_num'])) {
            $min = $r;
        }
    }
    return $min['price'] ?? '';
};

return [
    'roof'        => $ntm_catalog['roof'],
    'gutter'      => $ntm_catalog['gutter'],
    'roof_from'   => $ntm_family_floor($ntm_catalog['roof']),
    'gutter_from' => $ntm_family_floor($ntm_catalog['gutter']),
];
