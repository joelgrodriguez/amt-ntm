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

use function Standard\LearningCenter\get_active_filters as get_learning_center_filters;
use function Standard\LearningCenter\get_category_filter_options;
use function Standard\LearningCenter\get_filter_groups;
use function Standard\LearningCenter\get_learning_center_url;
use function Standard\LearningCenter\get_machine_filter_options;
use function Standard\LearningCenter\get_type_filter_options;

$content = [
    'eyebrow'         => __('Search', 'standard'),
    'title'           => __('Search Results', 'standard'),
    'title_query'     => __('Search results for "%s"', 'standard'),
    'filter_type'     => __('Content', 'standard'),
    'filter_category' => __('Categories', 'standard'),
    'filter_machine'  => __('Machines', 'standard'),
    'placeholder'     => __('Search machines, manuals, profiles, articles...', 'standard'),
    'submit'          => __('Search', 'standard'),
    'apply'           => __('Apply filters', 'standard'),
    'reset'           => __('Clear filters', 'standard'),
    'prev'            => __('Previous', 'standard'),
    'next'            => __('Next', 'standard'),
];

$search_form_id = 'search-filter-form';
$search_query   = get_search_query();
$filters = get_learning_center_filters();
$groups = get_filter_groups($filters, [
    'all_type_label' => __('All results', 'standard'),
]);

$type_options = get_type_filter_options(false);
$category_options = get_category_filter_options(false);
$machine_options = get_machine_filter_options(false);
$learning_center_url = get_learning_center_url();

$build_remove_url = static function (string $param) use ($search_query, $filters, $learning_center_url): string {
    $params = [];

    $next = $filters;
    $next[$param] = '';

    $has_next_filters = ($next['category'] ?? '') !== ''
        || ($next['type'] ?? '') !== ''
        || ($next['machine'] ?? '') !== '';

    if ($search_query !== '') {
        $params['s'] = $search_query;
    } elseif ($has_next_filters) {
        $params['s'] = '';
    } else {
        return $learning_center_url;
    }

    if (($next['category'] ?? '') !== '') {
        $params['lc_category'] = $next['category'];
    }
    if (($next['type'] ?? '') !== '') {
        $params['lc_type'] = $next['type'];
    }
    if (($next['machine'] ?? '') !== '') {
        $params['lc_machine'] = $next['machine'];
    }

    return add_query_arg($params, \Standard\Url\internal('/'));
};

$chips = [];
$active_type = (string) ($filters['type'] ?? '');
if ($active_type !== '' && isset($type_options[$active_type])) {
    $chips[] = ['label' => $type_options[$active_type], 'remove_url' => $build_remove_url('type')];
}
$active_category = (string) ($filters['category'] ?? '');
if ($active_category !== '' && isset($category_options[$active_category])) {
    $chips[] = ['label' => $category_options[$active_category], 'remove_url' => $build_remove_url('category')];
}
$active_machine = (string) ($filters['machine'] ?? '');
if ($active_machine !== '' && isset($machine_options[$active_machine])) {
    $chips[] = ['label' => $machine_options[$active_machine], 'remove_url' => $build_remove_url('machine')];
}

$result_count = isset($GLOBALS['wp_query']) && $GLOBALS['wp_query'] instanceof WP_Query
    ? (int) $GLOBALS['wp_query']->found_posts
    : 0;
$reset_url = $search_query !== ''
    ? add_query_arg(['s' => $search_query], \Standard\Url\internal('/'))
    : $learning_center_url;
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

            <form id="<?php echo esc_attr($search_form_id); ?>" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto] md:items-end" role="search" method="get" action="<?php echo esc_url(\Standard\Url\internal('/')); ?>">
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

                <button type="submit" class="btn btn-primary btn--commit w-full md:w-auto">
                    <?php echo esc_html($content['submit']); ?>
                </button>
            </form>
        </div>
    </section>

    <section class="container section-compact" aria-label="<?php esc_attr_e('Search results', 'standard'); ?>">
        <div class="layout-with-rail">
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
                        'chips' => $chips,
                        'label' => __('Active search filters', 'standard'),
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
                                <?php esc_html_e('Try a broader keyword or remove a filter from the sidebar.', 'standard'); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
