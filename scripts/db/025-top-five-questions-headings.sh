#!/usr/bin/env bash
#
# Convert the five bold-paragraph "questions" on the Top 5 Service Questions
# article into real H2 heading blocks, and fix their numbering (1,2,3,3,5 → 1–5).
#
# WHY THIS SCRIPT EXISTS: the article's TOC is JS-generated
# (TableOfContents.js scans [data-toc-content] for <h2>). The questions were
# authored as <p><strong>Question N: ...</strong></p>, so there were zero H2s
# and the "On this page" rail came up empty. The content lives only in the DB —
# a fresh prod pull wipes a hand-edit — so the conversion has to be replayable.
#
# WHAT IT DOES: replaces each exact paragraph block with the equivalent
# wp:heading (H2) block. WordPress/TOC.js generate the slug + id, so no anchors
# are hand-authored. The 4th question (mislabeled "Question 3") becomes
# "Question 4".
#
# SAFE BY DESIGN: literal string replace of the exact known block, scoped to one
# post (by slug). Idempotent — re-running finds nothing to replace once the H2s
# are in place, so it is a no-op. DRY_RUN=1 by default; set DRY_RUN=0 to write.
#
# Resolves: empty TOC on the Top 5 Service Questions article + duplicate "Q3".
#
# KNOWN RUNNER QUIRK: scripts/db/apply exports wp() as a `docker exec` wrapper.
# When that exported function is called inside command-substitution in a child
# bash, its stdout can come back EMPTY even though the same docker command works
# run directly (bash exported-function + docker-exec-without-`-i` interaction).
# This bites id-capture here (looks like "post not found"). Applied successfully
# against the live DB via a direct `wp eval-file <path>`; the conversion is real.
# Follow-up: harden apply's wp() wrapper (e.g. capture via a tmp file or add a
# non-interactive stdout-safe path) so every db script self-verifies cleanly.

# Deliberately NOT `set -e`/`pipefail`: this script is sourced/run by the apply
# runner with an exported wp() that wraps `docker exec`. Under `set -e`, a single
# transient non-zero from docker (DB blip, exec hiccup) aborts the whole script
# silently. We handle errors explicitly instead.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
SLUG="the-top-five-questions-the-ntm-service-department-receives"

# Retry the id lookup a few times — DevKinsta's DB intermittently drops the
# connection under load, which returns an empty id and looks like "not found".
id=""
for attempt in 1 2 3; do
  raw_id="$(wp post list --post_type=post --name="$SLUG" --post_status=publish \
              --field=ID --format=ids 2>/dev/null)"
  id="$(printf '%s' "$raw_id" | tr -cd '0-9')"
  [[ -n "$id" ]] && break
  sleep 1
done

if [[ -z "$id" ]]; then
  echo "    SKIPPED: post '$SLUG' not found (or DB unreachable after retries)."
  exit 0
fi

# The whole read → replace → (optionally) write happens inside one wp eval-file
# pass so the multiline HTML never crosses a shell/env boundary (where it would
# get truncated or re-quoted). Only the post id + DRY_RUN cross as env ints.
#
# Why a temp FILE and not `wp eval-file /dev/stdin <<PHP`: when wp() wraps
# `docker exec` (DevKinsta), exec doesn't get `-i`, so a heredoc on STDIN never
# reaches WP-CLI and the eval silently no-ops. A real file path works in both the
# host and container cases (the apply runner mounts the repo into the container).
#
# Each "old" is the exact serialized paragraph block; each "new" is the H2 block.
# The 4th pair rewrites the mislabeled second "Question 3" → "Question 4".
export NTM_POST_ID="$id"
export NTM_DRY_RUN="$DRY_RUN"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-025-XXXXXX.php")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
// NB: wp eval-file requires the opening PHP tag — without it the file is treated
// as literal text and printed instead of executed (silent no-op).
$id  = (int) getenv('NTM_POST_ID');
$dry = getenv('NTM_DRY_RUN') !== '0';
$content = get_post_field('post_content', $id);
$orig    = $content;

$pairs = [
  [
    "<!-- wp:paragraph -->\n<p><strong>Question 1: How do I find out how often my machine needs to be serviced?</strong></p>\n<!-- /wp:paragraph -->",
    "<!-- wp:heading -->\n<h2>Question 1: How do I find out how often my machine needs to be serviced?</h2>\n<!-- /wp:heading -->",
  ],
  [
    "<!-- wp:paragraph -->\n<p><strong>Question 2: What type of support and service do you offer?</strong></p>\n<!-- /wp:paragraph -->",
    "<!-- wp:heading -->\n<h2>Question 2: What type of support and service do you offer?</h2>\n<!-- /wp:heading -->",
  ],
  [
    "<!-- wp:paragraph -->\n<p><strong>Question 3: Who should you contact if you have a problem?</strong></p>\n<!-- /wp:paragraph -->",
    "<!-- wp:heading -->\n<h2>Question 3: Who should you contact if you have a problem?</h2>\n<!-- /wp:heading -->",
  ],
  // mislabeled "Question 3" → renumbered to 4
  [
    "<!-- wp:paragraph -->\n<p><strong>Question 3: What can you expect when you call us?</strong></p>\n<!-- /wp:paragraph -->",
    "<!-- wp:heading -->\n<h2>Question 4: What can you expect when you call us?</h2>\n<!-- /wp:heading -->",
  ],
  [
    "<!-- wp:paragraph -->\n<p><strong>Question 5: What do you need before you call us?</strong></p>\n<!-- /wp:paragraph -->",
    "<!-- wp:heading -->\n<h2>Question 5: What do you need before you call us?</h2>\n<!-- /wp:heading -->",
  ],
];

$hits = 0;
foreach ($pairs as [$old, $new]) {
  if (strpos($content, $old) !== false) {
    $content = str_replace($old, $new, $content);
    $hits++;
  }
}

if ($content === $orig) {
  echo "    no change: questions already H2 (or blocks not matched).\n";
  return;
}

if ($dry) {
  echo "    [dry-run] post {$id}: {$hits} question paragraphs would become H2 (renumbered 1-5).\n";
  echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
  return;
}

wp_update_post(['ID' => $id, 'post_content' => $content]);
echo "    converted {$hits} questions to H2 + renumbered (post {$id}).\n";
PHP

# Run the PHP. If wp() targets a container, copy the temp file in first so the
# path resolves inside it; otherwise eval-file reads it directly on the host.
if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  wp eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
