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

    if (!array_key_exists($url, $cache)) {
        $cache[$url] = function_exists('attachment_url_to_postid')
            ? (int) attachment_url_to_postid($url)
            : 0;
    }

    return $cache[$url];
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

    $defaults = [
        'loading'  => 'lazy',
        'decoding' => 'async',
    ];
    // Only pass alt explicitly when the caller provided one. Empty caller alt
    // lets wp_get_attachment_image fall back to the media library's stored
    // alt; external images get an empty alt (treated as decorative).
    if ($alt !== '') {
        $defaults['alt'] = $alt;
    }
    $attrs = array_merge($defaults, $attrs);

    $attachment_id = get_attachment_id($url);
    if ($attachment_id > 0) {
        echo wp_get_attachment_image($attachment_id, $size, false, $attrs);
        return;
    }

    // External image: always emit an alt attribute (empty = decorative).
    if (!array_key_exists('alt', $attrs)) {
        $attrs['alt'] = '';
    }

    $attributes = '';
    foreach ($attrs as $name => $value) {
        if ($name !== 'alt' && $value === '') {
            continue;
        }
        $attributes .= ' ' . esc_attr((string) $name) . '="' . esc_attr((string) $value) . '"';
    }

    echo '<img src="' . esc_url($url) . '"' . $attributes . '>';
}
