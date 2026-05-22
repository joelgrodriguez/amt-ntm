<?php
/**
 * Manuals — Catalog section.
 *
 * Renders one labelled grid of up to 4 manual cards for a category,
 * with a "View all" link to the category archive when more exist.
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
$is_first    = (bool) ($args['is_first'] ?? false);
$is_last     = (bool) ($args['is_last'] ?? false);

if ($category_id <= 0 || $title === '' || $section_id === '') {
    return;
}

$per_page = 4;

$query = new \WP_Query([
    'post_type'              => 'manual',
    'post_status'            => 'publish',
    'posts_per_page'         => $per_page,
    'cat'                    => $category_id,
    'orderby'                => 'title',
    'order'                  => 'ASC',
    'update_post_meta_cache' => false,
]);

if (!$query->have_posts()) {
    return;
}

$total          = (int) $query->found_posts;
$has_more       = $total > $per_page;
$archive_link   = get_category_link($category_id);
$view_all_label = sprintf(
    /* translators: %d total manuals in this category. */
    __('View all %d', 'standard'),
    $total
);

$wrapper_classes = ['grid', 'gap-8', 'scroll-mt-24'];
if (!$is_first) {
    $wrapper_classes[] = 'pt-16';
    $wrapper_classes[] = 'lg:pt-20';
}
if (!$is_last) {
    $wrapper_classes[] = 'pb-16';
    $wrapper_classes[] = 'lg:pb-20';
    $wrapper_classes[] = 'border-b';
    $wrapper_classes[] = 'border-blue-200';
}
?>

<section aria-labelledby="<?php echo esc_attr($section_id . '-title'); ?>" class="<?php echo esc_attr(implode(' ', $wrapper_classes)); ?>" id="<?php echo esc_attr($section_id); ?>" tabindex="-1">

    <header class="flex flex-wrap items-end justify-between gap-4">
        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($eyebrow); ?>
                <span class="text-blue-400" aria-hidden="true">
                    · <?php echo esc_html((string) $total); ?>
                </span>
            </p>
            <div class="section-divider"></div>
            <h2 id="<?php echo esc_attr($section_id . '-title'); ?>"
                class="font-sans font-semibold text-blue-900 leading-tight tracking-tight"
                style="font-size: var(--text-heading-sm); line-height: var(--leading-heading-sm);">
                <?php echo esc_html($title); ?>
            </h2>
        </div>

        <?php if ($has_more && $archive_link) : ?>
            <a href="<?php echo esc_url($archive_link); ?>"
               class="hidden sm:inline-flex items-center gap-2 text-sm font-mono font-medium text-blue-500 hover:underline">
                <?php echo esc_html($view_all_label); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </a>
        <?php endif; ?>
    </header>

    <ul class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-8 list-none p-0 m-0">
        <?php while ($query->have_posts()) : $query->the_post(); ?>
            <li>
                <?php get_template_part('templates/parts/card-manual', null, [
                    'manual'  => get_post(),
                    'context' => 'grid',
                ]); ?>
            </li>
        <?php endwhile; ?>
    </ul>

    <?php if ($has_more && $archive_link) : ?>
        <div class="sm:hidden">
            <a href="<?php echo esc_url($archive_link); ?>"
               class="inline-flex items-center gap-2 text-sm font-mono font-medium text-blue-500 hover:underline">
                <?php echo esc_html($view_all_label); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            </a>
        </div>
    <?php endif; ?>

</section>

<?php wp_reset_postdata(); ?>
