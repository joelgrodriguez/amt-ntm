#!/usr/bin/env bash
#
# Purge the placeholder-bearing rows from Schema Pro's per-post output cache
# (meta key wp_schema_pro_optimized_structured_data).
#
# WHY THIS SCRIPT EXISTS: db 041 drafted the broken "Sitewide Custom Schema"
# source post, but WP Schema Pro caches its rendered JSON-LD per post in the
# wp_schema_pro_optimized_structured_data meta key, and drafting the source does
# NOT invalidate that cache. 788 posts (posts, videos, pages, products, profiles,
# manuals, ...) still carried the invalid placeholder block — containing
# NEWTECH-UPDATE sameAs URLs, path-to-logo, foundingDate "YYYY", and JS /* */
# comments inside JSON — so ~788 pages kept serving broken JSON-LD after 041.
# Deleting a cache row is safe: Schema Pro regenerates it from the live schema
# posts on next render, and the placeholder source is draft, so it cannot come
# back. Replayed at cutover via npm run db:apply — this is what fixes PROD.
#
# CANARY EVIDENCE (2026-07-10, post 18601 = flagship SSQ3 MultiPro product):
# deleted its cache row alone -> page returned 200 with zero NEWTECH-UPDATE
# hits, and the meta row regenerated on the next render WITHOUT the placeholder.
#
# WHAT IT DOES: selects post_ids whose wp_schema_pro_optimized_structured_data
# value contains NEWTECH-UPDATE (prepared $wpdb LIKE query), then deletes that
# one meta row per post. Needle-scoped: clean cache rows (e.g. the Home Page
# Custom Schema copies) do not match and are never touched. No other meta key,
# no schema posts, no plugin settings.
#
# SAFE BY DESIGN: strictly scoped to rows containing the placeholder needle;
# idempotent — after a purge (or on an already-clean DB) the query matches 0
# rows and the script reports a no-op. DRY_RUN=1 by default prints the count and
# first 10 ids only; set DRY_RUN=0 to delete.
#
# Verify after apply (self-signed TLS, use -k):
#   curl -sk https://newtech.local/ | grep -c 'NEWTECH-UPDATE'                                        # -> 0
#   curl -sk https://newtech.local/machines/roof-wall-panel-machines/ssq3-multipro/ | grep -c 'NEWTECH-UPDATE'  # -> 0
#
# See 041-unpublish-placeholder-custom-schema.sh for the runner-quirk rationale:
# all logic lives in one eval-file, invoked via a direct `docker exec` (never
# apply's exported wp() wrapper, which mangles `wp eval-file <path>` when
# inherited by the per-script child bash).

# Deliberately NOT `set -e`: run by the apply runner; a single transient docker
# non-zero under `set -e` would abort silently. Handle errors explicitly.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

# Trailing X's, no suffix: BSD/macOS mktemp only expands X's when they end the
# template (a ".php" suffix makes it a literal name). eval-file needs no .php.
php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-042-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

global $wpdb;
$meta_key = 'wp_schema_pro_optimized_structured_data';
$needle   = 'NEWTECH-UPDATE';

$post_ids = $wpdb->get_col($wpdb->prepare(
    "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value LIKE %s",
    $meta_key,
    '%' . $wpdb->esc_like($needle) . '%'
));
$count = count($post_ids);

if ($count === 0) {
    echo "    nothing to do: no {$meta_key} rows contain {$needle}.\n";
    return;
}

if ($dry) {
    $preview = implode(', ', array_slice($post_ids, 0, 10));
    echo "    [dry-run] {$count} cache rows contain {$needle}; first ids: {$preview}\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

$deleted = 0; $failed = 0;
foreach ($post_ids as $pid) {
    if (delete_post_meta((int) $pid, $meta_key)) {
        $deleted++;
    } else {
        $failed++;
        echo "    error: could not delete cache row for post {$pid}.\n";
    }
}
echo "    done: {$deleted} placeholder cache rows deleted"
    . ($failed ? ", {$failed} FAILED" : '')
    . ". Schema Pro regenerates clean rows on next render.\n";
PHP

# Direct docker exec for eval-file — see header (runner quirk).
if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "${WP_PHP_BIN:-php8.3}" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
