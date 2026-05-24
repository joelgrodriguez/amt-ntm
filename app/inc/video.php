<?php
/**
 * Video embed helpers.
 *
 * Centralises video rendering logic so that template parts, page templates,
 * and single-video pages can all share the same embed pipeline.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Video;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Allowed iframe attributes for supported video embeds.
 */
const ALLOWED_VIDEO_EMBED_HTML = [
    'iframe' => [
        'allow'                 => true,
        'allowfullscreen'       => true,
        'allowtransparency'     => true,
        'class'                 => true,
        'frameborder'           => true,
        'height'                => true,
        'loading'               => true,
        'mozallowfullscreen'    => true,
        'msallowfullscreen'     => true,
        'name'                  => true,
        'playsinline'           => true,
        'referrerpolicy'        => true,
        'scrolling'             => true,
        'src'                   => true,
        'title'                 => true,
        'webkitallowfullscreen' => true,
        'width'                 => true,
    ],
];

/**
 * Check whether a URL points to a Wistia video.
 *
 * @param string|null $url The URL or legacy embed markup to check.
 * @return bool
 */
function is_wistia_url(?string $url): bool
{
    $normalized = normalize_video_url($url);
    if ($normalized === null) {
        return false;
    }

    $host = wp_parse_url($normalized, PHP_URL_HOST);
    if (!is_string($host)) {
        return false;
    }

    $host = strtolower($host);

    return host_matches($host, 'wistia.com') || host_matches($host, 'wistia.net');
}

/**
 * Render video embed HTML from various sources.
 *
 * Handles:
 * - Plain video URLs.
 * - Legacy iframe/embed HTML by extracting the src URL first.
 * - Direct Wistia embed iframe URLs (fast.wistia.net/embed/iframe/…).
 * - Wistia share URLs (wistia.com/medias/…).
 * - Supported oEmbed video providers such as YouTube and Vimeo.
 *
 * URLs are preferred. Raw embed HTML is accepted only for backward
 * compatibility and is normalized down to its src URL before rendering.
 *
 * @param string|null $video The video URL or legacy embed code.
 * @return string The rendered embed HTML.
 */
function render_video_embed(?string $video): string
{
    $url = normalize_video_url($video);
    if ($url === null) {
        return '';
    }

    $host = wp_parse_url($url, PHP_URL_HOST);
    if (!is_string($host)) {
        return '';
    }

    $host = strtolower($host);
    if (host_matches($host, 'fast.wistia.net') && str_contains($url, '/embed/iframe/')) {
        return sanitize_video_embed_html(sprintf(
            '<iframe src="%s" allowtransparency="true" frameborder="0" scrolling="no" name="wistia_embed" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>',
            esc_url($url)
        ));
    }
    if (host_matches($host, 'wistia.com') && str_contains($url, '/medias/')) {
        if (preg_match('/medias\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return sanitize_video_embed_html(sprintf(
                '<iframe src="https://fast.wistia.net/embed/iframe/%s?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" name="wistia_embed" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>',
                esc_attr($matches[1])
            ));
        }
    }

    if (!is_supported_oembed_host($host)) {
        return '';
    }

    $embed = wp_oembed_get($url);

    return is_string($embed) ? sanitize_video_embed_html($embed) : '';
}

/**
 * Normalize a video field value down to a validated URL.
 *
 * Accepts plain URLs or legacy iframe/embed HTML and returns the extracted src.
 *
 * @param string|null $video Raw field value.
 * @return string|null
 */
function normalize_video_url(?string $video): ?string
{
    if (!is_string($video)) {
        return null;
    }

    $video = trim($video);
    if ($video === '') {
        return null;
    }

    if (str_contains($video, '<iframe') || str_contains($video, '<embed')) {
        $video = extract_embed_src($video) ?? '';
    } else {
        $video = wp_strip_all_tags($video);
    }

    $video = trim($video);
    if ($video === '') {
        return null;
    }

    if (str_starts_with($video, '//')) {
        $video = 'https:' . $video;
    }

    return wp_http_validate_url($video) ? $video : null;
}

/**
 * Extract the src attribute from iframe/embed markup.
 *
 * @param string $markup Legacy embed markup.
 * @return string|null
 */
function extract_embed_src(string $markup): ?string
{
    if (!preg_match('/<(?:iframe|embed)\b[^>]*\bsrc=(["\'])(.*?)\1/i', $markup, $matches)) {
        return null;
    }

    $src = html_entity_decode($matches[2], ENT_QUOTES, 'UTF-8');

    return trim($src) !== '' ? $src : null;
}

/**
 * Sanitize trusted provider embed HTML.
 *
 * @param string $embed_html Embed HTML from a supported provider.
 * @return string
 */
function sanitize_video_embed_html(string $embed_html): string
{
    return wp_kses($embed_html, ALLOWED_VIDEO_EMBED_HTML);
}

/**
 * Check whether a host matches a domain or subdomain.
 *
 * @param string $host Parsed URL host.
 * @param string $domain Allowed domain.
 * @return bool
 */
function host_matches(string $host, string $domain): bool
{
    return $host === $domain || str_ends_with($host, '.' . $domain);
}

/**
 * Limit oEmbed usage to known video providers.
 *
 * @param string $host Parsed URL host.
 * @return bool
 */
function is_supported_oembed_host(string $host): bool
{
    foreach ([
        'youtube.com',
        'youtube-nocookie.com',
        'youtu.be',
        'vimeo.com',
    ] as $domain) {
        if (host_matches($host, $domain)) {
            return true;
        }
    }

    return false;
}
