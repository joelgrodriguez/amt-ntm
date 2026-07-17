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
  page_id="$(wp post create --post_type=page --post_status=publish \
               --post_title="Service Search" --post_name="search" \
               --post_parent="${parent_id}" --porcelain)"
  echo "    created Service Search page ${page_id} under service-hub (${parent_id})"
else
  # Ensure it's published and correctly parented on re-runs.
  wp post update "${page_id}" --post_parent="${parent_id}" --post_status=publish >/dev/null || true
  echo "    found Service Search page ${page_id} (re-parented to ${parent_id})"
fi

wp post meta update "${page_id}" _wp_page_template "${template_slug}" >/dev/null || true
echo "    set template=${template_slug} on page ${page_id} (service-hub/search)"

# New page = new permalink; flush so /service-hub/search/ resolves immediately.
wp rewrite flush >/dev/null 2>&1 || true
echo "    flushed rewrite rules"
