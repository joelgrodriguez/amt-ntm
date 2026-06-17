<?php
/**
 * Seed/refresh knowledgebase posts from the committed fixtures.
 *
 * Run via `wp eval-file` from 024-seed-knowledgebase.sh. Kept as a PHP file
 * (not a shell loop of `wp post create`) so the upsert logic — find-by-meta,
 * term assignment, multi-tag — runs in one WordPress context with real APIs.
 *
 * IDEMPOTENT: each article is keyed on the `_kb_source_url` meta. Re-running
 * updates the existing post in place; it never creates a duplicate.
 *
 * Resolves the fixtures via get_template_directory() so it works regardless of
 * host-vs-container theme paths.
 *
 * NOTE: no `declare(strict_types=1)` here. WP-CLI's `eval-file` wraps the file
 * body in eval(), where a declare() is illegal (it must be a file's first
 * statement). The fixtures file it requires keeps its own strict_types.
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "must run inside WordPress (wp eval-file)\n");
    exit(1);
}

const KB_POST_TYPE   = 'knowledgebase';
const KB_META_KEY    = '_kb_source_url';
const KB_DEPT_TAX    = 'content_department';
const KB_DEPT_TERM   = 'service-repair';

if (!post_type_exists(KB_POST_TYPE)) {
    fwrite(STDERR, "    knowledgebase post type not registered — is the theme active? Aborting.\n");
    exit(1);
}

// The active theme root IS the app/ dir (functions.php lives in app/), so
// get_template_directory() already points at .../amt-ntm/app — the fixtures
// are at data/knowledgebase/ under it, not app/data/.
$fixtures = trailingslashit(get_template_directory()) . 'data/knowledgebase/articles.php';
if (!is_readable($fixtures)) {
    fwrite(STDERR, "    fixtures not found at {$fixtures} — aborting.\n");
    exit(1);
}

/** @var array<int, array<string, mixed>> $articles */
$articles = require $fixtures;

// Ensure the department term exists once, up front.
if (!term_exists(KB_DEPT_TERM, KB_DEPT_TAX)) {
    wp_insert_term('Service & Repair', KB_DEPT_TAX, ['slug' => KB_DEPT_TERM]);
}

/**
 * Look up an attachment ID by its slug (post_name).
 *
 * Queries by name with post_status=inherit directly: get_page_by_path() misses
 * attachments in this WP version (their inherit status isn't matched by the
 * path resolver). Returns 0 if not found.
 */
function kb_attachment_by_slug(string $slug): int {
    if ($slug === '') {
        return 0;
    }
    $found = get_posts([
        'post_type'   => 'attachment',
        'post_status' => 'inherit',
        'name'        => $slug,
        'numberposts' => 1,
        'fields'      => 'ids',
    ]);
    return $found ? (int) $found[0] : 0;
}

/**
 * Resolve a curated featured-image attachment ID for an article.
 *
 * Prefers the article's image_slug (attachment post_name — stable across a
 * fresh prod pull). Falls back to the product photo of the article's first
 * machine, sideloaded from the remote URL once if it isn't already an
 * attachment. Returns 0 when nothing resolves.
 */
function kb_resolve_image(array $article): int {
    $slug = (string) ($article['image_slug'] ?? '');
    if ($slug !== '') {
        $id = kb_attachment_by_slug($slug);
        if ($id > 0) {
            return $id;
        }
        fwrite(STDERR, "    image_slug '{$slug}' not found — falling back to machine photo\n");
    }

    // Fallback: the first mapped machine's product photo.
    $machine_slugs = array_values(array_filter((array) ($article['machine_slugs'] ?? [])));
    $first = $machine_slugs[0] ?? '';
    if ($first === '' || !function_exists('Standard\\MachineProductData\\get_machine_product_data')) {
        return 0;
    }
    $data = \Standard\MachineProductData\get_machine_product_data($first) ?? [];
    $url  = (string) ($data['hero']['image'] ?? $data['hero']['hero_image'] ?? '');
    if ($url === '') {
        return 0;
    }

    // Match an existing attachment by source-URL basename before sideloading,
    // so re-runs don't import duplicates.
    $basename = sanitize_title(pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_FILENAME));
    $existing = kb_attachment_by_slug($basename);
    if ($existing > 0) {
        return $existing;
    }

    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $id = media_sideload_image($url, 0, null, 'id');
    return is_wp_error($id) ? 0 : (int) $id;
}

$created = 0;
$updated = 0;

foreach ($articles as $article) {
    $source_url = (string) ($article['source_url'] ?? '');
    $title      = (string) ($article['title'] ?? '');
    if ($source_url === '' || $title === '') {
        fwrite(STDERR, "    skipping record with no source_url/title\n");
        continue;
    }

    // Find existing by source URL (the upsert key).
    $existing = get_posts([
        'post_type'        => KB_POST_TYPE,
        'post_status'      => 'any',
        'numberposts'      => 1,
        'fields'           => 'ids',
        'meta_key'         => KB_META_KEY,
        'meta_value'       => $source_url,
        'suppress_filters' => false,
    ]);

    $postarr = [
        'post_type'    => KB_POST_TYPE,
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_content' => (string) ($article['body'] ?? ''),
        'post_excerpt' => (string) ($article['excerpt'] ?? ''),
    ];

    $published = (string) ($article['published'] ?? '');
    if ($published !== '') {
        $postarr['post_date'] = $published . ' 09:00:00';
    }

    if (!empty($existing)) {
        $post_id = (int) $existing[0];
        $postarr['ID'] = $post_id;
        wp_update_post($postarr);
        $updated++;
        $verb = 'updated';
    } else {
        $post_id = (int) wp_insert_post($postarr);
        if ($post_id === 0) {
            fwrite(STDERR, "    failed to insert: {$title}\n");
            continue;
        }
        update_post_meta($post_id, KB_META_KEY, $source_url);
        $created++;
        $verb = 'created';
    }

    // Featured image: resolve the curated attachment by slug (stable across a
    // prod pull, unlike IDs). Fall back to the machine's product photo so a
    // card is never image-less. Idempotent: only sets when it differs.
    $image_id = kb_resolve_image($article);
    if ($image_id > 0 && (int) get_post_thumbnail_id($post_id) !== $image_id) {
        set_post_thumbnail($post_id, $image_id);
    }

    // Department: service-repair (set, not append — idempotent).
    wp_set_object_terms($post_id, [KB_DEPT_TERM], KB_DEPT_TAX, false);

    // Machine tags: one post_tag per mapped machine slug. `false` replaces the
    // full set each run, so removing a slug from the fixture un-tags it too.
    $machine_slugs = array_values(array_filter((array) ($article['machine_slugs'] ?? [])));
    wp_set_object_terms($post_id, $machine_slugs, 'post_tag', false);

    printf("    %s #%d  %s  [%s]\n", $verb, $post_id, $title, implode(', ', $machine_slugs));
}

printf("    summary: %d created, %d updated, %d total\n", $created, $updated, count($articles));
