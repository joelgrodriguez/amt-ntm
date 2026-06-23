#!/usr/bin/env bash
#
# Create the NTM safety landing page at /safety/ in DRAFT status and assign it
# the "Safety" page template (page-safety.php).
#
# WHY THIS SCRIPT EXISTS: the safety landing page (stakeholder review
# 2026-06-17, Adam) is a theme template, but WordPress needs an actual page
# pointed at it for the route to exist. Pages live only in the DB, and a fresh
# prod pull wipes hand-created pages — so the page has to be replayable.
#
# WHY DRAFT, NOT PUBLISH: the room flagged safety messaging as a legal gate —
# everything safety-worded goes to counsel (Jenkins/Jake) before it goes live
# (see docs/legal/safety-copy-review.md). Creating the page as a DRAFT wires the
# route + template without exposing a public URL. After sign-off, publish with:
#   wp post update <id> --post_status=publish   (or a follow-up db script).
#
# WHY /safety/ (TOP-LEVEL): WooCommerce's product permalink base is
# /machines/%product_cat%/, so any path under /machines/ risks being parsed as
# <product_cat>/<product> (this is what forced the trailer page to a single
# segment, see 028-trailer-landing-page.sh). A top-level page (no parent) at
# /safety/ does not touch that base and does not collide.
#
# WHAT IT DOES, idempotently:
#   1. Ensures a top-level "safety" page exists (get-or-create by slug).
#   2. Keeps it in DRAFT if this script created it. If the page was already
#      published (counsel signed off and someone published it), it is LEFT
#      PUBLISHED — the script never demotes a live page back to draft.
#   3. Assigns the safety page the page-safety.php template via _wp_page_template.
#
# SAFE BY DESIGN: get-or-create by slug, never duplicates, never un-publishes a
# live page. Re-running is a no-op after the first apply. DRY_RUN=1 by default;
# set DRY_RUN=0 to write.
#
# Resolves: safety landing page from the 2026-06-17 action items (legal-gated).

# Deliberately NOT `set -e`/`pipefail`: the apply runner sources this with an
# exported wp() that wraps `docker exec`; a single transient docker non-zero
# under `set -e` would abort silently. Handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

TEMPLATE="page-safety.php"

export NTM_DRY_RUN="$DRY_RUN"
export NTM_TEMPLATE="$TEMPLATE"

# Build the migration PHP into a variable, then pipe it to `wp eval-file -`
# (reads from stdin). This avoids the docker-cp + temp-file dance entirely —
# no platform-specific mktemp behavior, no in-container cleanup, no stray file.
read -r -d '' PHP_SRC <<'PHP'
<?php
// NB: wp eval-file requires the opening PHP tag or the file is printed, not run.
$dry      = getenv('NTM_DRY_RUN') !== '0';
$template = getenv('NTM_TEMPLATE');

// Find any existing "safety" page by slug, regardless of status — so we never
// create a duplicate and never demote a page counsel may have already published.
$existing = get_posts([
    'post_type'        => 'page',
    'name'             => 'safety',
    'post_status'      => ['publish', 'draft', 'pending', 'private'],
    'numberposts'      => 1,
    'fields'           => 'ids',
    'suppress_filters' => false,
]);
$safety_id = !empty($existing) ? (int) $existing[0] : 0;

if ($dry) {
    if ($safety_id) {
        $status = get_post_status($safety_id);
        echo "    [dry-run] safety page {$safety_id} exists (status: {$status}); would assert template '{$template}', leave status as-is.\n";
    } else {
        echo "    [dry-run] would create top-level 'safety' page in DRAFT with template '{$template}'.\n";
    }
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

if (!$safety_id) {
    $safety_id = (int) wp_insert_post([
        'post_type'   => 'page',
        'post_status' => 'draft',     // LEGAL GATE: not public until counsel signs off.
        'post_title'  => 'Safety',
        'post_name'   => 'safety',
        'post_parent' => 0,           // top-level → /safety/, no Woo collision.
    ]);
    echo "    created 'safety' page (id {$safety_id}) in DRAFT at /safety/.\n";
} else {
    // Never un-publish a page that may have cleared legal review and gone live.
    echo "    safety page {$safety_id} already exists (status: " . get_post_status($safety_id) . "); leaving status unchanged.\n";
}

if ($safety_id === 0) {
    echo "    ERROR: failed to create or find the safety page.\n";
    return;
}

// Assign the template (idempotent: no-ops if already correct).
$current = get_post_meta($safety_id, '_wp_page_template', true);
if ($current === $template) {
    echo "    template already '{$template}' on page {$safety_id} (no-op).\n";
} else {
    update_post_meta($safety_id, '_wp_page_template', $template);
    echo "    assigned template '{$template}' to page {$safety_id} (/safety/).\n";
}

// Rewrite cache must be refreshed so the new page route resolves.
flush_rewrite_rules(false);
echo "    flushed rewrite rules.\n";
PHP

# `read -r -d ''` returns non-zero at EOF even on success; PHP_SRC is populated.
if [[ -z "${PHP_SRC:-}" ]]; then
  echo "    ERROR: failed to assemble migration PHP." >&2
  exit 1
fi

# Pipe the PHP to WP-CLI via stdin. Inside the container we need `docker exec -i`
# so stdin reaches WP-CLI; the runner's exported wp() uses plain `docker exec`,
# so call docker directly here when a container is set.
if [[ -n "${WP_CONTAINER:-}" ]]; then
  printf '%s\n' "$PHP_SRC" | docker exec -i \
    -e NTM_DRY_RUN="$NTM_DRY_RUN" \
    -e NTM_TEMPLATE="$NTM_TEMPLATE" \
    "$WP_CONTAINER" wp --path="${WP_PATH:-/www/kinsta/public/newtech}" --allow-root eval-file -
else
  printf '%s\n' "$PHP_SRC" | wp eval-file -
fi
