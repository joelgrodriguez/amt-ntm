#!/usr/bin/env bash
#
# Draft the broken placeholder "Sitewide Custom Schema" Schema Pro custom-markup
# entry so it stops emitting invalid JSON-LD site-wide.
#
# WHY THIS SCRIPT EXISTS: WP Schema Pro custom-markup post "Sitewide Custom
# Schema" (post_type aiosrs-schema) injects a JSON-LD Organization block on every
# page that is BOTH syntactically invalid — it contains JavaScript-style /* ... */
# comments inside JSON, so no crawler can parse it — AND full of unfilled template
# placeholders: "logo": ".../path-to-logo.png", "foundingDate": "YYYY", and
# sameAs URLs like .../NEWTECH-UPDATE. Yoast (active) already emits the proper
# Organization/WebSite graph, so this block is pure noise even if it were fixed.
# The entry lives only in the DB, which a fresh prod pull wipes and would
# resurrect — so the fix has to be replayable. Prod is serving the broken block
# today; npm run db:apply replays this against the fresh prod DB at cutover.
#
# WHAT IT DOES: resolves the target by TITLE + post_type (ids shift across
# environments; titles are stable), guards that its custom-markup meta still
# contains a placeholder string (NEWTECH-UPDATE or path-to-logo) so a later hand-
# repair is never clobbered, and sets the post to `draft`. Schema Pro does not
# emit drafts. Drafting is reversible; nothing is deleted.
#
# SCOPE: only "Sitewide Custom Schema" qualified. "Home Page Custom Schema"
# (18578) was inspected and is a real, populated WebPage/Breadcrumb/FAQ graph
# with zero placeholder strings — deliberately left untouched. The typed Schema
# Pro entries (Video Object, FAQ, Product, Article) are out of scope.
#
# SAFE BY DESIGN: resolves by stable title, guards on the placeholder string, and
# idempotent — a re-run finds the entry already draft (or the guard string gone
# after a repair) and does nothing. DRY_RUN=1 by default; set DRY_RUN=0 to write.
#
# Verify after apply (self-signed TLS, use -k):
#   curl -sk https://newtech.local/ | grep -c 'NEWTECH-UPDATE'   # -> 0
#
# See 040-profile-featured-images.sh and 025-top-five-questions-headings.sh for
# the eval-file precedent + the KNOWN RUNNER QUIRK: apply's exported wp() wrapper,
# once inherited by the per-script child bash, mangles `wp eval-file <path>` into
# a spurious "does not exist" (bash exported-function + docker-exec-without-`-i`).
# This script sidesteps it two ways — all resolve/guard/action live inside one
# eval-file (no id-capture through the wrapper), and the eval-file call below uses
# a direct `docker exec` instead of the wrapper.

# Deliberately NOT `set -e`: run by the apply runner with an exported wp() that
# wraps `docker exec`. A single transient docker non-zero under `set -e` would
# abort silently. Handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

# Trailing X's, no suffix: BSD/macOS mktemp only expands X's when they end the
# template (a ".php" suffix makes it a literal name). eval-file needs no .php.
php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-041-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

// Placeholder custom-markup entries to draft, resolved by exact title.
$titles = [
    'Sitewide Custom Schema',
];

// An entry qualifies only if its markup still carries a template placeholder.
$guard_needles = ['NEWTECH-UPDATE', 'path-to-logo'];

$drafted = 0; $noop = 0;
foreach ($titles as $title) {
    $posts = get_posts([
        'post_type'      => 'aiosrs-schema',
        'title'          => $title,
        'post_status'    => 'any',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);
    if (!$posts) {
        echo "    skip: '{$title}' not found.\n";
        $noop++;
        continue;
    }
    $pid = (int) $posts[0];

    // Schema Pro stores this meta as an array
    // (['custom-markup' => 'custom-text', 'custom-markup-custom-text' => '<script>…']);
    // flatten it to a searchable string rather than casting the array to "Array".
    $meta   = get_post_meta($pid, 'bsf-aiosrs-custom-markup', true);
    $markup = is_array($meta) ? implode("\n", array_map('strval', $meta)) : (string) $meta;
    $has_placeholder = false;
    foreach ($guard_needles as $needle) {
        if (strpos($markup, $needle) !== false) { $has_placeholder = true; break; }
    }
    if (!$has_placeholder) {
        echo "    skip: '{$title}' (post {$pid}) has no placeholder string — leaving as-is.\n";
        $noop++;
        continue;
    }

    $status = get_post_status($pid);
    if ($status === 'draft') {
        echo "    skip: '{$title}' (post {$pid}) already draft.\n";
        $noop++;
        continue;
    }

    if ($dry) {
        echo "    [dry-run] would draft '{$title}' (post {$pid}, currently {$status}).\n";
        continue;
    }

    $res = wp_update_post(['ID' => $pid, 'post_status' => 'draft'], true);
    if (is_wp_error($res)) {
        echo "    error: could not draft '{$title}' (post {$pid}): " . $res->get_error_message() . "\n";
        continue;
    }
    echo "    drafted '{$title}' (post {$pid}, was {$status}).\n";
    $drafted++;
}
echo $dry
    ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n"
    : "    done: {$drafted} drafted, {$noop} left unchanged.\n";
PHP

# Run the PHP inside the WP install. When targeting a container we call
# `docker exec` DIRECTLY here rather than through apply's exported wp() wrapper:
# that wrapper, once inherited by the child bash the apply runner spawns per
# script, mangles `wp eval-file <path>` into a spurious "does not exist" (the
# documented bash-exported-function + docker-exec-without-`-i` quirk — see the
# 025 header). A direct exec is the reliable path both standalone and under
# apply, and it reuses the same WP_CONTAINER/WP_PATH/WP_PHP_BIN the runner sets.
if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "${WP_PHP_BIN:-php8.3}" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
