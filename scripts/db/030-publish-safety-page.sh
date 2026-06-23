#!/usr/bin/env bash
#
# Publish the /safety landing page (created in Draft by 029).
#
# WHY THIS SCRIPT EXISTS: 029 creates the safety page in Draft because the room
# set a counsel-review-before-publish gate (issue #41). Joel has consciously
# waived that gate (issue #43) and is publishing now, owning the risk; the page
# is facts-only with zero claims, and docs/legal/safety-copy-review.md remains
# for retroactive sign-off. A fresh prod pull would reset the page to whatever
# state the DB ships, so the PUBLISH has to be replayable, same as the create.
#
# WHAT IT DOES, idempotently:
#   - Finds the "safety" page by slug (not a hard ID — a fresh prod pull renumbers).
#   - If it is draft/pending, flips it to publish.
#   - If it is already published, no-op. Never demotes.
#   - If no safety page exists yet, reports that 029 must run first.
#
# Depends on 029 having created the page (run order is numeric, so 029 < 030).
# DRY_RUN=1 by default; set DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"

read -r -d '' PHP_SRC <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

$ids = get_posts([
    'post_type'        => 'page',
    'name'             => 'safety',
    'post_status'      => ['publish', 'draft', 'pending', 'private'],
    'numberposts'      => 1,
    'fields'           => 'ids',
    'suppress_filters' => false,
]);
$id = !empty($ids) ? (int) $ids[0] : 0;

if (!$id) {
    echo "    SKIPPED: no 'safety' page found. Run 029-safety-landing-page-draft.sh first.\n";
    return;
}

$status = get_post_status($id);

if ($status === 'publish') {
    echo "    safety page {$id} already published (no-op).\n";
    return;
}

if ($dry) {
    echo "    [dry-run] would publish safety page {$id} (current status: {$status}).\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

wp_update_post(['ID' => $id, 'post_status' => 'publish']);
flush_rewrite_rules(false);
echo "    published safety page {$id} (was {$status}). /safety/ is now live.\n";
PHP

if [[ -z "${PHP_SRC:-}" ]]; then
  echo "    ERROR: failed to assemble migration PHP." >&2
  exit 1
fi

if [[ -n "${WP_CONTAINER:-}" ]]; then
  printf '%s\n' "$PHP_SRC" | docker exec -i \
    -e NTM_DRY_RUN="$NTM_DRY_RUN" \
    "$WP_CONTAINER" wp --path="${WP_PATH:-/www/kinsta/public/newtech}" --allow-root eval-file -
else
  printf '%s\n' "$PHP_SRC" | wp eval-file -
fi
