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
        'panels' => [
            [
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
                'id'             => 'upgrades',
                'label'          => __('Upgrades & Accessories', 'standard'),
                'type'           => 'product-grid',
                'category'       => 'accessories-add-on-equipment',
                'view_all_url'   => \Standard\Url\internal('/product-category/accessories-add-on-equipment/'),
                'view_all_label' => __('Shop all upgrades', 'standard'),
            ],
            [
                'id'    => 'learning-center',
                'label' => __('Learning Center', 'standard'),
                'type'  => 'learning-center',
            ],
            [
                'id'    => 'profiles',
                'label' => __('Profiles', 'standard'),
                'type'  => 'tabbed-profiles',
                'tabs'  => [
                    [
                        'id'       => 'engineered',
                        'label'    => __('Engineered', 'standard'),
                        'category' => 'engineered',
                    ],
                    [
                        'id'       => 'non-engineered',
                        'label'    => __('Non-Engineered', 'standard'),
                        'category' => 'non-engineered',
                    ],
                ],
                'view_all_url'   => \Standard\Url\internal('/profiles/'),
                'view_all_label' => __('View all profiles', 'standard'),
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
