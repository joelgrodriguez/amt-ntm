#!/usr/bin/env bash
#
# Fix the stray "Thank you for submitting the form." heading on the
# "NTM Machine Quote Checklist" page (ID 11062, slug
# ntm-machine-quote-checklist-thank-you).
#
# WHY THIS SCRIPT EXISTS: stakeholder review 2026-06-17 flagged the quote-
# checklist page opening with leftover form-confirmation copy on a page that
# has no form. The page content lives only in the DB, so a fresh prod pull
# wipes a hand-edit — the fix has to be replayable.
#
# WHAT IT DOES: replaces the exact opening H3 block
#   "Thank you for submitting the form."  ->  "Your Machine Quote Checklist"
# and updates the auto-generated anchor id to match the new text so the block
# stays internally consistent.
#
# SCOPE NOTE: this only de-confuses the heading. The page's BODY copy is still
# post-download "thank you" boilerplate, and the slug itself ends in
# "-thank-you" — both are open content/IA questions for Joel (a slug rename
# would also need a redirect entry, per the repo DB-capture rule). This script
# deliberately does NOT rename the slug or rewrite the body.
#
# SAFE BY DESIGN: literal string replace of one known block, scoped to one post
# (by ID). Idempotent — re-running finds nothing to replace once applied, so it
# is a no-op. DRY_RUN=1 by default; set DRY_RUN=0 to write.
#
# Resolves (partial): quote-checklist "thank you for filling out the form" copy
# from the 2026-06-17 stakeholder review action items.

# Deliberately NOT `set -e`/`pipefail`: the apply runner sources this with an
# exported wp() that wraps `docker exec`; a single transient docker non-zero
# under `set -e` would abort silently. Handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
POST_ID="11062"

# Confirm the post exists (and the DB is reachable) before touching it.
id=""
for attempt in 1 2 3; do
  raw_id="$(wp post list --post__in="$POST_ID" --post_type=page \
              --field=ID --format=ids 2>/dev/null)"
  id="$(printf '%s' "$raw_id" | tr -cd '0-9')"
  [[ -n "$id" ]] && break
  sleep 1
done

if [[ -z "$id" ]]; then
  echo "    SKIPPED: page $POST_ID not found (or DB unreachable after retries)."
  exit 0
fi

export NTM_POST_ID="$id"
export NTM_DRY_RUN="$DRY_RUN"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-027-XXXXXX.php")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
// NB: wp eval-file requires the opening PHP tag or the file is printed, not run.
$id  = (int) getenv('NTM_POST_ID');
$dry = getenv('NTM_DRY_RUN') !== '0';
$content = get_post_field('post_content', $id);
$orig    = $content;

$old = "<!-- wp:heading {\"level\":3} -->\n"
     . "<h3 class=\"wp-block-heading\" id=\"h-thank-you-for-submitting-the-form\">Thank you for submitting the form.</h3>\n"
     . "<!-- /wp:heading -->";
$new = "<!-- wp:heading {\"level\":3} -->\n"
     . "<h3 class=\"wp-block-heading\" id=\"h-your-machine-quote-checklist\">Your Machine Quote Checklist</h3>\n"
     . "<!-- /wp:heading -->";

if (strpos($content, $old) === false) {
  echo "    no change: stray heading not found (already fixed or block differs).\n";
  return;
}

$content = str_replace($old, $new, $content);

if ($content === $orig) {
  echo "    no change: replacement produced identical content.\n";
  return;
}

if ($dry) {
  echo "    [dry-run] post {$id}: stray 'Thank you for submitting the form.' H3 would become 'Your Machine Quote Checklist'.\n";
  echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
  return;
}

wp_update_post(['ID' => $id, 'post_content' => $content]);
echo "    fixed stray heading on post {$id}.\n";
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  wp eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
