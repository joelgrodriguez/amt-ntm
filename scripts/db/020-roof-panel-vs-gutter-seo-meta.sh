#!/usr/bin/env bash
#
# Set the SEO title + meta description for the /roof-panel-vs-gutter/ decision
# page (WP page, slug "roof-panel-vs-gutter").
#
# WHY THIS SCRIPT EXISTS: the page template is meticulously built for SEO/AEO,
# but the WP page itself shipped with no Yoast title/description, so the rendered
# <title> fell back to the bare page title and the meta description rendered the
# placeholder excerpt ("Placeholder. See template."). That excerpt text is the
# literal Google snippet for the exact query this page is meant to win. The fix
# lives in the DB (Yoast post meta), not git, so a fresh prod pull wipes it —
# this script makes it replayable via `npm run db:apply`.
#
# IDEMPOTENT: pure `meta update` (set to X). Re-running changes nothing.
#
# Resolves: roof-panel-vs-gutter critique P0 (placeholder <title>/meta description)

set -euo pipefail

# Resolve the page by slug so the script survives a different post ID on a fresh
# prod pull (don't hardcode 20675).
page_id="$(wp post list --post_type=page --name="roof-panel-vs-gutter" \
             --field=ID --format=ids 2>/dev/null || true)"

if [[ -z "${page_id}" ]]; then
  echo "    roof-panel-vs-gutter page not found — skipping (page may not exist on this DB yet)"
  exit 0
fi

# ~57 chars. Leads with the query, states the payoff, brands at the end.
seo_title='Roof Panel vs. Gutter Machine: Which Do You Need? | NTM'

# ~155 chars. Self-contained answer for the snippet: names both families, the
# deciding factor (what you make), and that NTM builds both.
seo_desc='Roof and wall panel machines form the metal panels for a roof; seamless gutter machines form the gutters that drain it. See which NTM machine your work needs.'

wp post meta update "${page_id}" _yoast_wpseo_title    "${seo_title}" || true
wp post meta update "${page_id}" _yoast_wpseo_metadesc  "${seo_desc}" || true

# Mirror to the Open Graph fields so the social/share snippet isn't the
# placeholder either (Yoast renders og:title/og:description from these).
wp post meta update "${page_id}" _yoast_wpseo_opengraph-title       "${seo_title}" || true
wp post meta update "${page_id}" _yoast_wpseo_opengraph-description  "${seo_desc}" || true

echo "    set SEO title + meta description on page ${page_id} (roof-panel-vs-gutter)"
