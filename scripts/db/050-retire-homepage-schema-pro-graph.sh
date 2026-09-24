#!/usr/bin/env bash
#
# Draft Schema Pro's invalid homepage custom graph and purge its homepage cache.
#
# WHY THIS SCRIPT EXISTS: "Home Page Custom Schema" is a Schema Pro
# custom-markup rule targeted to special-front. Its literal JSON-LD graph
# duplicates Yoast's homepage WebPage/BreadcrumbList and emits invalid Product,
# Review, VideoObject, and hidden FAQ markup. Schema Pro also stores rendered
# output in wp_schema_pro_optimized_structured_data, so drafting the source rule
# alone can leave the bad homepage graph live from cache.
#
# WHAT IT DOES: resolves the source by exact title + post_type, verifies it is
# Schema Pro custom markup, verifies the special-front targeting, verifies three
# graph signatures unique to this homepage payload, drafts that one rule, then
# deletes only the static front page's Schema Pro cache row when that row carries
# the same signatures.
#
# SCOPE: only "Home Page Custom Schema" (post_type aiosrs-schema) and only the
# front page's wp_schema_pro_optimized_structured_data row. Schema Pro stays
# active. FAQ, Article, Video, Product, and navigation rules are untouched.
#
# SAFE BY DESIGN: DRY_RUN=1 by default; set DRY_RUN=0 to write. Re-runs no-op
# after the schema post is already draft and the matching cache row is gone.
# The unique graph signatures are #home-featured-machines, #customer-reviews,
# and #brand-video.
#
# Uses the direct docker exec/wp eval-file runner workaround from 041/042: the
# apply runner's exported wp() wrapper can mangle eval-file in child shells, so
# this script invokes wp eval-file directly when WP_CONTAINER is set.

# Deliberately NOT `set -e`: see 041. Preserve eval-file status explicitly.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-050-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

global $wpdb;

$schema_title = 'Home Page Custom Schema';
$schema_post_type = 'aiosrs-schema';
$schema_type_meta_key = 'bsf-aiosrs-schema-type';
$schema_location_meta_key = 'bsf-aiosrs-schema-location';
$custom_markup_meta_key = 'bsf-aiosrs-custom-markup';
$cache_meta_key = 'wp_schema_pro_optimized_structured_data';
$required_location = 'special-front';
$signatures = [
    '#home-featured-machines',
    '#customer-reviews',
    '#brand-video',
];

$flatten = static function ($value) use (&$flatten): string {
    if (is_array($value)) {
        return implode("\n", array_map($flatten, $value));
    }

    if (is_object($value)) {
        return (string) wp_json_encode($value);
    }

    return (string) $value;
};

$missing_signatures = static function (string $text) use ($signatures): array {
    $missing = [];

    foreach ($signatures as $signature) {
        if (strpos($text, $signature) === false) {
            $missing[] = $signature;
        }
    }

    return $missing;
};

$delete_metadata_meta_ids = static function (int $post_id, string $meta_key, $meta_value) use ($wpdb): array {
    $table = _get_meta_table('post');

    if (!$table) {
        echo "    error: could not resolve WordPress postmeta table.\n";
        exit(1);
    }

    $type_column = sanitize_key('post_id');
    $id_column = 'meta_id';
    $post_id = absint($post_id);

    // Mirror delete_metadata(): callers pass slashed input; WP unslashes before
    // maybe_serialize() and the exact meta_value SQL predicate.
    $meta_key = wp_unslash($meta_key);
    $meta_value = maybe_serialize(wp_unslash($meta_value));

    $query = $wpdb->prepare(
        "SELECT {$id_column} FROM {$table} WHERE meta_key = %s AND {$type_column} = %d",
        $meta_key,
        $post_id
    );

    if ('' !== $meta_value && null !== $meta_value && false !== $meta_value) {
        $query .= $wpdb->prepare(' AND meta_value = %s', $meta_value);
    }

    return array_map('intval', $wpdb->get_col($query));
};

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

if ($schema_type !== 'custom-markup') {
    echo "    skip: '{$schema_title}' (post {$schema_id}) schema type is '{$schema_type}', not custom-markup.\n";
    return;
}

$location = get_post_meta($schema_id, $schema_location_meta_key, true);
$rules = is_array($location) && isset($location['rule']) ? array_map('strval', (array) $location['rule']) : [];

if (!in_array($required_location, $rules, true)) {
    echo "    skip: '{$schema_title}' (post {$schema_id}) is not targeted to {$required_location}.\n";
    return;
}

$markup = $flatten(get_post_meta($schema_id, $custom_markup_meta_key, true));
$missing = $missing_signatures($markup);

if ($missing) {
    echo "    skip: '{$schema_title}' (post {$schema_id}) is missing graph signatures: " . implode(', ', $missing) . ".\n";
    return;
}

echo "    matched schema: '{$schema_title}' (post {$schema_id}, status {$schema_status}, target {$required_location}).\n";
echo "    matched graph signatures: " . implode(', ', $signatures) . ".\n";

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

$front_id = (int) get_option('page_on_front');

if ($front_id <= 0) {
    echo "    skip: no static front page configured; no {$cache_meta_key} row purged.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$front_post = get_post($front_id);

if (!$front_post instanceof WP_Post) {
    echo "    skip: configured front page post {$front_id} not found; no {$cache_meta_key} row purged.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$cache_rows = $wpdb->get_results($wpdb->prepare(
    "SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_key = %s ORDER BY meta_id ASC",
    $front_id,
    $cache_meta_key
));

if (!$cache_rows) {
    echo "    skip: front page '{$front_post->post_title}' (post {$front_id}) has no {$cache_meta_key} rows.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$matching_cache_rows = [];

foreach ($cache_rows as $cache_row) {
    $cache_text = (string) $cache_row->meta_value;

    if (!$missing_signatures($cache_text)) {
        $matching_cache_rows[] = [
            'meta_id' => (int) $cache_row->meta_id,
            'raw_value' => $cache_text,
            'delete_value' => wp_slash($cache_text),
        ];
    }
}

if (!$matching_cache_rows) {
    echo "    skip: front page '{$front_post->post_title}' (post {$front_id}) cache has " . count($cache_rows) . " {$cache_meta_key} row(s), none with the targeted graph signatures.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$selected_meta_ids = array_map(static fn (array $row): int => $row['meta_id'], $matching_cache_rows);
$selected_meta_id_lookup = array_fill_keys($selected_meta_ids, true);
$delete_values = [];

foreach ($matching_cache_rows as $cache_row) {
    $matched_meta_ids = $delete_metadata_meta_ids($front_id, $cache_meta_key, $cache_row['delete_value']);

    if (!in_array($cache_row['meta_id'], $matched_meta_ids, true)) {
        echo "    error: delete_metadata predicate would not match selected cache meta_id {$cache_row['meta_id']}. Refusing to continue.\n";
        exit(1);
    }

    $unexpected_meta_ids = array_values(array_filter(
        $matched_meta_ids,
        static fn (int $meta_id): bool => !isset($selected_meta_id_lookup[$meta_id])
    ));

    if ($unexpected_meta_ids) {
        echo "    error: delete_metadata predicate for selected cache meta_id {$cache_row['meta_id']} would also match unexpected meta_id(s): " . implode(', ', $unexpected_meta_ids) . ". Refusing to continue.\n";
        exit(1);
    }

    $delete_values[hash('sha256', $cache_row['raw_value'])] = [
        'delete_value' => $cache_row['delete_value'],
        'meta_ids' => $matched_meta_ids,
    ];
}

echo "    verified delete_metadata predicate for " . count($matching_cache_rows) . " targeted front page cache row(s): meta_id " . implode(', ', $selected_meta_ids) . ".\n";

if ($dry) {
    echo "    [dry-run] would delete " . count($matching_cache_rows) . " of " . count($cache_rows) . " {$cache_meta_key} row(s) from front page '{$front_post->post_title}' (post {$front_id}).\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

$deleted_meta_ids = [];

foreach ($delete_values as $delete_candidate) {
    if (!delete_post_meta($front_id, $cache_meta_key, $delete_candidate['delete_value'])) {
        echo "    error: delete_post_meta failed for targeted cache meta_id(s): " . implode(', ', $delete_candidate['meta_ids']) . ".\n";
        exit(1);
    }

    $deleted_meta_ids = array_merge($deleted_meta_ids, $delete_candidate['meta_ids']);
}

$remaining_meta_ids = [];

foreach ($delete_values as $delete_candidate) {
    $remaining_meta_ids = array_merge(
        $remaining_meta_ids,
        $delete_metadata_meta_ids($front_id, $cache_meta_key, $delete_candidate['delete_value'])
    );
}

if ($remaining_meta_ids) {
    echo "    error: targeted cache meta_id(s) still match after delete: " . implode(', ', array_unique($remaining_meta_ids)) . ".\n";
    exit(1);
}

echo "    deleted " . count(array_unique($deleted_meta_ids)) . " targeted {$cache_meta_key} row(s) from front page '{$front_post->post_title}' (post {$front_id}).\n";
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
