#!/usr/bin/env bash
# Remove the edited rooftop photo from published featured images.
set -uo pipefail
DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"
WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"
php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-058-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';
global $wpdb;
$attachment = static function (string $file) use ($wpdb): int {
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
        $file
    ));
};
$old = $attachment('2021/04/roof-panel-machine-on-roof-e1621440327575.jpg');
$new = $attachment('2026/06/ssq3-machine-side-loaded-coils.jpg');
if (!$new || !wp_attachment_is_image($new)) {
    fwrite(STDERR, "Replacement attachment missing.\n");
    exit(1);
}
if (!$old) {
    echo "Edited rooftop attachment already removed.\n";
    return;
}
$posts = $wpdb->get_col($wpdb->prepare(
    "SELECT p.ID FROM {$wpdb->posts} p JOIN {$wpdb->postmeta} m ON m.post_id = p.ID WHERE p.post_type = 'post' AND p.post_status = 'publish' AND m.meta_key = '_thumbnail_id' AND m.meta_value = %s",
    (string) $old
));
$yoast = $wpdb->prefix . 'yoast_indexable';
$has_yoast = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $yoast)) === $yoast;
foreach ($posts as $id) {
    echo ($dry ? '[dry-run] ' : '') . "Replace featured image on post {$id}; rebuild Yoast indexable.\n";
    if ($dry) {
        continue;
    }
    if (!set_post_thumbnail((int) $id, $new)) {
        exit(1);
    }
    if ($has_yoast && $wpdb->delete($yoast, ['object_id' => $id, 'object_type' => 'post']) === false) {
        exit(1);
    }
}
echo count($posts) . " featured image(s) " . ($dry ? 'planned' : 'replaced') . ".\n";
PHP
if [[ -n "$WP_CONTAINER" ]]; then
  in_container="/tmp/$(basename "$php_tmp")"
  docker cp "$php_tmp" "${WP_CONTAINER}:${in_container}" >/dev/null || exit $?
  status=0
  docker exec -e NTM_DRY_RUN "$WP_CONTAINER" "$WP_PHP_BIN" \
    /usr/local/bin/wp --path="$WP_PATH" --allow-root eval-file "$in_container" || status=$?
  docker exec "$WP_CONTAINER" rm -f "$in_container" >/dev/null 2>&1 || true
  exit "$status"
else
  wp eval-file "$php_tmp"
fi
