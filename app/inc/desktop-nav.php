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
 * IA = four action-driven labels. Each opens a flyout-groups panel: an intro
 * column on the left + three groups of links. The first item in each group is
 * the "anchor" — visually emphasized. Spec: docs/handoff/03-mega-menu-spec.md.
 *
 * @return array{items: array<int, array<string, mixed>>, utility: array<int, array<string, mixed>>}
 */
function get_desktop_nav(): array {
    return [
        'items' => [

            // ── 1. Get started ────────────────────────────────────────────
            [
                'kind'          => 'mega',
                'id'            => 'get-started',
                'label'         => __('Get Started', 'standard'),
                'type'          => 'flyout-groups',
                'intro'         => [
                    'title'           => __('Get Started', 'standard'),
                    'body'            => __('New to portable rollforming? Learn what NTM does, decide if it fits your business, and pick a direction.', 'standard'),
                    'secondary_label' => __('First-time buyer playlist', 'standard'),
                    'secondary_url'   => \Standard\Url\internal('/first-time-buyer-playlist/'),
                ],
                'groups'        => [
                    [
                        'label' => __('Start here', 'standard'),
                        'items' => [
                            [
                                'label' => __('What is an NTM machine?', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/portable-rollforming-machine-equipment-types-uses/'),
                            ],
                            [
                                'label' => __('Roof panel vs gutter machines', 'standard'),
                                'url'   => \Standard\Url\internal('/roof-panel-vs-gutter/'),
                            ],
                            [
                                'label' => __('Misconceptions about portable rollforming', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/portable-rollforming-misconceptions/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('See if it fits', 'standard'),
                        'items' => [
                            [
                                'label' => __('Are you ready to manufacture? (Quiz)', 'standard'),
                                'url'   => \Standard\Url\internal('/portable-rollforming-machine-readiness-assessment/'),
                            ],
                            [
                                'label' => __('Profit calculator', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/download/portable-rollforming-profit-calculator/'),
                            ],
                            [
                                'label' => __('Portable vs factory panel suppliers', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/portable-rollforming-vs-factory-panel-suppliers/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Explore', 'standard'),
                        'items' => [
                            [
                                'label' => __('Start Here landing', 'standard'),
                                'url'   => \Standard\Url\internal('/start-here/'),
                            ],
                            [
                                'label' => __('First-time buyer playlist', 'standard'),
                                'url'   => \Standard\Url\internal('/first-time-buyer-playlist/'),
                            ],
                            [
                                'label' => __('Learning Center', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/'),
                            ],
                        ],
                    ],
                ],
                'current_paths' => [
                    '/start-here/',
                    '/first-time-buyer-playlist/',
                    '/learning-center/',
                    '/portable-rollforming-machine-readiness-assessment/',
                    '/roof-panel-vs-gutter/',
                ],
            ],

            // ── 2. Choose your machine ─────────────────────────────────────
            [
                'kind'          => 'mega',
                'id'            => 'choose-machine',
                'label'         => __('Choose Your Machine', 'standard'),
                'type'          => 'flyout-groups',
                'intro'         => [
                    'title'           => __('Choose Your Machine', 'standard'),
                    'secondary_label' => __('See all machines', 'standard'),
                    'secondary_url'   => \Standard\Url\internal('/machines/'),
                ],
                'groups'        => [
                    [
                        'label' => __('See all machines', 'standard'),
                        'items' => [
                            [
                                'label' => __('All NTM machines', 'standard'),
                                'url'   => \Standard\Url\internal('/machines/'),
                            ],
                            [
                                'label' => __('Roof & wall panel machines', 'standard'),
                                'url'   => \Standard\Url\internal('/roof-wall-panel-machines/'),
                            ],
                            [
                                'label' => __('Seamless gutter machines', 'standard'),
                                'url'   => \Standard\Url\internal('/seamless-gutter-machines/'),
                            ],
                            [
                                'label' => __('Profiles archive', 'standard'),
                                'url'   => \Standard\Url\internal('/profiles/'),
                            ],
                            [
                                'label' => __('Accessories & upgrades', 'standard'),
                                'url'   => \Standard\Url\internal('/machines/upgrades/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Help me choose', 'standard'),
                        'items' => [
                            [
                                'label' => __('Which roof panel machine? (Quiz)', 'standard'),
                                'url'   => \Standard\Url\internal('/roof-panel-machine-assessment-quiz/'),
                            ],
                            [
                                'label' => __('Portable gutter machine selection guide', 'standard'),
                                'url'   => \Standard\Url\internal('/portable-gutter-machine-selection-guide/'),
                            ],
                            [
                                'label' => __('What coil width should you use?', 'standard'),
                                'url'   => \Standard\Url\internal('/what-coil-width-should-you-use/'),
                            ],
                            [
                                'label' => __('Machine chooser landing', 'standard'),
                                'url'   => \Standard\Url\internal('/choose-your-machine/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Compare', 'standard'),
                        'items' => [
                            [
                                'label' => __('Compare roof-panel machines', 'standard'),
                                'url'   => \Standard\Url\internal('/compare-roof-panel-machines/'),
                            ],
                            [
                                'label' => __('SSQII vs SSR', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/portable-roof-panel-machines-ssq-ii-vs-ssr/'),
                            ],
                            [
                                'label' => __('SSR / SSH / SSQII', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/comparison-ntms-ssr-ssh-and-ssq-ii-portable-rollformers/'),
                            ],
                            [
                                'label' => __('SSQ3 MultiPro', 'standard'),
                                'url'   => \Standard\Url\internal('/configurator/ssq3-multi-pro/'),
                            ],
                        ],
                    ],
                ],
                'current_paths' => [
                    '/machines/',
                    '/roof-wall-panel-machines/',
                    '/seamless-gutter-machines/',
                    '/profiles/',
                    '/profile/',
                    '/uniq-control-system/',
                    '/machine/',
                    '/product/',
                    '/choose-your-machine/',
                    '/compare-roof-panel-machines/',
                    '/configurator/ssq3-multi-pro/',
                    '/configurator/ssh/',
                    '/configurator/ssr/',
                    '/configurator/ssqii/',
                    '/configurator/wav/',
                    '/configurator/machii/',
                    '/configurator/5vc/',
                    '/roof-panel-machine-assessment-quiz/',
                    '/portable-gutter-machine-selection-guide/',
                    '/what-coil-width-should-you-use/',
                ],
            ],

            // ── 3. How to buy ──────────────────────────────────────────────
            [
                'kind'          => 'mega',
                'id'            => 'how-to-buy',
                'label'         => __('How To Buy', 'standard'),
                'type'          => 'flyout-groups',
                'intro'         => [
                    'title'           => __('How To Buy', 'standard'),
                    'secondary_label' => __('Request a quote', 'standard'),
                    'secondary_url'   => \Standard\Url\with_query('/contact/', ['form' => 'quote']),
                ],
                'groups'        => [
                    [
                        'label' => __('Get a quote', 'standard'),
                        'items' => [
                            [
                                'label' => __('Request a quote', 'standard'),
                                'url'   => \Standard\Url\with_query('/contact/', ['form' => 'quote']),
                            ],
                            [
                                'label' => __('What to know before quoting', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/getting-a-portable-rollforming-machine-quote/'),
                            ],
                            [
                                'label' => __('How to get a quote on an NTM machine', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/how-to-get-a-quote-for-an-ntm-rollforming-machine/'),
                            ],
                            [
                                'label' => __('NTM machine quote checklist', 'standard'),
                                'url'   => \Standard\Url\internal('/ntm-machine-quote-checklist-thank-you/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Understand the deal', 'standard'),
                        'items' => [
                            [
                                'label' => __('Panel machine cost (2026)', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/portable-roof-panel-rollforming-machine-cost/'),
                            ],
                            [
                                'label' => __('Gutter machine cost (2026)', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/gutter-machine-cost-what-to-look-for/'),
                            ],
                            [
                                'label' => __('Financing & leasing', 'standard'),
                                'url'   => \Standard\Url\internal('/machines/leasing-financing/'),
                            ],
                            [
                                'label' => __('Build & finance walkthrough', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/how-to-build-and-finance-your-ntm-rollformer-all-on-one-site/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Talk or configure', 'standard'),
                        'items' => [
                            [
                                'label' => __('Talk to a specialist', 'standard'),
                                'url'   => \Standard\Url\internal('/contact/'),
                            ],
                            [
                                'label' => __('Configure your machine (Expert shortcut)', 'standard'),
                                'url'   => \Standard\Url\internal('/configurator/'),
                            ],
                            [
                                'label' => __('How buying from NTM works', 'standard'),
                                'url'   => \Standard\Url\internal('/how-buying-works/'),
                            ],
                        ],
                    ],
                ],
                'current_paths' => [
                    '/contact/',
                    '/configurator/',
                    '/machines/leasing-financing/',
                    '/how-buying-works/',
                    '/ntm-machine-quote-checklist-thank-you/',
                ],
            ],

            // ── 4. Get owner support ───────────────────────────────────────
            [
                'kind'          => 'mega',
                'id'            => 'owner-support',
                'label'         => __('Get Owner Support', 'standard'),
                'type'          => 'flyout-groups',
                'intro'         => [
                    'title'           => __('Get Owner Support', 'standard'),
                    'secondary_label' => __('Open a service request', 'standard'),
                    'secondary_url'   => \Standard\Url\internal('/service-hub/'),
                ],
                'groups'        => [
                    [
                        'label' => __('Get support now', 'standard'),
                        'items' => [
                            [
                                'label' => __('Open a service request', 'standard'),
                                'url'   => \Standard\Url\internal('/service-hub/request/'),
                            ],
                            [
                                'label' => __('NTM Knowledge Base', 'standard'),
                                'url'   => \Standard\Url\internal('/service-hub/'),
                            ],
                            [
                                'label' => __('Owner support landing', 'standard'),
                                'url'   => \Standard\Url\internal('/owner-support/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Operate', 'standard'),
                        'items' => [
                            [
                                'label' => __('Machine manuals', 'standard'),
                                'url'   => \Standard\Url\internal('/machines/manuals/'),
                            ],
                            [
                                'label' => __('Request training', 'standard'),
                                'url'   => \Standard\Url\internal('/service-training/'),
                            ],
                            [
                                'label' => __('Warranty registration', 'standard'),
                                'url'   => \Standard\Url\internal('/machines/warranty-registration/'),
                            ],
                            [
                                'label' => __('Parts request', 'standard'),
                                'url'   => \Standard\Url\internal('/request-parts/'),
                            ],
                        ],
                    ],
                    [
                        'label' => __('Troubleshoot & buy again', 'standard'),
                        'items' => [
                            [
                                'label' => __('Common problems & fixes', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/common-problems-with-ntm-portable-rollforming-machines-and-how-to-solve-them/'),
                            ],
                            [
                                'label' => __('Questions the service department hears most', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/the-top-five-questions-the-ntm-service-department-receives/'),
                            ],
                            [
                                'label' => __('Prevent voiding your warranty', 'standard'),
                                'url'   => \Standard\Url\internal('/learning-center/ways-to-prevent-voiding-machine-warranty/'),
                            ],
                            [
                                'label' => __('Add a machine', 'standard'),
                                'url'   => \Standard\Url\internal('/add-a-machine/'),
                            ],
                        ],
                    ],
                ],
                'current_paths' => [
                    '/service-hub/',
                    '/service-training/',
                    '/owner-support/',
                    '/owner-resources/',
                    '/service-hub/request/',
                    '/machines/manuals/',
                    '/machines/warranty-registration/',
                    '/manual/',
                    '/request-parts/',
                    '/add-a-machine/',
                    '/resources/',
                    '/resource/',
                    '/downloads/',
                    '/download/',
                ],
            ],
        ],
        'utility' => [
            [
                'label'     => __('Talk To A Specialist', 'standard'),
                'url'       => \Standard\Url\internal('/contact/'),
                'highlight' => true,
            ],
        ],
    ];
}
