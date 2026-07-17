<?php
/**
 * Woo accessory product card data.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Accessories;

if (!defined('ABSPATH')) {
    exit;
}

const MACHINE_CATEGORY_SLUGS = ['roof-wall-panel-machines', 'gutter-machines'];
const ACCESSORY_CATEGORY_SLUG = 'accessories-add-on-equipment';

/**
 * Curated accessory fitment map.
 *
 * Keys are accessory Woo product slugs. Values are published machine product
 * slugs. This is code-owned on purpose: the catalog has a finite accessory set,
 * and DB-only compatibility data gets wiped on fresh production pulls.
 *
 * @return array<string, array<int, string>>
 */
function compatible_machine_slug_map(): array {
    $mach_ii = [
        'mach-ii-5-gutter-machine',
        'mach-ii-6-gutter-machine',
        'mach-ii-5-6-combo-gutter-machine',
    ];

    $panel_core = [
        'ssq3-multipro',
        'ssq-roof-panel-machine',
        'ssh-roof-panel-machine',
        'ssr-multipro-jr-roof-panel-machine',
    ];

    $ssq = [
        'ssq3-multipro',
        'ssq-roof-panel-machine',
    ];

    $all_current_non_mach_ii = [
        'ssq3-multipro',
        'ssq-roof-panel-machine',
        'ssh-roof-panel-machine',
        'ssr-multipro-jr-roof-panel-machine',
        '5vc-5v-crimp-roof-panel-machine',
        'wav-wall-panel-machine',
        'bg7-box-gutter-machine',
    ];

    return [
        'ssr-dual-overhead-reel-rack'               => ['ssr-multipro-jr-roof-panel-machine'],
        'mach-ii-5-6-gutter-machine-cart'           => $mach_ii,
        'roof-panel-machine-cart'                   => ['ssq3-multipro', 'ssq-roof-panel-machine', 'ssh-roof-panel-machine', '5vc-5v-crimp-roof-panel-machine'],
        'bg7-run-out-stand-mount-adapter'           => ['bg7-box-gutter-machine'],
        'ssr-cart-with-casters'                     => ['ssr-multipro-jr-roof-panel-machine'],
        '12-volt-electrical-system-12v'             => $mach_ii,
        'rl16'                                      => $mach_ii,
        'rl20'                                      => $mach_ii,
        'hk5-alcoa-hook'                            => $mach_ii,
        'angled-slitter-features'                   => $ssq,
        'bottom-bead-assembly-bb5-and-bb6'          => $mach_ii,
        'coil-cradle-cr5-5-6'                       => $mach_ii,
        'ntm-gutter-workstation'                    => $mach_ii,
        'dr1-dual-overhead-reel-stand'              => ['ssq-roof-panel-machine', 'ssh-roof-panel-machine', '5vc-5v-crimp-roof-panel-machine', 'bg7-box-gutter-machine'],
        'dual-overhead-reel-stand-dr1-ssq3'         => ['ssq3-multipro'],
        'dr01-ex-expandable-arbor'                  => $all_current_non_mach_ii,
        'z-cutter-metal-closure-flashing-shear'     => $ssq,
        'ez-counter-computerized-length-controller' => array_merge($mach_ii, ['ssr-multipro-jr-roof-panel-machine']),
        'machine-cover-cvr-wav-g'                   => ['wav-wall-panel-machine'],
        'rrf-frame-assembly'                        => ['ssq3-multipro', 'ssq-roof-panel-machine', 'ssh-roof-panel-machine'],
        'frame-assembly-rrf-r'                      => ['ssr-multipro-jr-roof-panel-machine'],
        'hs-1-hand-seamer'                          => $panel_core,
        'hand-seamer-hs2'                           => $ssq,
        'hmt-hot-melt-sealant-pump-interface'       => ['ssh-roof-panel-machine'],
        'hmt-unq-hot-melt-system-interface'         => $ssq,
        'machine-cover-cvr-5vc'                     => ['5vc-5v-crimp-roof-panel-machine'],
        'cvr-machine-cover'                         => ['bg7-box-gutter-machine'],
        'machine-cover-cvr-gm5-gm6-or-gm56'         => $mach_ii,
        'machine-cover-cvr-ssh'                     => ['ssh-roof-panel-machine'],
        'flush-wall-perforator'                     => $ssq,
        'machine-cover-cvr-ssq'                     => ['ssq-roof-panel-machine'],
        'machine-cover-cvr-ssq3'                    => ['ssq3-multipro'],
        'machine-cover-cvr-ssq3-n'                  => ['ssq3-multipro'],
        'machine-cover-cvr-ssq3-n-unq'              => ['ssq3-multipro'],
        'machine-cover-cvr-ssq3-unq'                => ['ssq3-multipro'],
        'machine-cover-cvr-ssr'                     => ['ssr-multipro-jr-roof-panel-machine'],
        'machine-cover-crv-wav-g'                   => ['wav-wall-panel-machine'],
        'notched-panel-features'                    => ['ssq-roof-panel-machine'],
        'rr1-thru-rr6-pencil-bead-v-and-striation-rollers' => $panel_core,
        'plc07-computer-control-2'                  => ['bg7-box-gutter-machine'],
        'plc08-computer-control'                    => ['5vc-5v-crimp-roof-panel-machine'],
        'qcpp-e-qcpp-g-quick-change-power-pack'     => ['ssq3-multipro', 'ssq-roof-panel-machine', 'ssh-roof-panel-machine', '5vc-5v-crimp-roof-panel-machine', 'bg7-box-gutter-machine'],
        'remote-4-button-start'                     => array_merge($mach_ii, ['ssh-roof-panel-machine', 'ssr-multipro-jr-roof-panel-machine', '5vc-5v-crimp-roof-panel-machine', 'bg7-box-gutter-machine']),
        'riser-pads'                                => $mach_ii,
        'rosg1-run-out-stand'                       => array_merge($mach_ii, ['bg7-box-gutter-machine']),
        'run-out-table-rosr-10'                     => ['ssr-multipro-jr-roof-panel-machine'],
        'rosg3'                                     => array_merge($mach_ii, ['bg7-box-gutter-machine']),
        'fsd1-single-free-standing-de-coiler'       => $all_current_non_mach_ii,
        'dual-overhead-reel-stand-dr1-wav'          => ['wav-wall-panel-machine'],
        'rr6-5vc-striation-rib'                     => ['5vc-5v-crimp-roof-panel-machine'],
        'pvc-strippable-tape-applicator'            => $all_current_non_mach_ii,
        'tr12-trailer'                              => ['ssq3-multipro', 'ssq-roof-panel-machine', 'ssh-roof-panel-machine', 'ssr-multipro-jr-roof-panel-machine', '5vc-5v-crimp-roof-panel-machine'],
        'tr12l-trailer-2'                           => ['bg7-box-gutter-machine'],
        'trailer-tr12xl'                            => ['wav-wall-panel-machine'],
        'trailer-tr23'                              => ['wav-wall-panel-machine'],
        'trailer-tr23g'                             => ['wav-wall-panel-machine'],
        'rac-transfer-rack'                         => $mach_ii,
        'triple-overhead-reel-stand-dr01-triple'    => ['wav-wall-panel-machine'],
        'turnstile-reel-stand-trn'                  => $mach_ii,
        'uniq-control-system'                       => ['ssq-roof-panel-machine'],
        'uniq-automatic-control-system'             => ['ssq3-multipro'],
        'ros-10-variable-height-run-out-table'      => ['ssq3-multipro', 'ssq-roof-panel-machine', 'ssh-roof-panel-machine', '5vc-5v-crimp-roof-panel-machine', 'wav-wall-panel-machine', 'bg7-box-gutter-machine'],
    ];
}

/**
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function get_compatible_machine_cards(int $limit = 4): array {
    $machine_posts = \get_posts([
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => $limit,
        'orderby'                => 'menu_order title',
        'order'                  => 'ASC',
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'tax_query'              => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => MACHINE_CATEGORY_SLUGS,
                'operator' => 'IN',
            ],
        ],
    ]);

    return product_cards(array_filter(array_map('\wc_get_product', $machine_posts)));
}

/**
 * Return compatible machines in the data shape expected by card-product.php.
 *
 * Used by the accessory page Compatibility section so machine cards in that
 * carousel render through the canonical card. This intentionally honors mapped
 * published products even when a machine is dormant in lineup lists: fitment
 * pages answer "does it work?", not "is this the current flagship?"
 *
 * @param \WC_Product|\WP_Post|int $accessory_product
 * @return array<int, array<string, mixed>>
 */
function get_compatible_machine_product_cards(\WC_Product|\WP_Post|int $accessory_product, int $limit = 8): array {
    if ($limit <= 0) {
        return [];
    }

    $machine_slugs = get_accessory_compatible_machine_slugs($accessory_product);
    if ($machine_slugs === []) {
        return [];
    }

    $machine_posts = \get_posts([
        'post_type'              => 'product',
        'post_status'            => 'publish',
        'posts_per_page'         => min($limit, count($machine_slugs)),
        'post_name__in'          => $machine_slugs,
        'orderby'                => 'post_name__in',
        'fields'                 => 'ids',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'tax_query'              => [
            [
                'taxonomy' => 'product_cat',
                'field'    => 'slug',
                'terms'    => MACHINE_CATEGORY_SLUGS,
                'operator' => 'IN',
            ],
        ],
    ]);

    $cards = [];
    foreach ($machine_posts as $post_id) {
        $product = \wc_get_product($post_id);
        if (!$product instanceof \WC_Product) {
            continue;
        }

        $woo_slug = $product->get_slug();
        $is_gutter = false;
        foreach ($product->get_category_ids() as $cat_id) {
            $term = \get_term((int) $cat_id, 'product_cat');
            if ($term instanceof \WP_Term && $term->slug === 'gutter-machines') {
                $is_gutter = true;
                break;
            }
        }

        $description = function_exists('Standard\\MachinesData\\get_machine_description')
            ? \Standard\MachinesData\get_machine_description($woo_slug)
            : '';

        $raw_price = $product->get_price();
        $price = ($raw_price === '' || $raw_price === null)
            ? \Standard\Woo\Catalog\FALLBACK_MACHINE_PRICE
            : '$' . \number_format((float) $raw_price);

        $cards[] = [
            'id'             => $product->get_id(),
            'title'          => \Standard\Woo\Catalog\get_short_title($product->get_name()),
            'category_label' => $is_gutter
                ? \__('Seamless Gutter Machine', 'standard')
                : \__('Roof & Wall Panel Machine', 'standard'),
            'description'    => $description,
            'image'          => \wp_get_attachment_url($product->get_image_id()),
            'price'          => $price,
            'price_label'    => \__('Starting at', 'standard'),
            'explore_url'    => $product->get_permalink(),
            'build_url'      => \Standard\Woo\Catalog\get_configurator_url($woo_slug),
            'badge'          => '',
            'is_accessory'   => false,
        ];

        if (count($cards) >= $limit) {
            break;
        }
    }

    return $cards;
}

/**
 * @param \WC_Product|\WP_Post|int $accessory_product
 * @return array<int, string>
 */
function get_accessory_compatible_machine_slugs(\WC_Product|\WP_Post|int $accessory_product): array {
    $product = resolve_product($accessory_product);
    if (!$product instanceof \WC_Product) {
        return [];
    }

    $map = compatible_machine_slug_map();
    foreach (get_accessory_map_keys($product) as $key) {
        if (isset($map[$key])) {
            return $map[$key];
        }
    }

    return [];
}

/**
 * @return array<int, string>
 */
function get_accessory_map_keys(\WC_Product $product): array {
    $keys = [$product->get_slug()];

    $sku = trim((string) $product->get_sku());
    if ($sku !== '') {
        $keys[] = $sku;
    }

    foreach ($product->get_category_ids() as $cat_id) {
        $term = \get_term((int) $cat_id, 'product_cat');
        if ($term instanceof \WP_Term && $term->slug !== '') {
            $keys[] = $term->slug;
        }
    }

    return array_values(array_unique($keys));
}

/**
 * @param \WC_Product|\WP_Post|int $product
 */
function resolve_product(\WC_Product|\WP_Post|int $product): ?\WC_Product {
    if ($product instanceof \WC_Product) {
        return $product;
    }

    $product_id = $product instanceof \WP_Post ? $product->ID : $product;
    $resolved   = \wc_get_product($product_id);

    return $resolved instanceof \WC_Product ? $resolved : null;
}

/**
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function get_related_accessory_cards(\WC_Product $product, int $limit = 4): array {
    $products = \Standard\Woo\Cache\get_products([
        'category' => [ACCESSORY_CATEGORY_SLUG],
        'exclude'  => [$product->get_id()],
        'limit'    => $limit,
        'status'   => 'publish',
        'orderby'  => 'rand',
    ]);

    return product_cards($products);
}

/**
 * @param \WC_Product[] $products
 * @return array<int, array{url: string, image_id: int, title: string, subtitle: string|null}>
 */
function product_cards(array $products): array {
    $cards = [];

    foreach ($products as $product) {
        if (!$product instanceof \WC_Product) {
            continue;
        }

        $cards[] = [
            'url'      => $product->get_permalink(),
            'image_id' => (int) $product->get_image_id(),
            'title'    => $product->get_name(),
            'subtitle' => $product->get_price_html() ?: null,
        ];
    }

    return $cards;
}
