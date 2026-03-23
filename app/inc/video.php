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

/**
 * Check whether a URL points to a Wistia video.
 *
 * @param string $url The URL to check.
 * @return bool
 */
function is_wistia_url(string $url): bool
{
    return str_contains($url, 'wistia.net') || str_contains($url, 'wistia.com');
}

/**
 * Render video embed HTML from various sources.
 *
 * Handles:
 * - Pre-rendered embed HTML (iframe / embed tags) — returned as-is.
 * - Direct Wistia embed iframe URLs (fast.wistia.net/embed/iframe/…).
 * - Wistia share URLs (wistia.com/medias/…).
 * - YouTube, Vimeo, or any other oEmbed provider via wp_oembed_get().
 *
 * @param string $video The video URL or embed code.
 * @return string The rendered embed HTML.
 */
function render_video_embed(string $video): string
{
    // Already an embed (iframe or embed tag)
    if (str_contains($video, '<iframe') || str_contains($video, '<embed')) {
        return $video;
    }

    $url = wp_strip_all_tags($video);

    // Direct Wistia embed iframe URL
    if (str_contains($url, 'fast.wistia.net/embed/iframe/')) {
        return sprintf(
            '<iframe src="%s" allowtransparency="true" frameborder="0" scrolling="no" name="wistia_embed" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>',
            esc_url($url)
        );
    }

    // Wistia share URL
    if (str_contains($url, 'wistia.com/medias/')) {
        if (preg_match('/medias\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return sprintf(
                '<iframe src="https://fast.wistia.net/embed/iframe/%s?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" name="wistia_embed" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>',
                esc_attr($matches[1])
            );
        }
    }

    // YouTube, Vimeo, or other oEmbed providers
    $embed = wp_oembed_get($url);

    return $embed ?: '';
}
