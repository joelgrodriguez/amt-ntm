#!/usr/bin/env bash
#
# Unpublish the duplicate UNIQ Automatic Control System (issue #96).
#
# WHY THIS SCRIPT EXISTS: two UNIQ automatic-controller accessories were listed:
#
#   id 2799  "UNIQ Automatic Control System"           $21,700  (no USB port)
#   id 18732 "UNIQ Automatic Control System UNQ-SSQ3-A" $22,500  (USB port)
#
# Per NTM (Joel, from Hailey): there should be only ONE UNIQ automatic controller
# listed as an accessory for the SSQ II and SSQ3, at the $22,500 price. That's
# the keeper (18732). The $21,700 duplicate (2799) is removed by setting it to
# DRAFT (kept in admin, off the storefront).
#
# Its public URL (/machines/accessories-add-on-equipment/uniq-control-system/)
# is redirected to the keeper in the same task via db/redirects.json, so the old
# link 301s instead of 404ing. Product status is DB state wiped by a fresh prod
# pull, so this must be replayable.
#
# SAFETY: resolves both products by ID and asserts exact product type, title,
# slug, status where relevant, and price before writing. The keeper must be the
# published $22,500 product before the $21,700 duplicate can be drafted.
# Mismatch => fail loudly.
#
# IDEMPOTENT: no-op if 2799 is already draft. DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
php_file="$HERE/049-draft-duplicate-uniq-controller.php"

echo "== 049 draft duplicate UNIQ controller (DRY_RUN=${DRY_RUN}) =="

if [[ ! -f "$php_file" ]]; then
  echo "FAIL: migration payload missing: $php_file" >&2
  exit 1
fi

status=0
if [[ -n "$WP_CONTAINER" ]]; then
  in_container="/tmp/$(basename "$php_file")"
  if ! docker cp "$php_file" "${WP_CONTAINER}:${in_container}" >/dev/null; then
    echo "FAIL: could not copy migration payload into ${WP_CONTAINER}" >&2
    exit 1
  fi
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "$WP_PHP_BIN" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container" || status=$?
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_file" || status=$?
fi

if [[ "$status" -ne 0 ]]; then
  echo "== 049 failed =="
  exit "$status"
fi

echo "== 049 done =="
