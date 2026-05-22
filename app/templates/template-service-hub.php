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
$machine_tags = get_terms_for_service_content('post_tag', 36);
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/');
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== '';

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

    <section class="border-b border-blue-200" aria-labelledby="service-hub-filters-title">
        <div class="container py-8 lg:py-10">
            <div class="mb-6 flex items-center justify-between gap-4">
                <h2 id="service-hub-filters-title" class="font-mono font-medium uppercase tracking-wider text-blue-500" style="font-size: var(--text-caption);">
                    <?php esc_html_e('Filter Service Content', 'standard'); ?>
                </h2>
                <?php if ($has_filters) : ?>
                    <a href="<?php echo esc_url($form_action); ?>" class="btn btn-sm btn-ghost">
                        <?php esc_html_e('Reset', 'standard'); ?>
                    </a>
                <?php endif; ?>
            </div>

            <form
                class="grid gap-4 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-[1.4fr_repeat(3,1fr)_auto] xl:items-end"
                method="get"
                action="<?php echo esc_url($form_action); ?>"
            >
                <div class="field md:col-span-2 lg:col-span-4 xl:col-span-1">
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

                <div class="field">
                    <label for="service-type" class="field-label">
                        <?php esc_html_e('Type', 'standard'); ?>
                    </label>
                    <select id="service-type" name="service_type" class="field-select">
                        <option value=""><?php esc_html_e('All types', 'standard'); ?></option>
                        <?php foreach ($post_type_options as $post_type => $option) : ?>
                            <option value="<?php echo esc_attr($post_type); ?>" <?php selected($filters['type'], $post_type); ?>>
                                <?php
                                echo esc_html(sprintf(
                                    /* translators: %1$s content type label, %2$d number of service items. */
                                    __('%1$s (%2$d)', 'standard'),
                                    $option['label'],
                                    $post_type_counts[$post_type] ?? 0
                                ));
                                ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="service-category" class="field-label">
                        <?php esc_html_e('Category', 'standard'); ?>
                    </label>
                    <select id="service-category" name="service_category" class="field-select">
                        <option value=""><?php esc_html_e('All categories', 'standard'); ?></option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($filters['category'], $category->slug); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="service-machine" class="field-label">
                        <?php esc_html_e('Machine', 'standard'); ?>
                    </label>
                    <select id="service-machine" name="service_machine" class="field-select">
                        <option value=""><?php esc_html_e('All machines', 'standard'); ?></option>
                        <?php foreach ($machine_tags as $machine_tag) : ?>
                            <option value="<?php echo esc_attr($machine_tag->slug); ?>" <?php selected($filters['machine'], $machine_tag->slug); ?>>
                                <?php echo esc_html($machine_tag->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-4 xl:col-span-1">
                    <button type="submit" class="btn btn-primary w-full md:w-auto">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="container section-compact" aria-labelledby="service-hub-results-title">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <p id="service-hub-results-title" class="font-mono font-medium uppercase tracking-wider text-blue-700" style="font-size: var(--text-caption);">
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
