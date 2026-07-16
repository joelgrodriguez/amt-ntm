#!/usr/bin/env bash
#
# Point the readiness-quiz page at the in-theme quiz template and clear the
# dead Abacus.AI iframe from its content.
#
# WHY THIS SCRIPT EXISTS: page 20405 (/portable-rollforming-machine-readiness-assessment/)
# rendered a bare "Not Found" because its content was a single iframe to
# readinessassessment.b.abacusai.app, which is dead (issue #94). The quiz is now
# rebuilt in-theme (template-readiness-quiz.php + ReadinessQuiz.js). This script
# switches the page to that template and empties the iframe content. Both are DB
# state that a fresh prod pull wipes, so the change must be replayable.
#
# WHAT IT DOES (page 20405):
#   1. Sets _wp_page_template to 'templates/template-readiness-quiz.php'.
#   2. Replaces the post_content (the dead iframe) with an empty string — the
#      template renders the quiz, so no post body is needed.
#
# Resolves the page by ID and asserts its title before writing: a fresh prod
# pull can renumber posts, so a blind ID write could hit the wrong page. Title
# mismatch => skip loudly, do not write.
#
# SAFE BY DESIGN: single page, asserts identity, idempotent (re-run is a no-op
# once template + empty content are in place). DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"

echo "== 046 readiness-quiz page template (DRY_RUN=${DRY_RUN}) =="

# DRY_RUN passed as a literal below: host env does not cross the docker exec
# boundary, so getenv() inside the container always reads unset (see 045).
docker exec "${WP_CONTAINER}" php8.3 /usr/local/bin/wp --path="${WP_PATH}" --allow-root eval '
$dry = "'"${DRY_RUN}"'" !== "0";
$id = 20405;
$expect_title = "Panel Machine Readiness Quiz";
$template = "templates/template-readiness-quiz.php";

$p = get_post($id);
if (!$p || $p->post_type !== "page") {
    echo "SKIP id={$id}: not a page (post IDs may have shifted after a prod pull)\n";
    return;
}
if (stripos($p->post_title, $expect_title) === false) {
    echo "SKIP id={$id}: title mismatch — expected \"{$expect_title}\", found \"{$p->post_title}\"\n";
    return;
}

$cur_template = get_page_template_slug($id);
$has_iframe = strpos($p->post_content, "abacusai") !== false;
$content_empty = trim($p->post_content) === "";

if ($cur_template === $template && $content_empty) {
    echo "OK id={$id}: already on quiz template with empty content — {$p->post_title}\n";
    return;
}

if ($dry) {
    echo "DRY id={$id}: {$p->post_title}\n";
    echo "      template: " . ($cur_template ?: "(default)") . " -> {$template}\n";
    echo "      content : " . ($has_iframe ? "dead Abacus iframe" : ($content_empty ? "(already empty)" : "(other)")) . " -> (empty)\n";
    return;
}

// Template is stored as post meta. Set it directly rather than via
// wp_update_post()s page_template field, which validates the slug against the
// ACTIVE theme dir and errors ("Invalid page template") when this script runs
// before the template file has landed in the served checkout (e.g. from a
// worktree pre-merge). The meta value is authoritative once the file exists.
update_post_meta($id, "_wp_page_template", $template);

// Clear the dead iframe content with a direct DB update to avoid
// wp_update_post()s incidental template revalidation on save.
global $wpdb;
$ok = $wpdb->update($wpdb->posts, ["post_content" => ""], ["ID" => $id]);
if ($ok === false) {
    echo "FAIL id={$id}: could not clear post_content\n";
    return;
}
clean_post_cache($id);
echo "WROTE id={$id}: template set to {$template}, iframe content cleared — {$p->post_title}\n";
'

echo "== 046 done =="
