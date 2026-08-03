#!/usr/bin/env bash
#
# Create the METALCON 2026 landing page at /metalcon-2026/ and assign it the
# "NTM — METALCON 2026" page template (page-metalcon-2026.php).
#
# WHY THIS SCRIPT EXISTS: the METALCON page is a theme template, but WordPress
# needs a published page for the route to exist. Pages live only in the DB, and
# a fresh prod pull wipes hand-created pages — so the page must be replayable.
#
# WHY TOP-LEVEL: the page is the single destination for five pre-show traffic
# channels (Meta ads, two email campaigns, sales calls, social). A short
# top-level URL is what goes in ad creative and print. It also avoids the
# /machines/%product_cat%/ permalink collision that forced the trailer page to
# sit directly under /machines/.
#
# WHAT IT DOES, idempotently:
#   1. Ensures a top-level "metalcon-2026" page exists and is published.
#   2. Assigns page-metalcon-2026.php via _wp_page_template.
#   3. Re-parents to top level if an earlier run nested it.
#
# SAFE BY DESIGN: get-or-create by slug, never duplicates. Re-running is a no-op
# after the first apply. DRY_RUN=1 by default; set DRY_RUN=0 to write.
#
# Resolves: GitHub issue #130 / monday.com item 12662983688.

# Deliberately NOT `set -e`/`pipefail`: the apply runner sources this with an
# exported wp() that wraps `docker exec`; a single transient docker non-zero
# under `set -e` would abort silently. Handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

TEMPLATE="page-metalcon-2026.php"

# NB: only DRY_RUN, NTM_DRY_RUN, NTM_POST_ID, NTM_MACHINES_ID, and NTM_TEMPLATE
# are forwarded into the container by scripts/db/apply. Anything else read via
# getenv() arrives empty. The slug and title are constants for this migration,
# so they are PHP literals below rather than env vars.
export NTM_DRY_RUN="$DRY_RUN"
export NTM_TEMPLATE="$TEMPLATE"

# BSD/macOS mktemp does not expand XXXXXX when a suffix follows it, so create
# the file without a suffix and rename. wp eval-file needs the .php extension.
php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-036-XXXXXX")"
mv "$php_tmp" "${php_tmp}.php"
php_tmp="${php_tmp}.php"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
// NB: wp eval-file requires the opening PHP tag or the file is printed, not run.
$dry      = getenv('NTM_DRY_RUN') !== '0';
$template = (string) getenv('NTM_TEMPLATE');
$slug     = 'metalcon-2026';
$title    = 'METALCON 2026';

// Guard: an empty slug or template would make the get_posts() lookup below
// match an arbitrary page and then rewrite it. Refuse rather than guess.
if ($slug === '' || $template === '') {
    echo "    ERROR: slug or template resolved empty; refusing to touch any page.\n";
    return;
}

$existing = get_posts([
    'post_type'        => 'page',
    'name'             => $slug,
    'post_status'      => ['publish', 'draft', 'pending'],
    'numberposts'      => 1,
    'fields'           => 'ids',
    'suppress_filters' => false,
]);
$page_id = !empty($existing) ? (int) $existing[0] : 0;

if ($dry) {
    if ($page_id) {
        $parent = (int) get_post($page_id)->post_parent;
        $note   = $parent === 0 ? 'already top-level' : "would re-parent from {$parent} to top level";
        echo "    [dry-run] '{$slug}' page {$page_id} exists ({$note}); would assert template '{$template}'.\n";
    } else {
        echo "    [dry-run] would create top-level '{$slug}' page with template '{$template}'.\n";
    }
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

if ($page_id) {
    $post = get_post($page_id);

    if ((int) $post->post_parent !== 0) {
        wp_update_post(['ID' => $page_id, 'post_parent' => 0]);
        echo "    re-parented '{$slug}' page {$page_id} to top level.\n";
    }

    if ($post->post_status !== 'publish') {
        wp_update_post(['ID' => $page_id, 'post_status' => 'publish']);
        echo "    published '{$slug}' page {$page_id}.\n";
    }

    if ((int) $post->post_parent === 0 && $post->post_status === 'publish') {
        echo "    '{$slug}' page {$page_id} already top-level and published (no-op).\n";
    }
} else {
    $page_id = (int) wp_insert_post([
        'post_type'   => 'page',
        'post_status' => 'publish',
        'post_title'  => $title,
        'post_name'   => $slug,
        'post_parent' => 0,
    ]);
    echo "    created '{$slug}' page (id {$page_id}) at top level.\n";
}

if ($page_id === 0) {
    echo "    ERROR: failed to create or find the '{$slug}' page.\n";
    return;
}

// Assign the template (idempotent: no-ops if already correct).
$current = get_post_meta($page_id, '_wp_page_template', true);
if ($current === $template) {
    echo "    template already '{$template}' on page {$page_id} (no-op).\n";
} else {
    update_post_meta($page_id, '_wp_page_template', $template);
    echo "    assigned template '{$template}' to page {$page_id} (/{$slug}/).\n";
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
