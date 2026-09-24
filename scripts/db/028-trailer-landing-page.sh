#!/usr/bin/env bash
#
# Create the NTM trailer landing page at /machines/trailer/ and assign it the
# "NTM Trailer" page template (page-trailer.php).
#
# WHY THIS SCRIPT EXISTS: the trailer landing page (stakeholder review
# 2026-06-17, Adam) is a theme template, but WordPress needs an actual published
# page pointed at it for the route to exist. Pages live only in the DB, and a
# fresh prod pull wipes hand-created pages — so the page has to be replayable.
#
# WHY /machines/trailer/ AND NOT /machines/upgrades/trailer/: WooCommerce's
# product permalink base is /machines/%product_cat%/, so ANY two-segment path
# under /machines/ (e.g. /machines/upgrades/trailer/) is parsed as
# <product_cat>/<product>. WP finds no product, then guess-404s with a 301 to
# the nearest product slug (trailer-tr23g). A one-segment page directly under
# /machines/ (like /machines/uniq-control-system/) does not collide. So the
# trailer page is a direct child of /machines/ (207), not nested under a parent.
#
# WHAT IT DOES, idempotently:
#   1. Ensures a "trailer" page exists directly under /machines/ (page 207).
#   2. Assigns the trailer page the page-trailer.php template via _wp_page_template.
#   3. If a stray "trailer" page exists under the old "upgrades" parent (from the
#      earlier route), re-parents it to 207 instead of creating a duplicate.
#
# SAFE BY DESIGN: get-or-create by slug, re-parenting an existing page rather
# than duplicating. Re-running is a no-op after the first apply. DRY_RUN=1 by
# default; set DRY_RUN=0 to write.
#
# Resolves: trailer landing page route from the 2026-06-17 action items.

# Deliberately NOT `set -e`/`pipefail`: the apply runner sources this with an
# exported wp() that wraps `docker exec`; a single transient docker non-zero
# under `set -e` would abort silently. Handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

# /machines/ is page 207 (the machines hub). Direct children render at
# /machines/<slug>/ — the only depth that does NOT collide with the Woo product
# permalink base /machines/%product_cat%/.
MACHINES_ID="207"
TEMPLATE="page-trailer.php"

export NTM_DRY_RUN="$DRY_RUN"
export NTM_MACHINES_ID="$MACHINES_ID"
export NTM_TEMPLATE="$TEMPLATE"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-028-XXXXXX.php")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
// NB: wp eval-file requires the opening PHP tag or the file is printed, not run.
$dry         = getenv('NTM_DRY_RUN') !== '0';
$machines_id = (int) getenv('NTM_MACHINES_ID');
$template    = getenv('NTM_TEMPLATE');

if (!get_post($machines_id)) {
    echo "    SKIPPED: /machines/ page (id {$machines_id}) not found; can't anchor the page.\n";
    return;
}

// Find any existing "trailer" page by slug, regardless of current parent — this
// catches the page from the earlier /machines/upgrades/trailer/ route so we
// re-parent it to /machines/ instead of leaving a duplicate behind.
$existing = get_posts([
    'post_type'        => 'page',
    'name'             => 'trailer',
    'post_status'      => ['publish', 'draft', 'pending'],
    'numberposts'      => 1,
    'fields'           => 'ids',
    'suppress_filters' => false,
]);
$trailer_id = !empty($existing) ? (int) $existing[0] : 0;

if ($dry) {
    if ($trailer_id) {
        $parent = (int) get_post($trailer_id)->post_parent;
        $note   = $parent === $machines_id ? 'already under /machines/' : "would re-parent from {$parent} to {$machines_id}";
        echo "    [dry-run] trailer page {$trailer_id} exists ({$note}); would assert template '{$template}'.\n";
    } else {
        echo "    [dry-run] would create 'trailer' page under /machines/ ({$machines_id}) with template '{$template}'.\n";
    }
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

if ($trailer_id) {
    // Re-parent to /machines/ if it's anywhere else (e.g. the old upgrades parent).
    if ((int) get_post($trailer_id)->post_parent !== $machines_id) {
        wp_update_post(['ID' => $trailer_id, 'post_parent' => $machines_id]);
        echo "    re-parented trailer page {$trailer_id} to /machines/ ({$machines_id}).\n";
    } else {
        echo "    trailer page {$trailer_id} already under /machines/ (no re-parent).\n";
    }
} else {
    $trailer_id = (int) wp_insert_post([
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_title'  => 'NTM Trailer',
        'post_name'   => 'trailer',
        'post_parent' => $machines_id,
    ]);
    echo "    created 'trailer' page (id {$trailer_id}) under /machines/ ({$machines_id}).\n";
}

if ($trailer_id === 0) {
    echo "    ERROR: failed to create or find the trailer page.\n";
    return;
}

// Assign the template (idempotent: no-ops if already correct).
$current = get_post_meta($trailer_id, '_wp_page_template', true);
if ($current === $template) {
    echo "    template already '{$template}' on page {$trailer_id} (no-op).\n";
} else {
    update_post_meta($trailer_id, '_wp_page_template', $template);
    echo "    assigned template '{$template}' to page {$trailer_id} (/machines/trailer/).\n";
}

// Rewrite cache must be refreshed so the new page route resolves immediately.
flush_rewrite_rules(false);
echo "    flushed rewrite rules.\n";
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  wp eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
