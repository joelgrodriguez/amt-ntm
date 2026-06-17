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
