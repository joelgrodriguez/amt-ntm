<?php
/**
 * Curated llms.txt.
 *
 * Yoast's automatic file copied stale post content, obsolete URLs, and escaped
 * HTML into a public file with a long cache lifetime. The theme owns a small,
 * factual file instead and sources machine prices from the same data used by
 * the product pages and Product schema.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\LlmsTxt;

if (!defined('ABSPATH')) {
    exit;
}

const CONTENT_VERSION = '2026-07-29-v3';
const VERSION_OPTION  = 'standard_curated_llms_txt_version';

add_action('after_setup_theme', __NAMESPACE__ . '\\sync_curated_llms_txt', 30);
add_action('wpseo_llms_txt_population', __NAMESPACE__ . '\\sync_curated_llms_txt', PHP_INT_MAX);

/**
 * Build the public llms.txt body.
 */
function build_curated_llms_txt(string $base_url = ''): string
{
    $base_url = rtrim($base_url !== '' ? $base_url : home_url('/'), '/');

    $gutter_machines = [
        [
            'name'        => 'MACH II™ 5" Gutter Machine',
            'slug'        => 'mach-ii-5-gutter-machine',
            'description' => 'Portable machine for producing 5-inch K-style seamless gutters on site.',
        ],
        [
            'name'        => 'MACH II™ 6" Gutter Machine',
            'slug'        => 'mach-ii-6-gutter-machine',
            'description' => 'Portable machine for producing 6-inch K-style seamless gutters on site.',
        ],
        [
            'name'        => 'MACH II™ 5"/6" Combo Gutter Machine',
            'slug'        => 'mach-ii-5-6-combo-gutter-machine',
            'description' => 'Portable machine that produces both 5-inch and 6-inch K-style seamless gutters.',
        ],
        [
            'name'        => 'BG7™ Box Gutter Machine',
            'slug'        => 'bg7-box-gutter-machine',
            'description' => 'Commercial machine for producing 7-inch box gutter profiles.',
        ],
    ];

    $lines = [
        '# New Tech Machinery (NTM)',
        '',
        '> New Tech Machinery manufactures portable rollforming equipment for seamless gutters and metal roof and wall panels.',
        '',
        'Official website: ' . $base_url . '/',
        '',
        '## Sitemaps',
        '',
        '- [XML sitemap index](' . $base_url . '/sitemap_index.xml)',
        '- [Product sitemap](' . $base_url . '/product-sitemap.xml)',
        '',
        '## Product families',
        '',
        '- [Portable rollforming machines](' . $base_url . '/machines/)',
        '- [Seamless gutter machines](' . $base_url . '/seamless-gutter-machines/)',
        '- [MACH II gutter machine family](' . $base_url . '/machines/machii/)',
        '- [Roof and wall panel machines](' . $base_url . '/roof-wall-panel-machines/)',
        '',
        '## Seamless gutter machines',
        '',
    ];

    foreach ($gutter_machines as $machine) {
        $price = get_machine_starting_price($machine['slug']);
        $lines[] = sprintf(
            '- [%s](%s/machines/gutter-machines/%s/): %s%s',
            $machine['name'],
            $base_url,
            $machine['slug'],
            $machine['description'],
            $price !== '' ? ' Starting at ' . $price . ' USD.' : ''
        );
    }

    $lines = array_merge($lines, [
        '',
        '## Roof and wall panel machines',
        '',
        '- [SSQ3 MultiPro roof panel machine](' . $base_url . '/machines/roof-wall-panel-machines/ssq3-multipro/)',
        '- [SSQ II MultiPro roof panel machine](' . $base_url . '/machines/roof-wall-panel-machines/ssq-roof-panel-machine/)',
        '- [SSH roof panel machine](' . $base_url . '/machines/roof-wall-panel-machines/ssh-roof-panel-machine/)',
        '- [SSR MultiPro Jr. roof panel machine](' . $base_url . '/machines/roof-wall-panel-machines/ssr-multipro-jr-roof-panel-machine/)',
        '- [5VC-5V CRIMP roof panel machine](' . $base_url . '/machines/roof-wall-panel-machines/5vc-5v-crimp-roof-panel-machine/)',
        '- [WAV wall panel machine](' . $base_url . '/machines/roof-wall-panel-machines/wav-wall-panel-machine/)',
        '',
        '## Buyer and owner resources',
        '',
        '- [Choose a portable rollforming machine](' . $base_url . '/choose-your-machine/)',
        '- [Machine financing](' . $base_url . '/machines/leasing-financing/)',
        '- [Machine service and support](' . $base_url . '/service-hub/)',
        '- [Frequently asked questions](' . $base_url . '/faq/)',
        '- [Learning center](' . $base_url . '/learning-center/)',
        '- [Contact New Tech Machinery](' . $base_url . '/contact/)',
        '',
    ]);

    return implode("\n", $lines);
}

/**
 * Get a machine's current starting price from the canonical machine data.
 */
function get_machine_starting_price(string $slug): string
{
    if (!function_exists('\\Standard\\MachineProductData\\get_machine_product_data')) {
        return '';
    }

    $machine = \Standard\MachineProductData\get_machine_product_data($slug);
    $price   = is_array($machine) ? ($machine['schema']['low_price'] ?? '') : '';

    if (!is_numeric($price) || (float) $price <= 0) {
        return '';
    }

    return '$' . number_format((float) $price, 0, '.', ',');
}

/**
 * Keep the physical root file aligned with the curated source.
 *
 * Nginx serves this file directly, so a template route cannot reliably replace
 * it. Atomic replacement keeps readers from seeing a partial write.
 */
function sync_curated_llms_txt(): bool
{
    $path          = rtrim(ABSPATH, '/\\') . '/llms.txt';
    $current       = is_readable($path) ? file_get_contents($path) : false;
    $current_site  = rtrim(home_url('/'), '/') . '/';
    $site_marker   = 'Official website: ' . $current_site;
    $is_our_file   = is_string($current)
        && str_starts_with($current, '# New Tech Machinery (NTM)')
        && str_contains($current, $site_marker);

    if ($is_our_file && get_option(VERSION_OPTION, '') === CONTENT_VERSION) {
        delete_option('wpseo_llms_txt_content_hash');
        return true;
    }

    $content = build_curated_llms_txt();

    if ($current === $content) {
        update_option(VERSION_OPTION, CONTENT_VERSION);
        delete_option('wpseo_llms_txt_content_hash');
        return true;
    }

    $temporary_path = $path . '.ntm-tmp';
    $bytes_written  = @file_put_contents($temporary_path, $content, LOCK_EX);

    if ($bytes_written !== strlen($content)) {
        @unlink($temporary_path);
        error_log('New Tech Machinery could not update llms.txt.');
        return false;
    }

    if (!@rename($temporary_path, $path)) {
        @unlink($temporary_path);
        error_log('New Tech Machinery could not replace llms.txt.');
        return false;
    }

    @chmod($path, 0644);
    update_option(VERSION_OPTION, CONTENT_VERSION);

    // A blank Yoast ownership hash prevents its weekly generator from
    // overwriting this manually curated file.
    delete_option('wpseo_llms_txt_content_hash');

    return true;
}
