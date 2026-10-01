#!/usr/bin/env bash
#
# Draft the retired /configurator/ssqii/ page.
#
# WHY THIS SCRIPT EXISTS: the SSQ II MultiPro was discontinued September 30,
# 2026. The theme redirects /configurator/ssqii/ to the SSQ3 configurator by
# request path (app/inc/machine-status.php), so the redirect does not need this
# page. While the page stays published, WordPress still lists it in page lists
# and the Yoast sitemap, and its content still embeds the SSQ2 Corbel quote
# form. Drafting it removes those leftovers; the page stays restorable.
#
# WHAT IT DOES, idempotently:
#   - Finds the page by path configurator/ssqii (not a hard ID).
#   - If it is published or private, sets it to draft.
#   - If it is already draft, or missing, no-op.
#
# DRY_RUN=1 by default; set DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"

read -r -d '' PHP_SRC <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

$parent = get_page_by_path('configurator');
$ids    = $parent ? get_posts([
    'post_type'   => 'page',
    'name'        => 'ssqii',
    'post_parent' => (int) $parent->ID,
    'post_status' => ['publish', 'private', 'draft', 'pending'],
    'numberposts' => 1,
    'fields'      => 'ids',
]) : [];
$id = !empty($ids) ? (int) $ids[0] : 0;

if (!$id) {
    echo "    no configurator/ssqii page found (no-op).\n";
    return;
}

$status = get_post_status($id);

if ($status === 'draft') {
    echo "    configurator/ssqii page {$id} already draft (no-op).\n";
    return;
}

if ($dry) {
    echo "    [dry-run] would draft configurator/ssqii page {$id} (current status: {$status}).\n";
    return;
}

wp_update_post(['ID' => $id, 'post_status' => 'draft']);
echo "    drafted configurator/ssqii page {$id} (was {$status}).\n";
PHP

if [[ -z "${PHP_SRC:-}" ]]; then
  echo "    ERROR: failed to assemble migration PHP." >&2
  exit 1
fi

if [[ -n "${WP_CONTAINER:-}" ]]; then
  printf '%s\n' "$PHP_SRC" | docker exec -i \
    -e NTM_DRY_RUN="$NTM_DRY_RUN" \
    "$WP_CONTAINER" "${WP_PHP_BIN:-php8.3}" /usr/local/bin/wp --path="${WP_PATH:-/www/kinsta/public/newtech}" --allow-root eval-file -
else
  printf '%s\n' "$PHP_SRC" | wp eval-file -
fi
