<?php
/**
 * Backfill WebP sidecars for WooCommerce product images.
 *
 * Usage:
 * wp eval-file wp-content/themes/amt-ntm/scripts/media/generate-product-webp.php
 */

if (!defined('ABSPATH')) {
    exit;
}

$product_ids = get_posts([
    'post_type'      => 'product',
    'post_status'    => ['publish', 'draft', 'private'],
    'posts_per_page' => -1,
    'fields'         => 'ids',
    'no_found_rows'  => true,
]);

$attachment_ids = [];
foreach ($product_ids as $product_id) {
    $product = function_exists('wc_get_product') ? wc_get_product((int) $product_id) : null;
    if (!$product) {
        continue;
    }

    $attachment_ids[] = (int) $product->get_image_id();
    foreach ($product->get_gallery_image_ids() as $gallery_id) {
        $attachment_ids[] = (int) $gallery_id;
    }
}

$attachment_ids = array_values(array_unique(array_filter($attachment_ids)));
$created = 0;
$existing = 0;
$failed = 0;

foreach ($attachment_ids as $attachment_id) {
    $source_path = get_attached_file($attachment_id);
    if (!is_string($source_path) || strtolower((string) pathinfo($source_path, PATHINFO_EXTENSION)) !== 'png') {
        continue;
    }

    $metadata = wp_get_attachment_metadata($attachment_id);
    $paths = [$source_path];
    $dir = trailingslashit(dirname($source_path));

    foreach (($metadata['sizes'] ?? []) as $size_data) {
        if (is_array($size_data) && !empty($size_data['file'])) {
            $paths[] = $dir . basename((string) $size_data['file']);
        }
    }

    foreach (array_unique($paths) as $path) {
        if (is_file($path . '.webp') && filemtime($path . '.webp') >= filemtime($path)) {
            ++$existing;
            continue;
        }

        if (\Standard\Images\generate_webp_sidecar($path)) {
            ++$created;
        } else {
            ++$failed;
            WP_CLI::warning(sprintf('Could not convert attachment %d: %s', $attachment_id, $path));
        }
    }
}

WP_CLI::success(sprintf(
    'Product WebP backfill complete: %d created, %d already existed, %d failed.',
    $created,
    $existing,
    $failed
));
