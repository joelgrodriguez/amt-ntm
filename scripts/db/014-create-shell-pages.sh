#!/usr/bin/env bash
#
# Create the theme's template-shell pages.
#
# WHY THIS SCRIPT EXISTS: five theme surfaces route through real WP pages that
# exist only to host a template (Service Hub, MACH II Family, the configurator
# entry/add pages, Request Parts). The pages are DB objects, so a fresh prod
# pull wipes them and every link into those surfaces 404s. This recreates them
# idempotently, resolved by slug (+ parent for /machines/machii/) so they
# survive different post IDs on a fresh pull.
#
# NOTE: 026-create-service-search-page.sh skips silently when service-hub is
# missing, so this script must run before it (014 < 026 in filename order).
#
# IDEMPOTENT: create-if-missing, then re-assert parent + template + status
# every run.

set -euo pipefail

# slug|title|parent-slug (empty = top level)|template|content
pages=(
  'service-hub|Service Hub||templates/template-service-hub.php|'
  'machii|MACH II Family|machines|page-machii.php|'
  'choose-your-machine|Choose Your Machine||page-choose-your-machine.php|Placeholder. See template.'
  'add-a-machine|Add a Machine||page-add-a-machine.php|Placeholder. See template.'
  'request-parts|Request Parts||page-request-parts.php|Placeholder. See template.'
)

for row in "${pages[@]}"; do
  IFS='|' read -r slug title parent_slug template content <<<"$row"

  parent_id=0
  if [[ -n "$parent_slug" ]]; then
    parent_id="$(wp post list --post_type=page --name="$parent_slug" \
                   --field=ID --format=ids 2>/dev/null | awk '{print $1}' || true)"
    if [[ -z "$parent_id" ]]; then
      echo "    parent '$parent_slug' not found — creating '$slug' at top level"
      parent_id=0
    fi
  fi

  # Match slug AND parent: prod has its own unrelated pages reusing these
  # slugs under other parents (e.g. a second published 'machii').
  page_id="$(wp post list --post_type=page --name="$slug" --post_parent="$parent_id" \
               --field=ID --format=ids 2>/dev/null | awk '{print $1}' || true)"

  if [[ -z "$page_id" ]]; then
    page_id="$(wp post create --post_type=page --post_status=publish \
                 --post_title="$title" --post_name="$slug" \
                 --post_parent="$parent_id" --post_content="$content" --porcelain)"
    echo "    created '$slug' (#$page_id)"
  else
    echo "    '$slug' exists (#$page_id)"
  fi

  # Re-assert the bits the theme depends on, every run.
  wp post update "$page_id" --post_status=publish --post_parent="$parent_id" >/dev/null
  wp post meta update "$page_id" _wp_page_template "$template" >/dev/null
done

echo "    shell pages OK"
