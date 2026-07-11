#!/usr/bin/env bash
#
# Strip hardcoded CTA buttons from machine product short descriptions
# (post_excerpt). The theme renders its own CTAs ("Open Configurator" /
# "Explore Financing") in templates/woo/product/parts/configurator-finance.php,
# so button markup baked into the excerpt is duplicate content.
#
# WHY THIS SCRIPT EXISTS: a fresh DB pull can bring the old excerpt CTAs back.
# This replayable migration removes them without relying on local hand-edits.
#
# WHAT IT DOES: runs a PHP cleanup scoped to published WooCommerce products in
# exactly these product_cat slugs: gutter-machines and roof-wall-panel-machines.
# It removes only a trailing <div> whose contents are one to three anchors and
# every anchor has a tokenized "btn" class. Ordinary inline links in the copy
# are left untouched.
#
# SAFE BY DESIGN: idempotent, scoped by taxonomy + publish status, and guarded
# by the trailing button-container check. A second run is a no-op.
#
# Resolves: data-normalization-backlog.md #8

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PHP_FILE="$HERE/010-strip-hardcoded-buttons-from-machine-excerpts.php"

if [[ ! -f "$PHP_FILE" ]]; then
  echo "    ERROR: missing PHP companion: $PHP_FILE" >&2
  exit 1
fi

# Normal db:apply runs provide wp(). This fallback keeps the migration runnable
# by itself while preserving the same target defaults.
if ! declare -F wp >/dev/null; then
  WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
  WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
  WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

  wp() {
    if [[ -n "$WP_CONTAINER" ]]; then
      docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "$WP_PHP_BIN" \
        /usr/local/bin/wp --path="$WP_PATH" --allow-root "$@"
    else
      command wp --path="$WP_PATH" "$@"
    fi
  }
fi

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$PHP_FILE")"
  docker cp "$PHP_FILE" "${WP_CONTAINER}:${in_container}" >/dev/null
  trap 'docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true' EXIT
  wp eval-file "$in_container"
else
  wp eval-file "$PHP_FILE"
fi
