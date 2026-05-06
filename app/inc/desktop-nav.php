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
                        'id'               => 'roof-wall',
                        'label'            => __('Roof & Wall Panel Machines', 'standard'),
                        'category'         => 'roof-wall-panel-machines',
                        'heading'          => __('Latest Roof & Wall Panel Machines', 'standard'),
                        'view_all_url'     => 'https://newtechmachinery.com/roof-wall-panel-machines/',
                        'view_all_label'   => __('View all Roof & Wall Panel Machines', 'standard'),
                    ],
                    [
                        'id'               => 'gutter',
                        'label'            => __('Seamless Gutter Machines', 'standard'),
                        'category'         => 'gutter-machines',
                        'heading'          => __('Latest Seamless Gutter Machines', 'standard'),
                        'view_all_url'     => 'https://newtechmachinery.com/seamless-gutter-machines/',
                        'view_all_label'   => __('View all Seamless Gutter Machines', 'standard'),
                    ],
                    [
                        'id'               => 'accessories',
                        'label'            => __('Accessories', 'standard'),
                        'category'         => 'accessories-add-on-equipment',
                        'heading'          => __('Accessories & Upgrades', 'standard'),
                        'view_all_url'     => \Standard\Url\internal('/upgrades-accessories/'),
                        'view_all_label'   => __('View all Upgrades & Accessories', 'standard'),
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
                        'id'             => 'roof-wall-panel',
                        'label'          => __('Roof & Wall Panel', 'standard'),
                        'category'       => 'profiles-metal-roof-wall-panel',
                        'heading'        => __('Latest Roof & Wall Panel Profiles', 'standard'),
                        'view_all_url'   => 'https://newtechmachinery.com/profile-search/?_sft_category=profiles-metal-roof-wall-panel',
                        'view_all_label' => __('View all Roof & Wall Panel Profiles', 'standard'),
                    ],
                    [
                        'id'             => 'gutter',
                        'label'          => __('Gutter', 'standard'),
                        'category'       => 'profiles-gutter',
                        'heading'        => __('Latest Seamless Gutter Profiles', 'standard'),
                        'view_all_url'   => 'https://newtechmachinery.com/profile-search/?_sft_category=profiles-gutter',
                        'view_all_label' => __('View all Seamless Gutter Profiles', 'standard'),
                    ],
                    [
                        'id'             => 'clip-relief-rib-rollers',
                        'label'          => __('Clip Relief / Rib Rollers', 'standard'),
                        'category'       => 'clip-relief-rib-rollers',
                        'heading'        => __('Latest Clip Relief / Rib Rollers', 'standard'),
                        'view_all_url'   => 'https://newtechmachinery.com/profile-search/?_sft_category=clip-relief-rib-rollers',
                        'view_all_label' => __('View all Clip Relief / Rib Rollers', 'standard'),
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
                        'id'             => 'articles',
                        'label'          => __('Articles', 'standard'),
                        'post_type'      => 'post',
                        'heading'        => __('Latest Articles', 'standard'),
                        'view_all_url'   => \Standard\Url\internal('/learning-center/articles/'),
                        'view_all_label' => __('View all Articles', 'standard'),
                    ],
                    [
                        'id'             => 'videos',
                        'label'          => __('Videos', 'standard'),
                        'post_type'      => 'video',
                        'heading'        => __('Latest Videos', 'standard'),
                        'view_all_url'   => \Standard\Url\internal('/learning-center/videos/'),
                        'view_all_label' => __('View all Videos', 'standard'),
                    ],
                    [
                        'id'             => 'downloads',
                        'label'          => __('Downloads', 'standard'),
                        'post_type'      => 'download',
                        'heading'        => __('Latest Downloads', 'standard'),
                        'view_all_url'   => \Standard\Url\internal('/learning-center/downloads/'),
                        'view_all_label' => __('View all Downloads', 'standard'),
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
