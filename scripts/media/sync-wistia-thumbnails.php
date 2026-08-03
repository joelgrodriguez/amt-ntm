<?php
/**
 * Download Wistia oEmbed thumbnails and store responsive WebP posters.
 *
 * Usage:
 * wp eval-file wp-content/themes/amt-ntm/scripts/media/sync-wistia-thumbnails.php
 */

if (!defined('ABSPATH')) {
    exit;
}

$sources = [];
$theme_dir = get_template_directory();
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($theme_dir, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $contents = file_get_contents($file->getPathname());
        if (is_string($contents) && stripos($contents, 'wistia') !== false) {
            $sources[] = $contents;
        }
    }
}

// Product videos are managed in WordPress/ACF, so their Wistia IDs do not
// necessarily appear in theme PHP. Include product content and metadata to
// keep locally stored posters in sync with the actual catalog.
global $wpdb;
$wistia_like = '%' . $wpdb->esc_like('wistia') . '%';
$database_sources = $wpdb->get_col($wpdb->prepare(
    "SELECT p.post_content
     FROM {$wpdb->posts} p
     WHERE p.post_type = 'product'
       AND p.post_content LIKE %s
     UNION ALL
     SELECT pm.meta_value
     FROM {$wpdb->postmeta} pm
     INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
     WHERE p.post_type = 'product'
       AND pm.meta_value LIKE %s",
    $wistia_like,
    $wistia_like
));

foreach ($database_sources as $database_source) {
    if (is_string($database_source) && $database_source !== '') {
        $sources[] = $database_source;
    }
}

$media_ids = [];
foreach ($sources as $source) {
    if (!is_string($source)) {
        continue;
    }

    preg_match_all(
        '~/(?:embed/iframe|medias)/([a-zA-Z0-9]+)~',
        html_entity_decode($source, ENT_QUOTES, 'UTF-8'),
        $matches
    );
    foreach ($matches[1] ?? [] as $media_id) {
        $media_ids[] = sanitize_key((string) $media_id);
    }
}

$media_ids = array_values(array_unique(array_filter($media_ids)));
sort($media_ids);

$destination_dir = $theme_dir . '/assets/images/wistia';
if (!wp_mkdir_p($destination_dir)) {
    WP_CLI::error('Could not create ' . $destination_dir);
}

require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/image.php';

$created = 0;
$failed = 0;

foreach ($media_ids as $media_id) {
    $media_url = 'https://home.wistia.com/medias/' . $media_id;
    $oembed_url = add_query_arg(
        ['url' => $media_url],
        'https://fast.wistia.com/oembed'
    );
    $response = wp_remote_get($oembed_url, ['timeout' => 15]);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        ++$failed;
        WP_CLI::warning('Wistia oEmbed failed for ' . $media_id);
        continue;
    }

    $payload = json_decode(wp_remote_retrieve_body($response), true);
    $thumbnail_url = is_array($payload) ? (string) ($payload['thumbnail_url'] ?? '') : '';
    if (!wp_http_validate_url($thumbnail_url)) {
        ++$failed;
        WP_CLI::warning('Wistia returned no thumbnail for ' . $media_id);
        continue;
    }

    $temporary_file = download_url($thumbnail_url, 30);
    if (is_wp_error($temporary_file)) {
        ++$failed;
        WP_CLI::warning('Thumbnail download failed for ' . $media_id);
        continue;
    }

    $media_failed = false;
    foreach ([480 => 270, 960 => 540] as $width => $height) {
        $editor = wp_get_image_editor($temporary_file);
        if (is_wp_error($editor)) {
            $media_failed = true;
            break;
        }

        $resized = $editor->resize($width, $height, true);
        if (is_wp_error($resized)) {
            $media_failed = true;
            break;
        }

        $editor->set_quality(82);
        $target = $destination_dir . '/' . $media_id . '-' . $width . '.webp';
        $saved = $editor->save($target, 'image/webp');
        if (is_wp_error($saved)) {
            $media_failed = true;
            break;
        }
    }

    wp_delete_file($temporary_file);

    if ($media_failed) {
        ++$failed;
        WP_CLI::warning('WebP conversion failed for ' . $media_id);
        continue;
    }

    ++$created;
    WP_CLI::log(sprintf(
        '%s — %s',
        $media_id,
        sanitize_text_field((string) ($payload['title'] ?? 'Wistia video'))
    ));
}

WP_CLI::success(sprintf(
    'Wistia poster sync complete: %d media processed, %d failed.',
    $created,
    $failed
));
