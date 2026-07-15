#!/usr/bin/env bash
#
# Append machine-compatibility / purchasing notes to three accessory products.
#
# WHY THIS SCRIPT EXISTS: sales feedback after the deploy was that accessory
# products don't say which machine they fit, so a prospect can't tell whether an
# item applies to them. Product excerpts live only in the DB, which a fresh prod
# pull wipes, so the edits have to be replayable from git.
#
# WHAT IT DOES: appends one sentence to the short description (post_excerpt) of
# three products. Wording is verbatim from sales; nothing is rewritten or removed.
#
#   2794  EZ-Counter Computerized Length Controller  -> Mach II / SSR only
#   2834  Hot Melt System Interface - HMT-UNQ        -> buy Hot Melt System direct
#   2859  Triple Overhead Reel Rack - DR1-TRIPLE     -> WAV machine only
#
# NOTE ON 2794: the existing copy says miter options work "on all gutter
# machines" while sales says the unit is "for Mach II and SSR only". Those may
# conflict. This script only APPENDS the compatibility line; resolving the
# "all gutter machines" clause needs a product decision and is deliberately out
# of scope here.
#
# Products are resolved by ID and asserted against expected titles: a fresh prod
# pull can renumber posts, so a blind ID write could hit the wrong product. If a
# title doesn't match, that product is skipped loudly rather than written.
#
# SAFE BY DESIGN: idempotent (skips if the note is already present), append-only,
# asserts identity before writing. DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP="docker exec ${WP_CONTAINER} php8.3 /usr/local/bin/wp --path=${WP_PATH} --allow-root"

echo "== 045 accessory compatibility notes (DRY_RUN=${DRY_RUN}) =="

# DRY_RUN is passed in as a literal below rather than read with getenv(): the
# host env does not cross the `docker exec` boundary, so getenv() inside the
# container always sees an unset value and silently stays in dry mode.
# shellcheck disable=SC2086
$WP eval '
$dry = "'"${DRY_RUN}"'" !== "0";

// id => [expected title fragment, note to append]
$targets = [
    2794 => [
        "EZ-Counter Computerized Length Controller",
        "For MACH II\u{2122} and SSR\u{2122} machines only.",
    ],
    2834 => [
        "Hot Melt System Interface",
        "The Hot Melt System must be purchased direct thru Hot Melt Technologies.",
    ],
    2859 => [
        "Triple Overhead Reel Rack",
        "For the WAV\u{2122} machine only.",
    ],
];

$changed = 0;
$skipped = 0;

foreach ($targets as $id => [$expect, $note]) {
    $p = get_post($id);

    if (!$p || $p->post_type !== "product") {
        echo "SKIP  id={$id}: not a product (post IDs may have shifted after a prod pull)\n";
        $skipped++;
        continue;
    }

    if (stripos($p->post_title, $expect) === false) {
        echo "SKIP  id={$id}: title mismatch — expected \"{$expect}\", found \"{$p->post_title}\"\n";
        $skipped++;
        continue;
    }

    $excerpt = $p->post_excerpt;

    if (stripos($excerpt, $note) !== false) {
        echo "OK    id={$id}: note already present — {$p->post_title}\n";
        continue;
    }

    $updated = rtrim($excerpt) . "\n\n" . $note;

    if ($dry) {
        echo "DRY   id={$id}: would append to {$p->post_title}\n";
        echo "        + {$note}\n";
        $changed++;
        continue;
    }

    $res = wp_update_post([
        "ID"           => $id,
        "post_excerpt" => $updated,
    ], true);

    if (is_wp_error($res)) {
        echo "FAIL  id={$id}: " . $res->get_error_message() . "\n";
        $skipped++;
        continue;
    }

    echo "WROTE id={$id}: {$p->post_title}\n";
    $changed++;
}

echo "\nchanged={$changed} skipped={$skipped}" . ($dry ? " (dry run — set DRY_RUN=0 to apply)" : "") . "\n";
'

echo "== 045 done =="
