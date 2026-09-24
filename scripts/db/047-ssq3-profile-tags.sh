#!/usr/bin/env bash
#
# Tag the SSQ3 MultiPro onto the profiles it runs, so "SSQ3 MultiPro" appears in
# the Profiles -> Filter by Machine sidebar.
#
# WHY THIS SCRIPT EXISTS (issue #99): the Profiles machine filter is built from
# profile post_tag terms that have profiles attached (hide_empty). The
# `ssq3-multipro` tag exists but ZERO profiles carry it — its 11 uses are on
# articles/products, not profiles. So the SSQ3, the current flagship, never shows
# in the sidebar, while its predecessor SSQ II MultiPro (21 tagged profiles) does.
#
# WHAT IT DOES: appends the `ssq3-multipro` tag to every profile currently tagged
# `ssq-ii-multipro-roof-panel-machine`. Confirmed by Joel (2026-07-17): the SSQ3
# runs the SAME profile set as the SSQ II MultiPro. Tag is APPENDED, not
# replaced — the SSQ II tag stays.
#
# Tag assignment is DB state that a fresh prod pull wipes, so it must be
# replayable from git.
#
# Resolves BOTH tags by slug (slugs survive a prod pull; term IDs may renumber).
# If either tag is missing, the script aborts loudly rather than guessing.
#
# SAFE BY DESIGN: idempotent (wp_set_object_terms with append skips dupes; the
# script also re-checks), append-only, asserts both tags exist first.
# DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP="docker exec ${WP_CONTAINER} php8.3 /usr/local/bin/wp --path=${WP_PATH} --allow-root"

echo "== 047 SSQ3 profile tags (DRY_RUN=${DRY_RUN}) =="

# DRY_RUN passed as a literal below: host env does not cross the docker exec
# boundary, so getenv() inside the container always reads unset (see 045).
# shellcheck disable=SC2086
$WP eval '
$dry = "'"${DRY_RUN}"'" !== "0";

$target_slug = "ssq3-multipro";
$source_slug = "ssq-ii-multipro-roof-panel-machine";

$target = get_term_by("slug", $target_slug, "post_tag");
$source = get_term_by("slug", $source_slug, "post_tag");

if (!$target instanceof WP_Term) {
    echo "ABORT: target tag \"{$target_slug}\" not found — nothing written\n";
    return;
}
if (!$source instanceof WP_Term) {
    echo "ABORT: source tag \"{$source_slug}\" not found — nothing written\n";
    return;
}

// Profiles currently tagged SSQ II MultiPro — the set the SSQ3 also runs.
$q = new WP_Query([
    "post_type"      => "profile",
    "post_status"    => "publish",
    "posts_per_page" => -1,
    "fields"         => "ids",
    "no_found_rows"  => true,
    "tax_query"      => [[
        "taxonomy" => "post_tag",
        "field"    => "term_id",
        "terms"    => $source->term_id,
    ]],
]);

if (empty($q->posts)) {
    echo "ABORT: no profiles carry \"{$source_slug}\" — refusing to run against an empty set\n";
    return;
}

$total = count($q->posts);
$wrote = 0;
$already = 0;

foreach ($q->posts as $pid) {
    $has = has_term($target->term_id, "post_tag", $pid);
    if ($has) {
        $already++;
        continue;
    }
    if ($dry) {
        echo "DRY  {$pid}: would add {$target_slug} — " . html_entity_decode(get_the_title($pid)) . "\n";
        $wrote++;
        continue;
    }
    // append=true: adds the tag without removing existing terms.
    $res = wp_set_object_terms($pid, [$target->term_id], "post_tag", true);
    if (is_wp_error($res)) {
        echo "FAIL {$pid}: " . $res->get_error_message() . "\n";
        continue;
    }
    $wrote++;
    echo "WROTE {$pid}: +{$target_slug} — " . html_entity_decode(get_the_title($pid)) . "\n";
}

if (!$dry) {
    // Recount the term so the sidebar (hide_empty) picks it up immediately.
    wp_update_term_count_now([$target->term_id], "post_tag");
    clean_term_cache([$target->term_id], "post_tag");
}

echo "\n";
echo ($dry ? "DRY SUMMARY: " : "SUMMARY: ") . "{$total} SSQ II profiles; ";
echo ($dry ? "would tag " : "tagged ") . "{$wrote}; already tagged {$already}\n";
'

echo "== 047 done =="
