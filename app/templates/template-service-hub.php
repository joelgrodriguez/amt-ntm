<?php
/**
 * Template Name: Service Hub
 *
 * Service-only landing page with search and filters.
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
use function Standard\ServiceHub\get_service_count;
use function Standard\ServiceHub\get_terms_for_service_content;

$filters = get_active_filters();
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$service_query = get_results_query($filters, $paged);
$post_type_options = get_post_type_options();
$post_type_counts = get_post_type_counts();
$categories = get_terms_for_service_content('category', 24);
$machine_tags = get_terms_for_service_content('post_tag', 36);
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/');
$total_service_count = get_service_count();
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== '';

get_header();
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-6 lg:py-12">
    <header class="container mb-6 lg:mb-10">
        <div class="grid gap-4 max-w-4xl">
            <span class="text-caption font-mono uppercase tracking-widest text-blue-500">
                <?php esc_html_e('Service Hub', 'standard'); ?>
            </span>
            <h1 class="font-mono font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight">
                <?php esc_html_e('Service and Support Content', 'standard'); ?>
            </h1>
            <p class="text-blue-600 text-base lg:text-lg leading-relaxed max-w-3xl">
                <?php esc_html_e('Manuals, troubleshooting articles, support videos, downloads, and related service material in one filtered view.', 'standard'); ?>
            </p>
        </div>
    </header>

    <section class="container mb-8 lg:mb-10" aria-labelledby="service-hub-filters-title">
        <div class="border border-blue-200 bg-white p-4 lg:p-6">
            <h2 id="service-hub-filters-title" class="sr-only">
                <?php esc_html_e('Filter service content', 'standard'); ?>
            </h2>

            <form class="grid gap-4 lg:grid-cols-[minmax(0,1.4fr)_repeat(3,minmax(0,1fr))_auto] lg:items-end" method="get" action="<?php echo esc_url($form_action); ?>">
                <div class="grid gap-2">
                    <label for="service-search" class="text-sm font-medium text-blue-900">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </label>
                    <input
                        id="service-search"
                        name="service_search"
                        type="search"
                        value="<?php echo esc_attr($filters['search']); ?>"
                        class="w-full min-h-11 border border-blue-200 bg-white px-3 py-2 text-blue-900 placeholder:text-blue-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                        placeholder="<?php esc_attr_e('Machine, manual, problem, topic...', 'standard'); ?>"
                    >
                </div>

                <div class="grid gap-2">
                    <label for="service-type" class="text-sm font-medium text-blue-900">
                        <?php esc_html_e('Type', 'standard'); ?>
                    </label>
                    <select id="service-type" name="service_type" class="w-full min-h-11 border border-blue-200 bg-white px-3 py-2 text-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
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

                <div class="grid gap-2">
                    <label for="service-category" class="text-sm font-medium text-blue-900">
                        <?php esc_html_e('Category', 'standard'); ?>
                    </label>
                    <select id="service-category" name="service_category" class="w-full min-h-11 border border-blue-200 bg-white px-3 py-2 text-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <option value=""><?php esc_html_e('All categories', 'standard'); ?></option>
                        <?php foreach ($categories as $category) : ?>
                            <option value="<?php echo esc_attr($category->slug); ?>" <?php selected($filters['category'], $category->slug); ?>>
                                <?php echo esc_html($category->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid gap-2">
                    <label for="service-machine" class="text-sm font-medium text-blue-900">
                        <?php esc_html_e('Machine', 'standard'); ?>
                    </label>
                    <select id="service-machine" name="service_machine" class="w-full min-h-11 border border-blue-200 bg-white px-3 py-2 text-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <option value=""><?php esc_html_e('All machines', 'standard'); ?></option>
                        <?php foreach ($machine_tags as $machine_tag) : ?>
                            <option value="<?php echo esc_attr($machine_tag->slug); ?>" <?php selected($filters['machine'], $machine_tag->slug); ?>>
                                <?php echo esc_html($machine_tag->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center border border-blue-900 bg-blue-900 px-4 py-2 font-mono text-sm font-medium uppercase tracking-widest text-white hover:bg-blue-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </button>
                    <?php if ($has_filters) : ?>
                        <a href="<?php echo esc_url($form_action); ?>" class="inline-flex min-h-11 items-center justify-center border border-blue-200 px-4 py-2 font-mono text-sm font-medium uppercase tracking-widest text-blue-700 no-underline hover:border-blue-500 hover:text-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            <?php esc_html_e('Reset', 'standard'); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </section>

    <section class="container" aria-labelledby="service-hub-results-title">
        <div class="mb-6 flex flex-col gap-2 border-b border-blue-200 pb-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h2 id="service-hub-results-title" class="text-xl lg:text-2xl font-semibold text-blue-900">
                    <?php esc_html_e('Service Content', 'standard'); ?>
                </h2>
                <p class="text-sm text-blue-600">
                    <?php
                    printf(
                        /* translators: %1$d visible result count, %2$d total service content count. */
                        esc_html__('%1$d results from %2$d service items', 'standard'),
                        (int) $service_query->found_posts,
                        $total_service_count
                    );
                    ?>
                </p>
            </div>

            <?php if ($filters['type'] !== '') : ?>
                <span class="font-mono text-caption uppercase tracking-widest text-blue-500">
                    <?php echo esc_html(get_post_type_label($filters['type'])); ?>
                </span>
            <?php endif; ?>
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
            <div class="border border-blue-200 bg-white p-8 text-center">
                <h3 class="text-xl font-semibold text-blue-900">
                    <?php esc_html_e('No service content found', 'standard'); ?>
                </h3>
                <p class="mt-2 text-blue-600">
                    <?php esc_html_e('Try a broader search or remove one filter.', 'standard'); ?>
                </p>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php
get_footer();
