#!/usr/bin/env bash
#
# Set the SEO title + meta description for the /start-here/ business-starting
# front-door page (WP page, slug "start-here").
#
# WHY THIS SCRIPT EXISTS: the page is anchor #1 of the IA rebuild and is built
# to win "start a metal roofing / rollforming business" intent, but the WP page
# shipped with no Yoast title/description, so the rendered <title> fell back to
# the bare page title and the meta description was empty. The fix lives in the
# DB (Yoast post meta), not git, so a fresh prod pull wipes it — this script
# makes it replayable via `npm run db:apply`.
#
# IDEMPOTENT: pure `meta update` (set to X). Re-running changes nothing.

set -euo pipefail

# Resolve the page by slug so the script survives a different post ID on a fresh
# prod pull (don't hardcode 20670).
page_id="$(wp post list --post_type=page --name="start-here" \
             --field=ID --format=ids 2>/dev/null || true)"

if [[ -z "${page_id}" ]]; then
  echo "    start-here page not found — skipping (page may not exist on this DB yet)"
  exit 0
fi

# ~52 chars. Leads with the start-a-business query, brands at the end.
seo_title='How to Start a Metal Roofing or Gutter Business | NTM'

# ~155 chars. Self-contained: the opportunity (make panels/gutters on the
# jobsite), and the "what it takes / where to begin" promise.
seo_desc='Make your own metal roofing panels and seamless gutters on the jobsite. See what it takes to start a portable rollforming business and where to begin.'

wp post meta update "${page_id}" _yoast_wpseo_title    "${seo_title}"
wp post meta update "${page_id}" _yoast_wpseo_metadesc  "${seo_desc}"

# Mirror to the Open Graph fields so the social/share snippet isn't empty
# either (Yoast renders og:title/og:description from these).
wp post meta update "${page_id}" _yoast_wpseo_opengraph-title       "${seo_title}"
wp post meta update "${page_id}" _yoast_wpseo_opengraph-description  "${seo_desc}"

echo "    set SEO title + meta description on page ${page_id} (start-here)"
