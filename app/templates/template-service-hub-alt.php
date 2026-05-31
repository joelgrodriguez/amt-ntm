<?php
/**
 * Template Name: Service Hub (Alt)
 *
 * Alternate Service Hub landing. Reframes the hub as a high-end service
 * option for NTM machine owners: a drenched dark hero + category-grouped
 * full-bleed machine gallery as the primary wayfinding (the machine IS the
 * entry), then a light "what you get" strip, a specialist band, and the
 * existing search relocated verbatim. Parallel to template-service-hub.php
 * for A/B; shares its search query plumbing (Standard\ServiceHub).
 *
 * Dark cinematic top (hero + gallery), light spec-sheet workspace below.
 * Hairline structural borders carry the seams (DESIGN.md §8).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_filter_groups;
use function Standard\ServiceHub\get_active_filters;
use function Standard\ServiceHub\get_post_type_label;
use function Standard\ServiceHub\get_post_type_options;
use function Standard\ServiceHub\get_results_query;

$filters = get_active_filters();
$paged = max(1, (int) get_query_var('paged'), (int) get_query_var('page'));
$service_query = get_results_query($filters, $paged);
$post_type_options = get_post_type_options();
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/');
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== '';

$service_form_id = 'service-hub-alt-form';

$type_choice_options = ['' => __('All types', 'standard')];
foreach ($post_type_options as $post_type => $option) {
    $type_choice_options[$post_type] = (string) $option['label'];
}

$service_groups = get_filter_groups([
    'category' => $filters['category'],
    'type'     => $filters['type'],
    'machine'  => $filters['machine'],
], [
    'names' => [
        'category' => 'service_category',
        'type'     => 'service_type',
        'machine'  => 'service_machine',
    ],
    'type_options' => $type_choice_options,
]);

$machine_categories = \Standard\MachinesData\get_machine_categories(false);

get_header();
?>

<main id="primary">

    <?php /* Band 1 — Drenched hero. Dark stage: the promise, pointing down to the gallery, with a single service-request escape hatch. */ ?>
    <header class="pattern-dot-grid pattern-dot-grid--dark bg-blue-900">
        <div class="container section">
            <div class="grid gap-6 max-w-3xl">
                <span class="section-eyebrow flex items-center gap-2 text-blue-300">
                    <span class="inline-block h-1 w-1 bg-red" aria-hidden="true"></span>
                    <?php esc_html_e('Service Hub', 'standard'); ?>
                </span>

                <h1 class="font-sans font-medium text-heading-lg lg:text-display text-white leading-tight tracking-tight">
                    <?php esc_html_e('Your machine. Everything it needs. One place.', 'standard'); ?>
                </h1>

                <p class="font-sans text-blue-200 max-w-2xl" style="font-size: var(--text-body); line-height: var(--leading-body);">
                    <?php esc_html_e('Find your machine below for its manuals, troubleshooting, parts, and videos. Or talk to the people who have built and backed these machines since 1991.', 'standard'); ?>
                </p>

                <p class="font-mono uppercase tracking-wider text-blue-300 m-0" style="font-size: var(--text-caption);">
                    <?php esc_html_e('Need to talk to us?', 'standard'); ?>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/service-hub/request/')); ?>" class="text-blue-200 underline underline-offset-2 transition-colors duration-200 hover:text-white">
                        <?php esc_html_e('Open a service request', 'standard'); ?>
                    </a>
                </p>
            </div>
        </div>
    </header>

    <?php /* Band 2 — Category-grouped machine gallery. Dark stage continues; the machine is the door. */ ?>
    <section class="bg-blue-900 border-t border-blue-800" aria-labelledby="service-hub-alt-machines-title">
        <div class="container section">
            <h2 id="service-hub-alt-machines-title" class="font-sans font-medium text-heading text-white m-0 mb-2">
                <?php esc_html_e('Find your machine', 'standard'); ?>
            </h2>
            <p class="font-sans text-blue-300 max-w-2xl m-0 mb-10" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php esc_html_e('Pick your machine to open its service content: manuals, troubleshooting, parts, and videos for that exact model.', 'standard'); ?>
            </p>

            <div class="grid gap-12 lg:gap-16">
                <?php foreach ($machine_categories as $category) : ?>
                    <?php
                    $cat_label    = (string) ($category['label'] ?? '');
                    $cat_machines = $category['machines'] ?? [];
                    if (empty($cat_machines)) {
                        continue;
                    }
                    ?>
                    <div class="grid gap-6">
                        <h3 class="font-mono font-medium uppercase tracking-wider text-blue-300 m-0" style="font-size: var(--text-caption);">
                            <?php echo esc_html($cat_label); ?>
                        </h3>
                        <div class="stagger grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($cat_machines as $machine) : ?>
                                <?php get_template_part('templates/parts/service-hub/machine-photo-card', null, ['machine' => $machine]); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php /* Band 3 — What every machine page gives you. Light. Hairline-divided row, no card grid. */ ?>
    <section class="bg-white border-t border-blue-200" aria-labelledby="service-hub-alt-includes-title">
        <div class="container section-compact">
            <h2 id="service-hub-alt-includes-title" class="font-mono font-medium uppercase tracking-wider text-blue-900 m-0 mb-8" style="font-size: var(--text-heading-sm);">
                <?php esc_html_e('Behind every machine', 'standard'); ?>
            </h2>
            <?php
            $includes = [
                ['icon' => 'file-text', 'label' => __('Manuals', 'standard'),            'desc' => __('Operation, setup, and maintenance docs.', 'standard')],
                ['icon' => 'settings',  'label' => __('Troubleshooting', 'standard'),     'desc' => __('Fixes and answers from the service team.', 'standard')],
                ['icon' => 'download',  'label' => __('Parts & footprints', 'standard'),  'desc' => __('Diagrams, downloads, and footprints.', 'standard')],
                ['icon' => 'play',      'label' => __('Videos', 'standard'),              'desc' => __('Setup, operation, and how-to clips.', 'standard')],
            ];
            ?>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 border-t border-l border-blue-200">
                <?php foreach ($includes as $item) : ?>
                    <div class="flex flex-col gap-2 border-b border-r border-blue-200 p-6">
                        <?php icon($item['icon'], ['class' => 'w-5 h-5 text-blue-500', 'aria-hidden' => 'true']); ?>
                        <span class="font-mono font-medium uppercase tracking-wider text-blue-900" style="font-size: var(--text-caption);">
                            <?php echo esc_html($item['label']); ?>
                        </span>
                        <span class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                            <?php echo esc_html($item['desc']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php /* Band 4 — Talk to a specialist. Light blue-50. Human-expertise proof + request CTA. */ ?>
    <section class="bg-blue-50 border-t border-blue-200" aria-labelledby="service-hub-alt-specialist-title">
        <div class="container section-compact">
            <div class="grid gap-6 lg:grid-cols-[2fr_1fr] lg:items-center">
                <div class="grid gap-4 max-w-2xl">
                    <span class="section-eyebrow flex items-center gap-2">
                        <span class="inline-block h-1 w-1 bg-red" aria-hidden="true"></span>
                        <?php esc_html_e('Service Team', 'standard'); ?>
                    </span>
                    <h2 id="service-hub-alt-specialist-title" class="font-sans font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight m-0">
                        <?php esc_html_e('Real specialists. On the phone since 1991.', 'standard'); ?>
                    </h2>
                    <p class="font-sans text-blue-600 m-0" style="font-size: var(--text-body); line-height: var(--leading-body);">
                        <?php esc_html_e('The same company that builds these machines backs them. More than 30 years of portable rollforming, machines in 40+ countries, and a service team that has answered the hard questions since the first SSP shipped. If the answer is not in the library, tell us what you need.', 'standard'); ?>
                    </p>
                </div>
                <div class="flex flex-col gap-3">
                    <a href="<?php echo esc_url(\Standard\Url\internal('/service-hub/request/')); ?>" class="btn btn-primary w-full">
                        <?php esc_html_e('Open a service request', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-secondary w-full">
                        <?php icon('phone', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Call the service team', 'standard'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php /* Band 5 — Full search, relocated verbatim from template-service-hub.php. Query logic unchanged. */ ?>
    <section id="search" class="bg-white border-t border-blue-200" aria-labelledby="service-hub-alt-search-heading">
        <div class="container section-compact">
            <h2 id="service-hub-alt-search-heading" class="font-mono font-medium uppercase tracking-wider text-blue-900 m-0" style="font-size: var(--text-heading-sm);">
                <?php esc_html_e('Search the library', 'standard'); ?>
            </h2>
        </div>

        <section class="border-b border-blue-200">
            <div class="container py-6 lg:py-8">
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

                    <button type="submit" class="btn btn-primary w-full md:w-auto h-11!">
                        <?php esc_html_e('Search', 'standard'); ?>
                    </button>

                    <?php if ($has_filters) : ?>
                        <a href="<?php echo esc_url($form_action); ?>" class="btn btn-ghost w-full md:w-auto h-11!">
                            <?php esc_html_e('Reset', 'standard'); ?>
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </section>

        <section class="container section-compact" aria-labelledby="service-hub-alt-results-title">
            <h2 id="service-hub-alt-results-title" class="sr-only">
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
    </section>

    <?php
    get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Still need a hand?', 'standard'),
        'text'            => __('Pick up the phone. A specialist who knows your machine will too.', 'standard'),
        'cta_primary'     => __('Talk to a service specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'service-hub-alt-closer-title',
    ]);
    ?>

</main>

<?php
get_footer();
