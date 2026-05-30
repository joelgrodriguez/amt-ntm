<?php
/**
 * Service Hub machine mini-pages: /service-hub/<slug>/.
 *
 * Rewrite + template routing mirror inc/post-archive.php. Content reuses
 * the service-hub query layer (service-repair department + machine tag),
 * grouped by post type. No new taxonomy, no content migration.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\ServiceHubMachines;

if (!defined('ABSPATH')) {
    exit;
}

const QUERY_VAR       = 'service_hub_machine';
const REWRITE_VERSION = '1';
const VERSION_OPTION  = 'standard_service_hub_machine_rewrite_version';

/**
 * Active machine slugs, validated against machines-data.
 *
 * @return string[]
 */
function valid_slugs(): array {
    $slugs = [];
    foreach (\Standard\MachinesData\get_all_machines(false) as $machine) {
        $slug = (string) ($machine['slug'] ?? '');
        if ($slug !== '') {
            $slugs[] = $slug;
        }
    }
    return $slugs;
}

/**
 * Find one active machine entry by slug, or null.
 *
 * @return array<string, mixed>|null
 */
function find_machine(string $slug): ?array {
    foreach (\Standard\MachinesData\get_all_machines(false) as $machine) {
        if ((string) ($machine['slug'] ?? '') === $slug) {
            return $machine;
        }
    }
    return null;
}

/**
 * Register /service-hub/<slug>/. The `request` segment is excluded so the
 * real /service-hub/request/ child page wins; any non-machine slug 404s
 * via template-side validation.
 */
function register_rewrites(): void {
    \add_rewrite_rule(
        '^service-hub/([^/]+)/?$',
        'index.php?' . QUERY_VAR . '=$matches[1]',
        'top'
    );
}
\add_action('init', __NAMESPACE__ . '\\register_rewrites');

/**
 * @param array<int, string> $vars
 * @return array<int, string>
 */
function register_query_vars(array $vars): array {
    $vars[] = QUERY_VAR;
    return $vars;
}
\add_filter('query_vars', __NAMESPACE__ . '\\register_query_vars');

/**
 * Flush rewrites once when REWRITE_VERSION changes.
 */
function maybe_flush_rewrites(): void {
    if (\get_option(VERSION_OPTION) === REWRITE_VERSION) {
        return;
    }
    register_rewrites();
    \flush_rewrite_rules(false);
    \update_option(VERSION_OPTION, REWRITE_VERSION);
}
\add_action('init', __NAMESPACE__ . '\\maybe_flush_rewrites', 99);

/**
 * Route a valid machine slug to the mini-page template; unknown slug 404s.
 */
function route_template(string $template): string {
    $slug = \get_query_var(QUERY_VAR);
    if (!\is_string($slug) || $slug === '') {
        return $template;
    }

    if (!\in_array($slug, valid_slugs(), true)) {
        global $wp_query;
        $wp_query->set_404();
        \status_header(404);
        return \get_query_template('404');
    }

    $machine_template = \get_theme_file_path('templates/template-service-hub-machine.php');
    return \file_exists($machine_template) ? $machine_template : $template;
}
\add_filter('template_include', __NAMESPACE__ . '\\route_template', 30);

/**
 * Grouped service content for one machine.
 *
 * Returns groups in display order; each group is a WP_Query of
 * service-repair content tagged with this machine, limited to that
 * group's post types. Empty groups are dropped by the caller.
 *
 * @return array<int, array{label: string, query: \WP_Query}>
 */
function get_content_groups(string $machine_slug): array {
    $groups = [
        ['label' => \__('Manuals', 'standard'),             'types' => ['manual']],
        ['label' => \__('Troubleshooting', 'standard'),     'types' => ['post']],
        ['label' => \__('Parts & footprints', 'standard'),  'types' => ['download', 'footprint', 'cutlist']],
        ['label' => \__('Videos', 'standard'),              'types' => ['video']],
    ];

    $built = [];
    foreach ($groups as $group) {
        $query = new \WP_Query([
            'post_type'           => $group['types'],
            'post_status'         => 'publish',
            'posts_per_page'      => 24,
            'ignore_sticky_posts' => true,
            'orderby'             => 'date',
            'order'               => 'DESC',
            'tax_query'           => [
                'relation' => 'AND',
                \Standard\ServiceHub\get_service_tax_query()[0],
                [
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => [$machine_slug],
                ],
            ],
        ]);
        $built[] = ['label' => $group['label'], 'query' => $query];
    }

    return $built;
}
