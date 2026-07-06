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

old_template='templates/template-crobel.php'
new_template='templates/template-corbel.php'

prefix="$(wp db prefix 2>/dev/null || wp config get table_prefix 2>/dev/null || true)"
if [[ -z "${prefix}" ]]; then
  echo "    could not resolve table prefix — skipping Corbel template migration"
  exit 0
fi

count="$(wp db query \
  "SELECT COUNT(*) FROM ${prefix}postmeta WHERE meta_key = '_wp_page_template' AND meta_value = '${old_template}'" \
  --skip-column-names 2>/dev/null | tr -d '[:space:]' || true)"

if [[ -z "${count}" || "${count}" == "0" ]]; then
  echo "    no pages use ${old_template} (no-op)"
  exit 0
fi

wp db query \
  "UPDATE ${prefix}postmeta SET meta_value = '${new_template}' WHERE meta_key = '_wp_page_template' AND meta_value = '${old_template}'" \
  >/dev/null

echo "    migrated ${count} page template value(s): ${old_template} -> ${new_template}"
