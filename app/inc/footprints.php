<?php
/**
 * Footprints — archive redirect.
 *
 * The footprint CPT registers its archive at /learning-center/footprint/,
 * but the canonical landing now lives at /machines/footprints/ (page
 * 1764, template page-footprints.php). 301 the CPT archive so we don't
 * carry two URLs for the same surface.
 *
 * Single-footprint URLs (/learning-center/footprint/<slug>/) are
 * untouched — only the bare archive index redirects.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Footprints;

if (!defined('ABSPATH')) {
    exit;
}

function redirect_archive_to_landing(): void {
    if (\is_admin() || !\is_post_type_archive('footprint')) {
        return;
    }

    \wp_safe_redirect(\home_url('/machines/footprints/'), 301);
    exit;
}
\add_action('template_redirect', __NAMESPACE__ . '\\redirect_archive_to_landing');
