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

DRY_RUN="${DRY_RUN-1}"

find_page() { # slug parent_id -> id or empty
  wp post list --post_type=page --name="$1" --post_parent="$2" \
    --field=ID --format=ids 2>/dev/null | awk '{print $1}' || true
}

is_dry_run() {
  [[ "$DRY_RUN" != "0" ]]
}

assert_post_field() { # post_id field expected context
  local post_id="$1"
  local field="$2"
  local expected="$3"
  local context="$4"
  local actual

  actual="$(wp post get "$post_id" --field="$field" 2>/dev/null)"
  if [[ "$actual" != "$expected" ]]; then
    echo "    ERROR: ${context}: expected ${field}='${expected}', got '${actual}'" >&2
    exit 1
  fi
}

assert_post_meta() { # post_id key expected context
  local post_id="$1"
  local key="$2"
  local expected="$3"
  local context="$4"
  local actual

  actual="$(wp post meta get "$post_id" "$key" 2>/dev/null)"
  if [[ "$actual" != "$expected" ]]; then
    echo "    ERROR: ${context}: expected ${key}='${expected}', got '${actual}'" >&2
    exit 1
  fi
}

machines_id="$(wp post list --post_type=page --name=machines \
                 --field=ID --format=ids 2>/dev/null | awk '{print $1}' || true)"

# --- Adoption: ntm-accessories -> upgrades (under /machines/) ---------------
if [[ -n "$machines_id" ]]; then
  if [[ -z "$(find_page upgrades "$machines_id")" ]]; then
    old_id="$(find_page ntm-accessories "$machines_id")"
    if [[ -n "$old_id" ]]; then
      if is_dry_run; then
        echo "    [dry-run] would adopt 'ntm-accessories' (#$old_id) -> slug 'upgrades'"
      else
        wp post update "$old_id" --post_name=upgrades >/dev/null
        assert_post_field "$old_id" post_name upgrades "adopt ntm-accessories"
        echo "    adopted 'ntm-accessories' (#$old_id) -> slug 'upgrades'"
      fi
    fi
  fi

  # --- Re-parenting: manuals + footprints live at /machines/<slug>/ ---------
  # Prod has these as top-level pages; the theme's IA (and the captured
  # /manuals/ + /footprints/ redirects) expect them under /machines/. Move
  # the top-level published page instead of creating a duplicate.
  for move_slug in manuals footprints; do
    if [[ -z "$(find_page "$move_slug" "$machines_id")" ]]; then
      stray_id="$(find_page "$move_slug" 0)"
      if [[ -n "$stray_id" ]]; then
        if is_dry_run; then
          echo "    [dry-run] would re-parent '$move_slug' (#$stray_id) under /machines/"
        else
          wp post update "$stray_id" --post_parent="$machines_id" >/dev/null
          assert_post_field "$stray_id" post_parent "$machines_id" "re-parent ${move_slug}"
          echo "    re-parented '$move_slug' (#$stray_id) under /machines/"
        fi
      fi
    fi
  done
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
    if is_dry_run; then
      echo "    [dry-run] would create '$slug' under parent ${parent_id} with template '${template}'"
      continue
    fi

    page_id="$(wp post create --post_type=page --post_status=publish \
                 --post_title="$title" --post_name="$slug" \
                 --post_parent="$parent_id" --post_content="$content" --porcelain)"
    if [[ -z "$page_id" || ! "$page_id" =~ ^[0-9]+$ ]]; then
      echo "    ERROR: failed to create '$slug' page" >&2
      exit 1
    fi
    echo "    created '$slug' (#$page_id)"
  else
    echo "    '$slug' exists (#$page_id)"
  fi

  # Re-assert the bits the theme depends on, every run.
  if is_dry_run; then
    echo "    [dry-run] would assert '$slug' (#$page_id): status=publish, parent=${parent_id}, template=${template}"
    continue
  fi

  current_status="$(wp post get "$page_id" --field=post_status 2>/dev/null)"
  current_parent="$(wp post get "$page_id" --field=post_parent 2>/dev/null)"
  if [[ "$current_status" != "publish" || "$current_parent" != "$parent_id" ]]; then
    wp post update "$page_id" --post_status=publish --post_parent="$parent_id" >/dev/null
  fi
  assert_post_field "$page_id" post_status publish "assert ${slug} status"
  assert_post_field "$page_id" post_parent "$parent_id" "assert ${slug} parent"

  current_template="$(wp post meta get "$page_id" _wp_page_template 2>/dev/null || true)"
  if [[ "$current_template" != "$template" ]]; then
    wp post meta update "$page_id" _wp_page_template "$template" >/dev/null
  fi
  assert_post_meta "$page_id" _wp_page_template "$template" "assert ${slug} template"
done

echo "    shell pages OK"
