<?php
/**
 * The template for displaying search results.
 *
 * Displays search results in a grid layout with pagination.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Search\get_post_type_filter_keys;
use function Standard\Search\get_post_type_filter_options;
use function Standard\Search\get_request_values;

$content = [
    'eyebrow'         => __('Search', 'standard'),
    'title'           => __('Search Results', 'standard'),
    'title_query'     => __('Search results for "%s"', 'standard'),
    'filter_type'     => __('Content Type', 'standard'),
    'filter_category' => __('Categories', 'standard'),
    'filter_topic'    => __('Topics', 'standard'),
    'placeholder'     => __('Search machines, manuals, profiles, articles...', 'standard'),
    'submit'          => __('Search', 'standard'),
    'apply'           => __('Apply filters', 'standard'),
    'reset'           => __('Clear filters', 'standard'),
    'prev'            => __('Previous', 'standard'),
    'next'            => __('Next', 'standard'),
];

$search_form_id = 'search-filter-form';
$search_query = get_search_query();
$type_options = get_post_type_filter_options();
$requested_types = array_values(array_intersect(
    get_request_values(get_post_type_filter_keys(), 'post_type'),
    array_keys($type_options)
));
if ($requested_types !== []) {
    $selected_type_options = [];

    foreach ($requested_types as $post_type) {
        $selected_type_options[$post_type] = $type_options[$post_type];
    }

    $type_options = $selected_type_options + $type_options;
}
$active_categories = get_request_values(['category', 'lc_category', '_sft_category'], 'term', 'category');
$active_tags = get_request_values(['post_tag', 'tag', 'lc_machine', '_sft_post_tag'], 'term', 'post_tag');
$get_terms_by_slugs = static function (array $slugs, string $taxonomy): array {
    $terms = [];

    foreach ($slugs as $slug) {
        $term = get_term_by('slug', $slug, $taxonomy);

        if ($term instanceof WP_Term) {
            $terms[] = $term;
        }
    }

    return $terms;
};
$prepend_active_terms = static function (array $terms, array $active_terms): array {
    $known_ids = [];

    foreach ($terms as $term) {
        if ($term instanceof WP_Term) {
            $known_ids[] = (int) $term->term_id;
        }
    }

    foreach (array_reverse($active_terms) as $active_term) {
        if (!$active_term instanceof WP_Term || in_array((int) $active_term->term_id, $known_ids, true)) {
            continue;
        }

        array_unshift($terms, $active_term);
        $known_ids[] = (int) $active_term->term_id;
    }

    return $terms;
};
$get_term_labels = static function (array $terms): array {
    return array_values(array_map(
        static fn(WP_Term $term): string => $term->name,
        array_filter($terms, static fn($term): bool => $term instanceof WP_Term)
    ));
};
$format_filter_group = static function (array $labels, string $plural_label): string {
    $labels = array_values(array_filter($labels));

    if (count($labels) <= 2) {
        return implode(' + ', $labels);
    }

    return sprintf(
        /* translators: %1$d selected filter count, %2$s filter group label. */
        __('%1$d %2$s', 'standard'),
        count($labels),
        $plural_label
    );
};
$selected_count_label = static function (int $count): string {
    if ($count < 1) {
        return '';
    }

    return sprintf(
        /* translators: %d selected filter count. */
        _n('%d selected', '%d selected', $count, 'standard'),
        $count
    );
};
$category_terms = get_categories([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 18,
]);
$category_terms = is_array($category_terms) ? $category_terms : [];
$active_category_terms = $get_terms_by_slugs($active_categories, 'category');
$category_terms = $prepend_active_terms($category_terms, $active_category_terms);
$tag_terms = get_terms([
    'taxonomy'   => 'post_tag',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 24,
]);
$tag_terms = is_wp_error($tag_terms) ? [] : $tag_terms;
$active_tag_terms = $get_terms_by_slugs($active_tags, 'post_tag');
$tag_terms = $prepend_active_terms($tag_terms, $active_tag_terms);
$active_type_labels = array_values(array_filter(array_map(
    static fn(string $post_type): string => $type_options[$post_type] ?? '',
    $requested_types
)));
$active_filter_labels = array_values(array_filter([
    $format_filter_group($active_type_labels, __('content types', 'standard')),
    $format_filter_group($get_term_labels($active_category_terms), __('categories', 'standard')),
    $format_filter_group($get_term_labels($active_tag_terms), __('topics', 'standard')),
]));
$result_count = isset($GLOBALS['wp_query']) && $GLOBALS['wp_query'] instanceof WP_Query
    ? (int) $GLOBALS['wp_query']->found_posts
    : 0;
$reset_url = $search_query !== ''
    ? add_query_arg(['s' => $search_query], \Standard\Url\internal('/'))
    : \Standard\Url\internal('/');
$active_filter_suffix = $active_filter_labels !== [] ? ' / ' . implode(' / ', $active_filter_labels) : '';
$count_label = sprintf(
    /* translators: %1$d result count, %2$s optional post type label. */
    _n('%1$d result%2$s', '%1$d results%2$s', $result_count, 'standard'),
    $result_count,
    $active_filter_suffix
);
$has_filters = $requested_types !== [] || $active_categories !== [] || $active_tags !== [];

get_header();
?>

<main id="primary">
    <header class="pattern-dot-grid pattern-dot-grid--surface bg-blue-50 border-b border-blue-200">
        <div class="container section-compact">
            <div class="section-header-left max-w-4xl">
                <p class="section-eyebrow"><?php echo esc_html($content['eyebrow']); ?></p>
                <div class="section-divider"></div>
                <h1 class="font-sans font-semibold text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                    <?php
                    echo $search_query !== ''
                        ? esc_html(sprintf($content['title_query'], $search_query))
                        : esc_html($content['title']);
                    ?>
                </h1>
                <p class="text-blue-600 max-w-2xl">
                    <?php echo esc_html($count_label); ?>
                </p>
            </div>
        </div>
    </header>

    <section class="border-b border-blue-200" aria-labelledby="search-form-title">
        <div class="container py-6 lg:py-8">
            <h2 id="search-form-title" class="sr-only">
                <?php esc_html_e('Search the site', 'standard'); ?>
            </h2>

            <form id="<?php echo esc_attr($search_form_id); ?>" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto_auto] md:items-end" role="search" method="get" action="<?php echo esc_url(\Standard\Url\internal('/')); ?>">
                <div class="field">
                    <label for="global-search-field" class="field-label">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </label>
                    <input
                        id="global-search-field"
                        class="field-input"
                        type="search"
                        name="s"
                        value="<?php echo esc_attr($search_query); ?>"
                        placeholder="<?php echo esc_attr($content['placeholder']); ?>"
                    >
                </div>

                <button type="submit" class="btn btn-primary w-full md:w-auto">
                    <?php echo esc_html($content['submit']); ?>
                </button>

                <?php if ($has_filters) : ?>
                    <a href="<?php echo esc_url($reset_url); ?>" class="btn btn-ghost w-full md:w-auto">
                        <?php echo esc_html($content['reset']); ?>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <section class="container section-compact" aria-label="<?php esc_attr_e('Search results', 'standard'); ?>">
        <div class="grid gap-8 lg:grid-cols-[240px_1fr] lg:gap-12">
            <aside class="border-b border-blue-200 pb-8 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-8" aria-label="<?php esc_attr_e('Search filters', 'standard'); ?>">
                <div class="grid gap-8 lg:sticky lg:top-24">
                    <fieldset class="grid gap-3">
                        <legend class="flex w-full items-center justify-between gap-3 text-sm font-medium text-blue-900">
                            <span class="flex items-center gap-2">
                                <?php icon('filter', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                <?php echo esc_html($content['filter_type']); ?>
                            </span>
                            <?php if ($selected_count_label(count($requested_types)) !== '') : ?>
                                <span class="font-mono text-xs font-normal text-blue-400">
                                    <?php echo esc_html($selected_count_label(count($requested_types))); ?>
                                </span>
                            <?php endif; ?>
                        </legend>

                        <div class="grid gap-2">
                            <?php foreach ($type_options as $post_type => $label) : ?>
                                <?php
                                $is_checked = in_array($post_type, $requested_types, true);
                                $post_type_counts = wp_count_posts($post_type);
                                $count = is_object($post_type_counts) && isset($post_type_counts->publish)
                                    ? (int) $post_type_counts->publish
                                    : 0;
                                ?>
                                <label class="flex min-h-11 cursor-pointer items-center gap-3 border px-3 py-2 text-sm transition-colors <?php echo $is_checked ? 'border-blue-500 bg-blue-50 text-blue-900' : 'border-blue-200 text-blue-700 hover:border-blue-500 hover:text-blue-900'; ?>">
                                    <input
                                        class="h-4 w-4 shrink-0 accent-blue-500"
                                        type="checkbox"
                                        name="post_type[]"
                                        value="<?php echo esc_attr($post_type); ?>"
                                        form="<?php echo esc_attr($search_form_id); ?>"
                                        <?php checked($is_checked); ?>
                                    >
                                    <span class="min-w-0 flex-1"><?php echo esc_html($label); ?></span>
                                    <span class="font-mono text-xs text-blue-400"><?php echo esc_html((string) $count); ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </fieldset>

                    <?php if (!empty($category_terms)) : ?>
                        <fieldset class="grid gap-3">
                            <legend class="flex w-full items-center justify-between gap-3 text-sm font-medium text-blue-900">
                                <span class="flex items-center gap-2">
                                    <?php icon('folder', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                    <?php echo esc_html($content['filter_category']); ?>
                                </span>
                                <?php if ($selected_count_label(count($active_category_terms)) !== '') : ?>
                                    <span class="font-mono text-xs font-normal text-blue-400">
                                        <?php echo esc_html($selected_count_label(count($active_category_terms))); ?>
                                    </span>
                                <?php endif; ?>
                            </legend>

                            <div class="grid gap-2">
                                <?php foreach ($category_terms as $category_term) : ?>
                                    <?php if (!$category_term instanceof WP_Term) continue; ?>
                                    <?php $is_checked = in_array($category_term->slug, $active_categories, true); ?>
                                    <label class="flex min-h-11 cursor-pointer items-center gap-3 border px-3 py-2 text-sm transition-colors <?php echo $is_checked ? 'border-blue-500 bg-blue-50 text-blue-900' : 'border-blue-200 text-blue-700 hover:border-blue-500 hover:text-blue-900'; ?>">
                                        <input
                                            class="h-4 w-4 shrink-0 accent-blue-500"
                                            type="checkbox"
                                            name="category[]"
                                            value="<?php echo esc_attr($category_term->slug); ?>"
                                            form="<?php echo esc_attr($search_form_id); ?>"
                                            <?php checked($is_checked); ?>
                                        >
                                        <span class="min-w-0 flex-1"><?php echo esc_html($category_term->name); ?></span>
                                        <span class="font-mono text-xs text-blue-400"><?php echo esc_html((string) $category_term->count); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endif; ?>

                    <?php if (!empty($tag_terms)) : ?>
                        <fieldset class="grid gap-3">
                            <legend class="flex w-full items-center justify-between gap-3 text-sm font-medium text-blue-900">
                                <span class="flex items-center gap-2">
                                    <?php icon('link', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                    <?php echo esc_html($content['filter_topic']); ?>
                                </span>
                                <?php if ($selected_count_label(count($active_tag_terms)) !== '') : ?>
                                    <span class="font-mono text-xs font-normal text-blue-400">
                                        <?php echo esc_html($selected_count_label(count($active_tag_terms))); ?>
                                    </span>
                                <?php endif; ?>
                            </legend>

                            <div class="grid gap-2">
                                <?php foreach ($tag_terms as $tag_term) : ?>
                                    <?php if (!$tag_term instanceof WP_Term) continue; ?>
                                    <?php $is_checked = in_array($tag_term->slug, $active_tags, true); ?>
                                    <label class="flex min-h-11 cursor-pointer items-center gap-3 border px-3 py-2 text-sm transition-colors <?php echo $is_checked ? 'border-blue-500 bg-blue-50 text-blue-900' : 'border-blue-200 text-blue-700 hover:border-blue-500 hover:text-blue-900'; ?>">
                                        <input
                                            class="h-4 w-4 shrink-0 accent-blue-500"
                                            type="checkbox"
                                            name="post_tag[]"
                                            value="<?php echo esc_attr($tag_term->slug); ?>"
                                            form="<?php echo esc_attr($search_form_id); ?>"
                                            <?php checked($is_checked); ?>
                                        >
                                        <span class="min-w-0 flex-1"><?php echo esc_html($tag_term->name); ?></span>
                                        <span class="font-mono text-xs text-blue-400"><?php echo esc_html((string) $tag_term->count); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </fieldset>
                    <?php endif; ?>

                    <div class="grid gap-3 border-t border-blue-200 pt-6">
                        <button type="submit" form="<?php echo esc_attr($search_form_id); ?>" class="btn btn-primary w-full">
                            <?php echo esc_html($content['apply']); ?>
                        </button>
                        <?php if ($has_filters) : ?>
                            <a href="<?php echo esc_url($reset_url); ?>" class="btn btn-ghost w-full">
                                <?php echo esc_html($content['reset']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </aside>

            <div class="grid gap-8 content-start">
                <?php if (have_posts()) : ?>
                    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                        <?php while (have_posts()) : the_post(); ?>
                            <?php get_template_part('templates/parts/content', 'search'); ?>
                        <?php endwhile; ?>
                    </div>

                    <nav>
                        <?php the_posts_pagination([
                            'mid_size'  => 2,
                            'prev_text' => '&larr; ' . esc_html($content['prev']),
                            'next_text' => esc_html($content['next']) . ' &rarr;',
                        ]); ?>
                    </nav>
                <?php else : ?>
                    <div class="border-t border-blue-200 pt-12">
                        <div class="grid gap-4 max-w-xl">
                            <span class="font-mono font-medium uppercase tracking-wider text-red" style="font-size: var(--text-caption);">
                                <?php esc_html_e('No matches', 'standard'); ?>
                            </span>
                            <h2 class="font-sans text-2xl font-semibold tracking-tight text-blue-900">
                                <?php esc_html_e('Nothing matched those filters.', 'standard'); ?>
                            </h2>
                            <p class="text-blue-600">
                                <?php esc_html_e('Try a broader keyword, remove a filter, or search all content.', 'standard'); ?>
                            </p>
                            <?php if ($has_filters) : ?>
                                <div>
                                    <a href="<?php echo esc_url($reset_url); ?>" class="btn btn-secondary">
                                        <?php echo esc_html($content['reset']); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
