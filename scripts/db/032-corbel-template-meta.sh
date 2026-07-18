#!/usr/bin/env bash
#
# Migrate pages using the misspelled Crobel embed template to the corrected
# Corbel template filename.
#
# WHY THIS SCRIPT EXISTS: _wp_page_template is stored in the database. Renaming
# templates/template-crobel.php in git is not enough; any page assigned to the
# old filename would fall back to a default template after a fresh production DB
# pull unless this replayable migration updates the meta value.
#
# IDEMPOTENT: updates only rows that still point at the old value. Re-running
# finds zero rows and exits cleanly.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"

old_template='templates/template-crobel.php'
new_template='templates/template-corbel.php'

if ! prefix="$(wp db prefix 2>/dev/null)"; then
  if ! prefix="$(wp config get table_prefix 2>/dev/null)"; then
    echo "    ERROR: could not resolve table prefix" >&2
    exit 1
  fi
fi

count="$(wp db query \
  "SELECT COUNT(*) FROM ${prefix}postmeta WHERE meta_key = '_wp_page_template' AND meta_value = '${old_template}'" \
  --skip-column-names | tr -d '[:space:]')"

if [[ -z "${count}" || "${count}" == "0" ]]; then
  echo "    no pages use ${old_template} (no-op)"
  exit 0
fi

if [[ "$DRY_RUN" != "0" ]]; then
  echo "    [dry-run] would migrate ${count} page template value(s): ${old_template} -> ${new_template}"
  exit 0
fi

wp db query \
  "UPDATE ${prefix}postmeta SET meta_value = '${new_template}' WHERE meta_key = '_wp_page_template' AND meta_value = '${old_template}'" \
  >/dev/null

remaining="$(wp db query \
  "SELECT COUNT(*) FROM ${prefix}postmeta WHERE meta_key = '_wp_page_template' AND meta_value = '${old_template}'" \
  --skip-column-names | tr -d '[:space:]')"
if [[ "$remaining" != "0" ]]; then
  echo "    ERROR: ${remaining} page template value(s) still use ${old_template}" >&2
  exit 1
fi

echo "    migrated ${count} page template value(s): ${old_template} -> ${new_template}"
