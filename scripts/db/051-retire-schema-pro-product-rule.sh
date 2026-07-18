#!/usr/bin/env bash
#
# Draft Schema Pro's generic Product rule and purge Product-rule cache rows.
#
# WHY THIS SCRIPT EXISTS: the theme now owns machine Product JSON-LD, WooCommerce
# owns normal product schema where it applies, and Schema Pro should stay active
# for FAQ blocks and useful VideoObject output. The generic Schema Pro "Product"
# rule is DB-stored, so a fresh DB pull would resurrect duplicate or empty
# Product cache output unless the retirement is captured here.
#
# WHAT IT DOES: resolves exactly one Schema Pro source post by title "Product",
# post_type aiosrs-schema, schema type product, and exact product|all targeting.
# It drafts that source post only when it is published, then purges only Schema
# Pro optimized cache rows that are Product-rule artifacts: parsed JSON-LD with
# a root/direct @type Product and no FAQPage/VideoObject, or product posts whose
# cached JSON-LD is the empty array [].
#
# SCOPE: Schema Pro stays active. FAQ, Article, Video, and custom rules/posts are
# untouched. Cache rows containing FAQPage or VideoObject are explicitly
# preserved, even when they also contain Product text.
#
# LOCAL DB EVIDENCE (read-only, 2026-07-18): "Product" is post 4656 with
# bsf-aiosrs-schema-type=product and bsf-aiosrs-schema-location rule product|all.
# wp_schema_pro_optimized_structured_data currently has 6 Product-only rows and
# 72 product-post empty [] rows to purge; 1 homepage row with Product+FAQPage+
# VideoObject and FAQ/Video rows are protected.
#
# SAFE BY DESIGN: DRY_RUN=1 by default; set DRY_RUN=0 to write. Re-runs no-op
# after the rule is draft and matching cache rows are gone. Cache rows are
# deleted by meta_id, not by broad string replacement.
#
# Uses the direct docker exec/wp eval-file runner workaround from 041/042/050:
# the apply runner's exported wp() wrapper can mangle eval-file in child shells,
# so this script invokes wp eval-file directly when WP_CONTAINER is set.

# Deliberately NOT `set -e`: see 041. Preserve eval-file status explicitly.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-051-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

global $wpdb;

$schema_title = 'Product';
$schema_post_type = 'aiosrs-schema';
$schema_type_meta_key = 'bsf-aiosrs-schema-type';
$schema_location_meta_key = 'bsf-aiosrs-schema-location';
$product_meta_key = 'bsf-aiosrs-product';
$cache_meta_key = 'wp_schema_pro_optimized_structured_data';
$required_rule = 'product|all';
$protected_types = ['FAQPage', 'VideoObject'];

$schema_ids = $wpdb->get_col($wpdb->prepare(
    "SELECT ID FROM {$wpdb->posts} WHERE post_type = %s AND post_title = %s AND post_status <> 'trash' ORDER BY ID ASC",
    $schema_post_type,
    $schema_title
));

if (count($schema_ids) === 0) {
    echo "    skip: '{$schema_title}' ({$schema_post_type}) not found.\n";
    return;
}

if (count($schema_ids) > 1) {
    echo "    error: multiple '{$schema_title}' {$schema_post_type} posts found: " . implode(', ', $schema_ids) . ". Refusing to guess.\n";
    exit(1);
}

$schema_id = (int) $schema_ids[0];
$schema_post = get_post($schema_id);
$schema_status = $schema_post instanceof WP_Post ? $schema_post->post_status : 'missing';
$schema_type = (string) get_post_meta($schema_id, $schema_type_meta_key, true);

if ($schema_type !== 'product') {
    echo "    skip: '{$schema_title}' (post {$schema_id}) schema type is '{$schema_type}', not product.\n";
    return;
}

$location = get_post_meta($schema_id, $schema_location_meta_key, true);
$rules = is_array($location) && isset($location['rule'])
    ? array_values(array_map('strval', (array) $location['rule']))
    : [];
$specific = is_array($location) && isset($location['specific'])
    ? array_values((array) $location['specific'])
    : [];

if (count($rules) !== 1 || $rules[0] !== $required_rule || $specific !== []) {
    echo "    skip: '{$schema_title}' (post {$schema_id}) targeting is not exactly {$required_rule} with no specifics.\n";
    return;
}

$product_map = get_post_meta($schema_id, $product_meta_key, true);
if (!is_array($product_map) || $product_map === []) {
    echo "    skip: '{$schema_title}' (post {$schema_id}) has no {$product_meta_key} map.\n";
    return;
}

if (!in_array($schema_status, ['publish', 'draft'], true)) {
    echo "    skip: '{$schema_title}' (post {$schema_id}) status is {$schema_status}, not publish/draft.\n";
    return;
}

echo "    matched schema: '{$schema_title}' (post {$schema_id}, status {$schema_status}, target {$required_rule}).\n";

if ($schema_status === 'draft') {
    echo "    skip: schema post {$schema_id} already draft.\n";
} elseif ($dry) {
    echo "    [dry-run] would draft schema post {$schema_id}.\n";
} else {
    $result = wp_update_post(['ID' => $schema_id, 'post_status' => 'draft'], true);

    if (is_wp_error($result)) {
        echo "    error: could not draft schema post {$schema_id}: " . $result->get_error_message() . "\n";
        exit(1);
    }

    clean_post_cache($schema_id);
    echo "    drafted schema post {$schema_id}.\n";
}

$jsonld_scripts = static function (string $value): array {
    if (!preg_match_all('~<script\b[^>]*application/ld\+json[^>]*>(.*?)</script>~is', $value, $matches)) {
        return [];
    }

    return array_map(
        static fn (string $json): string => html_entity_decode(trim($json), ENT_QUOTES | ENT_HTML5),
        $matches[1]
    );
};

$collect_types = static function ($node, array &$types) use (&$collect_types): void {
    if (!is_array($node)) {
        return;
    }

    if (isset($node['@type'])) {
        foreach ((array) $node['@type'] as $type) {
            $types[(string) $type] = true;
        }
    }

    foreach ($node as $child) {
        $collect_types($child, $types);
    }
};

$array_is_list = static function (array $value): bool {
    if ($value === []) {
        return true;
    }

    return array_keys($value) === range(0, count($value) - 1);
};

$collect_direct_types = static function ($node, array &$types) use ($array_is_list): void {
    if (!is_array($node)) {
        return;
    }

    if (isset($node['@type'])) {
        foreach ((array) $node['@type'] as $type) {
            $types[(string) $type] = true;
        }
        return;
    }

    if (!$array_is_list($node)) {
        return;
    }

    foreach ($node as $child) {
        if (!is_array($child) || !isset($child['@type'])) {
            continue;
        }

        foreach ((array) $child['@type'] as $type) {
            $types[(string) $type] = true;
        }
    }
};

$cache_profile = static function (string $value) use ($jsonld_scripts, $collect_types, $collect_direct_types): array {
    $scripts = $jsonld_scripts($value);
    $types = [];
    $direct_types = [];
    $decoded_values = [];

    foreach ($scripts as $json) {
        $decoded = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            continue;
        }

        $decoded_values[] = $decoded;
        $collect_types($decoded, $types);
        $collect_direct_types($decoded, $direct_types);
    }

    return [
        'types' => array_keys($types),
        'direct_types' => array_keys($direct_types),
        'empty_jsonld_array' => count($decoded_values) === 1 && $decoded_values[0] === [],
    ];
};

$cache_rows = $wpdb->get_results($wpdb->prepare(
    "SELECT pm.meta_id, pm.post_id, p.post_type, p.post_title, pm.meta_value
       FROM {$wpdb->postmeta} pm
       LEFT JOIN {$wpdb->posts} p ON p.ID = pm.post_id
      WHERE pm.meta_key = %s
      ORDER BY pm.post_id ASC, pm.meta_id ASC",
    $cache_meta_key
));

$candidates = [];
$protected = 0;
$reason_counts = [];

foreach ($cache_rows as $cache_row) {
    $value = (string) $cache_row->meta_value;
    $profile = $cache_profile($value);
    $type_lookup = array_fill_keys($profile['types'], true);
    $direct_type_lookup = array_fill_keys($profile['direct_types'], true);

    $has_protected_type = false;
    foreach ($protected_types as $protected_type) {
        if (isset($type_lookup[$protected_type]) || strpos($value, $protected_type) !== false) {
            $has_protected_type = true;
            break;
        }
    }

    if ($has_protected_type) {
        $protected++;
        continue;
    }

    $reason = '';
    if (isset($direct_type_lookup['Product'])) {
        $reason = 'Product JSON-LD';
    } elseif ((string) $cache_row->post_type === 'product' && $profile['empty_jsonld_array']) {
        $reason = 'product empty [] cache';
    }

    if ($reason === '') {
        continue;
    }

    $candidates[] = [
        'meta_id' => (int) $cache_row->meta_id,
        'post_id' => (int) $cache_row->post_id,
        'post_type' => (string) $cache_row->post_type,
        'post_title' => (string) $cache_row->post_title,
        'reason' => $reason,
    ];
    $reason_counts[$reason] = ($reason_counts[$reason] ?? 0) + 1;
}

if ($protected > 0) {
    echo "    preserved {$protected} cache row(s) containing FAQPage or VideoObject.\n";
}

if ($candidates === []) {
    echo "    nothing to do: no targeted {$cache_meta_key} rows found.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

ksort($reason_counts);
foreach ($reason_counts as $reason => $count) {
    echo "    matched {$count} {$cache_meta_key} row(s): {$reason}.\n";
}

$preview = array_slice($candidates, 0, 10);
echo "    first targeted cache rows: "
    . implode(', ', array_map(
        static fn (array $row): string => "{$row['post_id']}/{$row['meta_id']}",
        $preview
    ))
    . ".\n";

if ($dry) {
    echo "    [dry-run] would delete " . count($candidates) . " targeted {$cache_meta_key} row(s).\n";
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
