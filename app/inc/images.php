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
        echo wp_get_attachment_image($attachment_id, $size, false, $attrs);
        return;
    }

    $attributes = '';
    foreach ($attrs as $name => $value) {
        if ($value === '') {
            continue;
        }
        $attributes .= ' ' . esc_attr((string) $name) . '="' . esc_attr((string) $value) . '"';
    }

    echo '<img src="' . esc_url($url) . '"' . $attributes . '>';
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
