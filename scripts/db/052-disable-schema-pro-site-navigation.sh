#!/usr/bin/env bash
#
# Disable Schema Pro's stale global SiteNavigationElement menu mapping.
#
# WHY THIS SCRIPT EXISTS: Schema Pro stores its global schema producers in the
# wp-schema-pro-global-schemas option. The local DB currently has
# site-navigation-element => "42", pointing Schema Pro at the legacy WordPress
# "Primary" menu. The visible navigation is theme-owned and hardcoded in
# app/inc/desktop-nav.php and app/inc/mobile-nav.php, so this schema producer is
# stale and non-authoritative.
#
# WHAT IT DOES: reads wp-schema-pro-global-schemas, verifies the
# site-navigation-element entry still points to legacy menu ID 42, then sets only
# that one option entry to the admin UI's "--None--" value: an empty string. It
# preserves every other option entry as-is. It also deletes only Schema Pro
# optimized cache rows whose value actually contains SiteNavigationElement.
#
# SCOPE: Schema Pro stays active. FAQ, Article, VideoObject, Product-related
# source rules/migrations, WordPress content, plugin files, and visible
# hardcoded desktop/mobile navigation are untouched. If a cache row also carries
# SiteNavigationElement, the whole cache row is purged because Schema Pro
# regenerates active non-navigation schema without the stale navigation producer.
#
# LOCAL DB EVIDENCE (read-only, 2026-07-18): wp-schema-pro-global-schemas is an
# array with about-page=209, contact-page=4323, site-navigation-element=42,
# sitelink-search-box=1, and breadcrumb=1. Menu 42 is "Primary" with 22 items.
# wp_schema_pro_optimized_structured_data currently has 548 rows and 0 rows
# containing SiteNavigationElement; this purge path is still required for fresh
# DB pulls or production caches.
#
# SAFE BY DESIGN: DRY_RUN=1 by default; set DRY_RUN=0 to write. Re-runs no-op
# after the mapping is empty and matching cache rows are gone. The option update
# is guarded by the exact legacy menu ID and cache rows are deleted by meta_id.
#
# Uses the direct docker exec/wp eval-file runner workaround from 041/042/050/051:
# the apply runner's exported wp() wrapper can mangle eval-file in child shells,
# so this script invokes wp eval-file directly when WP_CONTAINER is set.

# Deliberately NOT `set -e`: see 041. Preserve eval-file status explicitly.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-052-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

global $wpdb;

$option_name = 'wp-schema-pro-global-schemas';
$nav_key = 'site-navigation-element';
$legacy_menu_id = '42';
$cache_meta_key = 'wp_schema_pro_optimized_structured_data';
$cache_needle = 'SiteNavigationElement';

$settings = get_option($option_name, null);
$cache_purge_safe = true;

if ($settings === null || $settings === false) {
    echo "    skip: {$option_name} option not found; no source mapping changed.\n";
} elseif (!is_array($settings)) {
    echo "    error: {$option_name} is not an array. Refusing to change an unexpected option shape.\n";
    exit(1);
} elseif (!array_key_exists($nav_key, $settings)) {
    echo "    skip: {$option_name}[{$nav_key}] is already missing; no source mapping changed.\n";
} elseif (!is_scalar($settings[$nav_key]) && $settings[$nav_key] !== null) {
    echo "    error: {$option_name}[{$nav_key}] has an unexpected non-scalar value. Refusing to guess.\n";
    exit(1);
} else {
    $current_menu_id = (string) $settings[$nav_key];

    if ($current_menu_id === '') {
        echo "    skip: {$option_name}[{$nav_key}] is already empty.\n";
    } elseif ($current_menu_id !== $legacy_menu_id) {
        echo "    skip: {$option_name}[{$nav_key}] is '{$current_menu_id}', not legacy menu {$legacy_menu_id}; leaving option unchanged.\n";
        echo "    skip: cache purge refused because a non-legacy SiteNavigationElement mapping may be intentional.\n";
        $cache_purge_safe = false;
    } else {
        $updated_settings = $settings;
        $updated_settings[$nav_key] = '';

        $preserved_keys = array_values(array_diff(array_keys($settings), [$nav_key]));

        echo "    matched legacy Schema Pro site navigation mapping: menu {$legacy_menu_id}.\n";
        echo "    preserved option entries: " . implode(', ', $preserved_keys) . ".\n";

        if ($dry) {
            echo "    [dry-run] would set {$option_name}[{$nav_key}] to an empty string.\n";
        } else {
            if (!update_option($option_name, $updated_settings)) {
                echo "    error: update_option failed for {$option_name}.\n";
                exit(1);
            }

            $after = get_option($option_name, null);
            if (!is_array($after) || (string) ($after[$nav_key] ?? null) !== '') {
                echo "    error: {$option_name}[{$nav_key}] was not empty after update.\n";
                exit(1);
            }

            foreach ($settings as $key => $value) {
                if ($key === $nav_key) {
                    continue;
                }

                if (!array_key_exists($key, $after) || $after[$key] !== $value) {
                    echo "    error: {$option_name}[{$key}] changed unexpectedly. Refusing to continue.\n";
                    exit(1);
                }
            }

            echo "    disabled {$option_name}[{$nav_key}] by setting it to an empty string.\n";
        }
    }
}

if (!$cache_purge_safe) {
    echo $dry ? "    set DRY_RUN=0 to apply after confirming the mapping is legacy menu {$legacy_menu_id}.\n" : '';
    return;
}

$cache_rows = $wpdb->get_results($wpdb->prepare(
    "SELECT pm.meta_id, pm.post_id, p.post_type, p.post_title, pm.meta_value
       FROM {$wpdb->postmeta} pm
       LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
      WHERE pm.meta_key = %s
        AND pm.meta_value LIKE %s
      ORDER BY pm.post_id ASC, pm.meta_id ASC",
    $cache_meta_key,
    '%' . $wpdb->esc_like($cache_needle) . '%'
));

$candidates = [];

foreach ($cache_rows as $cache_row) {
    $value = (string) $cache_row->meta_value;

    // MySQL LIKE can be case-insensitive depending on collation; this keeps the
    // final delete set tied to the exact schema type token.
    if (strpos($value, $cache_needle) === false) {
        continue;
    }

    $candidates[] = [
        'meta_id' => (int) $cache_row->meta_id,
        'post_id' => (int) $cache_row->post_id,
        'post_type' => (string) $cache_row->post_type,
        'post_title' => (string) $cache_row->post_title,
    ];
}

if ($candidates === []) {
    echo "    nothing to do: no {$cache_meta_key} rows contain {$cache_needle}.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$preview = array_slice($candidates, 0, 10);
echo "    targeted cache rows: "
    . implode(', ', array_map(
        static fn (array $row): string => "{$row['post_id']}/{$row['meta_id']}",
        $preview
    ))
    . ".\n";

if ($dry) {
    echo "    [dry-run] would delete " . count($candidates) . " {$cache_meta_key} row(s) containing {$cache_needle}.\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

$deleted = 0;
$failed = 0;

foreach ($candidates as $candidate) {
    if (delete_metadata_by_mid('post', $candidate['meta_id'])) {
        $deleted++;
        continue;
    }

    $failed++;
    echo "    error: could not delete targeted cache meta_id {$candidate['meta_id']} for post {$candidate['post_id']}.\n";
}

if ($failed > 0) {
    echo "    error: {$failed} targeted cache row(s) failed to delete.\n";
    exit(1);
}

echo "    deleted {$deleted} targeted {$cache_meta_key} row(s). Schema Pro regenerates only active rule output on next render.\n";
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  status=0
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "$WP_PHP_BIN" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container" || status=$?
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
  exit "$status"
else
  wp eval-file "$php_tmp"
fi
