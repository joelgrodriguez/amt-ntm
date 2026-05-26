<?php
/**
 * Dedicated /articles/ archive for the built-in `post` type.
 *
 * WordPress doesn't expose a CPT-style archive for `post`; this rewrites
 * /articles/ (and /articles/page/N/) to the standard post archive query,
 * so it mirrors how /video/, /resource/, and /download/ work.
 *
 * Single-post URLs are untouched — only the archive endpoint is added.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\PostArchive;

if (!defined('ABSPATH')) {
    exit;
}

const ARCHIVE_SLUG    = 'learning-center/articles';
const REWRITE_VERSION = '2';
const VERSION_OPTION  = 'standard_post_archive_rewrite_version';

/**
 * Register the /articles/ and /articles/page/N/ rewrites.
 *
 * Setting `is_post_type_archive` + `post_type=post` here makes WP treat
 * the request as a post archive, so archive.php picks it up and
 * get_post_type_archive_link('post') returns /articles/.
 */
function register_rewrites(): void {
    \add_rewrite_rule(
        '^' . ARCHIVE_SLUG . '/?$',
        'index.php?post_type=post&is_post_type_archive=1',
        'top'
    );

    \add_rewrite_rule(
        '^' . ARCHIVE_SLUG . '/page/([0-9]{1,})/?$',
        'index.php?post_type=post&is_post_type_archive=1&paged=$matches[1]',
        'top'
    );
}
\add_action('init', __NAMESPACE__ . '\\register_rewrites');

/**
 * Whitelist the `is_post_type_archive` query var so the rewrite payload survives.
 *
 * @param array<int, string> $vars
 * @return array<int, string>
 */
function register_query_vars(array $vars): array {
    $vars[] = 'is_post_type_archive';
    return $vars;
}
\add_filter('query_vars', __NAMESPACE__ . '\\register_query_vars');

/**
 * Make get_post_type_archive_link('post') resolve to /articles/.
 *
 * WP returns false by default for `post` since it has no `has_archive`.
 * Other call sites (home.php hero nav, archive.php filter sidebar) can
 * then use the same helper everything else uses.
 */
/**
 * @param string|false $link
 * @return string|false
 */
function filter_archive_link($link, string $post_type) {
    if ($post_type !== 'post') {
        return $link;
    }

    return \home_url('/' . ARCHIVE_SLUG . '/');
}
\add_filter('post_type_archive_link', __NAMESPACE__ . '\\filter_archive_link', 10, 2);

/**
 * Give the plain post archive a real title.
 *
 * WP's default for an unstructured post archive is "Archives", which is
 * meaningless. Only override when we're on /articles/ specifically — date,
 * category, and tag archives keep their normal titles.
 */
function filter_archive_title(string $title): string {
    if (\is_post_type_archive('post')) {
        return \__('Articles', 'standard');
    }
    return $title;
}
\add_filter('get_the_archive_title', __NAMESPACE__ . '\\filter_archive_title');

/**
 * Force archive.php (not home.php) when the post archive endpoint is hit.
 *
 * WP's template hierarchy for a built-in post archive falls back to
 * home.php because `post` has no has_archive registration. We override
 * specifically when our rewrite fired (is_post_type_archive query var
 * set + post_type=post) so the CPT-style archive surface renders.
 */
function force_archive_template(string $template): string {
    if (!\get_query_var('is_post_type_archive')) {
        return $template;
    }

    if (\get_query_var('post_type') !== 'post') {
        return $template;
    }

    $candidate = \get_theme_file_path('archive.php');
    return file_exists($candidate) ? $candidate : $template;
}
\add_filter('template_include', __NAMESPACE__ . '\\force_archive_template', 99);

/**
 * Make conditional tags agree we're on a post archive.
 *
 * Without this, is_post_type_archive('post') returns false because WP
 * only sets that flag for types registered with has_archive. archive.php
 * uses the flag to pick filter rails, so we have to backfill it.
 */
function mark_as_post_type_archive(\WP_Query $query): void {
    if (\is_admin() || !$query->is_main_query()) {
        return;
    }

    if (!$query->get('is_post_type_archive')) {
        return;
    }

    if ($query->get('post_type') !== 'post') {
        return;
    }

    $query->is_post_type_archive = true;
    $query->is_archive           = true;
    $query->is_home              = false;
    $query->is_singular          = false;
}
\add_action('parse_query', __NAMESPACE__ . '\\mark_as_post_type_archive');

/**
 * Flush rewrite rules once when this file ships a new REWRITE_VERSION.
 *
 * Avoids the common mistake of flushing on every request (slow) or
 * relying on the user to visit Settings -> Permalinks.
 */
function maybe_flush(): void {
    if (\get_option(VERSION_OPTION) === REWRITE_VERSION) {
        return;
    }

    register_rewrites();
    \flush_rewrite_rules(false);
    \update_option(VERSION_OPTION, REWRITE_VERSION, false);
}
\add_action('init', __NAMESPACE__ . '\\maybe_flush', 20);
