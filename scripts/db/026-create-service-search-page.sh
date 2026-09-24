#!/usr/bin/env bash
#
# Create /service-hub/search/ and point it at the Service Search template.
#
# WHY THIS SCRIPT EXISTS: the dedicated service-content search results page
# (template-service-search.php) needs a real WP page to route to. The page is a
# DB object, so a fresh prod pull would wipe it. This recreates it idempotently:
#
#   - Page "Service Search", slug `search`, parent = the service-hub page →
#     URL /service-hub/search/.
#   - _wp_page_template = templates/template-service-search.php.
#
# The page only exists to host the template (its post_content is empty; the
# template renders everything). Resolved by slug + parent so it survives a
# different post ID on a fresh pull.
#
# IDEMPOTENT: create-if-missing, then set parent + template every run.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"

template_slug='templates/template-service-search.php'

# Parent: the Service Hub landing page (slug service-hub).
parent_id="$(wp post list --post_type=page --name="service-hub" \
               --field=ID --format=ids 2>/dev/null | head -n1 || true)"

if [[ -z "${parent_id}" ]]; then
  echo "    service-hub page not found — skipping (parent must exist first)"
  exit 0
fi

# Find an existing `search` child of service-hub (don't collide with any other
# top-level /search/). post_parent filters the lookup to this branch.
page_id="$(wp post list --post_type=page --name="search" --post_parent="${parent_id}" \
             --field=ID --format=ids 2>/dev/null | head -n1 || true)"

if [[ -z "${page_id}" ]]; then
  if [[ "$DRY_RUN" != "0" ]]; then
    echo "    [dry-run] would create Service Search page under service-hub (${parent_id})"
    echo "    [dry-run] would set template=${template_slug} and flush rewrite rules"
    exit 0
  fi

  page_id="$(wp post create --post_type=page --post_status=publish \
               --post_title="Service Search" --post_name="search" \
               --post_parent="${parent_id}" --porcelain)"
  if [[ -z "$page_id" || ! "$page_id" =~ ^[0-9]+$ ]]; then
    echo "    ERROR: failed to create Service Search page" >&2
    exit 1
  fi
  echo "    created Service Search page ${page_id} under service-hub (${parent_id})"
else
  if [[ "$DRY_RUN" != "0" ]]; then
    echo "    [dry-run] would assert Service Search page ${page_id}: parent=${parent_id}, status=publish"
    echo "    [dry-run] would set template=${template_slug} on page ${page_id}"
    echo "    [dry-run] would flush rewrite rules"
    exit 0
  fi

  current_parent="$(wp post get "${page_id}" --field=post_parent 2>/dev/null)"
  current_status="$(wp post get "${page_id}" --field=post_status 2>/dev/null)"

  if [[ "$current_parent" != "$parent_id" || "$current_status" != "publish" ]]; then
    wp post update "${page_id}" --post_parent="${parent_id}" --post_status=publish >/dev/null
  fi

  actual_parent="$(wp post get "${page_id}" --field=post_parent 2>/dev/null)"
  actual_status="$(wp post get "${page_id}" --field=post_status 2>/dev/null)"
  if [[ "$actual_parent" != "$parent_id" || "$actual_status" != "publish" ]]; then
    echo "    ERROR: Service Search page ${page_id} did not persist parent/status" >&2
    exit 1
  fi

  echo "    found Service Search page ${page_id} (parent ${actual_parent}, status ${actual_status})"
fi

current_template="$(wp post meta get "${page_id}" _wp_page_template 2>/dev/null || true)"
if [[ "$current_template" != "$template_slug" ]]; then
  wp post meta update "${page_id}" _wp_page_template "${template_slug}" >/dev/null
fi
actual_template="$(wp post meta get "${page_id}" _wp_page_template 2>/dev/null || true)"
if [[ "$actual_template" != "$template_slug" ]]; then
  echo "    ERROR: template did not persist on page ${page_id}" >&2
  exit 1
fi
echo "    set template=${template_slug} on page ${page_id} (service-hub/search)"

# New page = new permalink; flush so /service-hub/search/ resolves immediately.
wp rewrite flush >/dev/null
echo "    flushed rewrite rules"
