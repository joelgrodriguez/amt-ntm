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
 * @return array<string, array{state:string,label:string,short_label:string,deadline:string,configurator_url:string,replacement_key:string,replacement_name:string,replacement_url:string}>
 */
function get_statuses(): array {
    return [
        'ssq-ii-multipro' => [
            'state'            => 'sunsetting',
            'label'            => __('Will Be Discontinued September 30, 2026', 'standard'),
            'short_label'      => __('Discontinuing Sep. 30, 2026', 'standard'),
            'deadline'         => '2026-09-30',
            'configurator_url' => '/configurator/ssqii/',
            'replacement_key'  => 'ssq3-multipro',
            'replacement_name' => 'SSQ3 MultiPro',
            'replacement_url'  => '/machines/roof-wall-panel-machines/ssq3-multipro/',
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
 * @return array{state:string,label:string,short_label:string,deadline:string,configurator_url:string,replacement_key:string,replacement_name:string,replacement_url:string}|null
 */
function get_status(string $slug): ?array {
    return get_statuses()[resolve_machine_key($slug)] ?? null;
}

function has_status(string $slug): bool {
    return get_status($slug) !== null;
}

function is_sunsetting(string $slug): bool {
    return (get_status($slug)['state'] ?? '') === 'sunsetting';
}

function is_discontinued(string $slug): bool {
    return (get_status($slug)['state'] ?? '') === 'discontinued';
}

function get_configurator_url(string $slug): string {
    $status = get_status($slug);
    if ($status === null || $status['configurator_url'] === '') {
        return '';
    }

    return \Standard\Url\internal($status['configurator_url']);
}

function get_replacement_url(string $slug): string {
    $status = get_status($slug);
    if ($status === null) {
        return '';
    }

    return \Standard\Url\internal($status['replacement_url']);
}

function get_replacement_name(string $slug): string {
    return get_status($slug)['replacement_name'] ?? '';
}

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
