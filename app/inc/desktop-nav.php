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
 * Build a taxonomy archive URL scoped to one post type.
 */
function get_scoped_term_url(string $taxonomy, string $slug, string $post_type): string {
    $term = \get_term_by('slug', $slug, $taxonomy);
    if ($term instanceof \WP_Term) {
        $link = \get_term_link($term);
        if (!\is_wp_error($link)) {
            return \add_query_arg(['post_type' => \sanitize_key($post_type)], $link);
        }
    }

    $base = $taxonomy === 'post_tag'
        ? '/tag/' . $slug . '/'
        : '/category/' . $slug . '/';

    return \add_query_arg(['post_type' => \sanitize_key($post_type)], \Standard\Url\internal($base));
}

/**
 * Test whether a nav item represents the URL the user is currently viewing.
 *
 * Compares the request path against the item's URL path (or any of its
 * known descendant paths). Mega items declare descendants via an optional
 * 'current_paths' array; link items just match their own url. Anything
 * pointing at "/" only matches the literal home page so it doesn't claim
 * every URL on the site.
 */
function is_current_item(array $item): bool {
    $candidate_urls = [];

    if (isset($item['url']) && is_string($item['url']) && $item['url'] !== '') {
        $candidate_urls[] = $item['url'];
    }

    // Mega items use their view_all_url as the "this item's home" target,
    // and an optional current_paths list for descendants the item owns.
    if (isset($item['view_all_url']) && is_string($item['view_all_url']) && $item['view_all_url'] !== '') {
        $candidate_urls[] = $item['view_all_url'];
    }

    if (isset($item['current_paths']) && is_array($item['current_paths'])) {
        foreach ($item['current_paths'] as $path) {
            if (is_string($path) && $path !== '') {
                $candidate_urls[] = \home_url($path);
            }
        }
    }

    if ($candidate_urls === []) {
        return false;
    }

    $request_path = current_request_path();

    foreach ($candidate_urls as $url) {
        $item_path = url_to_path($url);

        if ($item_path === '/' || $item_path === '') {
            // Home only matches the literal front page.
            if ($request_path === '/' && \is_front_page()) {
                return true;
            }
            continue;
        }

        if ($request_path === $item_path || str_starts_with($request_path, $item_path)) {
            return true;
        }
    }

    return false;
}

/**
 * Normalize the current request to "/some/path/" — leading + trailing slash,
 * no query string, no host. Returns "/" for the front page.
 */
function current_request_path(): string {
    $uri = isset($_SERVER['REQUEST_URI']) ? (string) $_SERVER['REQUEST_URI'] : '/';
    $path = (string) parse_url($uri, PHP_URL_PATH);
    if ($path === '' || $path === false) {
        $path = '/';
    }

    if ($path !== '/' && !str_ends_with($path, '/')) {
        $path .= '/';
    }

    return $path;
}

/**
 * Strip host + query from a URL down to its path with leading + trailing slash.
 */
function url_to_path(string $url): string {
    $path = (string) parse_url($url, PHP_URL_PATH);
    if ($path === '' || $path === false) {
        return '/';
    }

    if (!str_starts_with($path, '/')) {
        $path = '/' . $path;
    }
    if ($path !== '/' && !str_ends_with($path, '/')) {
        $path .= '/';
    }

    return $path;
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
                        'heading'          => __('Roof & Wall Panel Machines', 'standard'),
                        'view_all_url'     => 'https://newtechmachinery.com/roof-wall-panel-machines/',
                        'view_all_label'   => __('View All', 'standard'),
                    ],
                    [
                        'id'               => 'gutter',
                        'label'            => __('Seamless Gutter Machines', 'standard'),
                        'category'         => 'gutter-machines',
                        'heading'          => __('Seamless Gutter Machines', 'standard'),
                        'view_all_url'     => 'https://newtechmachinery.com/seamless-gutter-machines/',
                        'view_all_label'   => __('View All', 'standard'),
                    ],
                    [
                        'id'               => 'accessories',
                        'label'            => __('Accessories', 'standard'),
                        'category'         => 'accessories-add-on-equipment',
                        'heading'          => __('Accessories & Upgrades', 'standard'),
                        'view_all_url'     => \Standard\Url\internal('/upgrades-accessories/'),
                        'view_all_label'   => __('View All', 'standard'),
                    ],
                ],
                'view_all_url'   => \Standard\Url\internal('/machines/'),
                'view_all_label' => __('See the full lineup', 'standard'),
                // URL roots this item "owns" for current-state detection.
                'current_paths'  => [
                    '/machines/',
                    '/roof-wall-panel-machines/',
                    '/seamless-gutter-machines/',
                    '/upgrades-accessories/',
                    '/uniq-control-system/',
                    '/machine/',
                    '/product/',
                ],
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
                        'heading'        => __('Roof & Wall Panel Profiles', 'standard'),
                        'view_all_url'   => get_scoped_term_url('category', 'profiles-metal-roof-wall-panel', 'profile'),
                        'view_all_label' => __('View All', 'standard'),
                    ],
                    [
                        'id'             => 'gutter',
                        'label'          => __('Gutter', 'standard'),
                        'category'       => 'profiles-gutter',
                        'heading'        => __('Seamless Gutter Profiles', 'standard'),
                        'view_all_url'   => get_scoped_term_url('category', 'profiles-gutter', 'profile'),
                        'view_all_label' => __('View All', 'standard'),
                    ],
                    [
                        'id'             => 'clip-relief-rib-rollers',
                        'label'          => __('Clip Relief / Rib Rollers', 'standard'),
                        'category'       => 'clip-relief-rib-rollers',
                        'heading'        => __('Clip Relief / Rib Rollers', 'standard'),
                        'view_all_url'   => get_scoped_term_url('category', 'clip-relief-rib-rollers', 'profile'),
                        'view_all_label' => __('View All', 'standard'),
                    ],
                ],
                'view_all_url'   => \Standard\Url\internal('/profiles/'),
                'view_all_label' => __('View all profiles', 'standard'),
                'current_paths'  => [
                    '/profiles/',
                    '/profile/',
                ],
            ],
            [
                'kind'          => 'link',
                'label'         => __('Resources', 'standard'),
                'url'           => \Standard\Url\internal('/resources/'),
                'current_paths' => [
                    '/resources/',
                    '/resource/',
                    '/manuals/',
                    '/manual/',
                    '/downloads/',
                    '/download/',
                ],
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
                'current_paths'  => [
                    '/learning-center/',
                    '/video/',
                    '/category/',
                ],
            ],
            [
                'kind'          => 'link',
                'label'         => __('Service & Support', 'standard'),
                'url'           => \Standard\Url\internal('/service-hub/'),
                'current_paths' => [
                    '/service-hub/',
                    '/service-training/',
                ],
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
