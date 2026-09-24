#!/usr/bin/env bash
#
# Replay the Service Hub content_department classification.
#
# WHY THIS SCRIPT EXISTS: ~600 posts/videos were classified into the
# content_department taxonomy (Sales / Service & Repair / Training) that powers
# the Service Hub and Learning Center department filters. The assignments were
# originally imported from a one-off CSV that never made it into the repo — a
# fresh prod pull silently empties every department filter. The canonical copy
# now lives at db/imports/content-departments.csv (Post ID,Department), keyed
# by post IDs that are stable across prod pulls (collision-range IDs were
# excluded at export time; `wp ntm service-hub import-csv` skips missing IDs).
#
# Content added on prod after this export lands unclassified — editors tag it
# in admin, or re-export the CSV (see docs/deploy/prod-content-merge-plan.md).
#
# IDEMPOTENT: wp_set_object_terms replaces, so re-runs converge. The CSV is
# authoritative — a re-run also reverts any department edits made in admin
# after the last export, so re-export before re-running if editors have been
# reclassifying.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"

csv="${CONTENT_DEPT_CSV-$WP_PATH/wp-content/themes/amt-ntm/db/imports/content-departments.csv}"

if [[ "$DRY_RUN" != "0" ]]; then
  echo "    [dry-run] would import content_department assignments from ${csv}"
  exit 0
fi

wp ntm service-hub import-csv "$csv"
