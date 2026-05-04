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
