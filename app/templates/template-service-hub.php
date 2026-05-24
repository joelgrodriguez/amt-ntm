<?php
/**
 * Template Name: Service Hub
 *
 * Service-only landing page with search and filters. Listings/utility
 * surface, not a product hero: no dot-grid wallpaper, no full-boxed
 * filter chrome. Hairline structural borders (DESIGN.md §8) carry the
 * blueprint feel; forms.css carries the input system.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Filters\build_choice_group;
use function Standard\MachinesData\get_machine_post_tags;
use function Standard\ServiceHub\get_active_filters;
use function Standard\ServiceHub\get_post_type_counts;
use function Standard\ServiceHub\get_post_type_label;
use function Standard\ServiceHub\get_post_type_options;
use function Standard\ServiceHub\get_results_query;
use function Standard\ServiceHub\get_terms_for_service_content;

$filters = get_active_filters();
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$service_query = get_results_query($filters, $paged);
$post_type_options = get_post_type_options();
$post_type_counts = get_post_type_counts();
$categories = get_terms_for_service_content('category', 24);
$machine_tags = get_machine_post_tags();
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/');
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== '';

$service_form_id = 'service-hub-form';

// Build sidebar filter groups (radio mode; the query layer reads each
// param as a scalar). Each group leads with an empty-value "All" row
// so users can clear that one axis without leaving the page.
$type_choice_options = ['' => __('All types', 'standard')];
foreach ($post_type_options as $post_type => $option) {
    $count = (int) ($post_type_counts[$post_type] ?? 0);
    $label = (string) $option['label'];
    $type_choice_options[$post_type] = $count > 0
        ? sprintf('%s (%d)', $label, $count)
        : $label;
}

$category_choice_options = ['' => __('All categories', 'standard')];
foreach ($categories as $category) {
    if ($category instanceof WP_Term) {
        $category_choice_options[$category->slug] = $category->name;
    }
}

$service_groups = [
    build_choice_group(
        'service-type',
        __('Type', 'standard'),
        'service_type',
        $type_choice_options,
        [(string) $filters['type']],
        [],
        'file-text',
        'radio'
    ),
    build_choice_group(
        'service-category',
        __('Category', 'standard'),
        'service_category',
        $category_choice_options,
        [(string) $filters['category']],
        [],
        'folder',
        'radio'
    ),
];

if (!empty($machine_tags)) {
    $machine_choice_options = ['' => __('All machines', 'standard')];
    foreach ($machine_tags as $machine_tag) {
        if ($machine_tag instanceof WP_Term) {
            $machine_choice_options[$machine_tag->slug] = $machine_tag->name;
        }
    }

    $service_groups[] = build_choice_group(
        'service-machine',
        __('Machine', 'standard'),
        'service_machine',
        $machine_choice_options,
        [(string) $filters['machine']],
        [],
        'settings',
        'radio'
    );
}

get_header();
?>

<main id="primary">

    <header class="pattern-dot-grid pattern-dot-grid--surface bg-blue-50 border-b border-blue-200">
        <div class="container section-compact">
            <div class="grid gap-4 max-w-4xl">
                <span class="section-eyebrow">
                    <?php esc_html_e('Service Hub', 'standard'); ?>
                </span>
                <h1 class="font-mono font-medium text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                    <?php esc_html_e('Service and Support', 'standard'); ?>
                </h1>
                <p class="font-sans text-blue-600 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Manuals, troubleshooting articles, support videos, machine parts and footprint downloads. Filter by machine, content type, or topic, or talk to the service team directly.', 'standard'); ?>
                </p>
            </div>
        </div>
    </header>

    <!-- Keyword search bar. Lives outside the rail because it's the primary
         entry point; the rail is for narrowing. -->
    <section class="border-b border-blue-200" aria-labelledby="service-hub-search-title">
        <div class="container py-6 lg:py-8">
            <h2 id="service-hub-search-title" class="sr-only">
                <?php esc_html_e('Search service content', 'standard'); ?>
            </h2>

            <form
                id="<?php echo esc_attr($service_form_id); ?>"
                method="get"
                action="<?php echo esc_url($form_action); ?>"
                class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto_auto] md:items-end"
            >
                <div class="field">
                    <label for="service-search" class="field-label">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </label>
                    <input
                        id="service-search"
                        name="service_search"
                        type="search"
                        value="<?php echo esc_attr($filters['search']); ?>"
                        class="field-input"
                        placeholder="<?php esc_attr_e('Machine, manual, problem, topic…', 'standard'); ?>"
                    >
                </div>

                <button type="submit" class="btn btn-primary w-full md:w-auto">
                    <?php esc_html_e('Search', 'standard'); ?>
                </button>

                <?php if ($has_filters) : ?>
                    <a href="<?php echo esc_url($form_action); ?>" class="btn btn-ghost w-full md:w-auto">
                        <?php esc_html_e('Reset', 'standard'); ?>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <section class="container section-compact" aria-labelledby="service-hub-results-title">
        <h2 id="service-hub-results-title" class="sr-only">
            <?php esc_html_e('Service results', 'standard'); ?>
        </h2>

        <div class="layout-with-rail">

            <?php
            get_template_part('templates/parts/filter-sidebar', null, [
                'groups'       => $service_groups,
                'form_id'      => $service_form_id,
                'apply_label'  => __('Apply filters', 'standard'),
                'reset_url'    => $has_filters ? $form_action : '',
                'reset_label'  => __('Clear filters', 'standard'),
                'drawer_label' => __('Filters', 'standard'),
                'aria_label'   => __('Service filters', 'standard'),
                'show_actions' => true,
            ]);
            ?>

            <div class="grid gap-8 content-start">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="font-mono font-medium uppercase tracking-wider text-blue-700 m-0" style="font-size: var(--text-caption);">
                        <?php
                        printf(
                            /* translators: %d result count. */
                            esc_html__('Results: %d', 'standard'),
                            (int) $service_query->found_posts
                        );
                        if ($filters['type'] !== '') :
                        ?>
                            <span class="text-blue-500"> &middot; <?php echo esc_html(get_post_type_label($filters['type'])); ?></span>
                        <?php endif; ?>
                    </p>

                    <?php
                    $sort_options = \Standard\ServiceHub\get_sort_options();
                    $current_sort = $filters['sort'] !== '' ? $filters['sort'] : 'newest';
                    ?>
                    <form method="get" action="<?php echo esc_url($form_action); ?>" class="field flex-row items-center gap-3" style="display: flex;">
                        <?php foreach (['service_search', 'service_type', 'service_category', 'service_machine'] as $passthrough) : ?>
                            <?php if (!empty($_GET[$passthrough])) : ?>
                                <input type="hidden" name="<?php echo esc_attr($passthrough); ?>" value="<?php echo esc_attr(\Standard\ServiceHub\get_query_value($passthrough, $passthrough === 'service_search' ? 'text' : 'key')); ?>">
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <label for="service-sort" class="field-label whitespace-nowrap">
                            <?php esc_html_e('Sort', 'standard'); ?>
                        </label>
                        <select id="service-sort" name="service_sort" class="field-select" onchange="this.form.submit()" style="min-width: 12rem;">
                            <?php foreach ($sort_options as $key => $option) : ?>
                                <option value="<?php echo esc_attr($key); ?>" <?php selected($current_sort, $key); ?>>
                                    <?php echo esc_html($option['label']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <noscript>
                            <button type="submit" class="btn btn-sm btn-secondary"><?php esc_html_e('Apply', 'standard'); ?></button>
                        </noscript>
                    </form>
                </div>

                <?php if ($service_query->have_posts()) : ?>
                    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <?php while ($service_query->have_posts()) : $service_query->the_post(); ?>
                            <?php get_template_part('templates/parts/card-post'); ?>
                        <?php endwhile; ?>
                    </div>

                    <?php
                    \Standard\Walkers\Pagination::render($service_query);
                    \wp_reset_postdata();
                    ?>
                <?php else : ?>
                    <div class="border-t border-blue-200 pt-12">
                        <div class="grid gap-4 max-w-xl">
                            <span class="font-mono font-medium uppercase tracking-wider text-red" style="font-size: var(--text-caption);">
                                <?php esc_html_e('No Matches', 'standard'); ?>
                            </span>
                            <h3 class="font-mono font-medium text-blue-900" style="font-size: var(--text-heading-sm);">
                                <?php esc_html_e('Nothing matches those filters.', 'standard'); ?>
                            </h3>
                            <p class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                                <?php esc_html_e('Try a broader search, remove a filter, or talk to the service team directly.', 'standard'); ?>
                            </p>
                            <div class="mt-2 flex flex-wrap gap-3">
                                <a href="<?php echo esc_url($form_action); ?>" class="btn btn-md btn-secondary">
                                    <?php esc_html_e('Clear filters', 'standard'); ?>
                                </a>
                                <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-md btn-ghost">
                                    <?php esc_html_e('Contact support', 'standard'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
    get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Still need a hand?', 'standard'),
        'text'            => __('Our service team has been on the other end of the phone for more than 30 years. If the answer is not in the library, call us.', 'standard'),
        'cta_primary'     => __('Talk to a service specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'service-hub-closer-title',
    ]);
    ?>
</main>

<?php
get_footer();
