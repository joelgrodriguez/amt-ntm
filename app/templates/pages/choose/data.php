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
 * Returns two ordered lists keyed by family: 'roof' and 'gutter'. Each row:
 *   key        machine data key (app/data/machines/<key>.php)
 *   name       short model name for the ledger (mono)
 *   best_when  one-line qualifier: who this machine is for
 *   chips      two {label,value} spec facts pulled from the data file stats
 *   price      price_range from the data file (e.g. "$43,400+")
 *   url        live product permalink, or null if the product is absent
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
 * Curated chooser rows. Specs reference real stat labels in each data file;
 * the assembler pulls the matching values so a spec change on a product page
 * flows through here. price comes straight from the data file price_range.
 */
$ntm_choose_catalog = [
    'roof' => [
        [
            'key'       => 'ssq3-multipro',
            'name'      => __('SSQ3™ MultiPro', 'standard'),
            'best_when' => __('You run high volume across many profiles and want the fastest, most capable machine NTM builds.', 'standard'),
            'chips'     => ['Panel Profiles', 'Tooling Changeover'],
        ],
        [
            'key'       => 'ssq-ii-multipro',
            'name'      => __('SSQ II™ MultiPro', 'standard'),
            'best_when' => __('You want the proven 16-profile workhorse at a lower entry than the SSQ3, not the newest platform.', 'standard'),
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
$ntm_choose_hydrate = static function (array $row): array {
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

    $price = is_array($data) && !empty($data['finance']['price_range'])
        ? $data['finance']['price_range']
        : '';

    $link = get_machine_product_link($row['key']);

    return [
        'name'      => $row['name'],
        'best_when' => $row['best_when'],
        'chips'     => $chips,
        'price'     => $price,
        'url'       => $link['url'] ?? null,
    ];
};

return [
    'roof'   => array_map($ntm_choose_hydrate, $ntm_choose_catalog['roof']),
    'gutter' => array_map($ntm_choose_hydrate, $ntm_choose_catalog['gutter']),
];
