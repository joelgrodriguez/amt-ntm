#!/usr/bin/env bash
#
# Set new color profile images as the featured image on each profile post, and
# record the remaining views (Seam/Snapped/3D/etc.) as an ordered gallery in
# post meta for the single-profile template to render as a thumbnail strip.
#
# WHY THIS SCRIPT EXISTS: a batch of color profile renders was uploaded to the
# media library (titles like SSQ675_Panel, SSQ675_Seam). Each profile's featured
# image should become its _Panel view; the other views become secondary gallery
# images. Attachment<->post links live only in the DB, which a fresh prod pull
# wipes — so the assignment has to be replayable. Mirrors 039/025.
#
# WHAT IT DOES, per profile (resolved by slug):
#   1. Resolves each image by its attachment TITLE (ids shift after a media
#      re-import; titles are stable), skipping any not found.
#   2. Sets _thumbnail_id to the featured (Panel) image.
#   3. Stores the ordered secondary attachment ids in _profile_gallery_ids meta
#      (comma-separated) for spec-sheet-layout.php to read.
#   4. Sets each image's post_parent to the profile (clean media association).
#
# NAME-DRIFT MAP (image title -> profile slug) is baked into the featured/
# secondary lists below, so the assignment is a verified constant, not a guess:
#   BBQ750->bb750, FFQ100/150->ff100/150, SSQ450/SL->ss450/sl, SSQ100->ss100.
#
# SAFE BY DESIGN: scoped per profile, resolves by stable titles, idempotent
# (re-running sets the same ids). DRY_RUN=1 by default; DRY_RUN=0 to write.
#
# See 025-top-five-questions-headings.sh for the precedent + KNOWN RUNNER QUIRK.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

export NTM_DRY_RUN="$DRY_RUN"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-040-XXXXXX.php")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

// profile slug => featured image title + ordered secondary image titles.
$map = [
  '5vc-210p' => ['featured' => '5VC-210P_3D', 'secondary' => []],
  '5vc-240p' => ['featured' => '5VC-240P_3D', 'secondary' => []],
  '5vc-245p' => ['featured' => '5VC-245P_3D', 'secondary' => []],
  'bb750-profile-board-and-batten' => ['featured' => 'BBQ750_Panel', 'secondary' => ['BBQ750_Seam']],
  'ff100' => ['featured' => 'FFQ100_Panel', 'secondary' => ['FFQ100_Seam']],
  'ff150' => ['featured' => 'FFQ150_Panel', 'secondary' => ['FFQ150_Seam']],
  'fwq100' => ['featured' => 'FWQ100_Panel', 'secondary' => ['FWQ100_Seam', 'FWQ100_Panel_Perforated', 'FWQ100_Panel_Underdeck']],
  'fwq150' => ['featured' => 'FWQ150_Panel', 'secondary' => ['FWQ150_3InchRevealWithPerfs_3D', 'FWQ150_Seam']],
  'ss100' => ['featured' => 'SS100_Panel', 'secondary' => ['SSQ100_Seam']],
  'ss150' => ['featured' => 'SS150_Panel', 'secondary' => ['SS150_Seam']],
  'ss450' => ['featured' => 'SSQ450_Panel', 'secondary' => ['SSQ450_Seam']],
  'ss450sl' => ['featured' => 'SSQ450SL_Panel', 'secondary' => ['SSQ450SL_Seam']],
  'ssq200' => ['featured' => 'SSQ200_Panel', 'secondary' => ['SSQ200_Seam']],
  'ssq210a' => ['featured' => 'SSQ210A_Panel', 'secondary' => ['SSQ210A_Seam']],
  'ssq275' => ['featured' => 'SSQ275_Panel', 'secondary' => ['SSQ275_Seamed', 'SSQ275_Snapped']],
  'ssq550' => ['featured' => 'SSQ550_Panel', 'secondary' => ['SSQ550_Seam']],
  'ssq675' => ['featured' => 'SSQ675_Panel', 'secondary' => ['SSQ675_Seam']],
  't-panel' => ['featured' => 'TPanel_Panel', 'secondary' => []],
  'trq250' => ['featured' => 'TRQ250_Panel', 'secondary' => ['TRQ250_Seam']],
  'wav-12-1f-profile' => ['featured' => 'WAV-12-1F', 'secondary' => []],
  'wav-16-4f-profile-with-flange' => ['featured' => 'WAV-16-4F', 'secondary' => []],
  'wav-8-1f' => ['featured' => 'WAV-8-1F', 'secondary' => []],
];

// Resolve an attachment id by its exact title (post_type=attachment).
$resolve = function (string $title): int {
    $q = get_posts([
        'post_type'      => 'attachment',
        'post_status'    => 'inherit',
        'title'          => $title,
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);
    return $q ? (int) $q[0] : 0;
};

$updated = 0; $skipped = 0;
foreach ($map as $slug => $spec) {
    $profiles = get_posts([
        'post_type'      => 'profile',
        'name'           => $slug,
        'post_status'    => 'publish',
        'posts_per_page' => 1,
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ]);
    if (!$profiles) {
        echo "    skip: profile '{$slug}' not found.\n";
        $skipped++;
        continue;
    }
    $pid = (int) $profiles[0];

    $feat_id = $resolve($spec['featured']);
    if (!$feat_id) {
        echo "    skip: featured image '{$spec['featured']}' not found for {$slug}.\n";
        $skipped++;
        continue;
    }

    $sec_ids = [];
    foreach ($spec['secondary'] as $t) {
        $sid = $resolve($t);
        if ($sid) { $sec_ids[] = $sid; }
        else { echo "    warn: secondary image '{$t}' not found for {$slug}.\n"; }
    }

    if ($dry) {
        $sec = $sec_ids ? implode(',', $sec_ids) : '(none)';
        echo "    [dry-run] {$slug} (post {$pid}): featured={$feat_id}, gallery=[{$sec}]\n";
        continue;
    }

    set_post_thumbnail($pid, $feat_id);
    if ($sec_ids) {
        update_post_meta($pid, '_profile_gallery_ids', implode(',', $sec_ids));
    } else {
        delete_post_meta($pid, '_profile_gallery_ids');
    }
    // Associate every image with the profile in the media library.
    foreach (array_merge([$feat_id], $sec_ids) as $aid) {
        wp_update_post(['ID' => $aid, 'post_parent' => $pid]);
    }
    $updated++;
    echo "    {$slug} (post {$pid}): featured={$feat_id}, " . count($sec_ids) . " gallery img.\n";
}
echo $dry
    ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n"
    : "    done: {$updated} profiles updated, {$skipped} skipped.\n";
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  wp eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
