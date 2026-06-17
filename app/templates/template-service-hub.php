<?php
/**
 * Template Name: Service Hub
 *
 * The Service Hub: the support gateway for NTM machine owners. A drenched
 * dark hero + category-grouped machine directory as the primary wayfinding
 * (the machine is the entry), a "service content" link strip, a specialist
 * band, a live latest-docs-and-videos block, and the full search library.
 *
 * Dark cinematic hero, then a light spec-sheet workspace below. Hairline
 * structural borders carry the seams (DESIGN.md §8). Shares its query layer
 * with the machine mini-pages and search (Standard\ServiceHub).
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
// 9 results per page (3 rows of the 3-up grid); pagination handles the rest.
$service_query = get_results_query($filters, $paged, 9);
$post_type_options = get_post_type_options();
$form_action = get_permalink() ?: \Standard\Url\internal('/service-hub/');
$has_filters = $filters['search'] !== ''
    || $filters['type'] !== ''
    || $filters['category'] !== ''
    || $filters['machine'] !== ''
    || $filters['sort'] !== '';

$service_form_id = 'service-hub-form';

// Only offer Resource Type options that actually have content — a 0-result
// type just dumps the user on the empty state. Counts come from the cached
// get_post_type_counts(). Always keep the currently-active type even if its
// count reads 0, so the user's own filter never vanishes from the UI.
$type_counts = \Standard\ServiceHub\get_post_type_counts();
$type_choice_options = ['' => __('All types', 'standard')];
foreach ($post_type_options as $post_type => $option) {
    if (($type_counts[$post_type] ?? 0) < 1 && $filters['type'] !== $post_type) {
        continue;
    }
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

// Include dormant machines: this is a support hub, not the sales catalog.
// Owners of superseded models (e.g. SSQ II) still need their service content,
// so they belong in the directory. The router (service-hub-machines.php)
// likewise resolves dormant slugs so the cards don't 404.
$machine_categories = \Standard\MachinesData\get_machine_categories(true);

// UNIQ Controller resources for the dedicated UNIQ section. Reuses the same
// curated {docs, videos} data that powers /machines/uniq-control-system/, so
// the two surfaces stay in sync. Each row is {url, kind, label}. A focused
// UNIQ block (vs a generic "latest docs/videos" list) avoids duplicating the
// search/content firehose below, which already surfaces newest service content.
$uniq_resources = \Standard\MachinesData\get_uniq_resources();
$uniq_docs      = $uniq_resources['docs'] ?? [];
$uniq_videos    = $uniq_resources['videos'] ?? [];

get_header();
?>

<main id="primary">

    <?php /* Band 1 — Hero. Shared hero-category part (same as /machines): sr-only h1 (WP
            title, SEO), visible h2 headline, blue primary CTA, and a 16:9 Wistia video panel
            on the right (native player, embedded inline). No meta rail (support page, not
            marketing). */ ?>
    <?php
    get_template_part('templates/parts/hero-category', null, [
        'section_id' => 'service-hub-hero',
        'content'    => [
            'kicker'           => __('Service Hub // Owner Support', 'standard'),
            'title'            => __('Everything for your<br class="hidden lg:inline"> NTM machine.', 'standard'),
            'subtitle'         => __('Manuals, troubleshooting, parts, and videos for every machine you run. Backed by the people who built them.', 'standard'),
            'cta_primary'      => __('Find your machine', 'standard'),
            'cta_primary_url'  => '#service-hub-machines',
            'cta_primary_icon' => 'arrow-down',
            'video'            => 'https://fast.wistia.net/embed/iframe/jxmgaicen7?videoFoam=true',
        ],
    ]);
    ?>

    <?php /* Band 2 — Category-grouped machine directory. Light. Compact horizontal cards; pick your machine, get its support content.
            id="service-hub-machines" is the hero "Find your machine" CTA target;
            smooth-scroll + header offset are global (base.css scroll-behavior /
            scroll-padding-top). */ ?>
    <section id="service-hub-machines" class="bg-blue-50 border-t border-blue-200 scroll-mt-24" aria-labelledby="service-hub-machines-title">
        <div class="container section">
            <h2 id="service-hub-machines-title" class="font-sans font-medium text-heading text-blue-900 m-0 mb-2" data-reveal="fade">
                <?php esc_html_e('Find your machine', 'standard'); ?>
            </h2>
            <p class="font-sans text-blue-600 max-w-2xl m-0 mb-10" style="font-size: var(--text-body); line-height: var(--leading-body);">
                <?php esc_html_e('Pick your machine to open its service content: manuals, troubleshooting, parts, and videos for that exact model.', 'standard'); ?>
            </p>

            <div class="grid gap-10 lg:gap-12">
                <?php foreach ($machine_categories as $category) : ?>
                    <?php
                    $cat_label    = (string) ($category['label'] ?? '');
                    $cat_machines = $category['machines'] ?? [];
                    if (empty($cat_machines)) {
                        continue;
                    }
                    ?>
                    <div class="grid gap-4">
                        <h3 class="font-mono font-medium uppercase tracking-wider text-blue-700 m-0" style="font-size: var(--text-body);">
                            <?php echo esc_html($cat_label); ?>
                        </h3>
                        <div class="stagger grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            <?php foreach ($cat_machines as $machine) : ?>
                                <?php get_template_part('templates/parts/service-hub/machine-photo-card', null, ['machine' => $machine, 'compact' => true]); ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php /* Band 3 — Service content for every machine. Light. Hairline-divided row of links.
            No band border-t: the white-on-blue-50 color change carries the seam, and
            this section reads as a continuation of the directory above. */ ?>
    <section class="bg-white" aria-labelledby="service-hub-includes-title">
        <div class="container section-compact">
            <h2 id="service-hub-includes-title" class="font-mono font-medium uppercase tracking-wider text-blue-900 m-0 mb-8" style="font-size: var(--text-heading-sm);">
                <?php esc_html_e('Service content for every machine', 'standard'); ?>
            </h2>
            <?php
            // Each card links to a real filtered view. Manuals / Articles / Videos
            // jump to this page's search pre-filtered to that service post type
            // (service_type=<type>) and anchored to the results. Owner Resources
            // points at the standalone /owner-resources/ page.
            $includes = [
                [
                    'icon'  => 'file-text',
                    'label' => __('Manuals', 'standard'),
                    'desc'  => __('Operation, setup, and maintenance documents.', 'standard'),
                    'url'   => add_query_arg('service_type', 'manual', $form_action) . '#search',
                ],
                [
                    'icon'  => 'folder',
                    'label' => __('Owner Resources', 'standard'),
                    'desc'  => __('Guides and resources for NTM machine owners.', 'standard'),
                    'url'   => \Standard\Url\internal('/owner-resources/'),
                ],
                [
                    'icon'  => 'file-text',
                    'label' => __('Service Articles', 'standard'),
                    'desc'  => __('How-tos and fixes from the service team.', 'standard'),
                    'url'   => add_query_arg('service_type', 'post', $form_action) . '#search',
                ],
                [
                    'icon'  => 'play',
                    'label' => __('Service Videos', 'standard'),
                    'desc'  => __('Setup, operation, and how-to clips.', 'standard'),
                    'url'   => add_query_arg('service_type', 'video', $form_action) . '#search',
                ],
            ];
            ?>
            <?php /* Vertical dividers only — no outer top/bottom border on the row.
                    border-l on the wrapper + border-r per card draws the column
                    separators between the four cards; the top and bottom edges of
                    the box are intentionally open. */ ?>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 border-l border-blue-200">
                <?php foreach ($includes as $item) : ?>
                    <a href="<?php echo esc_url($item['url']); ?>"
                       class="group flex flex-col gap-2 border-r border-blue-200 p-6 no-underline transition-colors duration-200 hover:bg-blue-50">
                        <?php icon($item['icon'], ['class' => 'w-5 h-5 text-blue-500', 'aria-hidden' => 'true']); ?>
                        <span class="flex items-center gap-1.5 font-mono font-medium uppercase tracking-wider text-blue-900 transition-colors duration-200 group-hover:text-blue-500" style="font-size: var(--text-caption);">
                            <?php echo esc_html($item['label']); ?>
                            <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
                        </span>
                        <span class="font-sans text-blue-600" style="font-size: var(--text-body); line-height: var(--leading-body);">
                            <?php echo esc_html($item['desc']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php /* Band 4 — Talk to a specialist. Light blue-50. Human-expertise proof + request CTA.
            No border-t: the blue-50-on-white shift is the seam against the section above. */ ?>
    <section class="bg-blue-50" aria-labelledby="service-hub-specialist-title">
        <div class="container section-compact">
            <div class="grid gap-6 lg:grid-cols-[2fr_1fr] lg:items-center">
                <div class="grid gap-4 max-w-2xl">
                    <span class="section-eyebrow flex items-center gap-2">
                        <span class="inline-block h-1 w-1 bg-red" aria-hidden="true"></span>
                        <?php esc_html_e('Talk To Us', 'standard'); ?>
                    </span>
                    <h2 id="service-hub-specialist-title" class="font-sans font-medium text-heading lg:text-heading-lg text-blue-900 leading-tight m-0" data-reveal="fade">
                        <?php esc_html_e('Your rollforming support team.', 'standard'); ?>
                    </h2>
                    <p class="font-sans text-blue-600 m-0" style="font-size: var(--text-body); line-height: var(--leading-body);">
                        <?php esc_html_e('NTM service is run by the people who build the machines: more than 30 years of portable rollforming, machines in 40+ countries, and specialists who know your roll former inside out. If the answer is not in the library, open a service request and tell us what you need.', 'standard'); ?>
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

    <?php /* Band 4.5 — FAQ. The most-asked owner questions, before the search
            firehose, so the common stuff is answered without a ticket. */ ?>
    <?php get_template_part('templates/parts/service-hub/faq'); ?>

    <?php /* Band 4.6 — UNIQ Automatic Control System. The controller that runs the
            SSQ II / SSQ3 / WAV has its own deep doc + video library and isn't a machine
            in the gallery, so it gets a dedicated section. Two-column hairline list
            (the /machines/uniq-control-system/ pattern), fed by the SAME curated
            get_uniq_resources() data so the two surfaces stay in sync. UNIQ-specific,
            so it doesn't duplicate the newest-content search firehose below. */ ?>
    <?php if (!empty($uniq_docs) || !empty($uniq_videos)) : ?>
    <section class="bg-blue-50 border-t border-blue-200" aria-labelledby="service-hub-uniq-title">
        <div class="container section-compact">
            <?php /* Two-column header: lede on the left, the "full page" link in the
                    second column opposite it, bottom-aligned with the lede so it sits
                    beside the description rather than below the whole box. */ ?>
            <div class="grid gap-6 mb-8 lg:grid-cols-[2fr_1fr] lg:items-end">
                <div class="grid gap-4 max-w-3xl">
                    <span class="section-eyebrow flex items-center gap-2">
                        <span class="inline-block h-1 w-1 bg-red" aria-hidden="true"></span>
                        <?php esc_html_e('Control System', 'standard'); ?>
                    </span>
                    <h2 id="service-hub-uniq-title" class="font-sans font-medium text-heading text-blue-900 leading-tight m-0">
                        <?php esc_html_e('UNIQ Automatic Control System', 'standard'); ?>
                    </h2>
                    <p class="font-sans text-blue-600 m-0" style="font-size: var(--text-body); line-height: var(--leading-body);">
                        <?php esc_html_e('The touchscreen brain for your SSQ II, SSQ3, and WAV rollformers. Field-update instructions, the supplement manual, and the full video library for installing, running, and upgrading UNIQ.', 'standard'); ?>
                    </p>
                </div>
                <p class="m-0 lg:justify-self-end">
                    <a href="<?php echo esc_url(\Standard\Url\internal('/machines/uniq-control-system/')); ?>"
                       class="group inline-flex items-center gap-2 font-mono uppercase tracking-mono-meta text-blue-500 hover:text-blue-700 transition-colors duration-150 no-underline" style="font-size: var(--text-body);">
                        <?php esc_html_e('Full UNIQ control system page', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4 transition-transform duration-150 group-hover:translate-x-1']); ?>
                    </a>
                </p>
            </div>

            <!-- Columns stretch to equal height (default items-stretch) so the
                 center divider (the docs column's md:border-r) runs the full box
                 height. items-start was leaving the shorter Documentation column
                 — and its right border — ending early, which broke the outer box
                 (uneven bottom edge + a dangling divider). Equal height keeps the
                 hairline box square. -->
            <div class="grid grid-cols-1 md:grid-cols-2 bg-white border border-blue-200">

                <?php if (!empty($uniq_docs)) : ?>
                    <div class="flex flex-col <?php echo !empty($uniq_videos) ? 'border-b border-blue-200 md:border-b-0 md:border-r' : ''; ?>">
                        <h3 class="font-mono font-medium uppercase tracking-mono-label text-blue-500 p-6 lg:p-8 border-b border-blue-200" style="font-size: var(--text-caption);">
                            <?php esc_html_e('Documentation', 'standard'); ?>
                        </h3>
                        <?php /* grow so the shorter column fills the equal-height box;
                                no mid-column line where the list ends. */ ?>
                        <ul class="grow">
                            <?php foreach ($uniq_docs as $i => $doc) :
                                $is_last = ($i === count($uniq_docs) - 1);
                            ?>
                                <li class="<?php echo $is_last ? '' : 'border-b border-blue-200'; ?>">
                                    <a href="<?php echo esc_url(\Standard\Url\internal($doc['url'])); ?>"
                                       class="group flex items-start gap-4 px-6 py-5 lg:px-8 lg:py-6 transition-colors duration-150 hover:bg-blue-50 no-underline">
                                        <span class="font-mono uppercase tracking-mono-meta text-blue-400 shrink-0 min-w-16 pt-1" style="font-size: 10px;">
                                            <?php echo esc_html($doc['kind']); ?>
                                        </span>
                                        <span class="flex-1 font-sans text-base text-blue-700 group-hover:text-blue-500 transition-colors">
                                            <?php echo esc_html($doc['label']); ?>
                                        </span>
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 mt-1.5 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <?php if (!empty($uniq_videos)) : ?>
                    <div class="flex flex-col">
                        <h3 class="font-mono font-medium uppercase tracking-mono-label text-blue-500 p-6 lg:p-8 border-b border-blue-200" style="font-size: var(--text-caption);">
                            <?php esc_html_e('Video Tutorials', 'standard'); ?>
                        </h3>
                        <ul class="grow">
                            <?php foreach ($uniq_videos as $i => $video) :
                                $is_last = ($i === count($uniq_videos) - 1);
                            ?>
                                <li class="<?php echo $is_last ? '' : 'border-b border-blue-200'; ?>">
                                    <a href="<?php echo esc_url(\Standard\Url\internal($video['url'])); ?>"
                                       class="group flex items-start gap-4 px-6 py-5 lg:px-8 lg:py-6 transition-colors duration-150 hover:bg-blue-50 no-underline">
                                        <span class="font-mono uppercase tracking-mono-meta text-blue-400 shrink-0 min-w-16 pt-1" style="font-size: 10px;">
                                            <?php echo esc_html($video['kind']); ?>
                                        </span>
                                        <span class="flex-1 font-sans text-base text-blue-700 group-hover:text-blue-500 transition-colors">
                                            <?php echo esc_html($video['label']); ?>
                                        </span>
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-400 shrink-0 mt-1.5 transition-transform duration-150 group-hover:translate-x-1 group-hover:text-blue-500']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php /* Band 5 — Search the content library. The keyword input leads the section
            directly above the results (heading + search bar as one block). */ ?>
    <section id="search" class="bg-white border-t border-blue-200" aria-labelledby="service-hub-search-heading">
        <div class="container section-compact">
            <div class="grid gap-5 md:flex md:items-end md:justify-between md:gap-8">
                <h2 id="service-hub-search-heading" class="font-mono font-medium uppercase tracking-wider text-blue-900 m-0 shrink-0" style="font-size: var(--text-heading-sm);">
                    <?php esc_html_e('Search the service content library', 'standard'); ?>
                </h2>

                <form
                    id="<?php echo esc_attr($service_form_id); ?>"
                    method="get"
                    action="<?php echo esc_url($form_action); ?>"
                    class="grid gap-3 sm:grid-cols-[minmax(0,1fr)_auto_auto] sm:items-end md:w-full md:max-w-xl"
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
        </div>

        <section class="container section-compact pt-0" aria-labelledby="service-hub-results-title">
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
        </section>
    </section>

    <?php
    get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Still need a hand?', 'standard'),
        'text'            => __('Pick up the phone. A specialist who knows your machine will too.', 'standard'),
        'cta_primary'     => __('Talk to a service specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'service-hub-closer-title',
    ]);
    ?>

</main>

<?php
get_footer();
