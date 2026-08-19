<?php
/**
 * Corbel Website Plugin integration.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Corbel;

if (!defined('ABSPATH')) {
    exit;
}

const SCRIPT_URL = 'https://plugins.corbelpay.com/newtechmachinery/corbel.js';

/**
 * Corbel catalog IDs keyed by the existing /configurator/<slug>/ routes.
 *
 * @var array<string, string>
 */
const PRODUCT_IDS_BY_PAGE_SLUG = [
    '5vc'           => '5vc-crimp-panel',
    'wav'           => 'wav-wall-panel',
    'ssh'           => 'ssh-multipro-panel',
    'ssr'           => 'ssr-multipro-panel',
    'ssqii'         => 'ssq2-multipro-panel',
    'ssq3-multi-pro' => 'ssq3',
    'machii'        => 'mach2-gutter',
];

const CONFIGURATOR_URL = 'https://app.corbelpay.com/reception/newtechmachinery/quote/new';

/**
 * Load Corbel's website plugin once in the document head.
 */
function render_loader(): void
{
    if (is_admin()) {
        return;
    }

    echo '<script async src="' . esc_url(SCRIPT_URL) . '"></script>' . "\n";
}
add_action('wp_head', __NAMESPACE__ . '\\render_loader', 1);

/**
 * Resolve the Corbel catalog ID for the current configurator page.
 *
 * Editors can set `corbel_product_id` when a page needs a catalog ID outside
 * the known route map. Unknown child pages intentionally use the generic
 * configurator instead of sending an invalid product ID to Corbel.
 */
function get_configurator_product_id(int $post_id = 0): string
{
    $post_id = $post_id > 0 ? $post_id : (int) get_queried_object_id();

    if ($post_id <= 0) {
        return '';
    }

    $product_id = get_post_meta($post_id, 'corbel_product_id', true);

    if (
        (!is_string($product_id) || trim($product_id) === '')
        && function_exists('get_field')
    ) {
        $product_id = get_field('corbel_product_id', $post_id, false);
    }

    if (is_string($product_id) && trim($product_id) !== '') {
        return \sanitize_text_field(trim($product_id));
    }

    if (
        !function_exists('Standard\\PageTemplates\\is_configurator_page_tree')
        || !\Standard\PageTemplates\is_configurator_page_tree($post_id)
    ) {
        return '';
    }

    $page_uri = trim((string) get_page_uri($post_id), '/');
    $segments = array_values(array_filter(explode('/', $page_uri)));

    if (count($segments) < 2 || $segments[0] !== 'configurator') {
        return '';
    }

    $page_slug = \sanitize_title((string) end($segments));

    return PRODUCT_IDS_BY_PAGE_SLUG[$page_slug] ?? '';
}

/**
 * Preserve campaign parameters that the legacy SSQ3 embed forwarded.
 *
 * @return array<string, string>
 */
function get_utm_parameters(): array
{
    $parameters = [];

    foreach ($_GET as $key => $value) {
        if (
            !is_string($key)
            || !str_starts_with(strtolower($key), 'utm_')
            || !is_scalar($value)
        ) {
            continue;
        }

        $value = \sanitize_text_field(\wp_unslash((string) $value));

        if ($value !== '') {
            $parameters[$key] = $value;
        }
    }

    return $parameters;
}

/**
 * Build an explicit configurator URL when campaign parameters are present.
 *
 * @param array<string, string> $utm_parameters
 */
function get_configurator_url(string $product_id, array $utm_parameters): string
{
    $query = $product_id !== '' ? ['o' => $product_id] : [];

    foreach ($utm_parameters as $key => $value) {
        $query[$key] = $value;
    }

    return \add_query_arg($query, CONFIGURATOR_URL);
}

/**
 * Render the target that Corbel replaces with the embedded configurator.
 */
function render_configurator_placeholder(): void
{
    $product_id = get_configurator_product_id();
    $utm_parameters = get_utm_parameters();

    echo '<div id="corbelConfigurator"';

    if ($product_id !== '') {
        echo ' data-corbel-product-id="' . esc_attr($product_id) . '"';
    }

    if ($utm_parameters !== []) {
        echo ' data-corbel-configurator-url="'
            . esc_attr(get_configurator_url($product_id, $utm_parameters))
            . '"';
    }

    echo '></div>' . "\n";
}

/**
 * Render the target for Corbel's optional floating assistant.
 */
function render_assistant_placeholder(): void
{
    if (is_admin()) {
        return;
    }

    echo '<div id="corbelAssistant"></div>' . "\n";
}
add_action('wp_footer', __NAMESPACE__ . '\\render_assistant_placeholder', 1);
