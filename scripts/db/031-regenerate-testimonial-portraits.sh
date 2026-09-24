#!/usr/bin/env bash
#
# Regenerate thumbnail sizes for the six front-page testimonial portraits.
#
# WHY THIS SCRIPT EXISTS: the testimonial slider (templates/parts/testimonial-
# slider.php) requests each portrait at -150x150 AND -300x300 via srcset. The
# 2025/06 portrait uploads predate the theme registering the 300x300 size, so a
# fresh prod DB pull ships attachment metadata WITHOUT the 300x300 file on disk.
# On any high-DPI display the browser picks the 300w candidate, 404s, and the
# portrait renders broken. Regenerating rebuilds every registered size from the
# original, filling the gap. Thumbnail files live under uploads/ (not git), so
# this must replay after every fresh pull.
#
# Portraits are resolved by their upload filename (guid), not attachment ID,
# because a fresh pull can renumber IDs.
#
# IDEMPOTENT: media regenerate is safe to re-run; it rebuilds sizes in place.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"

# Filenames of the six portraits the slider renders (uploads/2025/06/<name>.png).
portraits=(
  "Danaik-1.png"
  "Todd.png"
  "Jim.png"
  "Abel.png"
  "Mike.png"
  "Keith.png"
)

ids=()
for name in "${portraits[@]}"; do
  id="$(wp db query \
        "SELECT ID FROM wp_posts WHERE post_type='attachment' AND guid LIKE '%2025/06/${name}' ORDER BY ID DESC LIMIT 1" \
        --skip-column-names 2>/dev/null | tr -d '[:space:]' || true)"
  if [[ -z "${id}" ]]; then
    echo "    portrait ${name} not found — skipping"
    continue
  fi
  ids+=("${id}")
done

if [[ ${#ids[@]} -eq 0 ]]; then
  echo "    no testimonial portraits found — nothing to regenerate"
  exit 0
fi

if [[ "$DRY_RUN" != "0" ]]; then
  echo "    [dry-run] would regenerate ${#ids[@]} testimonial portrait(s): ${ids[*]}"
  exit 0
fi

echo "    regenerating ${#ids[@]} testimonial portrait(s): ${ids[*]}"
wp media regenerate "${ids[@]}" --yes
echo "    testimonial portraits regenerated"
