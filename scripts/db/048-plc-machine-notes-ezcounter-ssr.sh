#!/usr/bin/env bash
#
# Add machine notes to the two PLC controllers and an SSR-miter caveat to the
# EZ-Counter (Hailey product-copy feedback, issue #96).
#
# WHY THIS SCRIPT EXISTS: three of Hailey's product-copy items are answered:
#
#   PLC07 (1354)  -> for the BG7. Same controller as PLC08, different mounting
#                    kit. Product copy doesn't say which machine it fits.
#   PLC08 (2810)  -> for the 5VC Crimp. Same controller as PLC07, different
#                    mounting kit.
#   EZ-Counter (2794) -> its miter option is gutter-only. The EZ-Counter is sold
#                    for MACH II and SSR, but the SSR is a panel machine, so the
#                    miter option does not apply to it. Existing copy said "miter
#                    options on all gutter machines" AND "For MACH II and SSR
#                    only", which read as a contradiction. This appends a note
#                    clarifying miter is not available on the SSR.
#
# Product excerpts live only in the DB, wiped by a fresh prod pull, so the edits
# must be replayable from git.
#
# Resolves by ID and asserts each product's title before writing: a fresh prod
# pull can renumber posts, so a blind ID write could hit the wrong product.
# Title mismatch => skip loudly, do not write.
#
# SAFE BY DESIGN: idempotent (skips if the note is already present), append-only,
# asserts identity before writing. DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP="docker exec ${WP_CONTAINER} php8.3 /usr/local/bin/wp --path=${WP_PATH} --allow-root"

echo "== 048 PLC machine notes + EZ-Counter SSR caveat (DRY_RUN=${DRY_RUN}) =="

# DRY_RUN passed as a literal below: host env does not cross the docker exec
# boundary, so getenv() inside the container always reads unset (see 045).
# shellcheck disable=SC2086
$WP eval '
$dry = "'"${DRY_RUN}"'" !== "0";

// id => [expected title fragment, note to append]
$targets = [
    1354 => [
        "PLC Computer Control - PLC07",
        "For the BG7\u{2122} box gutter machine.",
    ],
    2810 => [
        "PLC Computer Control - PLC08",
        "For the 5VC\u{2122} 5V Crimp machine.",
    ],
    2794 => [
        "EZ-Counter Computerized Length Controller",
        "Note: the miter option applies to gutter machines only and is not available on the SSR\u{2122}.",
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

echo "== 048 done =="
