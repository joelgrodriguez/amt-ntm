<?php
/**
 * Machine lifecycle status and replacement paths.
 *
 * Keeps lifecycle messaging and sales routes in one place so pages, search,
 * and machine-readable output cannot drift apart.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\MachineStatus;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * @return array<string, array{state:string,label:string,short_label:string,deadline:string,retired_configurator_path:string,replacement_key:string,replacement_name:string,replacement_url:string,replacement_configurator_url:string}>
 */
function get_statuses(): array {
    return [
        'ssq-ii-multipro' => [
            'state'                        => 'discontinued',
            'label'                        => __('Discontinued September 30, 2026', 'standard'),
            'short_label'                  => __('Discontinued', 'standard'),
            'deadline'                     => '2026-09-30',
            'retired_configurator_path'    => '/configurator/ssqii/',
            'replacement_key'              => 'ssq3-multipro',
            'replacement_name'             => 'SSQ3 MultiPro',
            'replacement_url'              => '/machines/roof-wall-panel-machines/ssq3-multipro/',
            'replacement_configurator_url' => '/configurator/ssq3-multi-pro/',
        ],
    ];
}

/**
 * Resolve data, WooCommerce, profile-tag, and configurator slugs.
 */
function resolve_machine_key(string $slug): string {
    if (function_exists('Standard\\MachineProductData\\resolve_machine_key')) {
        $resolved = \Standard\MachineProductData\resolve_machine_key($slug);
        if (is_string($resolved) && $resolved !== '') {
            return $resolved;
        }
    }

    $aliases = [
        'ssq-roof-panel-machine'                 => 'ssq-ii-multipro',
        'ssq-ii-multipro-roof-panel-machine'     => 'ssq-ii-multipro',
        'ssqii'                                  => 'ssq-ii-multipro',
        'ssq2'                                   => 'ssq-ii-multipro',
    ];

    return $aliases[$slug] ?? $slug;
}

/**
 * @return array{state:string,label:string,short_label:string,deadline:string,retired_configurator_path:string,replacement_key:string,replacement_name:string,replacement_url:string,replacement_configurator_url:string}|null
 */
function get_status(string $slug): ?array {
    return get_statuses()[resolve_machine_key($slug)] ?? null;
}

function has_status(string $slug): bool {
    return get_status($slug) !== null;
}

function is_discontinued(string $slug): bool {
    return (get_status($slug)['state'] ?? '') === 'discontinued';
}

function get_replacement_url(string $slug): string {
    $status = get_status($slug);
    if ($status === null) {
        return '';
    }

    return \Standard\Url\internal($status['replacement_url']);
}

function get_replacement_configurator_url(string $slug): string {
    $status = get_status($slug);
    if ($status === null) {
        return '';
    }

    return \Standard\Url\internal($status['replacement_configurator_url']);
}

function get_replacement_name(string $slug): string {
    return get_status($slug)['replacement_name'] ?? '';
}

/**
 * Resolve where a retired configurator URL now leads, or '' when the path is
 * not retired. Scalar query parameters carry over so campaign tracking on old
 * links still reaches the replacement configurator.
 *
 * @param array<array-key, mixed> $query
 */
function get_retired_configurator_redirect(string $request_path, array $query): string {
    $request_path = '/' . trim(strtolower($request_path), '/') . '/';

    foreach (get_statuses() as $status) {
        if ($status['state'] !== 'discontinued' || $status['retired_configurator_path'] !== $request_path) {
            continue;
        }

        $target = \Standard\Url\internal($status['replacement_configurator_url']);
        $query  = array_filter($query, 'is_scalar');

        return $query === []
            ? $target
            : $target . '?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
    }

    return '';
}

/**
 * Send old buying links to the replacement configurator. Matching the request
 * path keeps the redirect working if the legacy WordPress page is unpublished.
 */
function redirect_retired_configurator(): void {
    $request_path = (string) wp_parse_url((string) ($_SERVER['REQUEST_URI'] ?? ''), PHP_URL_PATH);
    $home_path    = rtrim((string) wp_parse_url(home_url('/'), PHP_URL_PATH), '/');

    if ($home_path !== '' && str_starts_with($request_path, $home_path)) {
        $request_path = substr($request_path, strlen($home_path));
    }

    $target = get_retired_configurator_redirect($request_path, wp_unslash($_GET));
    if ($target === '') {
        return;
    }

    // Kinsta's edge cache ignores utm_* and click IDs in its cache key, so a
    // cached redirect would send every visitor to the first visitor's target.
    nocache_headers();
    wp_safe_redirect($target, 301);
    exit;
}
add_action('template_redirect', __NAMESPACE__ . '\\redirect_retired_configurator', 1);

/**
 * Match content whose subject is the SSQ II, without false positives such as
 * SSQ200, SSQ210A, or SSQ275 profile names.
 */
function title_mentions_discontinued_machine(string $title): bool {
    return preg_match('/\bSSQ\s*(?:II|2)\b/i', html_entity_decode($title, ENT_QUOTES, 'UTF-8')) === 1;
}

/**
 * Limit the resource notice to pages primarily about the affected model.
 * Passing compatibility mentions remain untouched.
 */
function is_focused_content(?int $post_id = null): bool {
    $post_id = $post_id ?? (int) get_the_ID();
    if ($post_id <= 0 || get_post_type($post_id) === 'product') {
        return false;
    }

    return title_mentions_discontinued_machine((string) get_the_title($post_id));
}

/**
 * Add the status notice to SSQ II WooCommerce tag archives while preserving
 * parts and accessories for current owners.
 */
function render_product_tag_archive_notice(): void {
    if (!function_exists('is_tax') || !is_tax('product_tag', ['ssq-ii', 'ssqii'])) {
        return;
    }

    get_template_part('templates/parts/machine-status-notice', null, [
        'machine_slug' => 'ssq-ii-multipro',
        'context'      => 'archive',
    ]);
}
add_action('woocommerce_archive_description', __NAMESPACE__ . '\\render_product_tag_archive_notice', 20);
