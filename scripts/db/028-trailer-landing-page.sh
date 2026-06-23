#!/usr/bin/env bash
#
# Create the NTM trailer landing page at /machines/upgrades/trailer/ and assign
# it the "NTM Trailer" page template (page-trailer.php).
#
# WHY THIS SCRIPT EXISTS: the trailer landing page (stakeholder review
# 2026-06-17, Adam) is a theme template, but WordPress needs an actual published
# page pointed at it for the route to exist. Pages live only in the DB, and a
# fresh prod pull wipes hand-created pages — so the page (and its parent
# "upgrades" page under /machines/) has to be replayable.
#
# WHAT IT DOES, idempotently:
#   1. Ensures a parent "upgrades" page exists under /machines/ (page 207).
#   2. Ensures a "trailer" child page exists under upgrades.
#   3. Assigns the trailer page the page-trailer.php template via _wp_page_template.
#
# SAFE BY DESIGN: every step is get-or-create by slug+parent. Re-running finds
# the existing pages and only re-asserts the template meta, so it is a no-op
# after the first apply. DRY_RUN=1 by default; set DRY_RUN=0 to write.
#
# Resolves: trailer landing page route from the 2026-06-17 action items.

# Deliberately NOT `set -e`/`pipefail`: the apply runner sources this with an
# exported wp() that wraps `docker exec`; a single transient docker non-zero
# under `set -e` would abort silently. Handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

# /machines/ is page 207 (the machines hub). Children of it render at
# /machines/<slug>/, grandchildren at /machines/<parent>/<slug>/.
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

// Get-or-create a published page by slug under a given parent. Returns the ID.
$ensure_page = function (string $slug, string $title, int $parent) use ($dry): int {
    $existing = get_posts([
        'post_type'        => 'page',
        'name'             => $slug,
        'post_parent'      => $parent,
        'post_status'      => ['publish', 'draft', 'pending'],
        'numberposts'      => 1,
        'fields'           => 'ids',
        'suppress_filters' => false,
    ]);
    if (!empty($existing)) {
        return (int) $existing[0];
    }
    if ($dry) {
        echo "    [dry-run] would create page '{$slug}' (parent {$parent}).\n";
        return 0;
    }
    $id = wp_insert_post([
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_title'  => $title,
        'post_name'   => $slug,
        'post_parent' => $parent,
    ]);
    echo "    created page '{$slug}' (id {$id}, parent {$parent}).\n";
    return (int) $id;
};

if (!get_post($machines_id)) {
    echo "    SKIPPED: /machines/ page (id {$machines_id}) not found; can't anchor the tree.\n";
    return;
}

$upgrades_id = $ensure_page('upgrades', 'Upgrades', $machines_id);
if ($upgrades_id === 0 && !$dry) {
    echo "    ERROR: failed to create 'upgrades' page.\n";
    return;
}

$trailer_id = $ensure_page('trailer', 'NTM Trailer', $upgrades_id ?: $machines_id);
if ($trailer_id === 0 && !$dry) {
    echo "    ERROR: failed to create 'trailer' page.\n";
    return;
}

// Assign the template (idempotent: set_post_meta no-ops if already correct).
if ($dry) {
    echo "    [dry-run] would assign template '{$template}' to the trailer page.\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

$current = get_post_meta($trailer_id, '_wp_page_template', true);
if ($current === $template) {
    echo "    template already '{$template}' on page {$trailer_id} (no-op).\n";
} else {
    update_post_meta($trailer_id, '_wp_page_template', $template);
    echo "    assigned template '{$template}' to page {$trailer_id} (/machines/upgrades/trailer/).\n";
}
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  wp eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
