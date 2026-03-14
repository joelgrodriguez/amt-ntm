<?php
/**
 * Machine Product JSON-LD Schema Generator
 *
 * Generates Product + FAQPage structured data for machine product pages.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineSchema;

/**
 * Render Product + FAQPage JSON-LD schema for a machine product.
 *
 * @param \WC_Product $product  WooCommerce product object.
 * @param array       $machine  Machine data array.
 */
function render_machine_schema(\WC_Product $product, array $machine): void {
    $product_schema = build_product_schema($product, $machine);
    if ($product_schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($product_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }

    $faq_schema = build_faq_schema($machine);
    if ($faq_schema) {
        echo '<script type="application/ld+json">' . wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
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

    $schema = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->get_name(),
        'description' => $hero['subtitle'] ?? $product->get_short_description(),
        'url'         => get_permalink($product->get_id()),
        'image'       => wp_get_attachment_url($product->get_image_id()) ?: ($hero['image'] ?? ''),
        'brand'       => [
            '@type' => 'Brand',
            'name'  => $overrides['brand'] ?? 'New Tech Machinery',
        ],
        'manufacturer' => [
            '@type' => 'Organization',
            'name'  => $overrides['manufacturer'] ?? 'New Tech Machinery',
        ],
    ];

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
    if (!empty($perf['speed'][0]['value'])) {
        $props[] = pv('Max Speed', $perf['speed'][0]['value']);
    }

    foreach (($machine['stats'] ?? []) as $stat) {
        $props[] = pv($stat['label'], $stat['value']);
    }

    foreach (($specs['materials'] ?? []) as $mat) {
        $props[] = pv('Material: ' . $mat['type'], $mat['gauge']);
    }

    if (!empty($specs['warranty']['description'])) {
        $props[] = pv('Warranty', $specs['warranty']['description']);
    }

    return $props;
}

/**
 * @param string $name
 * @param string $value
 * @return array
 */
function pv(string $name, string $value): array {
    return [
        '@type' => 'PropertyValue',
        'name'  => $name,
        'value' => $value,
    ];
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
                'text'  => $faq['answer'],
            ],
        ];
    }

    return [
        '@context'   => 'https://schema.org',
        '@type'      => 'FAQPage',
        'mainEntity' => $entities,
    ];
}
