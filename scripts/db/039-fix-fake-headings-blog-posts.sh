#!/usr/bin/env bash
#
# Convert bold-paragraph pseudo-headings into real H2 heading blocks across six
# learning-center articles whose authors used <p><strong>...</strong></p> where
# a heading belonged.
#
# WHY THIS SCRIPT EXISTS: the single-post TOC is JS-generated
# (TableOfContents.js scans [data-toc-content] for <h2>) and single.php now only
# renders the TOC rail when a post has >=3 real H2s. These posts had ZERO real
# headings (bold paragraphs only), so they had no TOC AND no heading structure
# for SEO. The content lives only in the DB — a fresh prod pull wipes a
# hand-edit — so the conversion has to be replayable. Mirrors 025.
#
# WHAT IT DOES: for each post (looked up by slug), replaces each exact serialized
# bold-paragraph block with the equivalent wp:heading (H2) block. The
# promote/skip decision per candidate was made by a model review; only genuine
# section headings are here. Sentences, an inline warning, and one malformed
# multi-block paragraph were deliberately left as-is.
#
# SAFE BY DESIGN: literal string replace of exact known blocks, scoped per post.
# Idempotent — once the H2s are in place there is nothing to match, so re-runs
# are no-ops. DRY_RUN=1 by default; set DRY_RUN=0 (or run via npm run db:apply)
# to write.
#
# See 025-top-five-questions-headings.sh for the single-post precedent and its
# KNOWN RUNNER QUIRK note (exported wp() + docker-exec stdout).

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

# original-post-id => slug. Ids can differ after a fresh import, so resolve by
# slug at run time and map back to the captured blocks by original id.
declare -A SLUGS=(
  [11682]="adjusting-the-gold-bars-for-alignment-on-the-ssq-ii-multipro"
  [7688]="7-reasons-to-invest-in-an-ntm-trailer-for-your-machine"
  [11449]="resetting-your-plc-controller-a-guide-to-recalibration"
  [11632]="resetting-the-parameters-on-your-uniq-controller-for-calibration"
  [10220]="changing-coil-widths-on-a-new-tech-machinery-ssr-multipro-jr"
  [8204]="how-to-switch-from-12-1f-to-8-1f-on-ntms-wav-panel-machine"
)

declare -A IDMAP=()
for pid in "${!SLUGS[@]}"; do
  slug="${SLUGS[$pid]}"
  id=""
  for attempt in 1 2 3; do
    raw_id="$(wp post list --post_type=post --name="$slug" --post_status=publish \
                --field=ID --format=ids 2>/dev/null)"
    id="$(printf '%s' "$raw_id" | tr -cd '0-9')"
    [[ -n "$id" ]] && break
    sleep 1
  done
  if [[ -z "$id" ]]; then
    echo "    SKIPPED: post '$slug' not found (or DB unreachable after retries)."
    continue
  fi
  IDMAP["$slug"]="$id"
done

map_str=""
for slug in "${!IDMAP[@]}"; do
  map_str+="${slug}=${IDMAP[$slug]};"
done
export NTM_IDMAP="$map_str"
export NTM_DRY_RUN="$DRY_RUN"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-039-XXXXXX.php")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

$idmap = [];
foreach (explode(';', (string) getenv('NTM_IDMAP')) as $pair) {
    if ($pair === '') { continue; }
    [$slug, $id] = array_pad(explode('=', $pair, 2), 2, '');
    if ($slug !== '' && $id !== '') { $idmap[$slug] = (int) $id; }
}

$slug_by_id = [
    11682 => 'adjusting-the-gold-bars-for-alignment-on-the-ssq-ii-multipro',
    7688 => '7-reasons-to-invest-in-an-ntm-trailer-for-your-machine',
    11449 => 'resetting-your-plc-controller-a-guide-to-recalibration',
    11632 => 'resetting-the-parameters-on-your-uniq-controller-for-calibration',
    10220 => 'changing-coil-widths-on-a-new-tech-machinery-ssr-multipro-jr',
    8204 => 'how-to-switch-from-12-1f-to-8-1f-on-ntms-wav-panel-machine',
];

$pairs_by_id = [
  11682 => [
    ['<!-- wp:paragraph -->
<p><strong>Understanding the Problem</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-understanding-the-problem">Understanding the Problem</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Tools Needed</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-tools-needed">Tools Needed</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Safety First</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-safety-first">Safety First</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Steps to Align the Gold Bars</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-steps-to-align-the-gold-bars">Steps to Align the Gold Bars</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Final Check</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-final-check">Final Check</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Final Check</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-final-check">Final Check</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Conclusion</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-conclusion">Conclusion</h2>
<!-- /wp:heading -->'],
  ],
  7688 => [
    ['<!-- wp:paragraph -->
<p><strong>1. Impressive Capacity and Tandem Axles</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-1-impressive-capacity-and-tandem-axles">1. Impressive Capacity and Tandem Axles</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>2. Crane Lifting Eyes for Efficient Roof Line Access</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-2-crane-lifting-eyes-for-efficient-roof-line-access">2. Crane Lifting Eyes for Efficient Roof Line Access</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>3. Safety First with Electronic Brakes</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-3-safety-first-with-electronic-brakes">3. Safety First with Electronic Brakes</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>4. NATM Compliance for Business Owners</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-4-natm-compliance-for-business-owners">4. NATM Compliance for Business Owners</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>5. Keeping the Job Site Tidy</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-5-keeping-the-job-site-tidy">5. Keeping the Job Site Tidy</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>6. Perfectly Balanced Design</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-6-perfectly-balanced-design">6. Perfectly Balanced Design</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>7. Reliable Stabilization with Drop Foot Jacks</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-7-reliable-stabilization-with-drop-foot-jacks">7. Reliable Stabilization with Drop Foot Jacks</h2>
<!-- /wp:heading -->'],
  ],
  11449 => [
    ['<!-- wp:paragraph -->
<p><strong>Step 1: Access the Setup Menu</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-step-1-access-the-setup-menu">Step 1: Access the Setup Menu</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Step 2: Locate the Status Menu</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-step-2-locate-the-status-menu">Step 2: Locate the Status Menu</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Step 3: Utilize the Ghost Button</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-step-3-utilize-the-ghost-button">Step 3: Utilize the Ghost Button</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Step 4: Test the Reset</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-step-4-test-the-reset">Step 4: Test the Reset</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>If You\'re Still Having Issues</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-if-youre-still-having-issues">If You\'re Still Having Issues</h2>
<!-- /wp:heading -->'],
  ],
  11632 => [
    ['<!-- wp:paragraph -->
<p><strong>The Problem: Over-Calibrating Your Machine</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-the-problem-over-calibrating-your-machine">The Problem: Over-Calibrating Your Machine</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Step-by-Step Guide to Resetting Parameters</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-step-by-step-guide-to-resetting-parameters">Step-by-Step Guide to Resetting Parameters</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Conclusion</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-conclusion">Conclusion</h2>
<!-- /wp:heading -->'],
  ],
  10220 => [
    ['<!-- wp:paragraph -->
<p><strong>Resources:</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-resources">Resources:</h2>
<!-- /wp:heading -->'],
  ],
  8204 => [
    ['<!-- wp:paragraph -->
<p><strong>Materials Needed</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-materials-needed">Materials Needed</h2>
<!-- /wp:heading -->'],
    ['<!-- wp:paragraph -->
<p><strong>Safety Protocol</strong></p>
<!-- /wp:paragraph -->', '<!-- wp:heading -->
<h2 class="wp-block-heading" id="h-safety-protocol">Safety Protocol</h2>
<!-- /wp:heading -->'],
  ],
];

$total = 0;
foreach ($pairs_by_id as $orig_id => $pairs) {
    $slug = $slug_by_id[$orig_id] ?? null;
    if ($slug === null || !isset($idmap[$slug])) {
        echo "    skip: post {$orig_id} ({$slug}) not resolved.\n";
        continue;
    }
    $id = $idmap[$slug];
    $content = get_post_field('post_content', $id);
    $before  = $content;
    $hits = 0;
    foreach ($pairs as [$old, $new]) {
        if (strpos($content, $old) !== false) {
            $content = str_replace($old, $new, $content);
            $hits++;
        }
    }
    if ($content === $before) {
        echo "    post {$id} ({$slug}): already converted — no change.\n";
        continue;
    }
    if ($dry) {
        echo "    [dry-run] post {$id} ({$slug}): {$hits} bold paragraphs would become H2.\n";
        continue;
    }
    wp_update_post(['ID' => $id, 'post_content' => $content]);
    $total += $hits;
    echo "    post {$id} ({$slug}): converted {$hits} bold paragraphs to H2.\n";
}
echo $dry
    ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n"
    : "    done: {$total} headings converted.\n";
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  wp eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
