<?php

declare(strict_types=1);

define('ABSPATH', __DIR__);

final class WP_Theme
{
}

final class WP_Post
{
    public function __construct(
        public int $ID,
        public string $post_type = 'page'
    ) {
    }
}

/** @var array<int, string> */
$ntm_template_slugs = [];

/** @var array<int, string> */
$ntm_page_uris = [];

function add_filter(string $hook, callable|string $callback, int $priority = 10, int $accepted_args = 1): bool
{
    return true;
}

function get_page_template_slug(int|WP_Post|null $post = null): string|false
{
    $post_id = $post instanceof WP_Post ? $post->ID : (int) $post;

    return $GLOBALS['ntm_template_slugs'][$post_id] ?? false;
}

function get_page_uri(int|WP_Post|null $post = null): string|false
{
    $post_id = $post instanceof WP_Post ? $post->ID : (int) $post;

    return $GLOBALS['ntm_page_uris'][$post_id] ?? false;
}

function __(string $text, string $domain = 'default'): string
{
    return $text;
}

require __DIR__ . '/../../app/inc/page-templates.php';

/**
 * @param mixed $expected
 * @param mixed $actual
 */
function ntm_assert_same($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        throw new RuntimeException(
            $message . ' Expected ' . var_export($expected, true) . ', got ' . var_export($actual, true) . '.'
        );
    }
}

$all_templates = [
    'page-about.php' => 'About',
    'templates/template-full-width.php' => 'Full Width',
    'templates/template-prose.php' => 'Prose Page',
    'templates/template-lead-form.php' => 'Lead Form Landing',
    'templates/template-hero-video.php' => 'Video Landing',
    'templates/template-careers.php' => 'Careers',
    'templates/template-service-hub.php' => 'Service Hub',
];

$approved_templates = [
    'templates/template-full-width.php' => 'Full Width',
    'templates/template-prose.php' => 'Prose Page',
    'templates/template-lead-form.php' => 'Lead Form Landing',
    'templates/template-hero-video.php' => 'Video Landing',
    'templates/template-careers.php' => 'Careers',
];

$theme = new WP_Theme();

ntm_assert_same(
    $approved_templates,
    \Standard\PageTemplates\filter_editor_page_templates($all_templates, $theme, null, 'page'),
    'A new page should expose only approved reusable templates.'
);

$ordinary_page = new WP_Post(10);
$GLOBALS['ntm_template_slugs'][10] = 'templates/template-prose.php';
ntm_assert_same(
    $approved_templates,
    \Standard\PageTemplates\filter_editor_page_templates($all_templates, $theme, $ordinary_page, 'page'),
    'An approved current template should not be duplicated or relabeled.'
);

$internal_page = new WP_Post(20);
$GLOBALS['ntm_template_slugs'][20] = 'templates/template-service-hub.php';
ntm_assert_same(
    $approved_templates + [
        'templates/template-service-hub.php' => 'Service Hub (Current internal template)',
    ],
    \Standard\PageTemplates\filter_editor_page_templates($all_templates, $theme, $internal_page, 'page'),
    'The current internal template should remain valid without exposing its peers.'
);

$legacy_page = new WP_Post(30);
$GLOBALS['ntm_template_slugs'][30] = 'page-full-classic.php';
ntm_assert_same(
    $approved_templates + [
        'page-full-classic.php' => 'page-full-classic (Current internal template)',
    ],
    \Standard\PageTemplates\filter_editor_page_templates($all_templates, $theme, $legacy_page, 'page'),
    'A legacy assignment absent from discovery should still remain valid.'
);

ntm_assert_same(
    $all_templates,
    \Standard\PageTemplates\filter_editor_page_templates($all_templates, $theme, null, 'post'),
    'Unrelated post types should be untouched.'
);

$GLOBALS['ntm_page_uris'][40] = 'configurator/product';
ntm_assert_same(
    true,
    \Standard\PageTemplates\is_configurator_page_tree(40),
    'Configurator child pages should use the configurator shell.'
);
ntm_assert_same(
    false,
    \Standard\PageTemplates\is_configurator_page_tree(599),
    'Non-page query objects should not cause a type error.'
);

echo "Page template filter tests passed.\n";
