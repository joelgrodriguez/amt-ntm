#!/usr/bin/env bash
#
# Create /gutter-machine-roi-calculator/ and point it at the ROI Calculator
# template.
#
# WHY THIS SCRIPT EXISTS: the gutter machine ROI calculator is built in-theme
# (template-roi-calculator.php + RoiCalculator.js, issue #131), but it needs a
# real WP page to route to. That page is a DB object, so a fresh prod pull would
# wipe it and the calculator would 404. This recreates it idempotently:
#
#   - Page "Gutter Machine ROI Calculator", slug
#     `gutter-machine-roi-calculator`, top level.
#   - _wp_page_template = templates/template-roi-calculator.php.
#
# The page exists only to host the template; post_content stays empty because
# the template renders everything. Resolved by slug, so it survives a different
# post ID on a fresh pull.
#
# IDEMPOTENT: create-if-missing, then assert status + template every run.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"

page_title='Gutter Machine ROI Calculator'
page_slug='gutter-machine-roi-calculator'
template_slug='templates/template-roi-calculator.php'

# Top-level lookup only. post_parent=0 keeps this from adopting a same-slug
# child page that belongs to some other branch.
page_id="$(wp post list --post_type=page --name="${page_slug}" --post_parent=0 \
             --post_status=any --field=ID --format=ids 2>/dev/null | head -n1 || true)"

if [[ -z "${page_id}" ]]; then
  if [[ "$DRY_RUN" != "0" ]]; then
    echo "    [dry-run] would create page \"${page_title}\" (/${page_slug}/)"
    echo "    [dry-run] would set template=${template_slug} and flush rewrite rules"
    exit 0
  fi

  page_id="$(wp post create --post_type=page --post_status=publish \
               --post_title="${page_title}" --post_name="${page_slug}" --porcelain)"
  if [[ -z "$page_id" || ! "$page_id" =~ ^[0-9]+$ ]]; then
    echo "    ERROR: failed to create ROI calculator page" >&2
    exit 1
  fi
  echo "    created ROI calculator page ${page_id} (/${page_slug}/)"
else
  if [[ "$DRY_RUN" != "0" ]]; then
    echo "    [dry-run] would assert page ${page_id} published"
    echo "    [dry-run] would set template=${template_slug} on page ${page_id}"
    exit 0
  fi

  current_status="$(wp post get "${page_id}" --field=post_status 2>/dev/null)"
  if [[ "$current_status" != "publish" ]]; then
    wp post update "${page_id}" --post_status=publish >/dev/null
  fi

  actual_status="$(wp post get "${page_id}" --field=post_status 2>/dev/null)"
  if [[ "$actual_status" != "publish" ]]; then
    echo "    ERROR: ROI calculator page ${page_id} did not persist status" >&2
    exit 1
  fi

  echo "    found ROI calculator page ${page_id} (status ${actual_status})"
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
echo "    set template=${template_slug} on page ${page_id} (/${page_slug}/)"

# New page = new permalink; flush so the URL resolves immediately.
wp rewrite flush >/dev/null
echo "    flushed rewrite rules"
