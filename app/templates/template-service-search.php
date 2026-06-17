<?php
/**
 * Template Name: Service Search
 *
 * The dedicated service-content search results page (/service-hub/search/).
 * Stripped to the essentials: a search header, a filter sidebar, the results
 * grid, and pagination. No hero firehose, no machine directory, no UNIQ band —
 * the landing page (template-service-hub.php) hands off here so a search lands
 * on results instead of reloading the whole hub.
 *
 * Every result is scoped to content_department = service-repair by the shared
 * Standard\ServiceHub query layer; the sidebar options are scoped to the same.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\ServiceHub\get_active_filters;
use function Standard\ServiceHub\get_filter_groups;
use function Standard\ServiceHub\get_post_type_label;
use function Standard\ServiceHub\get_post_type_options;
use function Standard\ServiceHub\get_results_query;

$filters = get_active_filters();
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
// 9 results per page (3 rows of the 3-up grid); pagination handles the rest.
$service_query = get_results_query($filters, $paged, 9);
$post_type_options = get_post_type_options();
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/search/');
$service_hub_url = \Standard\Url\internal('/service-hub/');
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== ''
    || $filters['sort'] !== '';

$service_form_id = 'service-search-form';

// Only offer Resource Type options that actually have content — a 0-result
// type just dumps the user on the empty state. Always keep the active type even
// if its count reads 0, so the user's own filter never vanishes.
$type_counts = \Standard\ServiceHub\get_post_type_counts();
$type_choice_options = ['' => __('All types', 'standard')];
foreach ($post_type_options as $post_type => $option) {
    if (($type_counts[$post_type] ?? 0) < 1 && $filters['type'] !== $post_type) {
        continue;
    }
    $type_choice_options[$post_type] = (string) $option['label'];
}

// Machine-first, service-scoped filter groups (Machine, Resource Type, Category).
$service_groups = get_filter_groups($filters, $type_choice_options);

get_header();
?>

<main id="primary">

    <?php /* Search header — the input leads the page; results live below. A back
            link returns to the full Service Hub. */ ?>
    <section class="bg-blue-50 border-b border-blue-200" aria-labelledby="service-search-heading">
        <div class="container section-compact grid gap-6">
            <div class="grid gap-3">
                <a href="<?php echo esc_url($service_hub_url); ?>" class="inline-flex items-center gap-2 font-mono font-medium uppercase tracking-wider text-blue-500 hover:text-blue-700 transition-colors w-fit" style="font-size: var(--text-caption);">
                    <?php icon('arrow-left', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                    <?php esc_html_e('Service Hub', 'standard'); ?>
                </a>
                <h1 id="service-search-heading" class="font-sans font-medium text-heading text-blue-900 leading-tight m-0">
                    <?php esc_html_e('Search the service content library', 'standard'); ?>
                </h1>
                <p class="font-sans text-blue-600 max-w-2xl m-0" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Every manual, troubleshooting article, and video tied to your machine — all service-and-repair content in one place.', 'standard'); ?>
                </p>
            </div>

            <form
                id="<?php echo esc_attr($service_form_id); ?>"
                method="get"
                action="<?php echo esc_url($form_action); ?>"
                class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto_auto] sm:items-end max-w-2xl"
                role="search"
            >
                <div class="field">
                    <label for="service-search" class="sr-only">
                        <?php esc_html_e('Search the service content library', 'standard'); ?>
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

                <button type="submit" class="btn btn-primary w-full sm:w-auto h-11!">
                    <?php esc_html_e('Search', 'standard'); ?>
                </button>

                <?php if ($has_filters) : ?>
                    <a href="<?php echo esc_url($form_action); ?>" class="btn btn-ghost w-full sm:w-auto h-11!">
                        <?php esc_html_e('Reset', 'standard'); ?>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </section>

    <section class="bg-white" aria-labelledby="service-search-results-title">
        <div class="container section-compact">
            <h2 id="service-search-results-title" class="sr-only">
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
                        <form method="get" action="<?php echo esc_url($form_action); ?>" class="field flex flex-row items-center gap-3">
                            <?php foreach (['service_search', 'service_type', 'service_category', 'service_machine'] as $passthrough) : ?>
                                <?php if (!empty($_GET[$passthrough])) : ?>
                                    <input type="hidden" name="<?php echo esc_attr($passthrough); ?>" value="<?php echo esc_attr(\Standard\ServiceHub\get_query_value($passthrough, $passthrough === 'service_search' ? 'text' : 'key')); ?>">
                                <?php endif; ?>
                            <?php endforeach; ?>

                            <label for="service-sort" class="field-label whitespace-nowrap">
                                <?php esc_html_e('Sort', 'standard'); ?>
                            </label>
                            <select id="service-sort" name="service_sort" class="field-select min-w-48" onchange="this.form.submit()">
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
                                <span class="font-mono font-medium uppercase tracking-wider text-blue-500" style="font-size: var(--text-caption);">
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
        </div>
    </section>

    <?php
    get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Still need a hand?', 'standard'),
        'text'            => __('Pick up the phone. A specialist who knows your machine will too.', 'standard'),
        'cta_primary'     => __('Talk to a service specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'service-search-closer-title',
    ]);
    ?>

</main>

<?php
get_footer();
