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
const REWRITE_VERSION = '2';
const VERSION_OPTION  = 'standard_service_hub_machine_rewrite_version';

/**
 * Machine slugs valid for a service mini-page. Includes dormant machines
 * (e.g. SSQ II): support outlives sales. A machine pulled from the lineup
 * still has owners in the field who need its manuals, parts, and fixes, so
 * /service-hub/<slug>/ must resolve for it even though the product pages
 * (which pass false) correctly hide it.
 *
 * @return string[]
 */
function valid_slugs(): array {
    $slugs = [];
    foreach (\Standard\MachinesData\get_all_machines(true) as $machine) {
        $slug = (string) ($machine['slug'] ?? '');
        if ($slug !== '') {
            $slugs[] = $slug;
        }
    }
    return $slugs;
}

/**
 * Find one machine entry by slug, or null. Includes dormant machines so
 * superseded models still resolve a service mini-page (see valid_slugs()).
 *
 * @return array<string, mixed>|null
 */
function find_machine(string $slug): ?array {
    foreach (\Standard\MachinesData\get_all_machines(true) as $machine) {
        if ((string) ($machine['slug'] ?? '') === $slug) {
            return $machine;
        }
    }
    return null;
}

/**
 * Register /service-hub/<slug>/. The negative lookahead excludes the
 * `request` segment, so the real /service-hub/request/ child page resolves
 * via WP's default page rule instead of being captured as a machine slug.
 * Any other non-machine slug still 404s via template-side validation.
 */
function register_rewrites(): void {
    \add_rewrite_rule(
        '^service-hub/(?!request/?$)([^/]+)/?$',
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
    // Order is owner-priority, not alphabetical: the manual is what an owner
    // reaches for first, videos second (the service team's strongest content
    // for this hub), then written fixes, then parts/footprint downloads.
    $groups = [
        ['label' => \__('Manuals', 'standard'),             'types' => ['manual']],
        ['label' => \__('Videos', 'standard'),              'types' => ['video']],
        ['label' => \__('Troubleshooting', 'standard'),     'types' => ['post']],
        ['label' => \__('Parts & footprints', 'standard'),  'types' => ['download', 'footprint', 'cutlist']],
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

/**
 * Resolve a machine's footprint drawing for off-loop rendering.
 *
 * The product page reads the footprint from the ACF `footprint` field on the
 * WooCommerce product *in the loop* (see woo/product/parts/blueprint.php). The
 * service-hub machine page is a virtual route with no post in context, so we
 * resolve it the long way: machine data-key -> WC product ID -> ACF footprint
 * post -> featured image + embedded PDF URL.
 *
 * Returns empty strings for any piece that isn't available; the caller decides
 * what to render. Static-cached per slug because the WC product lookup walks
 * the published-product list once.
 *
 * @return array{image: string, alt: string, pdf: string}
 */
function get_machine_footprint(string $machine_slug): array {
    static $cache = [];
    if (isset($cache[$machine_slug])) {
        return $cache[$machine_slug];
    }

    $empty = ['image' => '', 'alt' => '', 'pdf' => ''];

    if (!\function_exists('get_field')) {
        return $cache[$machine_slug] = $empty;
    }

    $product_id = resolve_product_id($machine_slug);
    if ($product_id === 0) {
        return $cache[$machine_slug] = $empty;
    }

    $footprint = \get_field('footprint', $product_id);
    $footprint_post_id = 0;

    if (\is_array($footprint) && !empty($footprint)) {
        $first = \reset($footprint);
        if (\is_object($first) && isset($first->ID)) {
            $footprint_post_id = (int) $first->ID;
        } elseif (\is_numeric($first)) {
            $footprint_post_id = (int) $first;
        }
    } elseif (\is_object($footprint) && isset($footprint->ID)) {
        $footprint_post_id = (int) $footprint->ID;
    } elseif (\is_numeric($footprint)) {
        $footprint_post_id = (int) $footprint;
    }

    if ($footprint_post_id === 0) {
        return $cache[$machine_slug] = $empty;
    }

    $image = '';
    $alt   = '';
    $thumb_id = (int) \get_post_thumbnail_id($footprint_post_id);
    if ($thumb_id > 0) {
        $image = (string) (\wp_get_attachment_image_url($thumb_id, 'large') ?: '');
        $alt   = (string) \get_post_meta($thumb_id, '_wp_attachment_image_alt', true);
        if ($alt === '') {
            $alt = (string) \get_the_title($footprint_post_id);
        }
    }

    $pdf = '';
    $footprint_post = \get_post($footprint_post_id);
    if ($footprint_post && \function_exists('parse_blocks')) {
        foreach (\parse_blocks($footprint_post->post_content) as $block) {
            if (($block['blockName'] ?? '') === 'pdfjsblock/pdfjs-embed' && !empty($block['attrs']['imageURL'])) {
                $pdf = (string) $block['attrs']['imageURL'];
                break;
            }
        }
    }

    return $cache[$machine_slug] = ['image' => $image, 'alt' => $alt, 'pdf' => $pdf];
}

/**
 * WooCommerce product ID for a machine slug, or 0.
 *
 * Machine data-keys (e.g. `ssh-multipro`) don't always match the WC product
 * slug (e.g. `ssh-roof-panel-machine`), so we check the data-key directly and
 * every WC slug that aliases to it (MachineProductData::get_slug_aliases()).
 */
function resolve_product_id(string $machine_slug): int {
    if (!\function_exists('wc_get_products')) {
        return 0;
    }

    $candidates = [$machine_slug];
    if (\function_exists('Standard\\MachineProductData\\get_slug_aliases')) {
        foreach (\Standard\MachineProductData\get_slug_aliases() as $wc_slug => $data_key) {
            if ($data_key === $machine_slug) {
                $candidates[] = $wc_slug;
            }
        }
    }

    $products = \function_exists('Standard\\Woo\\Cache\\get_products')
        ? \Standard\Woo\Cache\get_products(['limit' => -1, 'status' => 'publish'])
        : \wc_get_products(['limit' => -1, 'status' => 'publish']);

    foreach ($products as $product) {
        if (\in_array($product->get_slug(), $candidates, true)) {
            return (int) $product->get_id();
        }
    }

    return 0;
}
