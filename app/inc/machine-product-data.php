<?php
/**
 * Machine Product Landing Page Data – Loader
 *
 * Loads per-machine content from individual data files in app/data/machines/.
 * WooCommerce handles price, SKU, gallery, and cart.
 * This module provides the landing page narrative content.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineProductData;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * All recognised machine data-file keys.
 *
 * @return string[]
 */
function get_machine_data_keys(): array {
    return [
        'ssq3-multipro',
        'ssq-ii-multipro',
        'ssh-multipro',
        'ssr-multipro-jr',
        '5vc-5v-crimp',
        'wav-wall-panel',
        'mach-ii-5-gutter',
        'mach-ii-6-gutter',
        'mach-ii-combo-gutter',
        'bg7-box-gutter',
    ];
}

/**
 * Explicit WooCommerce slug → data key aliases.
 *
 * Maps product slugs that don't match data file keys.
 * Add entries here when WooCommerce product slugs differ
 * from the data file names in app/data/machines/.
 *
 * @return array<string, string>
 */
function get_slug_aliases(): array {
    return [
        'ssq-roof-panel-machine'            => 'ssq-ii-multipro',
        'ssq3-roof-panel-machine'           => 'ssq3-multipro',
        'ssh-roof-panel-machine'            => 'ssh-multipro',
        'ssr-roof-panel-machine'            => 'ssr-multipro-jr',
        'ssr-multipro-jr-roof-panel-machine' => 'ssr-multipro-jr',
        '5vc-5v-crimp-roof-panel-machine'   => '5vc-5v-crimp',
        'wav-wall-panel-machine'            => 'wav-wall-panel',
        'mach-ii-5-gutter-machine'          => 'mach-ii-5-gutter',
        'mach-ii-6-gutter-machine'          => 'mach-ii-6-gutter',
        'mach-ii-5-6-combo-gutter-machine'  => 'mach-ii-combo-gutter',
        'mach-ii-combo-gutter-machine'      => 'mach-ii-combo-gutter',
        'bg7-box-gutter-machine'            => 'bg7-box-gutter',
    ];
}

/**
 * Resolve a WooCommerce product slug to a machine data key.
 *
 * Tries: exact match → alias map → longest-prefix match.
 *
 * @param string $slug WooCommerce product slug.
 * @return string|null The matching machine key, or null.
 */
function resolve_machine_key(string $slug): ?string {
    $keys = get_machine_data_keys();
    if (in_array($slug, $keys, true)) {
        return $slug;
    }
    $aliases = get_slug_aliases();
    if (isset($aliases[$slug])) {
        return $aliases[$slug];
    }
    $sorted = $keys;
    usort($sorted, fn(string $a, string $b): int => strlen($b) - strlen($a));

    foreach ($sorted as $key) {
        if (str_starts_with($slug, $key)) {
            return $key;
        }
    }

    return null;
}

/**
 * Get landing page data for a specific machine by slug.
 *
 * @param string $slug WooCommerce product slug.
 * @return array|null Machine data or null if not found.
 */
function get_machine_product_data(string $slug): ?array {
    $key = resolve_machine_key($slug);

    if ($key !== null) {
        $file = get_template_directory() . '/data/machines/' . $key . '.php';

        if (file_exists($file)) {
            $data = require $file;

            if (is_array($data)) {
                $data = array_merge(get_default_machine_data(), $data);
                return filter_available_resources($data);
            }
        }
    }

    return null;
}

/**
 * Drop resource links that 404 on the current site.
 *
 * @param array<string, mixed> $data
 * @return array<string, mixed>
 */
function filter_available_resources(array $data): array {
    if (!isset($data['resources']) || !is_array($data['resources'])) {
        return $data;
    }

    foreach (['manual', 'brochure'] as $key) {
        $path = $data['resources'][$key] ?? '';
        if (!is_string($path) || $path === '') {
            continue;
        }
        if (!\Standard\Url\resource_path_resolves($path)) {
            unset($data['resources'][$key]);
        }
    }

    if ($data['resources'] === []) {
        $data['resources'] = null;
    }

    return $data;
}

/**
 * Default skeleton data for machines without dedicated data files.
 *
 * @return array
 */
function get_default_machine_data(): array {
    return [
        'hero'         => null,
        'stats'        => [],
        'finance'      => null,
        'breakdown'    => [],
        'fit'          => null,
        'blueprint'    => null,
        'gallery'      => null,
        'profiles'     => null,
        'accessories'  => null,
        'testimonials' => [],
        'comparison'   => null,
        'specs'        => null,
        'resources'    => null,
        'faq'          => [],
        'schema'       => null,
    ];
}

/**
 * Resolve a machine data key to its WooCommerce product permalink, name, and
 * featured image.
 *
 * @param string $machine_key Machine data key (e.g. 'mach-ii-5-gutter').
 * @param string $image_size  Image size for the featured-image URL.
 * @return array{url: string, name: string, image: string, image_alt: string}|null
 *               Product link data, or null if the product isn't found.
 *               `image` is '' when the product has no featured image.
 */
function get_machine_product_link(string $machine_key, string $image_size = 'woocommerce_thumbnail'): ?array {
    $aliases = get_slug_aliases();
    $reverse = array_flip($aliases);
    $slugs   = isset($reverse[$machine_key]) ? [$reverse[$machine_key]] : [$machine_key];

    if (!in_array($machine_key, $slugs, true)) {
        $slugs[] = $machine_key;
    }

    foreach ($slugs as $slug) {
        $posts = get_posts([
            'post_type'   => 'product',
            'name'        => $slug,
            'numberposts' => 1,
            'fields'      => 'ids',
        ]);

        if (!empty($posts)) {
            $product = wc_get_product($posts[0]);
            if ($product) {
                $image_id  = $product->get_image_id();
                $image_url = $image_id ? wp_get_attachment_image_url((int) $image_id, $image_size) : '';
                $image_alt = $image_id ? (string) get_post_meta((int) $image_id, '_wp_attachment_image_alt', true) : '';

                return [
                    'url'       => get_permalink($posts[0]),
                    'name'      => $product->get_name(),
                    'image'     => $image_url ?: '',
                    'image_alt' => $image_alt,
                ];
            }
        }
    }

    return null;
}
