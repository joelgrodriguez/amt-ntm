<?php
/**
 * URL helpers.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Url;

if (!defined('ABSPATH')) {
    exit;
}

function internal(string $path): string {
    $path = trim($path);

    if ($path === '' || str_starts_with($path, '#') || \wp_http_validate_url($path)) {
        return $path;
    }

    if (str_starts_with($path, 'mailto:') || str_starts_with($path, 'tel:')) {
        return $path;
    }

    return \home_url($path);
}

/**
 * @param array<string, scalar|null> $query
 */
function with_query(string $path, array $query): string {
    return \add_query_arg($query, internal($path));
}

/**
 * Rebase an absolute URL's host onto the current site.
 *
 * Curated data files (app/data/machines/*.php) hardcode the production host
 * (https://newtechmachinery.com/...). On any other environment that sends an
 * owner off-site. This keeps the path and swaps the host to home_url()'s, so a
 * link resolves on whatever site is serving it. The prod host is the canonical
 * source; the rebase only ever points "more local", never away.
 *
 * Non-newtechmachinery.com absolute URLs (e.g. a Wistia embed) and relative
 * paths are returned untouched — only the known prod host is rebased.
 */
/**
 * Whether an internal resource path resolves to a published post on this site.
 *
 * Machine data may reference literature/manual URLs before the posts exist, or
 * with a slug that drifted from the Learning Center export. Templates should
 * not surface brochure/manual CTAs when this returns false.
 */
function resource_path_resolves(string $path): bool {
    $path = trim($path);
    if ($path === '') {
        return false;
    }

    $url = canonical($path);
    if (!\wp_http_validate_url($url)) {
        $url = internal($path);
    }

    $post_id = \url_to_postid($url);
    if ($post_id <= 0) {
        return false;
    }

    return \get_post_status($post_id) === 'publish';
}

function canonical(string $url): string {
    $url = trim($url);
    if ($url === '' || !\wp_http_validate_url($url)) {
        return internal($url);
    }

    $host = \wp_parse_url($url, PHP_URL_HOST);
    if ($host === null || \stripos((string) $host, 'newtechmachinery.com') === false) {
        return $url;
    }

    $path  = (string) (\wp_parse_url($url, PHP_URL_PATH) ?? '/');
    $query = \wp_parse_url($url, PHP_URL_QUERY);
    $frag  = \wp_parse_url($url, PHP_URL_FRAGMENT);
    if ($query) {
        $path .= '?' . $query;
    }
    if ($frag) {
        $path .= '#' . $frag;
    }

    return \home_url($path);
}
