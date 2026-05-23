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
    'eyebrow'     => __('Search', 'standard'),
    'title'       => __('Search Results', 'standard'),
    'title_query' => __('Search results for "%s"', 'standard'),
    'all_types'   => __('All content', 'standard'),
    'all_categories' => __('All categories', 'standard'),
    'filter_type' => __('Content Type', 'standard'),
    'filter_category' => __('Category', 'standard'),
    'placeholder' => __('Search machines, manuals, profiles, articles...', 'standard'),
    'submit'      => __('Search', 'standard'),
    'reset'       => __('Clear filters', 'standard'),
    'prev'        => __('Previous', 'standard'),
    'next'        => __('Next', 'standard'),
];

$search_query = get_search_query();
$type_options = get_post_type_filter_options();
$requested_types = get_request_values(get_post_type_filter_keys(), 'post_type');
$active_type = count($requested_types) === 1 ? $requested_types[0] : '';
$active_type_label = $active_type !== '' && isset($type_options[$active_type])
    ? $type_options[$active_type]
    : '';
$active_categories = get_request_values(['category', 'lc_category', '_sft_category'], 'term', 'category');
$active_category = count($active_categories) === 1 ? $active_categories[0] : '';
$active_category_term = $active_category !== '' ? get_term_by('slug', $active_category, 'category') : null;
$active_category_label = $active_category_term instanceof WP_Term ? $active_category_term->name : '';
$category_terms = get_categories([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 18,
]);
if ($active_category_term instanceof WP_Term) {
    $has_active_category_term = false;

    foreach ($category_terms as $category_term) {
        if ($category_term instanceof WP_Term && (int) $category_term->term_id === (int) $active_category_term->term_id) {
            $has_active_category_term = true;
            break;
        }
    }

    if (!$has_active_category_term) {
        array_unshift($category_terms, $active_category_term);
    }
}
$result_count = isset($GLOBALS['wp_query']) && $GLOBALS['wp_query'] instanceof WP_Query
    ? (int) $GLOBALS['wp_query']->found_posts
    : 0;
$reset_url = $search_query !== ''
    ? add_query_arg(['s' => $search_query], \Standard\Url\internal('/'))
    : \Standard\Url\internal('/');
$active_filter_labels = array_values(array_filter([$active_type_label, $active_category_label]));
$active_filter_suffix = $active_filter_labels !== [] ? ' / ' . implode(' / ', $active_filter_labels) : '';
$count_label = sprintf(
    /* translators: %1$d result count, %2$s optional post type label. */
    _n('%1$d result%2$s', '%1$d results%2$s', $result_count, 'standard'),
    $result_count,
    $active_filter_suffix
);
$has_filters = $active_type !== '' || $active_category !== '';
$build_filter_url = static function (array $overrides = [], array $remove = []) use ($search_query, $active_type, $active_category): string {
    $params = [];

    if ($search_query !== '') {
        $params['s'] = $search_query;
    }

    if ($active_type !== '') {
        $params['post_type'] = $active_type;
    }

    if ($active_category !== '') {
        $params['category'] = $active_category;
    }

    foreach ($remove as $key) {
        unset($params[$key]);
    }

    foreach ($overrides as $key => $value) {
        if ($value === '' || $value === null) {
            unset($params[$key]);
            continue;
        }

        $params[$key] = $value;
    }

    return add_query_arg($params, \Standard\Url\internal('/'));
};

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

            <form class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto_auto] md:items-end" role="search" method="get" action="<?php echo esc_url(\Standard\Url\internal('/')); ?>">
                <?php if ($active_type !== '') : ?>
                    <input type="hidden" name="post_type" value="<?php echo esc_attr($active_type); ?>">
                <?php endif; ?>
                <?php if ($active_category !== '') : ?>
                    <input type="hidden" name="category" value="<?php echo esc_attr($active_category); ?>">
                <?php endif; ?>

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
                <nav class="grid gap-8 lg:sticky lg:top-24">
                    <div>
                        <h2 class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-4">
                            <?php icon('filter', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                            <?php echo esc_html($content['filter_type']); ?>
                        </h2>
                        <ul class="grid gap-1 border-l border-blue-200 list-none p-0 m-0">
                            <li>
                                <a href="<?php echo esc_url($build_filter_url([], ['post_type'])); ?>"
                                   class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $active_type === '' ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                    <span><?php echo esc_html($content['all_types']); ?></span>
                                </a>
                            </li>
                            <?php foreach ($type_options as $post_type => $label) : ?>
                                <?php $count = wp_count_posts($post_type)->publish ?? 0; ?>
                                <li>
                                    <a href="<?php echo esc_url($build_filter_url(['post_type' => $post_type])); ?>"
                                       class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $active_type === $post_type ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                        <span><?php echo esc_html($label); ?></span>
                                        <span class="text-xs text-blue-400"><?php echo esc_html((string) $count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <?php if (!empty($category_terms)) : ?>
                        <div>
                            <h2 class="flex items-center gap-2 text-sm font-medium text-blue-900 mb-4">
                                <?php icon('folder', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                <?php echo esc_html($content['filter_category']); ?>
                            </h2>
                            <ul class="grid gap-1 border-l border-blue-200 list-none p-0 m-0">
                                <li>
                                    <a href="<?php echo esc_url($build_filter_url([], ['category'])); ?>"
                                       class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $active_category === '' ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                        <span><?php echo esc_html($content['all_categories']); ?></span>
                                    </a>
                                </li>
                                <?php foreach ($category_terms as $category_term) : ?>
                                    <?php if (!$category_term instanceof WP_Term) continue; ?>
                                    <li>
                                        <a href="<?php echo esc_url($build_filter_url(['category' => $category_term->slug])); ?>"
                                           class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px <?php echo $active_category === $category_term->slug ? 'border-blue-500 text-blue-500 font-medium' : 'border-transparent text-blue-600 hover:text-blue-900 hover:border-blue-300'; ?>">
                                            <span><?php echo esc_html($category_term->name); ?></span>
                                            <span class="text-xs text-blue-400"><?php echo esc_html((string) $category_term->count); ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </nav>
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
