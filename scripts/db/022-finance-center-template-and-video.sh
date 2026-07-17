#!/usr/bin/env bash
#
# Point /machines/leasing-financing/ at the new Finance Center template and
# wire its hero video.
#
# WHY THIS SCRIPT EXISTS: the page is being rebuilt as the NTM Finance Center.
# Two things it needs live in the DB, not git, so a fresh prod pull would wipe
# them:
#
#   1. _wp_page_template — the page shipped on the legacy "page-form.php" slug,
#      which routes to template-lead-form.php (prose column + sticky form). The
#      rebuild lives in page-finance-center.php and is selected per-page via
#      this meta key.
#
#   2. hero_video — the "How to Finance Your NTM Machine" Wistia video existed
#      only in WordPress's oembed cache (embedded inline in old content); the
#      ACF hero_video field was empty, so the hero rendered video-less. Setting
#      it lights up the two-column video hero. (The template also falls back to
#      this same media ID in code, so the page is never video-less even before
#      this runs — but the field is the source of truth, so we set it.)
#
# IDEMPOTENT: pure `meta update` (set to X). Re-running changes nothing.

set -euo pipefail

# Resolve by slug so the script survives a different post ID on a fresh prod
# pull (don't hardcode 217).
page_id="$(wp post list --post_type=page --name="leasing-financing" \
             --field=ID --format=ids 2>/dev/null || true)"

if [[ -z "${page_id}" ]]; then
  echo "    leasing-financing page not found — skipping (page may not exist on this DB yet)"
  exit 0
fi

template_slug='page-finance-center.php'

# Embeddable Wistia iframe URL for "How to Finance Your NTM Machine Video"
# (media hesm0txl1n). Must be the embed URL, not a share/landing URL: the
# hero-category facade uses this value directly as the iframe src on click
# (same embed shape /machines/ uses). A wistia.com/medias/<id> share URL would
# not load in an iframe.
hero_video='https://fast.wistia.net/embed/iframe/hesm0txl1n?seo=false&videoFoam=true'

wp post meta update "${page_id}" _wp_page_template "${template_slug}" || true
wp post meta update "${page_id}" hero_video        "${hero_video}" || true

echo "    set template=${template_slug} + hero_video on page ${page_id} (leasing-financing)"
