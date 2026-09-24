#!/usr/bin/env bash
#
# Clear the About page's stale legacy template meta so page-about.php renders.
#
# WHY THIS SCRIPT EXISTS: the prod DB assigns page "about" the old mazz-ntm
# template slug `page-form.php` in _wp_page_template. In the amt-ntm theme,
# inc/page-templates.php maps that legacy slug to templates/template-lead-form.php
# (template_include, priority 20), so the About page renders as a lead-form page
# instead of the dedicated page-about.php ("Template Name: About") that matches
# its slug. The meta lives only in the DB, which a fresh prod pull resurrects —
# so the fix must be replayable. npm run db:apply replays this against the fresh
# prod DB at cutover.
#
# WHAT IT DOES: resolves the page by path ('about' — ids shift across
# environments; paths are stable), guards that _wp_page_template is still the
# legacy 'page-form.php' so a later deliberate assignment is never clobbered,
# and sets it to 'default'. WordPress then falls through the page hierarchy to
# page-about.php via the slug match.
#
# SCOPE: audited every page whose _wp_page_template shadows a dedicated
# page-{slug}.php (2026-07-10). Only 'about' was broken — all other matches
# point at their own dedicated template on purpose (e.g. safety, trailer,
# start-here) or at an existing template variant (service-hub). Deliberately
# not generalized.
#
# SAFE BY DESIGN: resolves by stable path, guards on the exact stale value, and
# idempotent — a re-run finds 'default' (or a later hand-set template) and does
# nothing. DRY_RUN=1 by default; set DRY_RUN=0 to write.
#
# Verify after apply (self-signed TLS, use -k):
#   curl -sk https://newtech.local/about/ | grep -c 'about-origin-title'   # -> 1
#
# Uses the direct-docker-exec eval-file pattern from 041 (see its header for the
# exported-wp()-wrapper quirk this sidesteps).

# Deliberately NOT `set -e`: see 041.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.
export NTM_DRY_RUN="$DRY_RUN"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-043-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';

$page = get_page_by_path('about', OBJECT, 'page');
if (!$page instanceof WP_Post) {
    echo "    skip: page 'about' not found.\n";
    return;
}
$pid  = (int) $page->ID;
$slug = get_page_template_slug($pid);

// Guard: only clear the known stale legacy value. Anything else — empty,
// 'default', or a later deliberate assignment — is left alone.
if ($slug !== 'page-form.php') {
    echo "    skip: page 'about' (post {$pid}) template is '" . ($slug ?: 'default') . "', not the stale 'page-form.php'.\n";
    echo $dry ? "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n" : '';
    return;
}

if ($dry) {
    echo "    [dry-run] would set page 'about' (post {$pid}) _wp_page_template: 'page-form.php' -> 'default'.\n";
    echo "    set DRY_RUN=0 to apply, or run via: npm run db:apply\n";
    return;
}

update_post_meta($pid, '_wp_page_template', 'default');
echo "    done: page 'about' (post {$pid}) _wp_page_template set to 'default'; page-about.php now renders via slug match.\n";
PHP

if [[ -n "${WP_CONTAINER:-}" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "${WP_PHP_BIN:-php8.3}" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container"
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
else
  wp eval-file "$php_tmp"
fi
