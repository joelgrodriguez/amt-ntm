<?php
/**
 * Knowledgebase custom post type.
 *
 * Troubleshooting articles for the service hub, migrated from the support
 * portal (see app/data/knowledgebase/articles.php and
 * scripts/db/024-seed-knowledgebase.sh).
 *
 * Registered in THEME CODE, deliberately unlike the other content CPTs
 * (video, manual, resource, download, literature, footprint, cutlist) which
 * live in the CPT-UI plugin (i.e. the database). This repo's rule is that the
 * database is wiped on a fresh prod pull and only git survives. A CPT defined
 * in CPT-UI would vanish on release, orphaning every seeded knowledgebase post
 * to an unregistered type. Registering here is the only way the type — and the
 * content the importer rebuilds — survives a pull. The legacy CPTs predate that
 * rule; they are not migrated here.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard\Knowledgebase;

if (!defined('ABSPATH')) {
    exit;
}

const POST_TYPE = 'knowledgebase';

/**
 * Register the knowledgebase post type.
 *
 * No standalone archive: this content surfaces inside the service hub
 * (/service-hub/<machine>/), not as its own archive. REST-enabled so it edits
 * in Gutenberg like a normal post.
 */
function register(): void {
    \register_post_type(POST_TYPE, [
        'labels' => [
            'name'               => \__('Knowledge Base', 'standard'),
            'singular_name'      => \__('Knowledge Base Article', 'standard'),
            'menu_name'          => \__('Knowledge Base', 'standard'),
            'add_new_item'       => \__('Add New Article', 'standard'),
            'edit_item'          => \__('Edit Article', 'standard'),
            'new_item'           => \__('New Article', 'standard'),
            'view_item'          => \__('View Article', 'standard'),
            'search_items'       => \__('Search Articles', 'standard'),
            'not_found'          => \__('No articles found.', 'standard'),
            'not_found_in_trash' => \__('No articles found in Trash.', 'standard'),
            'all_items'          => \__('All Articles', 'standard'),
        ],
        'public'        => true,
        'show_in_rest'  => true,
        'has_archive'   => false,
        'hierarchical'  => false,
        'menu_icon'     => 'dashicons-sos',
        'menu_position' => 21,
        'supports'      => ['title', 'editor', 'excerpt', 'thumbnail', 'revisions'],
        'rewrite'       => ['slug' => 'knowledgebase', 'with_front' => false],
    ]);
}
\add_action('init', __NAMESPACE__ . '\\register', 5);
