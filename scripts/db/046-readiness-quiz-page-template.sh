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
# Resolves the page by ID and asserts exact title + slug before writing: a fresh
# prod pull can renumber posts, so a blind ID write could hit the wrong page.
# Mismatch => fail loudly, do not write.
#
# SAFE BY DESIGN: single page, asserts identity, only clears empty content or
# the known legacy iframe host, idempotent (re-run is a no-op once template +
# empty content are in place). DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

HERE="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
php_file="$HERE/046-readiness-quiz-page-template.php"

echo "== 046 readiness-quiz page template (DRY_RUN=${DRY_RUN}) =="

if [[ ! -f "$php_file" ]]; then
  echo "FAIL: migration payload missing: $php_file" >&2
  exit 1
fi

status=0
if [[ -n "$WP_CONTAINER" ]]; then
  in_container="/tmp/$(basename "$php_file")"
  if ! docker cp "$php_file" "${WP_CONTAINER}:${in_container}" >/dev/null; then
    echo "FAIL: could not copy migration payload into ${WP_CONTAINER}" >&2
    exit 1
  fi
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "$WP_PHP_BIN" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container" || status=$?
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_file" || status=$?
fi

if [[ "$status" -ne 0 ]]; then
  echo "== 046 failed =="
  exit "$status"
fi

echo "== 046 done =="
