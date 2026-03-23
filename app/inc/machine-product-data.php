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
 * Resolve a WooCommerce product slug to a machine data key.
 *
 * Tries an exact match first, then longest-prefix match.
 *
 * @param string $slug WooCommerce product slug.
 * @return string|null The matching machine key, or null.
 */
function resolve_machine_key(string $slug): ?string {
    $keys = get_machine_data_keys();

    // Exact match
    if (in_array($slug, $keys, true)) {
        return $slug;
    }

    // Longest-prefix match
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
                return array_merge(get_default_machine_data(), $data);
            }
        }
    }

    // Fallback: return default skeleton so all machines render the custom template
    return get_default_machine_data();
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
