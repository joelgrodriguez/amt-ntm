<?php
/**
 * Safe redirects for the retired /build-finance/ entry point.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\LegacyBuildFinance;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Resolve a legacy machine value to its canonical configurator path.
 */
function get_redirect_path(string $machine): string {
    $machine = \sanitize_key($machine);
    $url     = $machine !== ''
        ? \Standard\MachinesData\get_configurator_url($machine)
        : '';

    if ($url === '') {
        return '/configurator/';
    }

    $path = (string) \wp_parse_url($url, PHP_URL_PATH);

    return $path !== '' ? $path : '/configurator/';
}

/**
 * Redirect only the exact retired route. The new URL never carries query data.
 */
function redirect_request(): void {
    if (\is_admin()) {
        return;
    }

    $request_uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '';
    $path        = (string) \wp_parse_url($request_uri, PHP_URL_PATH);

    if (\untrailingslashit($path) !== '/build-finance') {
        return;
    }

    $machine = isset($_GET['machine']) && is_scalar($_GET['machine'])
        ? (string) \wp_unslash($_GET['machine'])
        : '';

    \wp_safe_redirect(\home_url(get_redirect_path($machine)), 301);
    exit;
}
\add_action('template_redirect', __NAMESPACE__ . '\\redirect_request', 1);
