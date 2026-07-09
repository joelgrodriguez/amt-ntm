<?php
/**
 * Disable redirects that shadow live content.
 *
 * Runs via `wp eval-file <this>` (no declare(strict_types): eval-file eval()s
 * the code, where a declare is a parse error; the file is trusted repo code).
 *
 * The production DB carries legacy Redirection rows written for the OLD
 * information architecture — e.g. /machines/manuals -> /learning-center/
 * resource/manuals/. Under the new theme those source paths are real,
 * published pages again, so the plugin (which matches before WP routes)
 * hijacks live content — and where the repo also captures the reverse
 * redirect, the two rows form an infinite 301 loop (observed on rehearsal:
 * /learning-center/resource/manuals/ <-> /machines/manuals).
 *
 * Rule: any ENABLED redirect whose source URL resolves to a published page
 * or post gets disabled. Sources that resolve to nothing (true legacy URLs)
 * are untouched. Regex rows are skipped — their source isn't a literal path.
 *
 * IDEMPOTENT: already-disabled rows are ignored; re-runs converge.
 *
 * @package Standard
 */

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

global $wpdb;

$rows = $wpdb->get_results(
    "SELECT id, url, action_data FROM {$wpdb->prefix}redirection_items
     WHERE status = 'enabled' AND regex = 0"
);

$disabled = 0;
$kept     = 0;

foreach ($rows as $row) {
    // Query-specific rows (e.g. /search-results/?_sf_s=... marketing
    // redirects) match surgically, not the whole path — keep them.
    if (strpos((string) $row->url, '?') !== false) {
        $kept++;
        continue;
    }

    $path = (string) parse_url((string) $row->url, PHP_URL_PATH);
    if ($path === '' || $path === '/') {
        $kept++;
        continue;
    }

    $post = get_page_by_path(trim($path, '/'), OBJECT, ['page', 'post']);
    if (!$post instanceof WP_Post || $post->post_status !== 'publish') {
        $kept++;
        continue;
    }

    $wpdb->update(
        "{$wpdb->prefix}redirection_items",
        ['status' => 'disabled'],
        ['id' => (int) $row->id]
    );
    $disabled++;
    WP_CLI::log("    disabled #{$row->id}  {$row->url}  (shadows {$post->post_type} '{$post->post_name}')");
}

if ($disabled > 0 && class_exists('Red_Module')) {
    Red_Module::flush_by_module(1); // rebuild the WordPress-module redirect cache
}

WP_CLI::success("Shadowing redirects: {$disabled} disabled, {$kept} kept.");
