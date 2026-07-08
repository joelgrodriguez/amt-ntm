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
 * Turn the Choose Your Machine desktop tabs into a single stacked mobile group.
 *
 * Desktop renders each tab as a live product/profile grid; mobile can't, so we
 * flatten each tab to one link pointing at its "View all" archive. The Profiles
 * tab has no single archive of its own on some tabs, so we fall back to the
 * tab's own view_all_url. Result: users can still reach every machine category
 * from the mobile L2 panel instead of hitting an empty screen.
 *
 * @param array<int, array<string, mixed>> $tabs
 * @return array<int, array<string, mixed>>
 */
function reshape_machine_tabs_to_groups(array $tabs): array {
    $items = [];
    foreach ($tabs as $tab) {
        $url = $tab['view_all_url'] ?? '';
        if ($url === '') {
            continue;
        }
        $items[] = [
            'label' => $tab['label'] ?? ($tab['heading'] ?? ''),
            'url'   => $url,
        ];
    }

    if ($items === []) {
        return [];
    }

    return [
        [
            'label' => __('Browse machines', 'standard'),
            'items' => $items,
        ],
    ];
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

        // The Choose Your Machine panel is 'tabbed-machines': its content lives
        // in 'tabs', not 'groups', and it has no 'intro'. On desktop the tabs
        // render live WooCommerce grids; mobile can't. Reshape the tabs into
        // stacked link groups so the L2 panel isn't empty (which would make the
        // L1 chevron row a dead panel-opener). Synthesize an intro so the
        // panel gets a "See all machines" footer link.
        if (($item['type'] ?? '') === 'tabbed-machines') {
            $top[] = [
                'type'   => 'panel',
                'slug'   => $item['id'],
                'label'  => $item['label'],
                'intro'  => [
                    'secondary_label' => $item['view_all_label'] ?? __('See all machines', 'standard'),
                    'secondary_url'   => $item['view_all_url'] ?? \Standard\Url\internal('/machines/'),
                    'secondary_links' => $item['secondary_links'] ?? [],
                ],
                'groups' => reshape_machine_tabs_to_groups($item['tabs'] ?? []),
            ];
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
