#!/usr/bin/env bash
#
# Replay repo-captured redirects from db/redirects.json.
#
# WHY THIS SCRIPT EXISTS: redirects are Redirection-plugin DB rows, wiped by a
# fresh prod pull. db/redirects.json (native plugin export) is the capture;
# this replays only the entries whose source URL is missing, because the fresh
# prod DB already contains most of them and the plugin's own import would
# duplicate those. Logic lives in 037-import-redirects.php (wp eval-file) so
# it can use the plugin's Red_Item API.
#
# IDEMPOTENT: skip-if-source-url-exists.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"

theme="${THEME_DIR-$WP_PATH/wp-content/themes/amt-ntm}"

wp eval-file "$theme/scripts/db/037-import-redirects.php" \
  "$theme/db/redirects.json"
