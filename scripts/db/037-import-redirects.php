<?php
/**
 * Re-import repo-captured redirects (db/redirects.json) after a fresh prod pull.
 *
 * Runs via `wp eval-file <this> <path-to-redirects.json>` so it can use the
 * Redirection plugin's real PHP API (Red_Item) instead of hand-written SQL.
 *
 * The JSON is the plugin's native export. A fresh prod DB already contains
 * most of these rows, and `wp redirection import` does NOT dedupe — so this
 * checks each entry by source URL first and only creates the missing ones.
 *
 * IDEMPOTENT: skip-if-source-url-exists; re-runs create nothing.
 *
 * No declare(strict_types) here: eval-file eval()s the code, where a declare
 * is a parse error.
 *
 * @package Standard
 */

if (!defined('WP_CLI') || !WP_CLI) {
    return;
}

if (!class_exists('Red_Item')) {
    WP_CLI::error('Redirection plugin is not active — activate it before importing redirects.');
}

$path = (string) ($args[0] ?? '');
if ($path === '' || !is_readable($path)) {
    WP_CLI::error("redirects.json not readable: {$path}");
}

$data = json_decode((string) file_get_contents($path), true);
if (!is_array($data) || !isset($data['redirects']) || !is_array($data['redirects'])) {
    WP_CLI::error('Unexpected JSON shape — expected a Redirection plugin export with a "redirects" array.');
}

global $wpdb;

$existing = array_fill_keys(array_map(
    'strval',
    $wpdb->get_col("SELECT url FROM {$wpdb->prefix}redirection_items")
), true);

$created = 0;
$skipped = 0;
$failed  = 0;

foreach ($data['redirects'] as $redirect) {
    $url = (string) ($redirect['url'] ?? '');
    if ($url === '') {
        continue;
    }

    if (isset($existing[$url])) {
        $skipped++;
        continue;
    }

    // Red_Item::create() takes the same shape the plugin's own JSON importer
    // feeds it; drop row-identity fields that must not carry across installs.
    unset($redirect['id'], $redirect['hits'], $redirect['last_access'], $redirect['position']);
    $redirect['status'] = !empty($redirect['enabled']) ? 'enabled' : 'disabled';

    $item = Red_Item::create($redirect);
    if (is_wp_error($item)) {
        $failed++;
        WP_CLI::warning("{$url}: " . $item->get_error_message());
        continue;
    }

    $existing[$url] = true;
    $created++;
    WP_CLI::log("    created {$url} -> " . (string) ($redirect['action_data']['url'] ?? '?'));
}

if ($failed > 0) {
    WP_CLI::error("Redirects: {$created} created, {$skipped} already present, {$failed} FAILED.");
}

WP_CLI::success("Redirects: {$created} created, {$skipped} already present.");
