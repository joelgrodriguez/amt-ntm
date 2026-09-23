#!/usr/bin/env bash
# Replace references to the rooftop machine photo with the registered SSQ3 image.
# Run with DRY_RUN=0 to apply; attachment IDs are resolved from file paths.
set -uo pipefail

DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"
WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"

php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-057-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';
global $wpdb;

$old_file = '2021/03/rollforming-machine-on-roof.jpg';
$new_file = '2026/06/ssq3-machine-side-loaded-coils.jpg';
$find_attachment = static function (string $file) use ($wpdb): int {
    return (int) $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
        $file
    ));
};
$old_id = $find_attachment($old_file);
$new_id = $find_attachment($new_file);
if (!$new_id || !wp_attachment_is_image($new_id)) {
    fwrite(STDERR, "Registered replacement image is missing. No changes made.\n");
    exit(1);
}
if (!$old_id) {
    $stale = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND post_content LIKE %s",
        '%rollforming-machine-on-roof%'
    ));
    if ($stale) {
        fwrite(STDERR, "Old attachment is missing but published posts still reference it.\n");
        exit(1);
    }
    echo "Old attachment already removed; no published image blocks to change.\n";
    return;
}

$old_url = wp_get_attachment_url($old_id);
$new_url = wp_get_attachment_url($new_id);
$new_large = wp_get_attachment_image_url($new_id, 'large');
if (!$old_url || !$new_url || !$new_large) {
    fwrite(STDERR, "Image URLs could not be resolved. No changes made.\n");
    exit(1);
}
$old_large = preg_replace('/\.jpg$/', '-1024x724.jpg', $old_url);
$posts = $wpdb->get_col($wpdb->prepare(
    "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'post' AND post_status = 'publish' AND post_content LIKE %s",
    '%rollforming-machine-on-roof%'
));
$changed = 0;
foreach ($posts as $post_id) {
    $post = get_post((int) $post_id);
    $content = str_replace(
        [$old_large, $old_url, '"id":' . $old_id, 'wp-image-' . $old_id],
        [$new_large, $new_url, '"id":' . $new_id, 'wp-image-' . $new_id],
        $post->post_content
    );
    $content = preg_replace(
        '/(<img\s+src="' . preg_quote($new_large, '/') . '"\s+alt=")[^"]*(")/',
        '$1SSQ3 roof panel machine with side-loaded metal coils$2',
        $content
    );
    if ($content === $post->post_content) {
        continue;
    }
    echo ($dry ? '[dry-run] ' : '') . "Update image block in {$post->post_name}\n";
    if (!$dry && is_wp_error(wp_update_post(['ID' => $post->ID, 'post_content' => $content], true))) {
        exit(1);
    }
    $changed++;
}

$featured = $wpdb->get_col($wpdb->prepare(
    "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %s",
    (string) $old_id
));
foreach ($featured as $post_id) {
    echo ($dry ? '[dry-run] ' : '') . "Update featured image for post {$post_id}\n";
    if (!$dry && !set_post_thumbnail((int) $post_id, $new_id)) {
        exit(1);
    }
    $changed++;
}

// AIOSEO stores a derived image list. Refresh only rows still carrying this image.
$aioseo = $wpdb->prefix . 'aioseo_posts';
if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $aioseo)) === $aioseo) {
    $rows = $wpdb->get_results($wpdb->prepare(
        "SELECT id, images FROM {$aioseo} WHERE images LIKE %s", '%rollforming-machine-on-roof%'
    ));
    foreach ($rows as $row) {
        $images = json_decode($row->images, true);
        if (!is_array($images)) {
            fwrite(STDERR, "Invalid AIOSEO image JSON in row {$row->id}.\n");
            exit(1);
        }
        foreach ($images as &$image) {
            if (isset($image['image:loc']) && str_contains($image['image:loc'], 'rollforming-machine-on-roof')) {
                $image['image:loc'] = $new_url;
            }
        }
        unset($image);
        echo ($dry ? '[dry-run] ' : '') . "Update AIOSEO images row {$row->id}\n";
        if (!$dry && $wpdb->update($aioseo, ['images' => wp_json_encode($images)], ['id' => $row->id]) === false) {
            exit(1);
        }
        $changed++;
    }
}

// Yoast's indexable image is cached from the featured image; rebuild it on demand.
$yoast = $wpdb->prefix . 'yoast_indexable';
if ($wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $yoast)) === $yoast) {
    foreach ($featured as $post_id) {
        $count = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$yoast} WHERE object_id = %d AND object_type = 'post'", $post_id
        ));
        if ($count) {
            echo ($dry ? '[dry-run] ' : '') . "Rebuild Yoast indexable for post {$post_id}\n";
            if (!$dry && $wpdb->delete($yoast, ['object_id' => $post_id, 'object_type' => 'post']) === false) {
                exit(1);
            }
            $changed++;
        }
    }
}
echo "{$changed} change(s) " . ($dry ? 'planned' : 'applied') . ". Old attachment retained for historical revisions and incoming URLs.\n";
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
