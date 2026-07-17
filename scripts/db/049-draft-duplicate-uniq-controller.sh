#!/usr/bin/env bash
#
# Unpublish the duplicate UNIQ Automatic Control System (issue #96).
#
# WHY THIS SCRIPT EXISTS: two UNIQ automatic-controller accessories were listed:
#
#   id 2799  "UNIQ Automatic Control System"           $21,700  (no USB port)
#   id 18732 "UNIQ Automatic Control System UNQ-SSQ3-A" $22,500  (USB port)
#
# Per NTM (Joel, from Hailey): there should be only ONE UNIQ automatic controller
# listed as an accessory for the SSQ II and SSQ3, at the $22,500 price. That's
# the keeper (18732). The $21,700 duplicate (2799) is removed by setting it to
# DRAFT (kept in admin, off the storefront).
#
# Its public URL (/machines/accessories-add-on-equipment/uniq-control-system/)
# is redirected to the keeper in the same task via db/redirects.json, so the old
# link 301s instead of 404ing. Product status is DB state wiped by a fresh prod
# pull, so this must be replayable.
#
# SAFETY: resolves by ID and asserts BOTH the title AND the $21,700 price before
# writing — the price assert guarantees we never accidentally draft the $22,500
# keeper if post IDs shift after a prod pull. Mismatch => skip loudly.
#
# IDEMPOTENT: no-op if 2799 is already draft. DRY_RUN=1 by default; DRY_RUN=0 to write.

set -uo pipefail

DRY_RUN="${DRY_RUN-1}"   # default safe: report only. DRY_RUN=0 to apply.

WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP="docker exec ${WP_CONTAINER} php8.3 /usr/local/bin/wp --path=${WP_PATH} --allow-root"

echo "== 049 draft duplicate UNIQ controller (DRY_RUN=${DRY_RUN}) =="

# DRY_RUN passed as a literal below: host env does not cross the docker exec
# boundary, so getenv() inside the container always reads unset (see 045).
# shellcheck disable=SC2086
$WP eval '
$dry = "'"${DRY_RUN}"'" !== "0";

$id            = 2799;
$expect_title  = "UNIQ";                 // must contain
$expect_price  = "21700";                // the duplicate, NOT the 22500 keeper
$keeper_id     = 18732;

$p = get_post($id);
if (!$p || $p->post_type !== "product") {
    echo "SKIP id={$id}: not a product (post IDs may have shifted after a prod pull)\n";
    return;
}
if (stripos($p->post_title, $expect_title) === false) {
    echo "SKIP id={$id}: title mismatch — expected to contain \"{$expect_title}\", found \"{$p->post_title}\"\n";
    return;
}
$price = (string) get_post_meta($id, "_regular_price", true);
if (strpos($price, $expect_price) === false) {
    echo "SKIP id={$id}: price mismatch — expected {$expect_price} (the duplicate), found \"{$price}\". Refusing to draft — this may be the keeper.\n";
    return;
}

// Sanity: the keeper must exist and be the 22500 one, else we could be about to
// leave the catalog with ZERO UNIQ controllers.
$keeper = get_post($keeper_id);
$keeper_price = $keeper ? (string) get_post_meta($keeper_id, "_regular_price", true) : "";
if (!$keeper || strpos($keeper_price, "22500") === false) {
    echo "ABORT: keeper id={$keeper_id} not found at price 22500 (found \"{$keeper_price}\"). Not drafting {$id} — would leave no UNIQ controller listed.\n";
    return;
}

if ($p->post_status === "draft") {
    echo "OK id={$id}: already draft — " . html_entity_decode($p->post_title) . "\n";
    return;
}

if ($dry) {
    echo "DRY id={$id}: would set to draft — " . html_entity_decode($p->post_title) . " (\${$price})\n";
    echo "     keeper stays: id={$keeper_id} " . html_entity_decode($keeper->post_title) . " (\${$keeper_price})\n";
    return;
}

$res = wp_update_post(["ID" => $id, "post_status" => "draft"], true);
if (is_wp_error($res)) {
    echo "FAIL id={$id}: " . $res->get_error_message() . "\n";
    return;
}
clean_post_cache($id);
echo "WROTE id={$id}: set to draft — " . html_entity_decode($p->post_title) . "\n";
echo "     keeper stays published: id={$keeper_id} " . html_entity_decode($keeper->post_title) . "\n";
'

echo "== 049 done =="
