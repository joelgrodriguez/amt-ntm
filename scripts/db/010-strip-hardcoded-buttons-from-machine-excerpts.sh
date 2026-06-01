#!/usr/bin/env bash
#
# Strip hardcoded CTA buttons from machine product short descriptions
# (post_excerpt). The theme renders its own CTAs — "Open Configurator" /
# "Explore Financing" in templates/woo/product/parts/configurator-finance.php —
# so any button baked into the excerpt is pure duplication and must be removed.
#
# WHY THIS SCRIPT EXISTS: this was done by hand once. A fresh prod DB pull brings
# the buttons back (the edit lived only in the local DB, not git). This makes the
# fix replayable: it runs on every `npm run db:apply`.
#
# SAFE BY DESIGN: the theme's CTAs are independent of the excerpt, so removing
# the excerpt button cannot remove a CTA the page needs. We only strip the button
# markup, never the surrounding sales copy.
#
# Resolves: data-normalization-backlog.md #8
#
# ⚠️ PATTERN_TODO — NOT YET FINALIZED.
# This local DB already has the buttons stripped, so the exact prod markup could
# not be observed when authoring. Before trusting this script:
#   1. On the NEXT fresh prod pull (buttons present), dump a machine excerpt:
#        wp post list --post_type=product --field=ID \
#          --product_cat=roof-wall-panel-machines | head -1   # get an ID
#        wp post get <ID> --field=post_excerpt
#   2. Copy the real button markup here and lock BUTTON_PATTERN below.
#   3. Dry-run (PRINT, don't write) and eyeball every diff before enabling writes.
# Until step 2 is done, this script runs in DRY-RUN and changes nothing.

set -euo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: print, don't write. Set DRY_RUN=0 to apply.

# Machine product categories whose excerpts carried the buttons.
CATS="roof-wall-panel-machines,gutter-machines"

# TODO: replace with the real markup once observed on a fresh prod pull.
# Likely an anchor with a btn class appended to the excerpt, e.g.
#   <a class="btn ..." href="...">Get a Quote</a>
# or a shortcode like [button ...]...[/button]. Confirm against real data.
BUTTON_PATTERN='PATTERN_TODO'

if [[ "$BUTTON_PATTERN" == "PATTERN_TODO" ]]; then
  echo "    SKIPPED: button markup pattern not yet finalized (see PATTERN_TODO)."
  echo "    Capture real markup on the next prod pull, then lock BUTTON_PATTERN."
  exit 0
fi

ids="$(wp post list --post_type=product --product_cat="$CATS" \
        --post_status=publish --field=ID --format=ids)"

for id in $ids; do
  excerpt="$(wp post get "$id" --field=post_excerpt)"
  # PHP-side strip keeps regex semantics consistent and handles multiline HTML.
  cleaned="$(printf '%s' "$excerpt" | wp eval-file /dev/stdin <<'PHP'
$in  = stream_get_contents(STDIN);
$out = preg_replace('#'.getenv('BUTTON_PATTERN').'#is', '', $in);
echo trim($out);
PHP
  )"
  if [[ "$cleaned" != "$excerpt" ]]; then
    if [[ "$DRY_RUN" == "0" ]]; then
      wp post update "$id" --post_excerpt="$cleaned" >/dev/null
      echo "    stripped button from product $id"
    else
      echo "    [dry-run] product $id has a button to strip"
    fi
  fi
done
