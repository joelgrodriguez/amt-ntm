<?php
/**
 * Singular WooCommerce Product JSON-LD ownership.
 *
 * Every product page gets one theme-owned Product node. Schema Pro's generic
 * per-page output and WooCommerce's Product/Review output are suppressed on
 * singular products so cached empty arrays and duplicate Product nodes cannot
 * compete with the canonical graph.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ProductSchema;

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;

const SCHEMA_PRO_CACHE_KEY = 'wp_schema_pro_optimized_structured_data';

add_action('wp', __NAMESPACE__ . '\\prepare_schema_pro_product_markup', 20);
add_action('wp_footer', __NAMESPACE__ . '\\render_singular_product_schema', 5);
add_filter('woocommerce_structured_data_type_for_page', __NAMESPACE__ . '\\filter_woocommerce_schema_types', 20);
add_filter('wp_schema_pro_schema_enabled', __NAMESPACE__ . '\\filter_schema_pro_schema_enabled', 20, 3);
add_filter('wp_schema_pro_comment_before_markup_enabled', __NAMESPACE__ . '\\filter_schema_pro_comments', 20);

/**
 * Drop stale Schema Pro Product cache once so its schema-level filter can
 * regenerate the page while preserving any unrelated FAQ or Video schema.
 */
function prepare_schema_pro_product_markup(): void {
    if (!is_singular('product') || !class_exists('BSF_AIOSRS_Pro_Markup')) {
        return;
    }

    $post_id = get_queried_object_id();
    $cached  = get_post_meta($post_id, SCHEMA_PRO_CACHE_KEY, true);

    if (is_string($cached) && schema_pro_cache_requires_refresh($cached)) {
        delete_post_meta($post_id, SCHEMA_PRO_CACHE_KEY);
    }
}

/**
 * Does a cached Schema Pro payload contain [] or a root Product node?
 */
function schema_pro_cache_requires_refresh(string $markup): bool {
    if (!preg_match_all(
        '~<script\b[^>]*type=["\']application/ld\+json["\'][^>]*>(.*?)</script>~is',
        $markup,
        $matches
    )) {
        return false;
    }

    foreach ($matches[1] as $json) {
        $decoded = json_decode(
            html_entity_decode(trim($json), ENT_QUOTES | ENT_HTML5),
            true
        );

        if (json_last_error() !== JSON_ERROR_NONE) {
            continue;
        }
        if ($decoded === []) {
            return true;
        }

        $roots = is_array($decoded) && isset($decoded['@graph']) && is_array($decoded['@graph'])
            ? $decoded['@graph']
            : (is_array($decoded) && array_is_list($decoded) ? $decoded : [$decoded]);

        foreach ($roots as $root) {
            $types = is_array($root) ? (array) ($root['@type'] ?? []) : [];
            if (in_array('Product', $types, true)) {
                return true;
            }
        }
    }

    return false;
}

/**
 * Schema Pro remains active on product pages for non-Product schema types.
 */
function filter_schema_pro_schema_enabled(bool $enabled, int $post_id, string $schema_type): bool {
    if (is_singular('product') && $schema_type === 'product') {
        return false;
    }

    return $enabled;
}

/**
 * Avoid a dangling Schema Pro comment when its Product rule is disabled.
 */
function filter_schema_pro_comments(bool $enabled): bool {
    return is_singular('product') ? false : $enabled;
}

/**
 * Render the canonical Product node for the current WooCommerce product.
 */
function render_singular_product_schema(): void {
    if (!is_singular('product') || !function_exists('wc_get_product')) {
        return;
    }

    $product = wc_get_product(get_queried_object_id());
    if (!$product instanceof \WC_Product) {
        return;
    }

    $machine = get_machine_product_data($product->get_slug());
    $schema  = is_array($machine)
        ? build_machine_product_schema($product, $machine)
        : build_woocommerce_product_schema($product);

    if ($schema === null) {
        return;
    }

    echo '<script type="application/ld+json">'
        . wp_json_encode($schema, \Standard\MachineSchema\SCHEMA_JSON_FLAGS)
        . '</script>' . "\n";
}

/**
 * Build the fields shared by every Product node.
 */
function build_base_product_schema(
    \WC_Product $product,
    string $description,
    string $brand = 'New Tech Machinery'
): array {
    $permalink      = get_permalink($product->get_id());
    $organization_id = home_url('/') . '#organization';
    $schema         = [
        '@context'     => 'https://schema.org',
        '@type'        => 'Product',
        '@id'          => $permalink . '#product',
        'name'         => wp_strip_all_tags($product->get_name()),
        'url'          => $permalink,
        'brand'        => [
            '@type' => 'Brand',
            'name'  => $brand,
        ],
        'manufacturer' => [
            '@id' => $organization_id,
        ],
    ];

    $description = wp_strip_all_tags($description);
    if ($description !== '') {
        $schema['description'] = $description;
    }

    $image = wp_get_attachment_url($product->get_image_id());
    if (is_string($image) && $image !== '') {
        $schema['image'] = $image;
    }

    $sku = $product->get_sku();
    if ($sku !== '') {
        $schema['sku'] = $sku;
    }

    return $schema;
}

/**
 * Build Product schema from the curated machine data source.
 */
function build_machine_product_schema(\WC_Product $product, array $machine): array {
    $overrides = $machine['schema'] ?? [];
    $hero      = $machine['hero'] ?? [];
    $specs     = $machine['specs'] ?? [];
    $schema    = build_base_product_schema(
        $product,
        (string) ($hero['subtitle'] ?? $product->get_short_description()),
        (string) ($overrides['brand'] ?? 'New Tech Machinery')
    );

    // Curated machine hero media is the fallback when WooCommerce has no
    // featured image.
    if (!isset($schema['image']) && !empty($hero['image'])) {
        $schema['image'] = $hero['image'];
    }

    if (!empty($overrides['category'])) {
        $schema['category'] = $overrides['category'];
    }

    if (($overrides['availability'] ?? '') === 'Discontinued') {
        $schema['offers'] = [
            '@type'        => 'Offer',
            'availability' => 'https://schema.org/Discontinued',
            'url'          => get_permalink($product->get_id()),
        ];
    } elseif (!empty($overrides['low_price'])) {
        $offer = [
            '@type'         => 'AggregateOffer',
            'priceCurrency' => 'USD',
            'lowPrice'      => $overrides['low_price'],
        ];
        if (!empty($overrides['high_price'])) {
            $offer['highPrice'] = $overrides['high_price'];
        }
        $schema['offers'] = $offer;
    }

    $sku = $product->get_sku();
    $schema['model'] = $sku !== ''
        ? $sku
        : wp_strip_all_tags($product->get_name());

    $properties = build_additional_properties($specs, $machine);
    if ($properties !== []) {
        $schema['additionalProperty'] = $properties;
    }

    return $schema;
}

/**
 * @return array<int, array>
 */
function build_additional_properties(array $specs, array $machine): array {
    $props = [];

    $dims = $specs['dimensions']['machine'] ?? [];
    if (!empty($dims['weight'])) {
        $props[] = property_value('Weight', $dims['weight']);
    }
    if (!empty($dims['length'])) {
        $props[] = property_value('Length', $dims['length']);
    }

    $performance = $specs['performance'] ?? [];
    if (!empty($performance['shear']['type'])) {
        $props[] = property_value('Shear Type', $performance['shear']['type']);
    }
    if (!empty($performance['drive']['type'])) {
        $props[] = property_value('Drive Type', $performance['drive']['type']);
    }
    if (!empty($performance['speed'][0]['rate'])) {
        $props[] = property_value('Max Speed', $performance['speed'][0]['rate']);
    }

    foreach (($machine['stats'] ?? []) as $stat) {
        $props[] = property_value((string) $stat['label'], $stat['value'] ?? '');
    }

    foreach (($specs['materials'] ?? []) as $material) {
        $props[] = property_value(
            'Material: ' . (string) $material['name'],
            $material['gauge'] ?? ''
        );
    }

    if (!empty($specs['warranty']['description'])) {
        $props[] = property_value('Warranty', $specs['warranty']['description']);
    }

    return $props;
}

/**
 * Curated machine values may be numeric, so normalize them at the boundary.
 */
function property_value(string $name, string|int|float $value): array {
    return [
        '@type' => 'PropertyValue',
        'name'  => $name,
        'value' => (string) $value,
    ];
}

/**
 * Build Product schema for accessories and any future WooCommerce product
 * without a curated machine data file.
 *
 * WooCommerce products without prices still get valid entity markup. An
 * Offer is added only when WooCommerce has a real active price.
 */
function build_woocommerce_product_schema(\WC_Product $product): array {
    $permalink      = get_permalink($product->get_id());
    $organization_id = home_url('/') . '#organization';
    $description    = wp_strip_all_tags(
        $product->get_short_description() ?: $product->get_description()
    );
    $schema         = build_base_product_schema($product, $description);

    $categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
    if (!is_wp_error($categories) && $categories !== []) {
        $schema['category'] = implode(' > ', array_map('strval', $categories));
    }

    $price = $product->get_price();
    if ($price !== '') {
        $schema['offers'] = [
            '@type'         => 'Offer',
            'price'         => (string) $price,
            'priceCurrency' => get_woocommerce_currency(),
            'availability'  => stock_status_url($product->get_stock_status()),
            'itemCondition' => 'https://schema.org/NewCondition',
            'url'           => $permalink,
            'seller'        => [
                '@id' => $organization_id,
            ],
        ];
    }

    return $schema;
}

/**
 * Map WooCommerce stock states to Schema.org availability URLs.
 */
function stock_status_url(string $stock_status): string {
    $availability = match ($stock_status) {
        'instock'     => 'InStock',
        'onbackorder' => 'BackOrder',
        default       => 'OutOfStock',
    };

    return 'https://schema.org/' . $availability;
}

/**
 * WooCommerce normally prints Product JSON-LD in the footer. The theme owns
 * that node on singular product pages; legitimate Review nodes remain active.
 *
 * @param array<int, string> $types
 * @return array<int, string>
 */
function filter_woocommerce_schema_types(array $types): array {
    if (!is_singular('product')) {
        return $types;
    }

    return array_values(array_filter(
        $types,
        static fn (string $type): bool => $type !== 'product'
    ));
}
