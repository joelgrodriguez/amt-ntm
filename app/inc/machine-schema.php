<?php
/**
 * Machine Product JSON-LD Schema Generator
 *
 * Generates Product + FAQPage structured data for machine product pages.
 *
 * Verified 2026-07-10: with Yoast SEO + Premium active, this is the ONLY
 * Product emitter on machine pages (Yoast emits no Product; WC core's
 * structured data does not fire on the custom single-machine template).
 * Do not add a seo_plugin_active() guard here — it would remove the page's
 * only Product schema.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineSchema;

if (!defined('ABSPATH')) {
    exit;
}

const SCHEMA_JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

/**
 * Render Product + FAQPage JSON-LD schema for a machine product.
 *
 * @param \WC_Product $product  WooCommerce product object.
 * @param array       $machine  Machine data array.
 */
function render_machine_schema(\WC_Product $product, array $machine): void {
    $product_schema = build_product_schema($product, $machine);
    if ($product_schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($product_schema, SCHEMA_JSON_FLAGS) . '</script>' . "\n";
    }

    $faq_schema = build_faq_schema($machine);
    if ($faq_schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, SCHEMA_JSON_FLAGS) . '</script>' . "\n";
    }
}

/**
 * @param \WC_Product $product
 * @param array       $machine
 * @return array|null
 */
function build_product_schema(\WC_Product $product, array $machine): ?array {
    $overrides = $machine['schema'] ?? [];
    $hero      = $machine['hero'] ?? [];
    $specs     = $machine['specs'] ?? [];
    $organization_id = home_url('/') . '#organization';

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => wp_strip_all_tags($hero['subtitle'] ?? $product->get_short_description()),
        'url'         => get_permalink($product->get_id()),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => $overrides['brand'] ?? 'New Tech Machinery',
        ],
        'manufacturer' => [
            '@id' => $organization_id,
        ],
    ];

    // Omit the image key entirely when empty: a missing image is a soft
    // warning for Google's Product rich results, but image: "" is an error.
    $image = wp_get_attachment_url($product->get_image_id()) ?: ($hero['image'] ?? '');
    if ($image !== '') {
        $schema['image'] = $image;
    }

    if (!empty($overrides['category'])) {
        $schema['category'] = $overrides['category'];
    }

    if (!empty($overrides['low_price'])) {
        $offer = [
            '@type'         => 'AggregateOffer',
            'priceCurrency' => 'USD',
            'lowPrice'      => $overrides['low_price'],
        ];
        if (!empty($overrides['high_price'])) {
            $offer['highPrice'] = $overrides['high_price'];
        }
        if (!empty($overrides['availability'])) {
            $offer['availability'] = 'https://schema.org/' . $overrides['availability'];
        }
        $schema['offers'] = $offer;
    }

    $properties = build_additional_properties($specs, $machine);
    if (!empty($properties)) {
        $schema['additionalProperty'] = $properties;
    }

    return $schema;
}

/**
 * @param array $specs
 * @param array $machine
 * @return array
 */
function build_additional_properties(array $specs, array $machine): array {
    $props = [];

    $dims = $specs['dimensions']['machine'] ?? [];
    if (!empty($dims['weight'])) {
        $props[] = pv('Weight', $dims['weight']);
    }
    if (!empty($dims['length'])) {
        $props[] = pv('Length', $dims['length']);
    }

    $perf = $specs['performance'] ?? [];
    if (!empty($perf['shear']['type'])) {
        $props[] = pv('Shear Type', $perf['shear']['type']);
    }
    if (!empty($perf['drive']['type'])) {
        $props[] = pv('Drive Type', $perf['drive']['type']);
    }
    if (!empty($perf['speed'][0]['rate'])) {
        $props[] = pv('Max Speed', $perf['speed'][0]['rate']);
    }

    foreach (($machine['stats'] ?? []) as $stat) {
        $props[] = pv((string) $stat['label'], $stat['value'] ?? '');
    }

    foreach (($specs['materials'] ?? []) as $mat) {
        $props[] = pv('Material: ' . (string) $mat['name'], $mat['gauge'] ?? '');
    }

    if (!empty($specs['warranty']['description'])) {
        $props[] = pv('Warranty', $specs['warranty']['description']);
    }

    return $props;
}

/**
 * PropertyValue node. Accepts any scalar because curated machine data is
 * hand-edited — a numeric weight must not fatal the page under strict_types.
 *
 * @param string $name
 * @param string|int|float $value
 * @return array
 */
function pv(string $name, string|int|float $value): array {
    return [
        '@type' => 'PropertyValue',
        'name'  => $name,
        'value' => (string) $value,
    ];
}

add_action('wp_head', __NAMESPACE__ . '\\render_front_page_machine_list', 6);

/**
 * ItemList of the machine lineup, front page only.
 *
 * Supplements Yoast's homepage graph (WebPage/Organization only) with a
 * machine-readable statement of the product line. URL-reference ListItems
 * ONLY — full Product nodes live exclusively on the single-machine pages
 * (render_machine_schema() above); embedding them here would duplicate Product
 * schema across URLs. No seo_plugin_active() guard: this adds to Yoast, it does
 * not replace it.
 */
function render_front_page_machine_list(): void {
    if (!is_front_page()) {
        return;
    }

    // Machines only — profiles and accessories are not machines.
    $machine_categories = ['roof-wall-panel-machines', 'gutter-machines'];

    $elements = [];
    $position = 1;

    foreach ($machine_categories as $category_slug) {
        $products = \Standard\Woo\Catalog\get_products_by_category($category_slug);

        foreach ($products as $product) {
            $name = str_replace(["\u{2122}", "\u{00AE}"], '', (string) ($product['title'] ?? ''));
            $url  = (string) ($product['explore_url'] ?? '');

            if ($name === '' || $url === '') {
                continue;
            }

            $elements[] = [
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => $name,
                'url'      => $url,
            ];
            $position++;
        }
    }

    // An empty ItemList is worse than none — render nothing.
    if ($elements === []) {
        return;
    }

    $schema = [
        '@context'        => 'https://schema.org',
        '@type'           => 'ItemList',
        'name'            => 'New Tech Machinery portable rollforming machines',
        'itemListElement' => $elements,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($schema, SCHEMA_JSON_FLAGS) . '</script>' . "\n";
}

/**
 * @param array $machine
 * @return array|null
 */
function build_faq_schema(array $machine): ?array {
    $faqs = $machine['faq'] ?? [];
    if (empty($faqs)) {
        return null;
    }

    $entities = [];
    foreach ($faqs as $faq) {
        $entities[] = [
            '@type' => 'Question',
            'name'  => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => wp_strip_all_tags($faq['answer']),
            ],
        ];
    }

    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];
}
