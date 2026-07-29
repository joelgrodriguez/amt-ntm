<?php
/**
 * Site-wide search, entity, and anonymous-session corrections.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\SiteHealth;

if (!defined('ABSPATH')) {
    exit;
}

const COMPANY_NAME = 'New Tech Machinery';

add_filter('wpseo_title', __NAMESPACE__ . '\\filter_wpseo_title', 20);
add_filter('wpseo_metadesc', __NAMESPACE__ . '\\filter_wpseo_description', 20);
add_filter('wpseo_opengraph_title', __NAMESPACE__ . '\\filter_wpseo_title', 20);
add_filter('wpseo_opengraph_desc', __NAMESPACE__ . '\\filter_wpseo_description', 20);
add_filter('wpseo_twitter_title', __NAMESPACE__ . '\\filter_wpseo_title', 20);
add_filter('wpseo_twitter_description', __NAMESPACE__ . '\\filter_wpseo_description', 20);
add_filter('wpseo_schema_company_name', __NAMESPACE__ . '\\filter_yoast_company_name', 20);

add_action('after_setup_theme', __NAMESPACE__ . '\\synchronize_yoast_company_name', 40);

// PixelYourSite otherwise starts a native PHP session on every public request,
// defeating full-page caches for traffic attribution that can run client-side.
add_action('wp', __NAMESPACE__ . '\\disable_pixelyoursite_php_session', 0);
add_filter('pys_disabled_start_session_cookie', __NAMESPACE__ . '\\disable_pixelyoursite_session_cookie');

/**
 * Search metadata owned by the theme rather than a particular database copy.
 *
 * @return array{title: string, description: string}|null
 */
function get_current_metadata_override(): ?array
{
    $slug = '';

    if (is_singular('product')) {
        $slug = (string) get_post_field('post_name', get_queried_object_id());
    } elseif (is_page('seamless-gutter-machines')) {
        $slug = 'seamless-gutter-machines';
    }

    $metadata = [
        'mach-ii-5-gutter-machine' => [
            'title'       => 'MACH II 5" Seamless Gutter Machine | NTM',
            'description' => 'Produce 5-inch K-style seamless gutters on site with the portable MACH II 5 gutter machine. Review specifications, pricing, accessories, and support.',
        ],
        'mach-ii-6-gutter-machine' => [
            'title'       => 'MACH II 6" Seamless Gutter Machine | NTM',
            'description' => 'Produce 6-inch K-style seamless gutters on site with the portable MACH II 6 gutter machine. Review specifications, pricing, accessories, and support.',
        ],
        'bg7-box-gutter-machine' => [
            'title'       => 'BG7 7" Commercial Box Gutter Machine | NTM',
            'description' => 'Produce 7-inch commercial box gutters on site with the hydraulic BG7 gutter machine. Review profiles, specifications, pricing, accessories, and support.',
        ],
        'seamless-gutter-machines' => [
            'title'       => 'Portable Seamless Gutter Machines | NTM',
            'description' => 'Compare NTM portable seamless gutter machines for 5-inch and 6-inch K-style or 7-inch box gutters, including specifications, pricing, and support.',
        ],
    ];

    return $metadata[$slug] ?? null;
}

function filter_wpseo_title(string $title): string
{
    $metadata = get_current_metadata_override();
    return $metadata['title'] ?? $title;
}

function filter_wpseo_description(string $description): string
{
    $metadata = get_current_metadata_override();
    return $metadata['description'] ?? $description;
}

function filter_yoast_company_name(string $company_name): string
{
    return COMPANY_NAME;
}

/**
 * Correct Yoast's persisted entity name so admin previews and future exports
 * agree with the frontend filter.
 */
function synchronize_yoast_company_name(): void
{
    $titles = get_option('wpseo_titles', []);

    if (!is_array($titles) || ($titles['company_name'] ?? '') === COMPANY_NAME) {
        return;
    }

    $titles['company_name'] = COMPANY_NAME;
    update_option('wpseo_titles', $titles);
}

/**
 * Remove PixelYourSite's native PHP session callback before priority 10 runs.
 */
function disable_pixelyoursite_php_session(): void
{
    if (!function_exists('\\PixelYourSite\\PYS')) {
        return;
    }

    $pixel_your_site = \PixelYourSite\PYS();
    if (is_object($pixel_your_site)) {
        remove_action('wp', [$pixel_your_site, 'controllSessionStart'], 10);
    }
}

function disable_pixelyoursite_session_cookie(bool $disabled = false): bool
{
    return true;
}
