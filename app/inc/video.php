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
 * Render a click-to-load facade for Wistia videos.
 *
 * No Wistia JavaScript, iframe, tracking pixel, or media request is made until
 * the visitor deliberately starts the video.
 */
function render_wistia_facade(?string $video, string $title = '', string $poster = '', bool $eager = false): string
{
    $url = normalize_video_url($video);
    if ($url === null || !is_wistia_url($url)) {
        return '';
    }

    $media_id = get_wistia_media_id($url);
    if ($media_id === '') {
        return '';
    }

    $host = strtolower((string) wp_parse_url($url, PHP_URL_HOST));
    if (host_matches($host, 'wistia.com') && preg_match('/medias\/([a-zA-Z0-9]+)/', $url, $matches)) {
        $url = 'https://fast.wistia.net/embed/iframe/' . $matches[1] . '?videoFoam=true';
    }

    if (!str_contains($url, '/embed/iframe/')) {
        return '';
    }

    $label = $title !== ''
        ? sprintf(__('Play %s', 'standard'), $title)
        : __('Play video', 'standard');

    $poster_markup = '';
    if ($poster !== '') {
        $poster_attrs = [
            'class'    => 'video-facade__poster',
            'loading'  => $eager ? 'eager' : 'lazy',
            'decoding' => 'async',
            'sizes'    => '(max-width: 1023px) 100vw, 50vw',
            'aria-hidden' => 'true',
        ];
        if ($eager) {
            $poster_attrs['fetchpriority'] = 'high';
        }

        ob_start();
        \Standard\Images\responsive_image($poster, '', 'large', $poster_attrs);
        $poster_markup = (string) ob_get_clean();
    } else {
        $poster_markup = get_local_wistia_poster_markup($media_id, $eager);
    }

    return sprintf(
        '<div class="video-facade" data-video-facade data-video-src="%s">%s<button type="button" class="video-facade__button" aria-label="%s"><span class="video-facade__play" aria-hidden="true"></span><span class="video-facade__label">%s</span></button></div>',
        esc_url($url),
        $poster_markup,
        esc_attr($label),
        esc_html__('Play video', 'standard')
    );
}

/**
 * Extract the stable media ID from a supported Wistia URL.
 */
function get_wistia_media_id(?string $video): string
{
    $url = normalize_video_url($video);
    if ($url === null) {
        return '';
    }

    if (!preg_match('~/(?:embed/iframe|medias)/([a-zA-Z0-9]+)~', $url, $matches)) {
        return '';
    }

    return sanitize_key($matches[1]);
}

/**
 * Render locally stored responsive Wistia poster images.
 */
function get_local_wistia_poster_markup(string $media_id, bool $eager = false): string
{
    $media_id = sanitize_key($media_id);
    if ($media_id === '') {
        return '';
    }

    $relative_dir = '/assets/images/wistia/';
    $path_480 = THEME_DIR . $relative_dir . $media_id . '-480.webp';
    $path_960 = THEME_DIR . $relative_dir . $media_id . '-960.webp';

    if (!is_file($path_480) || !is_file($path_960)) {
        return '';
    }

    $version = (string) max((int) filemtime($path_480), (int) filemtime($path_960));
    $url_480 = add_query_arg('ver', $version, THEME_URI . $relative_dir . $media_id . '-480.webp');
    $url_960 = add_query_arg('ver', $version, THEME_URI . $relative_dir . $media_id . '-960.webp');
    $loading = $eager ? 'eager' : 'lazy';
    $priority = $eager ? ' fetchpriority="high"' : '';

    return sprintf(
        '<picture class="ntm-picture"><source type="image/webp" srcset="%1$s 480w, %2$s 960w" sizes="(max-width: 1023px) 100vw, 50vw"><img src="%2$s" width="960" height="540" class="video-facade__poster" alt="" loading="%3$s" decoding="async" aria-hidden="true"%4$s></picture>',
        esc_url($url_480),
        esc_url($url_960),
        esc_attr($loading),
        $priority
    );
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
