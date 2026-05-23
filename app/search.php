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

use function Standard\Filters\build_choice_group;
use function Standard\Filters\build_term_choice_group;
use function Standard\Filters\get_post_type_counts;
use function Standard\Search\get_post_type_filter_keys;
use function Standard\Search\get_post_type_filter_options;
use function Standard\Search\get_request_values;

$content = [
    'eyebrow'         => __('Search', 'standard'),
    'title'           => __('Search Results', 'standard'),
    'title_query'     => __('Search results for "%s"', 'standard'),
    'filter_type'     => __('Content', 'standard'),
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
$search_query   = get_search_query();
$type_options   = get_post_type_filter_options();
$type_counts    = get_post_type_counts();
$requested_types = array_values(array_intersect(
    get_request_values(get_post_type_filter_keys(), 'post_type'),
    array_keys($type_options)
));

// Float selected types to the top of the list so they stay visible.
if ($requested_types !== []) {
    $reordered = [];
    foreach ($requested_types as $post_type) {
        $reordered[$post_type] = $type_options[$post_type];
    }
    $type_options = $reordered + $type_options;
}

$active_categories = get_request_values(['category', 'lc_category', '_sft_category'], 'term', 'category');
$active_tags = get_request_values(['post_tag', 'tag', 'lc_machine', '_sft_post_tag'], 'term', 'post_tag');

$category_terms = get_categories([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 18,
]);
$category_terms = is_array($category_terms) ? $category_terms : [];

$tag_terms = get_terms([
    'taxonomy'   => 'post_tag',
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 24,
]);
$tag_terms = is_wp_error($tag_terms) ? [] : $tag_terms;

// Ensure any active term not in the top-N stays visible.
$prepend_active_terms = static function (array $terms, array $active_slugs, string $taxonomy): array {
    $known = [];
    foreach ($terms as $term) {
        if ($term instanceof WP_Term) {
            $known[$term->slug] = true;
        }
    }

    foreach (array_reverse($active_slugs) as $slug) {
        if (isset($known[$slug])) {
            continue;
        }

        $term = get_term_by('slug', $slug, $taxonomy);
        if ($term instanceof WP_Term) {
            array_unshift($terms, $term);
            $known[$slug] = true;
        }
    }

    return $terms;
};

$category_terms = $prepend_active_terms($category_terms, $active_categories, 'category');
$tag_terms      = $prepend_active_terms($tag_terms, $active_tags, 'post_tag');

// Build the three groups for the shared sidebar.
$groups = [
    build_choice_group(
        'content-type',
        $content['filter_type'],
        'post_type[]',
        $type_options,
        $requested_types,
        array_intersect_key($type_counts, $type_options),
        'filter'
    ),
];

if ($category_terms !== []) {
    $groups[] = build_term_choice_group(
        'category',
        $content['filter_category'],
        'category[]',
        $category_terms,
        $active_categories,
        'folder'
    );
}

if ($tag_terms !== []) {
    $groups[] = build_term_choice_group(
        'topic',
        $content['filter_topic'],
        'post_tag[]',
        $tag_terms,
        $active_tags,
        'link'
    );
}

// Header summary suffix + chip strip data.
$active_type_labels = array_values(array_filter(array_map(
    static fn(string $post_type): string => $type_options[$post_type] ?? '',
    $requested_types
)));
$active_category_labels = [];
foreach ($category_terms as $term) {
    if ($term instanceof WP_Term && in_array($term->slug, $active_categories, true)) {
        $active_category_labels[$term->slug] = $term->name;
    }
}
$active_tag_labels = [];
foreach ($tag_terms as $term) {
    if ($term instanceof WP_Term && in_array($term->slug, $active_tags, true)) {
        $active_tag_labels[$term->slug] = $term->name;
    }
}

$build_remove_url = static function (string $param, string $value) use ($search_query, $requested_types, $active_categories, $active_tags): string {
    $params = [];
    if ($search_query !== '') {
        $params['s'] = $search_query;
    }

    $types = $requested_types;
    $cats  = $active_categories;
    $tags  = $active_tags;

    if ($param === 'post_type') {
        $types = array_values(array_diff($types, [$value]));
    } elseif ($param === 'category') {
        $cats = array_values(array_diff($cats, [$value]));
    } elseif ($param === 'post_tag') {
        $tags = array_values(array_diff($tags, [$value]));
    }

    if ($types !== []) {
        $params['post_type'] = $types;
    }
    if ($cats !== []) {
        $params['category'] = $cats;
    }
    if ($tags !== []) {
        $params['post_tag'] = $tags;
    }

    return add_query_arg($params, \Standard\Url\internal('/'));
};

$chips = [];
foreach ($requested_types as $slug) {
    $label = $type_options[$slug] ?? '';
    if ($label !== '') {
        $chips[] = ['label' => $label, 'remove_url' => $build_remove_url('post_type', $slug)];
    }
}
foreach ($active_category_labels as $slug => $label) {
    $chips[] = ['label' => $label, 'remove_url' => $build_remove_url('category', $slug)];
}
foreach ($active_tag_labels as $slug => $label) {
    $chips[] = ['label' => $label, 'remove_url' => $build_remove_url('post_tag', $slug)];
}

$result_count = isset($GLOBALS['wp_query']) && $GLOBALS['wp_query'] instanceof WP_Query
    ? (int) $GLOBALS['wp_query']->found_posts
    : 0;
$reset_url = $search_query !== ''
    ? add_query_arg(['s' => $search_query], \Standard\Url\internal('/'))
    : \Standard\Url\internal('/');
$has_filters = $chips !== [];
$selected_total = count($chips);
$drawer_label = $selected_total > 0
    ? sprintf(
        /* translators: %d active filter count. */
        _n('Filters (%d)', 'Filters (%d)', $selected_total, 'standard'),
        $selected_total
    )
    : __('Filters', 'standard');

$count_label = sprintf(
    /* translators: %d result count. */
    _n('%d result', '%d results', $result_count, 'standard'),
    $result_count
);

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
            <?php
            get_template_part('templates/parts/filter-sidebar', null, [
                'groups'       => $groups,
                'form_id'      => $search_form_id,
                'apply_label'  => $content['apply'],
                'reset_url'    => $has_filters ? $reset_url : '',
                'reset_label'  => $content['reset'],
                'drawer_label' => $drawer_label,
                'aria_label'   => __('Search filters', 'standard'),
                'show_actions' => true,
            ]);
            ?>

            <div class="grid gap-8 content-start">
                <?php if ($chips !== []) : ?>
                    <?php get_template_part('templates/parts/filter-chips', null, [
                        'chips'     => $chips,
                        'clear_url' => $reset_url,
                        'label'     => __('Active search filters', 'standard'),
                    ]); ?>
                <?php endif; ?>

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
