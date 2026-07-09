#!/usr/bin/env bash
#
# Regenerate the 'product-card' image size so it stops clipping square /
# product-shape images.
#
# WHY THIS SCRIPT EXISTS: the theme used to register
#   add_image_size('product-card', 640, 360, true)   // true = HARD CROP
# so WordPress generated a 640×360 file already center-cropped to 16:9. The card
# CSS (object-contain p-6) then "fit" that pre-cropped file — so a square machine
# or accessory shot lost its top/bottom at the FILE level before CSS ran. The
# letterboxing the CSS comment promised never happened.
#
# The theme now registers it as a bounding box (crop = false), so WP scales the
# source to fit WITHIN 640×360 keeping aspect. But add_image_size only governs
# NEW uploads — existing attachments keep their already-cropped product-card
# files until regenerated. A fresh prod DB pull re-imports media metadata, so
# this regen has to be replayable, not a one-time hand run.
#
# SAFE BY DESIGN: --image_size limits regen to the 'product-card' size only;
# 'card-thumbnail', 'full', and every other size are untouched. --only-missing is
# NOT used — we WANT it to overwrite the stale cropped files. Re-running is
# idempotent: it just rebuilds the same size from the originals again.
#
# Resolves: square images clipped in product/accessory cards.

set -euo pipefail

# Regenerate just the product-card size, no confirmation prompt, across all
# attachments. Yes/no is auto-answered so the script is non-interactive under
# `npm run db:apply`.
#
# FAIL-SOFT: `media regenerate` exits non-zero if ANY attachment fails, which
# would abort the whole db:apply run (set -e). Thumbnails are best-effort —
# the known benign failure is PDF attachments where ImageMagick's security
# policy forbids PDF rasterization (DevKinsta's container default). Warn and
# continue instead of killing the replay pipeline.
if ! wp media regenerate --image_size=product-card --yes; then
  echo "    !! some product-card thumbnails failed to regenerate (see above) — continuing."
fi

echo "    product-card thumbnails regenerated (no-crop 16:9 bounding box)."
