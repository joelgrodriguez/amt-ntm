<?php
/**
 * Template Name: Resources
 *
 * Landing for the `resource` post type. Resources are heterogeneous
 * (calculators, training programs, brand guidelines, reference docs)
 * and don't subdivide into a useful taxonomy, so the layout drops the
 * filter sidebar that powers /profiles and /manuals and replaces it
 * with a curated rhythm: a pinned featured strip on top, then the full
 * library in a denser grid below.
 *
 * Editorial pins resolve in this order:
 *   1. Filter `standard_resources_featured` returning an array of post IDs.
 *   2. Default hard-coded list (Coil Calculator, Cutlist Generator,
 *      Machine Training) ID-resolved at render time; missing IDs are
 *      dropped silently.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Resolve the featured resource IDs to actual published posts.
 *
 * @param int[] $ids
 * @return \WP_Post[]
 */
$resolve_featured = static function (array $ids): array {
    $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
    if ($ids === []) {
        return [];
    }

    $posts = get_posts([
        'post_type'              => 'resource',
        'post_status'            => 'publish',
        'posts_per_page'         => count($ids),
        'post__in'               => $ids,
        'orderby'                => 'post__in',
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ]);

    return is_array($posts) ? $posts : [];
};

$default_featured = [7800, 1398, 1395];
/**
 * Filter: standard_resources_featured
 * Override the list of pinned resource IDs shown in the featured strip.
 *
 * @param int[] $ids
 */
$featured_ids = (array) apply_filters('standard_resources_featured', $default_featured);
$featured     = $resolve_featured($featured_ids);
$featured_ids_resolved = array_map(static fn(\WP_Post $p): int => $p->ID, $featured);

get_header();
?>

<main id="primary">

    <?php get_template_part('templates/pages/resources/hero'); ?>

    <?php if (!empty($featured)) : ?>
        <?php get_template_part('templates/pages/resources/featured', null, [
            'featured' => $featured,
        ]); ?>
    <?php endif; ?>

    <?php get_template_part('templates/pages/resources/library', null, [
        'exclude' => $featured_ids_resolved,
    ]); ?>

</main>

<?php get_footer(); ?>
