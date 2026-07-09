#!/usr/bin/env bash
#
# Create (or adopt) the theme's template-shell pages.
#
# WHY THIS SCRIPT EXISTS: the theme routes many surfaces through real WP pages
# that exist only to host a page-<slug>.php template (Service Hub, Start Here,
# the configurator entry pages, Owner Support, the comparison pages, ...).
# The pages are DB objects, so a fresh prod pull wipes them and every link
# into those surfaces 404s. This recreates them idempotently, resolved by
# slug (+ parent) so they survive different post IDs on a fresh pull.
#
# ADOPTION: /machines/upgrades/ is prod's existing "NTM Accessories" page
# re-slugged (ntm-accessories -> upgrades; the old->new redirect is captured
# in db/redirects.json). On a fresh pull we rename prod's page instead of
# creating a duplicate next to it.
#
# NOTE: 026-create-service-search-page.sh skips silently when service-hub is
# missing, so this script must run before it (014 < 026 in filename order).
# The trailer (028) and safety (029/030) pages have their own scripts.
#
# IDEMPOTENT: adopt-if-renamed, create-if-missing, then re-assert parent +
# template + status every run.

set -euo pipefail

find_page() { # slug parent_id -> id or empty
  wp post list --post_type=page --name="$1" --post_parent="$2" \
    --field=ID --format=ids 2>/dev/null | awk '{print $1}' || true
}

machines_id="$(wp post list --post_type=page --name=machines \
                 --field=ID --format=ids 2>/dev/null | awk '{print $1}' || true)"

# --- Adoption: ntm-accessories -> upgrades (under /machines/) ---------------
if [[ -n "$machines_id" ]]; then
  if [[ -z "$(find_page upgrades "$machines_id")" ]]; then
    old_id="$(find_page ntm-accessories "$machines_id")"
    if [[ -n "$old_id" ]]; then
      wp post update "$old_id" --post_name=upgrades >/dev/null
      echo "    adopted 'ntm-accessories' (#$old_id) -> slug 'upgrades'"
    fi
  fi
fi

# slug|title|parent-slug (empty = top level)|template|content
pages=(
  'service-hub|Service Hub||templates/template-service-hub.php|'
  'service-hub-alt|Service Hub Alt||templates/template-service-hub-alt.php|'
  'request|Open a service request|service-hub|page-service-request.php|'
  'machii|MACH II Family|machines|page-machii.php|'
  'upgrades|NTM Accessories|machines|page-accessories.php|'
  'choose-your-machine|Choose Your Machine||page-choose-your-machine.php|Placeholder. See template.'
  'add-a-machine|Add a Machine||page-add-a-machine.php|Placeholder. See template.'
  'request-parts|Request Parts||page-request-parts.php|Placeholder. See template.'
  'start-here|Start Here||page-start-here.php|Placeholder. See template.'
  'owner-support|Owner Support||page-owner-support.php|Placeholder. See template.'
  'how-buying-works|How Buying Works||page-how-buying-works.php|Placeholder. See template.'
  'compare-roof-panel-machines|Compare Roof Panel Machines||page-compare-roof-panel-machines.php|Placeholder. See template.'
  'roof-panel-vs-gutter|Roof Panel vs Gutter||page-roof-panel-vs-gutter.php|Placeholder. See template.'
  'first-time-buyer-playlist|First-Time Buyer Playlist||page-first-time-buyer-playlist.php|Placeholder. See template.'
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
  page_id="$(find_page "$slug" "$parent_id")"

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
