#!/usr/bin/env bash
#
# Assign the Full Height Embed template to quiz/assessment pages that ship
# only a third-party iframe or Outgrow embed in post content.
#
# WHY: Default and Prose templates add hero + container chrome; Full Width
# drops the wrapper but does not stretch embeds to the viewport. This template
# keeps header/footer and fills the content band.
#
# IDEMPOTENT: sets _wp_page_template only when the slug matches and the value
# differs.

set -euo pipefail

template='templates/template-embed.php'
slugs=(
  'portable-rollforming-machine-readiness-assessment'
  'roof-panel-machine-assessment-quiz'
)

for slug in "${slugs[@]}"; do
  id="$(wp post list --post_type=page --name="$slug" --field=ID --format=ids 2>/dev/null | tr -d '[:space:]' || true)"
  if [[ -z "$id" ]]; then
    echo "    skipped: page slug ${slug} not found"
    continue
  fi

  current="$(wp post meta get "$id" _wp_page_template 2>/dev/null | tr -d '[:space:]' || true)"
  if [[ "$current" == "$template" ]]; then
    echo "    no change: ${slug} (${id}) already uses Full Height Embed"
    continue
  fi

  wp post meta update "$id" _wp_page_template "$template" >/dev/null || true
  echo "    set ${slug} (${id}) template -> ${template} (was: ${current:-default})"
done