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
 *           - slug is the panel identifier used by data attributes / aria hooks
 *           - category is the WooCommerce product category slug used to query L2 cards
 *           - the two may match (e.g. roof-wall-panel-machines) or differ
 *             (e.g. slug=seamless-gutter-machines, category=gutter-machines)
 *       - link items navigate directly; require label, url
 *   - featured: optional card-style CTA rendered above the top group on
 *     the L1 panel; requires label, subtitle, url, image
 *   - contact: optional brand-red CTA banner rendered between the top
 *     group and the secondary group on the L1 panel; requires label,
 *     url; may include an optional icon
 *   - bottom: array of secondary link items rendered at the panel bottom
 *       - each requires label, url; may include an optional `icon` (icon
 *         name from app/assets/icons/ rendered to the left of the label)
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
                'view_all_url' => \Standard\Url\internal('/roof-wall-panel-machines/'),
            ],
            [
                'type'         => 'panel',
                'slug'         => 'seamless-gutter-machines',
                'label'        => __('Seamless Gutter Machines', 'standard'),
                'category'     => 'gutter-machines',
                'view_all_url' => \Standard\Url\internal('/seamless-gutter-machines/'),
            ],
            [
                'type'  => 'link',
                'label' => __('Parts & Accessories', 'standard'),
                'url'   => \Standard\Url\internal('/product-category/accessories-add-on-equipment/'),
            ],
            [
                'type'  => 'link',
                'label' => __('Build & Finance', 'standard'),
                'url'   => \Standard\Url\internal('/build-finance/'),
            ],
        ],
        'featured' => [
            'label'    => __('Find your machine', 'standard'),
            'subtitle' => __('See the full lineup', 'standard'),
            'url'      => \Standard\Url\internal('/machines/'),
            // SSQ3 in-context shot pulled from app/data/machines/ssq3-multipro.php
            // (hero.image). Hardcoded here rather than reaching across systems.
            'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
        ],
        'contact' => [
            'label' => __('Contact us', 'standard'),
            'url'   => \Standard\Url\internal('/contact/'),
            'icon'  => 'mail',
        ],
        'bottom' => [
            [
                'type'  => 'link',
                'label' => __('Service & Repair', 'standard'),
                'url'   => \Standard\Url\internal('/service-training/'),
                'icon'  => 'life-buoy',
            ],
            [
                'type'  => 'link',
                'label' => __('Learning Center', 'standard'),
                'url'   => \Standard\Url\internal('/learning-center/'),
                'icon'  => 'graduation-cap',
            ],
            [
                'type'  => 'link',
                'label' => __('About', 'standard'),
                'url'   => \Standard\Url\internal('/about/'),
                'icon'  => 'help-circle',
            ],
        ],
    ];
}
