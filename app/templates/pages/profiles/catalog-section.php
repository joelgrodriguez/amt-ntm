<?php
/**
 * Profiles — Catalog section.
 *
 * Renders one labelled grid of profile cards for a category.
 *
 * Args:
 *   category_id (int):    The category term ID to query.
 *   eyebrow (string):     Small label above the section title.
 *   title (string):       Section heading.
 *   section_id (string):  aria-labelledby target.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$category_id = (int) ($args['category_id'] ?? 0);
$eyebrow     = (string) ($args['eyebrow'] ?? '');
$title       = (string) ($args['title'] ?? '');
$section_id  = (string) ($args['section_id'] ?? '');

if ($category_id <= 0 || $title === '' || $section_id === '') {
    return;
}

$query = new \WP_Query([
    'post_type'              => 'profile',
    'post_status'            => 'publish',
    'posts_per_page'         => -1,
    'cat'                    => $category_id,
    'orderby'                => 'title',
    'order'                  => 'ASC',
    'no_found_rows'          => true,
    'update_post_meta_cache' => false,
]);

if (!$query->have_posts()) {
    return;
}

$count = (int) $query->found_posts ?: (int) $query->post_count;
?>

<section aria-labelledby="<?php echo esc_attr($section_id); ?>" class="grid gap-8">

    <header class="section-header-left">
        <p class="section-eyebrow">
            <?php echo esc_html($eyebrow); ?>
            <span class="text-blue-400" aria-hidden="true">
                · <?php echo esc_html((string) $count); ?>
            </span>
        </p>
        <div class="section-divider"></div>
        <h2 id="<?php echo esc_attr($section_id); ?>"
            class="font-mono font-medium text-blue-900 leading-tight"
            style="font-size: var(--text-heading-sm); line-height: var(--leading-heading-sm);">
            <?php echo esc_html($title); ?>
        </h2>
    </header>

    <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-8 list-none p-0 m-0">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <li>
                <?php get_template_part('templates/parts/card-profile', null, [
                    'profile' => get_post(),
                    'context' => 'grid',
                ]); ?>
            </li>
        <?php endwhile; ?>
    </ul>

</section>

<?php wp_reset_postdata(); ?>
