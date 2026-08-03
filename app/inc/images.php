<?php
/**
 * Image rendering helpers.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Images;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Resolve an attachment ID from a URL with a per-request cache.
 */
function get_attachment_id(string $url): int {
    static $cache = [];

    if ($url === '') {
        return 0;
    }

    // Cache-busting query strings appear in curated machine data but are not
    // part of the Media Library's canonical attachment URL.
    $lookup_url = (string) preg_replace('/[?#].*$/', '', $url);

    if (!array_key_exists($lookup_url, $cache)) {
        $key   = 'url2id_' . md5($lookup_url);
        $found = wp_cache_get($key, 'amt-ntm-images');
        if ($found !== false) {
            $cache[$lookup_url] = (int) $found;
        } else {
            $cache[$lookup_url] = function_exists('attachment_url_to_postid')
                ? (int) attachment_url_to_postid($lookup_url)
                : 0;
            wp_cache_set($key, $cache[$lookup_url], 'amt-ntm-images', 12 * HOUR_IN_SECONDS);
        }
    }

    return $cache[$lookup_url];
}

/**
 * Return the WebP sidecar URL for an attachment image URL when it exists.
 *
 * Sidecars deliberately retain the original extension
 * (`machine.png.webp`). The original image remains a universal fallback.
 */
function get_webp_sidecar_url(string $url): string {
    static $cache = [];

    if (isset($cache[$url])) {
        return $cache[$url];
    }

    $uploads   = wp_get_upload_dir();
    $baseurl   = (string) ($uploads['baseurl'] ?? '');
    $basedir   = (string) ($uploads['basedir'] ?? '');
    $clean_url = (string) preg_replace('/[?#].*$/', '', $url);

    if ($baseurl === '' || $basedir === '' || !str_starts_with($clean_url, $baseurl)) {
        $cache[$url] = '';
        return '';
    }

    $relative = rawurldecode(ltrim(substr($clean_url, strlen($baseurl)), '/'));
    $path     = trailingslashit($basedir) . $relative;

    if (!is_file($path . '.webp')) {
        $cache[$url] = '';
        return '';
    }

    $query = wp_parse_url($url, PHP_URL_QUERY);
    $cache[$url] = $clean_url . '.webp' . (is_string($query) && $query !== '' ? '?' . $query : '');

    return $cache[$url];
}

/**
 * Translate a WordPress srcset to available WebP sidecars.
 */
function get_webp_srcset(int $attachment_id, string $size): string {
    $srcset = wp_get_attachment_image_srcset($attachment_id, $size);
    if (!is_string($srcset) || $srcset === '') {
        return '';
    }

    $webp_candidates = [];
    foreach (explode(',', $srcset) as $candidate) {
        $candidate = trim($candidate);
        if (!preg_match('/^(\S+)\s+(.+)$/', $candidate, $matches)) {
            continue;
        }

        $webp_url = get_webp_sidecar_url(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
        if ($webp_url !== '') {
            $webp_candidates[] = esc_url($webp_url) . ' ' . sanitize_text_field($matches[2]);
        }
    }

    return implode(', ', $webp_candidates);
}

/**
 * Return responsive attachment markup with WebP sources and an original
 * format fallback.
 *
 * @param array<string, string> $attrs
 */
function get_attachment_picture(int|string $attachment_id, string $size = 'large', array $attrs = []): string {
    $attachment_id = (int) $attachment_id;

    if ($attachment_id <= 0) {
        return '';
    }

    $attrs = array_merge([
        'alt'      => '',
        'loading'  => 'lazy',
        'decoding' => 'async',
    ], $attrs);

    $image = wp_get_attachment_image($attachment_id, $size, false, $attrs);
    if (!is_string($image) || $image === '') {
        return '';
    }

    $webp_srcset = get_webp_srcset($attachment_id, $size);
    if ($webp_srcset === '') {
        return $image;
    }

    $sizes = $attrs['sizes'] ?? wp_get_attachment_image_sizes($attachment_id, $size);
    if (!is_string($sizes) || $sizes === '') {
        $sizes = '100vw';
    }

    return sprintf(
        '<picture class="ntm-picture"><source type="image/webp" srcset="%s" sizes="%s">%s</picture>',
        esc_attr($webp_srcset),
        esc_attr($sizes),
        $image
    );
}

/**
 * Render a responsive image when the URL belongs to a WP attachment.
 *
 * External URLs fall back to a plain img tag with the same attributes.
 * This keeps templates simple while still getting srcset/sizes whenever
 * WordPress knows the image.
 *
 * @param array<string, string> $attrs
 */
function responsive_image(string $url, string $alt = '', string $size = 'large', array $attrs = []): void {
    if ($url === '') {
        return;
    }

    // Rebase hardcoded prod-host URLs (curated data files) onto the current
    // site so the attachment lookup below can succeed locally and dev/staging
    // never loads images from production. No-op for any other host.
    $url = \Standard\Url\canonical($url);

    $attrs = array_merge([
        'alt'      => $alt,
        'loading'  => 'lazy',
        'decoding' => 'async',
    ], $attrs);

    $attachment_id = get_attachment_id($url);
    if ($attachment_id > 0) {
        echo get_attachment_picture($attachment_id, $size, $attrs);
        return;
    }

    $attributes = '';
    foreach ($attrs as $name => $value) {
        if ($value === '' && $name !== 'alt') {
            continue;
        }
        $attributes .= ' ' . esc_attr((string) $name) . '="' . esc_attr((string) $value) . '"';
    }

    echo '<img src="' . esc_url($url) . '"' . $attributes . '>';
}

/**
 * Generate WebP sidecars for the original and generated PNG sizes.
 *
 * This runs for new uploads. Existing product media is backfilled by the
 * accompanying WP-CLI script in scripts/media/generate-product-webp.php.
 *
 * @param array<string, mixed> $metadata
 * @return array<string, mixed>
 */
function generate_png_webp_sidecars(array $metadata, int $attachment_id): array {
    $source_path = get_attached_file($attachment_id);
    if (!is_string($source_path) || strtolower((string) pathinfo($source_path, PATHINFO_EXTENSION)) !== 'png') {
        return $metadata;
    }

    $paths = [$source_path];
    $dir   = trailingslashit(dirname($source_path));
    foreach (($metadata['sizes'] ?? []) as $size_data) {
        if (is_array($size_data) && !empty($size_data['file'])) {
            $paths[] = $dir . basename((string) $size_data['file']);
        }
    }

    foreach (array_unique($paths) as $path) {
        generate_webp_sidecar($path);
    }

    return $metadata;
}
add_filter('wp_generate_attachment_metadata', __NAMESPACE__ . '\\generate_png_webp_sidecars', 20, 2);

/**
 * Generate one WebP sidecar without replacing the original asset.
 */
function generate_webp_sidecar(string $source_path): bool {
    if (!is_file($source_path)) {
        return false;
    }

    $sidecar_path = $source_path . '.webp';
    if (is_file($sidecar_path) && filemtime($sidecar_path) >= filemtime($source_path)) {
        return true;
    }

    $editor = wp_get_image_editor($source_path);
    if (is_wp_error($editor)) {
        return false;
    }

    $editor->set_quality(82);
    $saved = $editor->save($sidecar_path, 'image/webp');

    return !is_wp_error($saved) && is_file($sidecar_path);
}

/**
 * Render an inline SVG placeholder for cards that lack a featured image.
 *
 * Inline SVG so it scales to any card width without an extra HTTP request,
 * and so the type/stroke colors stay in sync with the card border palette
 * (blue-50 ground, blue-200 hairline, blue-400 mark). Sits inside the
 * card's existing aspect-ratio wrapper, so we don't redeclare the box.
 *
 * @param array<string, string> $attrs
 */
function fallback_image(array $attrs = []): void {
    $attrs = array_merge([
        'class'       => 'w-full h-full block',
        'role'        => 'img',
        'aria-hidden' => 'true',
    ], $attrs);

    $attributes = '';
    foreach ($attrs as $name => $value) {
        if ($value === '') {
            continue;
        }
        $attributes .= ' ' . esc_attr((string) $name) . '="' . esc_attr((string) $value) . '"';
    }

    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 180" preserveAspectRatio="xMidYMid slice"' . $attributes . '>'
        . '<rect width="320" height="180" fill="#eff6ff"/>'
        . '<g fill="none" stroke="#bfdbfe" stroke-width="1">'
        . '<path d="M0 45h320M0 90h320M0 135h320"/>'
        . '<path d="M80 0v180M160 0v180M240 0v180"/>'
        . '</g>'
        . '<g transform="translate(160 90)" text-anchor="middle" font-family="ui-monospace, SFMono-Regular, Menlo, monospace" fill="#60a5fa">'
        . '<text y="-6" font-size="16" font-weight="600" letter-spacing="2">NEWTECH</text>'
        . '<text y="16" font-size="10" letter-spacing="3">MACHINERY</text>'
        . '</g>'
        . '</svg>';
}
