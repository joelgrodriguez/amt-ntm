<?php
/**
 * Product Catalog Data
 *
 * WooCommerce product queries for theme templates.
 * Uses WooCommerce as the catalog source when available and falls back to
 * machine data for local development without WooCommerce.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Woo\Catalog;

if (!defined('ABSPATH')) {
    exit;
}

const FALLBACK_MACHINE_PRICE = '$87,245';

/**
 * Get product categories for the Explore Machines section.
 *
 * Each entry has a full `label` (used for aria, screen readers, and the
 * full-width tab on md+) and a `short` label (used on mobile where the
 * full label wraps or overflows).
 *
 * @return array<string, array{label: string, short: string}>
 */
function get_product_categories(): array {
    return [
        'roof-wall-panel-machines' => [
            'label' => \__('Roof & Wall Panel Machines', 'standard'),
            'short' => \__('Panels', 'standard'),
        ],
        'gutter-machines' => [
            'label' => \__('Seamless Gutter Machines', 'standard'),
            'short' => \__('Gutters', 'standard'),
        ],
        'accessories-add-on-equipment' => [
            'label' => \__('Accessories & Upgrades', 'standard'),
            'short' => \__('Accessories', 'standard'),
        ],
    ];
}

/**
 * Get products by category.
 *
 * @return array<int, array>
 */
function get_products_by_category(string $category_slug): array {
    if (function_exists('wc_get_products')) {
        return get_woocommerce_products($category_slug);
    }

    return get_sample_products($category_slug);
}

/**
 * Get products from WooCommerce.
 *
 * @return array<int, array>
 */
function get_woocommerce_products(string $category_slug): array {
    if ($category_slug === 'all') {
        return array_merge(
            get_woocommerce_products('roof-wall-panel-machines'),
            get_woocommerce_products('gutter-machines'),
            array_slice(get_woocommerce_products('accessories-add-on-equipment'), 0, 7)
        );
    }

    $is_accessory = $category_slug === 'accessories-add-on-equipment';

    $products = \Standard\Woo\Cache\get_products([
        'category' => [$category_slug],
        'limit'    => $is_accessory ? 7 : 10,
        'status'   => 'publish',
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ]);

    // Strip dormant machines (e.g. SSQ II) so they don't appear in any
    // front-end listing while their Woo product page remains live.
    $dormant_slugs = function_exists('Standard\\MachinesData\\get_dormant_wc_slugs')
        ? \Standard\MachinesData\get_dormant_wc_slugs()
        : [];

    $formatted = [];
    foreach ($products as $product) {
        if (!empty($dormant_slugs) && in_array($product->get_slug(), $dormant_slugs, true)) {
            continue;
        }
        // Flagship badge: hardcoded slug list. Currently SSQ3 only.
        // Add more slugs here as the flagship lineup grows; for a
        // larger set, move this to a per-machine data flag.
        $flagship_slugs = ['ssq3-multipro'];
        $is_flagship    = \in_array($product->get_slug(), $flagship_slugs, true);

        $description = '';
        if (!$is_accessory && function_exists('Standard\\MachinesData\\get_machine_description')) {
            $description = \Standard\MachinesData\get_machine_description($product->get_slug());
        }

        $formatted[] = [
            'id'             => $product->get_id(),
            'title'          => get_short_title($product->get_name()),
            'category_label' => get_primary_category_label($product),
            'description'    => $description,
            'descriptor'     => $is_accessory ? '' : \wp_strip_all_tags($product->get_short_description()),
            'image'          => \wp_get_attachment_url($product->get_image_id()),
            'price'          => $is_accessory ? '' : get_display_price($product),
            'price_label'    => \__('Starting at', 'standard'),
            'subtitle'       => $is_accessory ? ($product->get_price_html() ?: null) : null,
            'explore_url'    => $product->get_permalink(),
            'build_url'      => $is_accessory ? '' : get_configurator_url($product->get_slug()),
            'badge'          => $is_flagship ? \__('Flagship', 'standard') : '',
        ];
    }

    return $formatted;
}

/**
 * Return the Woo product price formatted for display, or the fallback if unset.
 */
function get_display_price(\WC_Product $product): string {
    $price = $product->get_price();
    if ($price === '' || $price === null) {
        return FALLBACK_MACHINE_PRICE;
    }

    return '$' . \number_format((float) $price);
}

/**
 * Strip known category suffixes from a Woo product name for card display.
 * Falls open: if no suffix matches, returns the original name unchanged.
 *
 * Example: "SSQ II™ MultiPro Roof and Wall Panel Machine" → "SSQ II™ MultiPro"
 */
function get_short_title(string $name): string {
    $suffixes = [
        ' Roof and Wall Panel Machine',
        ' Roof Panel Machine',
        ' Wall Panel Machine',
        ' Seamless Gutter Machine',
        ' Gutter Machine',
    ];

    foreach ($suffixes as $suffix) {
        $len = \strlen($suffix);
        if ($len > 0 && \substr($name, -$len) === $suffix) {
            return \trim(\substr($name, 0, -$len));
        }
    }

    return $name;
}

/**
 * Return the first category name for a Woo product, or '' if none.
 */
function get_primary_category_label(\WC_Product $product): string {
    $ids = $product->get_category_ids();
    if (empty($ids)) {
        return '';
    }
    $term = \get_term((int) $ids[0], 'product_cat');

    return $term instanceof \WP_Term ? $term->name : '';
}

/**
 * Get sample products for development/fallback.
 *
 * @return array<int, array>
 */
function get_sample_products(string $category_slug): array {
    if ($category_slug === 'all') {
        return array_merge(
            get_sample_products('roof-wall-panel-machines'),
            get_sample_products('gutter-machines'),
            array_slice(get_sample_products('accessories-add-on-equipment'), 0, 7)
        );
    }

    if ($category_slug === 'roof-wall-panel-machines' || $category_slug === 'gutter-machines') {
        return get_sample_machine_products($category_slug);
    }

    if ($category_slug === 'accessories-add-on-equipment') {
        return get_sample_accessory_products();
    }

    return [];
}

/**
 * @return array<int, array>
 */
function get_sample_machine_products(string $category_slug): array {
    $machines = $category_slug === 'roof-wall-panel-machines'
        ? \Standard\MachinesData\get_roof_wall_machines()
        : \Standard\MachinesData\get_gutter_machines();

    return array_map(
        fn(array $machine): array => format_sample_machine_product($machine, $category_slug),
        $machines
    );
}

/**
 * @param array<string, mixed> $machine
 * @return array<string, mixed>
 */
function format_sample_machine_product(array $machine, string $category_slug): array {
    $slug              = (string) ($machine['slug'] ?? '');
    $public_slug       = get_public_machine_slug($slug);
    $configurator_slug = (string) ($machine['configurator_slug'] ?? '');
    $is_gutter         = $category_slug === 'gutter-machines';

    return [
        'id'             => $public_slug,
        'title'          => $machine['short_name'] ?? $machine['name'] ?? '',
        'category_label' => $is_gutter ? \__('Seamless Gutter Machine', 'standard') : \__('Roof & Wall Panel Machine', 'standard'),
        'description'    => $machine['description'] ?? '',
        'descriptor'     => $machine['descriptor'] ?? '',
        'image'          => $machine['image'] ?? '',
        'price'          => !empty($machine['price']) ? $machine['price'] : FALLBACK_MACHINE_PRICE,
        'price_label'    => !empty($machine['price_label']) ? $machine['price_label'] : \__('Starting at', 'standard'),
        'explore_url'    => get_sample_machine_url($machine, $category_slug, $public_slug),
        'build_url'      => $configurator_slug !== '' ? \Standard\Url\internal('/configurator/' . $configurator_slug . '/') : '',
        'badge'          => get_sample_machine_badge($slug),
    ];
}

/**
 * Map a WooCommerce product slug to its /configurator/<slug>/ page URL.
 * Returns '' if the product has no configurator page.
 */
function get_configurator_url(string $woo_slug): string {
    $map = [
        '5vc-5v-crimp-roof-panel-machine'  => '5vc',
        'wav-wall-panel-machine'           => 'wav',
        'ssh-roof-panel-machine'           => 'ssh',
        'ssr-multipro-jr-roof-panel-machine' => 'ssr',
        'ssq-roof-panel-machine'           => 'ssqii',
        'mach-ii-5-gutter-machine'         => 'machii',
        'mach-ii-6-gutter-machine'         => 'machii',
        'mach-ii-6-gutter-machine-copy'    => 'machii',
        'mach-ii-5-6-combo-gutter-machine' => 'machii',
    ];

    $configurator_slug = $map[$woo_slug] ?? '';

    return $configurator_slug !== '' ? \Standard\Url\internal('/configurator/' . $configurator_slug . '/') : '';
}

/**
 * Match legacy fallback URLs used before machine data became canonical.
 */
function get_public_machine_slug(string $slug): string {
    $map = [
        'mach-ii-5-gutter'     => 'mach-ii-5',
        'mach-ii-6-gutter'     => 'mach-ii-6',
        'mach-ii-combo-gutter' => 'mach-ii-combo',
    ];

    return $map[$slug] ?? $slug;
}

/**
 * @param array<string, mixed> $machine
 */
function get_sample_machine_url(array $machine, string $category_slug, string $public_slug): string {
    $url = (string) ($machine['url'] ?? '');
    if ($url !== '' && $url !== '#') {
        return \Standard\Url\internal($url);
    }

    return \Standard\Url\internal('/machines/' . $category_slug . '/' . $public_slug . '/');
}

function get_sample_machine_badge(string $slug): string {
    $map = [
        'ssq3-multipro'    => 'Best Seller',
        'mach-ii-5-gutter' => 'Popular',
    ];

    return $map[$slug] ?? '';
}

/**
 * @return array<int, array<string, string>>
 */
function get_sample_accessory_products(): array {
    $uploads_url = 'https://newtechmachinery.com/wp-content/uploads';

    return [
        [
            'id'          => 'coil-reel',
            'title'       => 'Coil Reel',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/coil-reel/'),
            'build_url'   => '',
            'badge'       => '',
        ],
        [
            'id'          => 'run-out-stand',
            'title'       => 'Run-Out Stand',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/run-out-stand/'),
            'build_url'   => '',
            'badge'       => '',
        ],
        [
            'id'          => 'slitter',
            'title'       => 'Slitter Attachment',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/slitter/'),
            'build_url'   => '',
            'badge'       => 'New',
        ],
        [
            'id'          => 'notcher',
            'title'       => 'Notcher',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/notcher/'),
            'build_url'   => '',
            'badge'       => '',
        ],
        [
            'id'          => 'hemmer',
            'title'       => 'Hemmer',
            'descriptor'  => '',
            'image'       => $uploads_url . '/2025/09/Machine-on-rooftop-scaled.jpg',
            'price'       => '',
            'price_label' => '',
            'explore_url' => \Standard\Url\internal('/accessories/hemmer/'),
            'build_url'   => '',
            'badge'       => '',
        ],
    ];
}
