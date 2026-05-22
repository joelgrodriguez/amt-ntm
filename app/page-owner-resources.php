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

$default_featured = [1396, 16648, 5508];
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

    <?php
    /**
     * Learning Center closer. Same four post-type entry points as the
     * home.php hero, then a trailing link to the LC landing itself.
     * All five links target real archive URLs — no anchor jumps.
     */
    $lc_landing = function_exists('Standard\\LearningCenter\\get_learning_center_url')
        ? \Standard\LearningCenter\get_learning_center_url()
        : home_url('/learning-center/');

    $lc_nav = [
        ['label' => __('Articles', 'standard'),  'icon' => 'file-text', 'href' => get_post_type_archive_link('post') ?: $lc_landing],
        ['label' => __('Videos', 'standard'),    'icon' => 'play',      'href' => get_post_type_archive_link('video') ?: $lc_landing],
        ['label' => __('Resources', 'standard'), 'icon' => 'folder',    'href' => get_post_type_archive_link('resource') ?: $lc_landing],
        ['label' => __('Downloads', 'standard'), 'icon' => 'download',  'href' => get_post_type_archive_link('download') ?: $lc_landing],
    ];
    ?>
    <section class="border-t border-blue-200 bg-blue-50 py-16 lg:py-24"
             aria-labelledby="resources-learning-center-title">
        <div class="container grid gap-10">

            <header class="section-header-left max-w-3xl">
                <p class="section-eyebrow">
                    <?php esc_html_e('Keep Learning', 'standard'); ?>
                </p>
                <div class="section-divider"></div>
                <h2 id="resources-learning-center-title"
                    class="font-mono font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight">
                    <?php esc_html_e('The Rollforming Learning Center', 'standard'); ?>
                </h2>
                <p class="font-sans text-blue-600 mt-4 max-w-2xl"
                   style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Articles, videos, resources, and downloads on running a portable rollforming business.', 'standard'); ?>
                </p>
            </header>

            <nav class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4" aria-label="<?php esc_attr_e('Browse Learning Center by content type', 'standard'); ?>">
                <?php foreach ($lc_nav as $item) : ?>
                    <a href="<?php echo esc_url($item['href']); ?>"
                       class="group flex items-center justify-between gap-3 p-4 lg:p-5 bg-white border border-blue-200 no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <span class="flex items-center gap-3 min-w-0">
                            <?php icon($item['icon'], ['class' => 'w-5 h-5 text-blue-500 shrink-0', 'aria-hidden' => 'true']); ?>
                            <span class="font-mono font-medium uppercase tracking-widest text-caption text-blue-900 group-hover:text-blue-500 transition-colors truncate">
                                <?php echo esc_html($item['label']); ?>
                            </span>
                        </span>
                        <span class="text-blue-400 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all shrink-0" aria-hidden="true">
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </nav>

            <p class="font-mono font-medium uppercase tracking-widest text-caption">
                <a href="<?php echo esc_url($lc_landing); ?>"
                   class="inline-flex items-center gap-2 text-blue-900 hover:text-blue-500 transition-colors no-underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                    <?php esc_html_e('Visit the Learning Center', 'standard'); ?>
                    <span aria-hidden="true">→</span>
                </a>
            </p>

        </div>
    </section>

</main>

<?php get_footer(); ?>
