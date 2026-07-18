#!/usr/bin/env bash
#
# Sideload the Corbel logo into the media library for the Finance Center
# "preferred lender" featured row.
#
# WHY THIS SCRIPT EXISTS: the Finance Center lender directory
# (app/templates/pages/finance-center/lenders.php) features Corbel as the
# preferred lender at the top, rendering its logo through responsive_image()
# against a WordPress attachment. The image + attachment row live only in the
# DB/uploads, which a fresh prod pull wipes — so the import has to be
# replayable. Mirrors 040's title-stable, idempotent approach.
#
# WHAT IT DOES:
#   1. Fetches the Corbel Open Graph card (white wordmark on green gradient)
#      from Corbel's CDN into wp-content/uploads.
#   2. Imports it as an attachment titled "Corbel preferred lender", alt "Corbel".
#   3. Idempotent: if an attachment with that exact title already exists, it is
#      reused instead of importing a duplicate.
#
# The template resolves the logo by its stable upload-relative path
# (2026/07/corbel-preferred-lender.png). If a re-import lands the file in a
# different year/month folder, update the $uploads path in lenders.php OR
# re-run this against the same target month; responsive_image() falls back to
# the raw URL when no attachment matches, so the row never blanks.
#
# SOURCE: https://cdn.prod.website-files.com/686fdc321593f675bf186a20/68c7f5d1119e14b64d156be6_37e3f857af6e10ef41879537e8081c10_Corbel%20-%20Open%20Graph.png
#
# SAFE BY DESIGN: single attachment, resolves/reuses by stable title,
# idempotent. DRY_RUN=1 by default; DRY_RUN=0 to write.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP="docker exec ${WP_CONTAINER} php8.3 /usr/local/bin/wp --path=${WP_PATH} --allow-root"

SRC_URL="https://cdn.prod.website-files.com/686fdc321593f675bf186a20/68c7f5d1119e14b64d156be6_37e3f857af6e10ef41879537e8081c10_Corbel%20-%20Open%20Graph.png"
TITLE="Corbel preferred lender"
FILENAME="corbel-preferred-lender.png"
STAGE="${WP_PATH}/wp-content/uploads/${FILENAME}"

# Reuse if an attachment with this exact title already exists (idempotent).
existing="$($WP post list --post_type=attachment --title="${TITLE}" --field=ID --posts_per_page=1 2>/dev/null | head -1)"
if [ -n "${existing}" ]; then
  echo "OK: attachment '${TITLE}' already exists (ID ${existing}); nothing to do."
  exit 0
fi

if [ "${DRY_RUN}" != "0" ]; then
  echo "[DRY_RUN] would fetch ${SRC_URL}"
  echo "[DRY_RUN] would import as attachment titled '${TITLE}' (alt 'Corbel')"
  echo "Set DRY_RUN=0 to apply."
  exit 0
fi

# Fetch into the container's uploads dir, import, then remove the staged copy
# (media import copies the file into the year/month structure itself).
cleanup_stage() {
  docker exec "${WP_CONTAINER}" rm -f "${STAGE}" >/dev/null 2>&1 || true
}
trap cleanup_stage EXIT

docker exec "${WP_CONTAINER}" bash -lc "curl -fsSL '${SRC_URL}' -o '${STAGE}'"
id="$($WP media import "${STAGE}" --title="${TITLE}" --alt="Corbel" --porcelain | tail -1)"

if [[ -n "${id}" && "${id}" =~ ^[0-9]+$ ]]; then
  url="$($WP post get "${id}" --field=guid 2>/dev/null)"
  echo "OK: imported '${TITLE}' as attachment ID ${id} -> ${url}"
else
  echo "ERROR: media import returned no attachment ID" >&2
  exit 1
fi
