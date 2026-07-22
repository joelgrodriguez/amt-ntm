#!/usr/bin/env bash
#
# Optimize SEO titles + homepage meta for the machine-buyer keyword strategy.
#
# WHY THIS SCRIPT EXISTS (builds on db 053):
#   - Homepage title was narrowed to "Portable Rollforming Machines" (053),
#     which dropped the commercial category terms. GSC shows NTM is page-1 on
#     gutter terms, stranded on page 2 for panel terms, and only ~pos 17 for
#     the exact head term "portable rollforming machine". The homepage should
#     lead with that owned umbrella term AND signal both categories.
#   - The two category landing pages have EMPTY _yoast_wpseo_title, so Yoast
#     builds the tag from post_title + " - " + sitename. Both post_titles
#     already contain the brand, producing "New Tech Machinery Seamless Gutter
#     Machines - New Tech Machinery" (brand twice, keyword buried). An explicit
#     SEO-title override fixes the tag without changing the visible page/H1.
#
# WHAT IT DOES: guarded, idempotent Yoast postmeta updates on three posts —
#   1. Front page (page_on_front): retitle, rewrite meta description, and the
#      matching OG title/description. Guards expect the db-053 values as the
#      predecessor state (db:apply runs 053 before 054), so a fresh prod replay
#      lands 053's values then upgrades them here. Any other current value is
#      left untouched (admin edits win).
#   2. /seamless-gutter-machines/  -> explicit SEO title (guard: currently empty)
#   3. /roof-wall-panel-machines/  -> explicit SEO title (guard: currently empty)
# After any real write it purges each touched post's yoast_indexable row so the
# rendered head rebuilds from the new meta.
#
# SCOPE: only these three posts' Yoast title/description/OG meta and their
# yoast_indexable rows. Post IDs are resolved at runtime (never hardcoded).
# Visible post titles, H1s, page content, and all other posts are untouched.
#
# SAFE BY DESIGN: DRY_RUN=1 by default; set DRY_RUN=0 to write. Guards make it
# idempotent and non-clobbering.
#
# Uses the direct docker exec/wp eval-file runner workaround from 041/042/050/053.

# Deliberately NOT `set -e`: see 041. Preserve eval-file status explicitly.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-054-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

global $wpdb;

// db-053 predecessor values (this script upgrades them).
$desc_053 = 'New Tech Machinery builds portable rollforming machines for standing seam metal roofing and seamless gutters, so contractors form panels on-site, on demand.';
$title_053 = 'Portable Rollforming Machines | %%sitename%%';
$ogtitle_053 = 'Portable Rollforming Machines | New Tech Machinery';

// New targets.
$desc_new    = 'New Tech Machinery builds portable rollforming machines for seamless gutters and standing seam roof & wall panels — formed on-site, any length, on demand.';
$title_new   = 'Portable Rollforming Machines for Metal Roofing & Gutters | %%sitename%%';
$ogtitle_new = 'Portable Rollforming Machines for Metal Roofing & Gutters | New Tech Machinery';

// Each row: [post_id, meta_key, stale_expected, target, guard('stale'|'empty')].
$targets = [];

$front = (int) get_option('page_on_front');
if ($front <= 0) {
    echo "    warn: no static front page configured; homepage rows skipped.\n";
} else {
    $targets[] = [$front, '_yoast_wpseo_title',                 $title_053,   $title_new,   'stale'];
    $targets[] = [$front, '_yoast_wpseo_metadesc',              $desc_053,    $desc_new,    'stale'];
    $targets[] = [$front, '_yoast_wpseo_opengraph-title',       $ogtitle_053, $ogtitle_new, 'stale'];
    $targets[] = [$front, '_yoast_wpseo_opengraph-description', $desc_053,    $desc_new,    'stale'];
}

// Category landing pages — resolve by slug, override the empty SEO title.
$category_titles = [
    'seamless-gutter-machines' => 'Seamless Gutter Machines | %%sitename%%',
    'roof-wall-panel-machines' => 'Roof & Wall Panel Machines | %%sitename%%',
];
foreach ($category_titles as $slug => $title) {
    $page = get_page_by_path($slug);
    if (!$page instanceof WP_Post) {
        echo "    warn: page '{$slug}' not found; skipped.\n";
        continue;
    }
    $targets[] = [$page->ID, '_yoast_wpseo_title', '', $title, 'empty'];
}

$writes = 0;
$touched = [];

foreach ($targets as [$post_id, $key, $stale, $target, $guard]) {
    $current = (string) get_post_meta($post_id, $key, true);

    if ($current === $target) {
        echo "    skip: post {$post_id} {$key} already applied.\n";
        continue;
    }

    if ($guard === 'stale' && $current !== $stale) {
        echo "    skip: post {$post_id} {$key} is '{$current}', not the expected predecessor; leaving unchanged.\n";
        continue;
    }

    if ($guard === 'empty' && $current !== '') {
        echo "    skip: post {$post_id} {$key} is non-empty ('{$current}'); refusing to clobber.\n";
        continue;
    }

    echo "    matched post {$post_id} {$key} (guard {$guard}).\n";

    if ($dry) {
        echo "    [dry-run] would set post {$post_id} {$key}.\n";
        $writes++;
        $touched[$post_id] = true;
        continue;
    }

    if (update_post_meta($post_id, $key, $target) === false) {
        echo "    error: update_post_meta failed for post {$post_id} {$key}.\n";
        exit(1);
    }

    if ((string) get_post_meta($post_id, $key, true) !== $target) {
        echo "    error: post {$post_id} {$key} did not hold the target after update.\n";
        exit(1);
    }

    echo "    set post {$post_id} {$key}.\n";
    $writes++;
    $touched[$post_id] = true;
}

if ($writes === 0) {
    echo "    nothing to do: all target values already applied.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

// Purge each touched post's Yoast indexable so the head rebuilds from new meta.
$table = $wpdb->prefix . 'yoast_indexable';
$table_exists = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table));

if (!$table_exists) {
    echo "    warn: {$table} not found; skipping indexable purge (Yoast may rebuild lazily).\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

$post_ids = array_keys($touched);

if ($dry) {
    $count = 0;
    foreach ($post_ids as $pid) {
        $count += (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$table} WHERE object_id = %d AND object_type = %s", $pid, 'post'
        ));
    }
    echo "    [dry-run] would update {$writes} meta value(s) across " . count($post_ids) . " post(s) and purge {$count} {$table} row(s).\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

$purged = 0;
foreach ($post_ids as $pid) {
    $deleted = $wpdb->delete($table, ['object_id' => $pid, 'object_type' => 'post'], ['%d', '%s']);
    if ($deleted === false) {
        echo "    error: failed to purge {$table} row(s) for post {$pid}.\n";
        exit(1);
    }
    $purged += (int) $deleted;
}

echo "    updated {$writes} meta value(s) across " . count($post_ids) . " post(s); purged {$purged} {$table} row(s). Yoast rebuilds on next request.\n";
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
