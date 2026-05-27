<?php
/**
 * Mobile navigation tree.
 *
 * Mirrors the 4-action mega menu IA for mobile. Each top-level section opens
 * an L2 panel that renders the same intro + 3-group structure as the desktop
 * flyout, just stacked. See app/inc/desktop-nav.php for the spec.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Nav;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns the mobile navigation tree.
 *
 * Shape:
 *   - top: array of L1 rows.
 *       - panel rows: slug, label, intro, groups (mirrors desktop flyout)
 *       - link rows:  label, url
 *   - contact: optional CTA banner; label, url, optional icon
 *   - bottom: array of secondary link items; each: label, url, optional icon
 *
 * @return array{
 *   top: array<int, array<string, mixed>>,
 *   contact?: array<string, mixed>,
 *   bottom: array<int, array<string, mixed>>
 * }
 */
function get_mobile_nav_tree(): array {
    $desktop = get_desktop_nav();

    // Reshape the 4 desktop mega items into mobile L1 panel rows. The mobile
    // L2 template reads intro + groups from each row and stacks them.
    $top = [];
    foreach ($desktop['items'] as $item) {
        if (($item['kind'] ?? '') !== 'mega') {
            continue;
        }
        $top[] = [
            'type'   => 'panel',
            'slug'   => $item['id'],
            'label'  => $item['label'],
            'intro'  => $item['intro']  ?? [],
            'groups' => $item['groups'] ?? [],
        ];
    }

    return [
        'top'     => $top,
        'contact' => [
            'label' => __('Talk to a specialist', 'standard'),
            'url'   => \Standard\Url\internal('/contact/'),
            'icon'  => 'mail',
        ],
        'bottom'  => [
            [
                'type'  => 'link',
                'label' => __('Learning Center', 'standard'),
                'url'   => \Standard\Url\internal('/learning-center/'),
                'icon'  => 'folder',
            ],
            [
                'type'  => 'link',
                'label' => __('Service Hub', 'standard'),
                'url'   => \Standard\Url\internal('/service-hub/'),
                'icon'  => 'life-buoy',
            ],
        ],
    ];
}
