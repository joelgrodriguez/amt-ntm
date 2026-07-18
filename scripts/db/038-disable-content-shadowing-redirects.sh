#!/usr/bin/env bash
#
# Disable legacy Redirection rows that shadow live content under the new IA.
#
# WHY THIS SCRIPT EXISTS: prod's redirect set predates the new theme's
# information architecture. Rows like /machines/manuals -> /learning-center/
# resource/manuals/ fire before WP routes, hijacking pages that are live
# again — and together with the repo-captured reverse redirect they form an
# infinite 301 loop. Logic lives in 038-*.php (wp eval-file): any enabled,
# non-regex redirect whose source resolves to a published page/post is
# disabled; true legacy sources stay.
#
# Runs after 014 (pages must exist to be detected) and after 037 (imports
# first, then prune what shadows).
#
# IDEMPOTENT: disabled rows are skipped on re-runs.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"

theme="${THEME_DIR-$WP_PATH/wp-content/themes/amt-ntm}"

wp eval-file "$theme/scripts/db/038-disable-content-shadowing-redirects.php"
