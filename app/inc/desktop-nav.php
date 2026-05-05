<?php
/**
 * Desktop navigation data.
 *
 * Hardcoded data source for the desktop mega menu and utility rail.
 * Returns the panel structure consumed by app/templates/parts/mega-menu.php
 * and app/header.php.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Nav;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Returns the desktop navigation structure.
 *
 * @return array{panels: array<int, array<string, mixed>>, utility: array<int, array<string, mixed>>}
 */
function get_desktop_nav(): array {
    return [
        // Ordered list of nav items — 'mega' items open a panel, 'link' items navigate directly.
        'items' => [
            [
                'kind'           => 'mega',
                'id'             => 'machines',
                'label'          => __('Machines', 'standard'),
                'type'           => 'tabbed-products',
                'tabs'           => [
                    [
                        'id'       => 'roof-wall',
                        'label'    => __('Roof & Wall Panel Machines', 'standard'),
                        'category' => 'roof-wall-panel-machines',
                    ],
                    [
                        'id'       => 'gutter',
                        'label'    => __('Seamless Gutter Machines', 'standard'),
                        'category' => 'gutter-machines',
                    ],
                ],
                'view_all_url'   => \Standard\Url\internal('/machines/'),
                'view_all_label' => __('See the full lineup', 'standard'),
            ],
            [
                'kind'           => 'mega',
                'id'             => 'profiles',
                'label'          => __('Profiles', 'standard'),
                'type'           => 'tabbed-profiles',
                'tabs'           => [
                    [
                        'id'       => 'roof-wall-panel',
                        'label'    => __('Roof & Wall Panel', 'standard'),
                        'category' => 'profiles-metal-roof-wall-panel',
                    ],
                    [
                        'id'       => 'gutter',
                        'label'    => __('Gutter', 'standard'),
                        'category' => 'profiles-gutter',
                    ],
                    [
                        'id'       => 'clip-relief-rib-rollers',
                        'label'    => __('Clip Relief / Rib Rollers', 'standard'),
                        'category' => 'clip-relief-rib-rollers',
                    ],
                ],
                'view_all_url'   => \Standard\Url\internal('/profiles/'),
                'view_all_label' => __('View all profiles', 'standard'),
            ],
            [
                'kind'  => 'link',
                'label' => __('Resources', 'standard'),
                'url'   => \Standard\Url\internal('/resources/'),
            ],
            [
                'kind'           => 'mega',
                'id'             => 'learning-center',
                'label'          => __('Learning Center', 'standard'),
                'type'           => 'tabbed-content',
                'tabs'           => [
                    [
                        'id'        => 'articles',
                        'label'     => __('Articles', 'standard'),
                        'post_type' => 'post',
                    ],
                    [
                        'id'        => 'videos',
                        'label'     => __('Videos', 'standard'),
                        'post_type' => 'video',
                    ],
                    [
                        'id'        => 'downloads',
                        'label'     => __('Downloads', 'standard'),
                        'post_type' => 'download',
                    ],
                ],
                'view_all_url'   => \Standard\Url\internal('/learning-center/'),
                'view_all_label' => __('Visit Learning Center', 'standard'),
            ],
            [
                'kind'  => 'link',
                'label' => __('Service & Support', 'standard'),
                'url'   => \Standard\Url\internal('/support/'),
            ],
        ],
        'utility' => [
            [
                'label' => __('Service & Repair', 'standard'),
                'url'   => \Standard\Url\internal('/service-training/'),
            ],
            [
                'label' => __('Build & Finance', 'standard'),
                'url'   => \Standard\Url\internal('/build-finance/'),
            ],
            [
                'label'     => __('Contact', 'standard'),
                'url'       => \Standard\Url\internal('/contact/'),
                'highlight' => true,
            ],
        ],
    ];
}
