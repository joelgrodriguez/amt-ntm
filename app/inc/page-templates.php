<?php
/**
 * Page template helpers and legacy compatibility.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\PageTemplates;

use const Standard\HubSpot\DEFAULT_FORM_ID;
use const Standard\HubSpot\META_FORM_ID;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Map old-theme template slugs to the new reusable templates.
 *
 * @return array<string, string>
 */
function legacy_template_map(): array
{
    return [
        'full-width'                         => 'templates/template-full-width.php',
        'page-full-width.php'                => 'templates/template-full-width.php',
        'page-full-classic.php'              => 'templates/template-full-width.php',
        'page-full-classic-prose.php'        => 'templates/template-prose.php',
        'page-full-simple-prose.php'         => 'templates/template-prose.php',
        'page-marketing-landing-simple.php'  => 'templates/template-hero-video.php',
        'page-video.php'                     => 'templates/template-hero-video.php',
        'page-form.php'                      => 'templates/template-lead-form.php',
        'page-form-meta.php'                 => 'templates/template-lead-form.php',
    ];
}

/**
 * Route legacy database template slugs to current template files.
 */
function include_legacy_page_template(string $template): string
{
    if (!is_page() || is_front_page()) {
        return $template;
    }

    $post_id = get_queried_object_id();
    $slug = get_page_template_slug($post_id);

    if (!is_string($slug) || $slug === '') {
        return $template;
    }

    $map = legacy_template_map();

    if (!isset($map[$slug])) {
        return $template;
    }

    $mapped_template = get_theme_file_path($map[$slug]);

    return file_exists($mapped_template) ? $mapped_template : $template;
}
add_filter('template_include', __NAMESPACE__ . '\\include_legacy_page_template', 20);

/**
 * Detect the /configurator/ page tree.
 *
 * Child pages do not inherit page-configurator.php in WordPress; routing by
 * page URI covers /configurator/ and /configurator/* consistently.
 */
function is_configurator_page_tree(int $post_id): bool
{
    $page_uri = trim(get_page_uri($post_id), '/');

    return $page_uri === 'configurator' || strpos($page_uri, 'configurator/') === 0;
}

/**
 * Render configurator pages with only wp_head(), content, and wp_footer().
 */
function include_configurator_empty_shell(string $template): string
{
    if (!is_page() || is_front_page()) {
        return $template;
    }

    $post_id = get_queried_object_id();

    if ($post_id <= 0 || !is_configurator_page_tree($post_id)) {
        return $template;
    }

    $empty_shell = get_theme_file_path('templates/template-empty-shell.php');

    return file_exists($empty_shell) ? $empty_shell : $template;
}
add_filter('template_include', __NAMESPACE__ . '\\include_configurator_empty_shell', 30);

/**
 * Read the first non-empty ACF/custom-field value from a page.
 *
 * @param list<string> $keys
 */
function get_page_field(int $post_id, array $keys, string $default = '', bool $format_value = true): string
{
    foreach ($keys as $key) {
        $value = null;

        if (function_exists('get_field')) {
            $value = get_field($key, $post_id, $format_value);
        }

        if ($value === null || $value === false || $value === '') {
            $value = get_post_meta($post_id, $key, true);
        }

        if (is_string($value) && trim($value) !== '') {
            return trim($value);
        }
    }

    return $default;
}

/**
 * Get normalized hero data for reusable page templates.
 *
 * @return array{
 *     eyebrow: string,
 *     title: string,
 *     description: string,
 *     legacy_content: string,
 *     video: string,
 *     has_content: bool,
 *     has_video: bool
 * }
 */
function get_hero_data(int $post_id): array
{
    $video = get_page_field($post_id, ['hero_video'], '', false);
    $legacy_content = get_page_field($post_id, ['hero_content'], '', true);
    $title = get_page_field($post_id, ['hero_title', 'page_hero_title'], '', true);
    $description = get_page_field($post_id, ['hero_description', 'hero_subtitle', 'page_hero_description'], '', true);
    $eyebrow = get_page_field($post_id, ['hero_eyebrow', 'page_eyebrow'], __('New Tech Machinery', 'standard'), true);

    if ($title === '' && $legacy_content === '' && $video !== '') {
        $title = get_the_title($post_id);
    }

    return [
        'eyebrow'         => $eyebrow,
        'title'           => $title,
        'description'     => $description,
        'legacy_content'  => $legacy_content,
        'video'           => $video,
        'has_content'     => $title !== '' || $description !== '' || $legacy_content !== '',
        'has_video'       => $video !== '',
    ];
}

/**
 * Get the HubSpot form ID for a page.
 */
function get_page_form_id(int $post_id): string
{
    $legacy_slug = get_page_template_slug($post_id);
    $default_form_id = $legacy_slug === 'page-form-meta.php' ? META_FORM_ID : DEFAULT_FORM_ID;

    return get_page_field($post_id, [
        'hubspot_form_id',
        'hs_form_id',
        'lead_form_id',
        'form_id',
    ], $default_form_id, true);
}

/**
 * Get a page template label field.
 *
 * @param list<string> $keys
 */
function get_label(int $post_id, array $keys, string $default): string
{
    return get_page_field($post_id, $keys, $default, true);
}
