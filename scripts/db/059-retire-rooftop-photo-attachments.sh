#!/usr/bin/env bash
# Remove both retired attachments and orphaned image variants after 057/058.
# DRY_RUN=1 by default. Requires the Redirection rules in db/redirects.json.
set -uo pipefail
DRY_RUN="${DRY_RUN-1}"
export NTM_DRY_RUN="$DRY_RUN"
WP_CONTAINER="${WP_CONTAINER-devkinsta_fpm}"
WP_PATH="${WP_PATH-/www/kinsta/public/newtech}"
WP_PHP_BIN="${WP_PHP_BIN-php8.3}"
php_tmp="$(mktemp "${TMPDIR:-/tmp}/ntm-059-XXXXXX")"
trap 'rm -f "$php_tmp"' EXIT
cat > "$php_tmp" <<'PHP'
<?php
$dry = getenv('NTM_DRY_RUN') !== '0';
global $wpdb;
$uploads = wp_get_upload_dir();
if (!empty($uploads['error'])) {
    WP_CLI::error($uploads['error']);
}
$families = [
    ['2021/03', 'rollforming-machine-on-roof', '2021/03/rollforming-machine-on-roof.jpg'],
    ['2021/04', 'roof-panel-machine-on-roof', '2021/04/roof-panel-machine-on-roof-e1621440327575.jpg'],
];
$replacement = $uploads['baseurl'] . '/2026/06/ssq3-machine-side-loaded-coils-1536x864.jpg';
$replacement_id = (int) $wpdb->get_var($wpdb->prepare(
    "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1",
    '2026/06/ssq3-machine-side-loaded-coils.jpg'
));
if (!$replacement_id || !is_file($uploads['basedir'] . '/2026/06/ssq3-machine-side-loaded-coils-1536x864.jpg')) {
    WP_CLI::error('Replacement attachment or image file is missing.');
}
$revisions = $wpdb->get_results(
    "SELECT ID, post_content FROM {$wpdb->posts} WHERE post_type = 'revision' AND (post_content LIKE '%rollforming-machine-on-roof%' OR post_content LIKE '%roof-panel-machine-on-roof%')"
);
foreach ($revisions as $revision) {
    $content = preg_replace_callback('~<!-- wp:image .*?<!-- /wp:image -->~s', static function ($match) use ($replacement_id) {
        $block = $match[0];
        if (!str_contains($block, 'rollforming-machine-on-roof') && !str_contains($block, 'roof-panel-machine-on-roof')) {
            return $block;
        }
        return preg_replace(['~"id":[0-9]+~', '~wp-image-[0-9]+~'],
            ['"id":' . $replacement_id, 'wp-image-' . $replacement_id], $block);
    }, $revision->post_content);
    $content = preg_replace(
        '~https?://[^"\s<>]+/wp-content/uploads/2021/(?:03/rollforming-machine-on-roof|04/roof-panel-machine-on-roof)(?:-e[0-9]+)?(?:-[0-9]+x[0-9]+)?\.jpg(?:\.webp)?~',
        $replacement,
        $revision->post_content
    );
    if (str_contains($content, 'rollforming-machine-on-roof') || str_contains($content, 'roof-panel-machine-on-roof')) {
        WP_CLI::error("Unmatched retired image reference in revision {$revision->ID}.");
    }
    WP_CLI::log(($dry ? '[dry-run] ' : '') . "Replace retired image reference in revision {$revision->ID}");
    if (!$dry && $wpdb->update($wpdb->posts, ['post_content' => $content], ['ID' => $revision->ID]) === false) {
        WP_CLI::error("Failed to update revision {$revision->ID}.");
    }
}
foreach ($families as [$directory, $stem, $file]) {
    $id = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_wp_attached_file' AND meta_value = %s LIMIT 1", $file
    ));
    $published = (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_status = 'publish' AND post_content LIKE %s", '%' . $stem . '%'
    ));
    $featured = $id ? (int) $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE meta_key = '_thumbnail_id' AND meta_value = %s", (string) $id
    )) : 0;
    if ($published || $featured) {
        WP_CLI::error("Live references remain for {$file}: {$published} published posts, {$featured} featured images.");
    }
    $dir = $uploads['basedir'] . '/' . $directory;
    $files = glob($dir . '/' . $stem . '*');
    foreach ($files as $path) {
        if (!is_file($path) || !preg_match('/^' . preg_quote($stem, '/') . '(?:-e[0-9]+)?(?:-[0-9]+x[0-9]+)?\.jpg(?:\.webp)?$/', basename($path))) {
            WP_CLI::error("Unexpected path in image family: {$path}");
        }
    }
    WP_CLI::log(($dry ? '[dry-run] ' : '') . "Delete attachment {$id} and " . count($files) . " image file(s) under {$directory}/{$stem}");
    if ($dry) {
        continue;
    }
    if ($id && !wp_delete_attachment($id, true)) {
        WP_CLI::error("Failed to delete attachment {$id}.");
    }
    foreach ($files as $path) {
        if (is_file($path)) {
            wp_delete_file($path);
            if (is_file($path)) {
                WP_CLI::error("Failed to delete {$path}");
            }
        }
    }
}
$backup = $uploads['basedir'] . '/backup/2021/04/roof-panel-machine-on-roof-e1621440327575.jpg';
if (is_file($backup)) {
    WP_CLI::log(($dry ? '[dry-run] ' : '') . 'Delete edited rooftop image from uploads/backup/2021/04');
    if (!$dry) {
        wp_delete_file($backup);
        if (is_file($backup)) {
            WP_CLI::error('Failed to remove rooftop image backup.');
        }
    }
}
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
