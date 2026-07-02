<?php
/**
 * SEO Fallback — head meta + sitewide JSON-LD
 *
 * Yoast is expected on production and owns titles, meta, social tags, and
 * the Organization/WebSite schema graph when active. This module is the
 * safety net: if no SEO plugin is active, it emits a meta description,
 * canonical, Open Graph / Twitter tags, and a LocalBusiness + WebSite
 * JSON-LD graph so the site never ships with a bare <head>.
 *
 * HQ address/phone come from Standard\ContactData — never hardcode them here.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Seo;

if (!defined('ABSPATH')) {
    exit;
}

const SCHEMA_JSON_FLAGS = JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;

add_action('wp_head', __NAMESPACE__ . '\\render_head_tags', 5);

/**
 * True when a dedicated SEO plugin owns the document head.
 */
function seo_plugin_active(): bool
{
    return defined('WPSEO_VERSION')      // Yoast SEO
        || defined('RANK_MATH_VERSION')  // Rank Math
        || defined('SEOPRESS_VERSION');  // SEOPress
}

/**
 * Emit fallback meta description, canonical, OG/Twitter tags, and schema.
 */
function render_head_tags(): void
{
    if (seo_plugin_active()) {
        return;
    }

    $description = meta_description();
    $canonical   = canonical_url();
    $title       = wp_get_document_title();
    $image       = og_image();

    if ($description !== '') {
        echo '<meta name="description" content="' . esc_attr($description) . '">' . "\n";
    }

    if ($canonical !== '') {
        // Core hooks rel_canonical at priority 10; we run at 5, so this
        // removal prevents a duplicate <link rel="canonical"> on singular.
        remove_action('wp_head', 'rel_canonical');
        echo '<link rel="canonical" href="' . esc_url($canonical) . '">' . "\n";
    }

    echo '<meta property="og:type" content="' . (is_singular('post') ? 'article' : 'website') . '">' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '">' . "\n";
    if ($description !== '') {
        echo '<meta property="og:description" content="' . esc_attr($description) . '">' . "\n";
    }
    if ($canonical !== '') {
        echo '<meta property="og:url" content="' . esc_url($canonical) . '">' . "\n";
    }
    echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
    echo '<meta property="og:image" content="' . esc_url($image) . '">' . "\n";
    echo '<meta name="twitter:card" content="summary_large_image">' . "\n";

    render_site_schema();
}

/**
 * Best-available meta description for the current view.
 */
function meta_description(): string
{
    if (is_front_page()) {
        return trim((string) get_bloginfo('description'));
    }

    if (is_singular()) {
        $post = get_queried_object();
        if ($post instanceof \WP_Post) {
            $text = $post->post_excerpt !== '' ? $post->post_excerpt : $post->post_content;
            $text = wp_strip_all_tags(strip_shortcodes($text));
            return wp_trim_words($text, 30, '…');
        }
    }

    if (is_category() || is_tag() || is_tax()) {
        $term_desc = term_description();
        if (is_string($term_desc) && $term_desc !== '') {
            return wp_trim_words(wp_strip_all_tags($term_desc), 30, '…');
        }
    }

    return trim((string) get_bloginfo('description'));
}

/**
 * Canonical URL for views where one is unambiguous; '' otherwise.
 */
function canonical_url(): string
{
    if (is_singular()) {
        return (string) wp_get_canonical_url();
    }

    if (is_front_page()) {
        return home_url('/');
    }

    if (is_category() || is_tag() || is_tax()) {
        $link = get_term_link(get_queried_object());
        if (is_string($link)) {
            return $link;
        }
    }

    if (is_post_type_archive()) {
        $link = get_post_type_archive_link((string) get_query_var('post_type'));
        if (is_string($link)) {
            return $link;
        }
    }

    return '';
}

/**
 * Social share image: featured image, then site icon, then theme logo.
 */
function og_image(): string
{
    if (is_singular() && has_post_thumbnail()) {
        $url = get_the_post_thumbnail_url(null, 'full');
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    $icon = get_site_icon_url(512);
    if ($icon !== '') {
        return $icon;
    }

    return THEME_URI . '/assets/images/ntm-logo-cropped.png';
}

/**
 * LocalBusiness + WebSite JSON-LD graph, sourced from ContactData.
 *
 * LocalBusiness is an Organization subtype — the right shape for a
 * single-HQ manufacturer, and one node instead of two.
 */
function render_site_schema(): void
{
    $home = home_url('/');

    $business = [
        '@type' => 'LocalBusiness',
        '@id'   => $home . '#organization',
        'name'  => get_bloginfo('name'),
        'url'   => $home,
        'image' => og_image(),
    ];

    $logo_id = (int) get_theme_mod('custom_logo');
    $logo    = $logo_id ? (string) wp_get_attachment_url($logo_id) : '';
    $business['logo'] = $logo !== '' ? $logo : THEME_URI . '/assets/images/ntm-logo-cropped.png';

    $hq = \Standard\ContactData\get_locations()[0] ?? null;
    if ($hq !== null) {
        $address = parse_postal_address((string) ($hq['address_html'] ?? ''));
        if ($address !== null) {
            $business['address'] = $address;
        }
        $tel = $hq['phones'][0]['tel'] ?? '';
        if ($tel !== '') {
            $business['telephone'] = $tel;
        }
    }

    $website = [
        '@type'     => 'WebSite',
        '@id'       => $home . '#website',
        'url'       => $home,
        'name'      => get_bloginfo('name'),
        'publisher' => ['@id' => $home . '#organization'],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => [
                '@type'       => 'EntryPoint',
                'urlTemplate' => $home . '?s={search_term_string}',
            ],
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $graph = [
        '@context' => 'https://schema.org',
        '@graph'   => [$business, $website],
    ];

    echo '<script type="application/ld+json">' . wp_json_encode($graph, SCHEMA_JSON_FLAGS) . '</script>' . "\n";
}

/**
 * Turn ContactData's "street<br>City, State Zip" HTML into a PostalAddress.
 */
function parse_postal_address(string $address_html): ?array
{
    $lines = array_map(
        static fn(string $line): string => trim(wp_strip_all_tags($line)),
        preg_split('/<br\s*\/?>/i', $address_html) ?: []
    );

    if ($lines === [] || $lines[0] === '') {
        return null;
    }

    $address = [
        '@type'          => 'PostalAddress',
        'streetAddress'  => $lines[0],
        'addressCountry' => 'US',
    ];

    // "Aurora, Colorado 80011" → locality / region / postal code.
    if (isset($lines[1]) && preg_match('/^(.+?),\s*(.+?)\s+(\S+)$/', $lines[1], $m)) {
        $address['addressLocality'] = $m[1];
        $address['addressRegion']   = $m[2];
        $address['postalCode']      = $m[3];
    }

    return $address;
}
