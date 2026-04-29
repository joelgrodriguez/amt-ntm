<?php
/**
 * Mobile navigation tree.
 *
 * Hardcoded data source for the mobile menu. Returns the L1 + L2 structure
 * consumed by app/header.php and app/templates/parts/mobile-menu-panel.php.
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
 *   - top: array of items rendered above the divider on L1
 *       - panel items open an L2 panel; require slug, label, category, view_all_url
 *       - link items navigate directly; require label, url
 *   - bottom: array of link items rendered below the divider on L1
 *
 * @return array{top: array<int, array<string, mixed>>, bottom: array<int, array<string, mixed>>}
 */
function get_mobile_nav_tree(): array {
    return [
        'top' => [
            [
                'type'         => 'panel',
                'slug'         => 'roof-wall-panel-machines',
                'label'        => __('Roof & Wall Panel Machines', 'standard'),
                'category'     => 'roof-wall-panel-machines',
                'view_all_url' => '/roof-wall-panel-machines/',
            ],
            [
                'type'         => 'panel',
                'slug'         => 'seamless-gutter-machines',
                'label'        => __('Seamless Gutter Machines', 'standard'),
                'category'     => 'gutter-machines',
                'view_all_url' => '/seamless-gutter-machines/',
            ],
            [
                'type'  => 'link',
                'label' => __('Parts & Accessories', 'standard'),
                'url'   => '/product-category/accessories-add-on-equipment/',
            ],
            [
                'type'  => 'link',
                'label' => __('Build & Finance', 'standard'),
                'url'   => '/build-finance/',
            ],
        ],
        'bottom' => [
            [
                'type'  => 'link',
                'label' => __('Service & Repair', 'standard'),
                'url'   => '/service-training/',
            ],
            [
                'type'  => 'link',
                'label' => __('Learning Center', 'standard'),
                'url'   => '/learning-center/',
            ],
            [
                'type'  => 'link',
                'label' => __('About', 'standard'),
                'url'   => '/about/',
            ],
            [
                'type'  => 'link',
                'label' => __('Contact', 'standard'),
                'url'   => '/contact/',
            ],
        ],
    ];
}
