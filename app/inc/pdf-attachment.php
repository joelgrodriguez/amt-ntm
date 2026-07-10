<?php
/**
 * Resolve the first PDF URL embedded in a post's content.
 *
 * Used by single-profile and single-footprint to surface native
 * download/open links beside the pdfjs viewer instead of trapping
 * the buyer inside it.
 *
 * Scans the post body for whichever marker shows up first:
 *   - shortcode arg `attachment_id=<id>`
 *   - block attribute `"imgID":<id>`
 *   - raw `url=<…>.pdf` on the shortcode
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\PdfAttachment;

if (!defined('ABSPATH')) {
    exit;
}

function url_from_post(\WP_Post $post): ?string {
    $content = (string) $post->post_content;
    if ($content === '') {
        return null;
    }

    if (preg_match('/attachment_id=(\d+)/', $content, $m) === 1) {
        $url = \wp_get_attachment_url((int) $m[1]);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    if (preg_match('/"imgID":(\d+)/', $content, $m) === 1) {
        $url = \wp_get_attachment_url((int) $m[1]);
        if (is_string($url) && $url !== '') {
            return $url;
        }
    }

    if (preg_match('/url=([^\s\]"]+\.pdf)/i', $content, $m) === 1) {
        $url = esc_url_raw($m[1]);
        return $url !== '' ? $url : null;
    }

    return null;
}
