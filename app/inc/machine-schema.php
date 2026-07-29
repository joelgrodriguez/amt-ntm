<?php
/**
 * Machine Supplemental Schema
 *
 * Builds the front-page machine ItemList and machine FAQPage data. Singular
 * Product output is owned by inc/product-schema.php.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineSchema;

if (!defined('ABSPATH')) {
    exit;
}

const SCHEMA_JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

add_action('wp_head', __NAMESPACE__ . '\\render_front_page_machine_list', 6);

/**
 * ItemList of the machine lineup, front page only.
 *
 * Supplements Yoast's homepage graph (WebPage/Organization only) with a
 * machine-readable statement of the product line. URL-reference ListItems
 * ONLY — full Product nodes live exclusively on the single-machine pages
 * (rendered by ProductSchema); embedding them here would duplicate Product
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
