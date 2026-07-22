#!/usr/bin/env bash
#
# Fix the static front page's Yoast SEO title, meta description, and Open Graph
# title/description.
#
# WHY THIS SCRIPT EXISTS: the homepage _yoast_wpseo_metadesc literally ends
# mid-sentence with "..." — that exact text ships to every SERP snippet and to
# the site-summary line of Yoast's generated /llms.txt. The SEO title also
# targets "Seamless Gutter Machines" while the H1 (and the actual brand-query
# demand in Search Console) is "Portable Rollforming Machines"; the category
# pages already own the gutter-machine queries. The Open Graph title/description
# are unset, so shares render as og:title "Home" with a scraped testimonial.
#
# WHAT IT DOES: resolves the static front page via page_on_front, then applies
# four GUARDED postmeta updates — each only overwrites when the current value is
# the exact known-stale string (or already the target, which no-ops). The two
# Open Graph keys are only written when currently empty. After any real write it
# purges the front page's wp_yoast_indexable row so Yoast rebuilds the rendered
# head from the new meta (Yoast serves head tags from indexables, not postmeta).
#
# SCOPE: only post_on_front's four Yoast meta keys and its single yoast_indexable
# row. og:image / og:image-id are left empty on purpose (Yoast falls back to the
# featured image). No other post, option, or plugin is touched.
#
# SAFE BY DESIGN: DRY_RUN=1 by default; set DRY_RUN=0 to write. Every write is
# guarded on the exact current value, so admin edits win and re-runs no-op. The
# indexable purge is Yoast's own recovery path (it recreates indexables on
# demand).
#
# Uses the direct docker exec/wp eval-file runner workaround from 041/042/050/052:
# the apply runner's exported wp() wrapper can mangle eval-file in child shells,
# so this script invokes wp eval-file directly when WP_CONTAINER is set.

# Deliberately NOT `set -e`: see 041. Preserve eval-file status explicitly.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-053-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

global $wpdb;

$front = (int) get_option('page_on_front');

if ($front <= 0) {
    echo "    skip: no static front page configured (page_on_front is 0).\n";
    return;
}

$new_description = 'New Tech Machinery builds portable rollforming machines for standing seam metal roofing and seamless gutters, so contractors form panels on-site, on demand.';

// Each entry: meta key => [stale value it must currently hold, target value,
// guard mode]. guard 'stale' overwrites only the known-stale string; guard
// 'empty' overwrites only an empty value. Either no-ops when already target.
$plan = [
    '_yoast_wpseo_title' => [
        'stale'  => 'Seamless Gutter Machines & Rollforming Equipment | %%sitename%%',
        'target' => 'Portable Rollforming Machines | %%sitename%%',
        'guard'  => 'stale',
    ],
    '_yoast_wpseo_metadesc' => [
        'stale'  => 'New Tech Machinery manufactures and sells portable rollforming equipment for standing seam metal roofing and the seamless gutter industries...',
        'target' => $new_description,
        'guard'  => 'stale',
    ],
    '_yoast_wpseo_opengraph-title' => [
        'stale'  => '',
        'target' => 'Portable Rollforming Machines | New Tech Machinery',
        'guard'  => 'empty',
    ],
    '_yoast_wpseo_opengraph-description' => [
        'stale'  => '',
        'target' => $new_description,
        'guard'  => 'empty',
    ],
];

$writes = 0;

foreach ($plan as $key => $spec) {
    $current = (string) get_post_meta($front, $key, true);

    if ($current === $spec['target']) {
        echo "    skip: {$key} already applied.\n";
        continue;
    }

    if ($spec['guard'] === 'stale' && $current !== $spec['stale']) {
        echo "    skip: {$key} is '{$current}', not the known-stale value; leaving unchanged.\n";
        continue;
    }

    if ($spec['guard'] === 'empty' && $current !== '') {
        echo "    skip: {$key} is non-empty ('{$current}'); refusing to clobber.\n";
        continue;
    }

    echo "    matched {$key} (guard {$spec['guard']}).\n";

    if ($dry) {
        echo "    [dry-run] would set {$key} to the new value.\n";
        $writes++;
        continue;
    }

    if (update_post_meta($front, $key, $spec['target']) === false) {
        // update_post_meta returns false when the value is unchanged, but we
        // already skipped that case above, so false here is a real failure.
        echo "    error: update_post_meta failed for {$key}.\n";
        exit(1);
    }

    $after = (string) get_post_meta($front, $key, true);
    if ($after !== $spec['target']) {
        echo "    error: {$key} did not hold the target value after update.\n";
        exit(1);
    }

    echo "    set {$key}.\n";
    $writes++;
}

if ($writes === 0) {
    echo "    nothing to do: all four Yoast meta values already applied.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

// Purge the front page's Yoast indexable so the rendered head rebuilds from the
// new postmeta. Yoast recreates indexables on demand; deleting is safe.
$table = $wpdb->prefix . 'yoast_indexable';
$table_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table));

if (!$table_exists) {
    echo "    warn: {$table} not found; skipping indexable purge (Yoast may rebuild lazily).\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$indexable_rows = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$table} WHERE object_id = %d AND object_type = %s",
    $front,
    'post'
));

if ($dry) {
    echo "    [dry-run] would set " . count(array_filter($plan)) . " meta key(s) and purge {$indexable_rows} {$table} row(s) for post {$front}.\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

$deleted = $wpdb->delete(
    $table,
    ['object_id' => $front, 'object_type' => 'post'],
    ['%d', '%s']
);

if ($deleted === false) {
    echo "    error: failed to purge {$table} row(s) for post {$front}.\n";
    exit(1);
}

echo "    purged {$deleted} {$table} row(s) for post {$front}; Yoast rebuilds the head on next request.\n";
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
