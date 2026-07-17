#!/usr/bin/env bash
#
# Register the theme's own media library items.
#
# WHY THIS SCRIPT EXISTS: the front page (quiz section, flagship band, hero
# slider), MACH II, and SSQ3 surfaces reference media uploaded during the
# redesign. The FILES ship with the uploads directory, but the attachment ROWS
# are DB objects a fresh prod pull wipes — and without rows, responsive_image()
# can't build srcset markup and admin media search can't find them. This
# re-registers each file in place (--skip-copy) with its original title/alt.
#
# Resolved by _wp_attached_file path, never by ID: the redesign-era IDs
# (20654-20745) collide with rows production allocated independently.
#
# IDEMPOTENT: skips any file already registered; skips (with a warning) files
# missing from uploads.

set -euo pipefail

uploads_dir="$(wp eval 'echo wp_get_upload_dir()["basedir"];')"
dry_run="${DRY_RUN:-0}"

# relative-path|title|alt
media=(
  '2026/05/20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p.mp4|20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p|'
  '2026/05/20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p-optimized.mp4|20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p-optimized|'
  '2026/07/20260708_NTM_SSQ3-Page-Panel-Running_V1-1080p.mp4|20260708_NTM_SSQ3-Page-Panel-Running_V1-1080p|'
  '2026/05/ntm-q3-hero-placeholder.png|ntm-q3-hero-placeholder|'
  '2026/05/ntm-q3-hero-placeholder-2.png|ntm-q3-hero-placeholder-2|'
  '2026/06/ntm-ssq3-on-trailer-001-1-scaled.png|NTM SSQ3 on trailer|NTM SSQ3 portable roof panel machine mounted on trailer, Unique control system visible'
  '2026/06/aerial-horizontal-panel-wall.jpg|aerial-horizontal-panel-wall|'
  '2026/06/aerial-ssq3-forming-panel.jpg|aerial-ssq3-forming-panel|'
  '2026/06/aerial-ssq3-jobsite.jpg|aerial-ssq3-jobsite|'
  '2026/06/aerial-standing-seam-roof.jpg|aerial-standing-seam-roof|'
  '2026/06/aerial-standing-seam-wall-progress.jpg|aerial-standing-seam-wall-progress|'
  '2026/06/completed-building-standing-seam-wall.jpg|completed-building-standing-seam-wall|'
  '2026/06/completed-building-wide.jpg|completed-building-wide|'
  '2026/06/ssq3-coil-cradle-detail.jpg|ssq3-coil-cradle-detail|'
  '2026/06/ssq3-controller-and-head.jpg|ssq3-controller-and-head|'
  '2026/06/ssq3-controller.jpg|ssq3-controller|'
  '2026/06/ssq3-crew-inspecting-panel.jpg|ssq3-crew-inspecting-panel|'
  '2026/06/ssq3-dual-coil-reels-branding.jpg|ssq3-dual-coil-reels-branding|'
  '2026/06/ssq3-forming-head-and-controller.jpg|ssq3-forming-head-and-controller|'
  '2026/06/ssq3-machine-full-side-jobsite.jpg|ssq3-machine-full-side-jobsite|'
  '2026/06/ssq3-machine-full-side-shop.jpg|ssq3-machine-full-side-shop|'
  '2026/06/ssq3-machine-rear-loaded.jpg|ssq3-machine-rear-loaded|'
  '2026/06/ssq3-machine-rear-panel-exit.jpg|ssq3-machine-rear-panel-exit|'
  '2026/06/ssq3-machine-rear-quarter-studio.jpg|ssq3-machine-rear-quarter-studio|'
  '2026/06/ssq3-machine-rear-quarter.jpg|ssq3-machine-rear-quarter|'
  '2026/06/ssq3-machine-side-backlit.jpg|ssq3-machine-side-backlit|'
  '2026/06/ssq3-machine-side-loaded-coils.jpg|ssq3-machine-side-loaded-coils|'
  '2026/06/ssq3-machine-side-yard.jpg|ssq3-machine-side-yard|'
  '2026/06/ssq3-operator-at-controls.jpg|ssq3-operator-at-controls|'
  '2026/06/ssq3-operator-feeding-panel-jobsite.jpg|ssq3-operator-feeding-panel-jobsite|'
  '2026/06/ssq3-operator-runout-table.jpg|ssq3-operator-runout-table|'
)

registered=0
skipped=0
missing=0
would_register=0

for row in "${media[@]}"; do
  IFS='|' read -r rel title alt <<<"$row"

  existing="$(wp post list --post_type=attachment --post_status=inherit \
                --meta_key=_wp_attached_file --meta_value="$rel" \
                --field=ID --format=ids 2>/dev/null | head -n1 || true)"
  if [[ -n "$existing" ]]; then
    skipped=$((skipped + 1))
    continue
  fi

  # Path goes through base64 so quoting survives the docker-exec + wp-eval hop.
  rel_b64="$(printf %s "$uploads_dir/$rel" | base64)"
  if ! wp eval "exit(file_exists(base64_decode('$rel_b64')) ? 0 : 1);" >/dev/null 2>&1; then
    echo "    !! file missing, skipped: $rel"
    missing=$((missing + 1))
    continue
  fi

  if [[ "$dry_run" == "1" ]]; then
    echo "    dry-run: would register $rel"
    would_register=$((would_register + 1))
    continue
  fi

  args=(--skip-copy --title="$title" --porcelain)
  [[ -n "$alt" ]] && args+=(--alt="$alt")
  new_id="$(wp media import "$uploads_dir/$rel" "${args[@]}")"
  echo "    registered #$new_id  $rel"
  registered=$((registered + 1))
done

echo "    theme media: $registered registered, $skipped already present, $missing missing, $would_register dry-run pending"
