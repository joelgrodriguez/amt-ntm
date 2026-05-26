<?php
/**
 * The blog landing page template (Learning Center).
 *
 * Displays when a static front page is set and this is the posts page.
 * Features hero with latest content, filtered sections by post type,
 * and category/machine tag filters.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Filters\build_choice_group;
use function Standard\Filters\build_term_choice_group;
use function Standard\LearningCenter\get_content_sections;
use function Standard\LearningCenter\filter_content_sections;
use function Standard\LearningCenter\get_active_filters;
use function Standard\LearningCenter\get_featured_query;
use function Standard\LearningCenter\get_learning_center_url;
use function Standard\LearningCenter\get_section_query;
use function Standard\LearningCenter\get_type_cta;
use function Standard\LearningCenter\get_type_icon;
use function Standard\LearningCenter\get_type_label;
use function Standard\MachinesData\get_machine_post_tags;

get_header();

$filters = get_active_filters();
$featured_query = get_featured_query($filters);

// Curated Learning Center category allowlist. Editors create many
// categories that don't belong on the LC rail (product taxonomies like
// Profiles / Manuals, internal tags like Resources). The curated slug
// list lives in inc/learning-center/config.php so the search sidebar
// and blog archive render the same shortlist.
$categories = \Standard\LearningCenter\get_allowed_categories();

// Restrict the Machine filter to the curated NTM machine catalog
// (SSQ3, SSQ II, SSH, SSR, 5V Crimp, WAV, MACH II, BG7), in that
// canonical order, instead of every post_tag the editors ever made.
$machine_tags = get_machine_post_tags();

$content_sections = filter_content_sections(get_content_sections(), $filters);
$filter_action    = get_learning_center_url();

// Build sidebar filter groups. Each is radio mode (single-select) because
// the query layer (inc/learning-center/queries.php) reads lc_category,
// lc_type, and lc_machine as scalars. An empty-value "All" row per group
// lets users clear the choice without leaving the page.
$lc_form_id = 'lc-filter-form';

$category_options = ['' => __('All categories', 'standard')];
foreach ($categories as $cat) {
    if ($cat instanceof WP_Term) {
        $category_options[$cat->slug] = $cat->name;
    }
}

$type_options = [
    ''         => __('All resources', 'standard'),
    'post'     => __('Articles', 'standard'),
    'video'    => __('Videos', 'standard'),
    'resource' => __('Resources', 'standard'),
    'download' => __('Downloads', 'standard'),
];

$lc_groups = [
    build_choice_group(
        'lc-category',
        __('Category', 'standard'),
        'lc_category',
        $category_options,
        [(string) ($filters['category'] ?? '')],
        [],
        'folder',
        'radio'
    ),
    build_choice_group(
        'lc-type',
        __('Resource Type', 'standard'),
        'lc_type',
        $type_options,
        [(string) ($filters['type'] ?? '')],
        [],
        'file-text',
        'radio'
    ),
];

if (!empty($machine_tags)) {
    $machine_options = ['' => __('All machines', 'standard')];
    foreach ($machine_tags as $tag) {
        if ($tag instanceof WP_Term) {
            $machine_options[$tag->slug] = $tag->name;
        }
    }

    $lc_groups[] = build_choice_group(
        'lc-machine',
        __('Machine', 'standard'),
        'lc_machine',
        $machine_options,
        [(string) ($filters['machine'] ?? '')],
        [],
        'settings',
        'radio'
    );
}

$lc_has_filters = ($filters['category'] ?? '') !== ''
    || ($filters['type'] ?? '') !== ''
    || ($filters['machine'] ?? '') !== '';
?>

<main id="primary">

    <!-- Hero: full fold (100dvh - header). Filter bar peeks below. -->
    <section class="lc-hero pattern-dot-grid border-b border-blue-200">
        <div class="container py-10 lg:py-14 lc-hero__inner">

            <!-- Hero copy -->
            <header class="grid gap-5 lg:gap-6 self-start">
                <span class="text-caption font-mono uppercase tracking-widest text-blue-500">
                    <?php esc_html_e('Learning Center', 'standard'); ?>
                </span>
                <h1 class="font-mono font-medium text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                    <?php esc_html_e('The Rollforming Learning Center', 'standard'); ?>
                </h1>
            </header>

            <!-- Featured Post (fills remaining fold space) -->
            <?php if ($featured_query->have_posts()) : $featured_query->the_post();
                $featured_cta   = get_type_cta((string) get_post_type());
                $featured_label = sprintf(
                    /* translators: %1$s post title, %2$s post-type-specific verb. */
                    __('%1$s. %2$s.', 'standard'),
                    wp_strip_all_tags(get_the_title()),
                    $featured_cta
                );
            ?>
                <?php
                $featured_categories = get_the_category();
                $featured_category   = !empty($featured_categories) ? $featured_categories[0]->name : '';
                $featured_word_count = str_word_count(wp_strip_all_tags((string) get_the_content()));
                $featured_minutes    = max(1, (int) ceil($featured_word_count / 200));
                ?>
                <article class="lc-hero__featured group relative bg-white border border-blue-200 transition-colors duration-200 hover:border-blue-500">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="lc-hero__featured-photo border-b lg:border-b-0 lg:border-r border-blue-200 transition-colors duration-200 group-hover:border-blue-500">
                            <?php the_post_thumbnail('large', [
                                'class'         => 'w-full h-full object-cover',
                                'loading'       => 'eager',
                                'fetchpriority' => 'high',
                                'alt'           => esc_attr(wp_strip_all_tags(get_the_title())),
                            ]); ?>
                        </div>
                    <?php endif; ?>
                    <div class="p-6 lg:p-10 grid grid-rows-[auto_1fr_auto] gap-4 lg:gap-6">

                        <!-- Eyebrow + meta strip -->
                        <div class="grid gap-3">
                            <span class="font-mono uppercase tracking-widest text-caption text-blue-500 inline-flex items-center gap-2">
                                <span class="w-2 h-2 bg-red" aria-hidden="true"></span>
                                <?php esc_html_e('Featured', 'standard'); ?>
                            </span>
                            <dl class="flex flex-wrap items-center gap-x-4 gap-y-1 font-mono text-caption text-blue-500">
                                <div class="inline-flex items-center gap-1.5">
                                    <dt class="sr-only"><?php esc_html_e('Published', 'standard'); ?></dt>
                                    <dd><?php echo esc_html(get_the_date()); ?></dd>
                                </div>
                                <span class="text-blue-300" aria-hidden="true">/</span>
                                <div class="inline-flex items-center gap-1.5">
                                    <dt class="sr-only"><?php esc_html_e('Reading time', 'standard'); ?></dt>
                                    <dd>
                                        <?php echo esc_html(sprintf(
                                            /* translators: %d minutes to read. */
                                            _n('%d min read', '%d min read', $featured_minutes, 'standard'),
                                            $featured_minutes
                                        )); ?>
                                    </dd>
                                </div>
                                <?php if ($featured_category !== '') : ?>
                                    <span class="text-blue-300" aria-hidden="true">/</span>
                                    <div class="inline-flex items-center gap-1.5">
                                        <dt class="sr-only"><?php esc_html_e('Category', 'standard'); ?></dt>
                                        <dd class="text-blue-700"><?php echo esc_html($featured_category); ?></dd>
                                    </div>
                                <?php endif; ?>
                            </dl>
                        </div>

                        <!-- Headline + excerpt -->
                        <div class="grid gap-3 lg:gap-4 self-center">
                            <?php the_title(sprintf(
                                '<h2 class="font-sans font-semibold text-heading lg:text-heading-lg text-blue-900 leading-tight tracking-tight line-clamp-3 group-hover:text-blue-500 transition-colors"><a href="%s" class="text-inherit no-underline hover:no-underline after:absolute after:inset-0 after:content-[\'\'] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" aria-label="%s">',
                                esc_url(get_permalink()),
                                esc_attr($featured_label)
                            ), '</a></h2>'); ?>
                            <?php if (has_excerpt() || get_the_excerpt()) : ?>
                                <p class="text-blue-600 text-base lg:text-lg leading-relaxed line-clamp-4 max-w-prose">
                                    <?php echo esc_html(wp_strip_all_tags(get_the_excerpt())); ?>
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- CTA -->
                        <div>
                            <span class="relative z-[1] inline-flex items-center gap-3 px-6 py-3 min-h-12 border border-blue-900 text-blue-900 font-mono font-medium text-sm uppercase tracking-widest group-hover:bg-blue-900 group-hover:text-white transition-colors">
                                <?php echo esc_html($featured_cta); ?>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 transition-transform duration-200 group-hover:translate-x-1', 'aria-hidden' => 'true']); ?>
                            </span>
                        </div>

                    </div>
                </article>
            <?php wp_reset_postdata(); endif; ?>

        </div>

    </section>

    <!-- Post-type quick-nav strip: links to the matching post-type archive. -->
    <section class="border-b border-blue-200 bg-blue-50" aria-label="<?php esc_attr_e('Browse by content type', 'standard'); ?>">
        <div class="container py-6 lg:py-8">
            <nav class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
                <?php
                $hero_nav = [
                    'post'     => ['label' => __('Articles', 'standard'),  'icon' => 'file-text', 'href' => get_post_type_archive_link('post') ?: get_learning_center_url()],
                    'video'    => ['label' => __('Videos', 'standard'),    'icon' => 'play',      'href' => get_post_type_archive_link('video') ?: '#'],
                    'resource' => ['label' => __('Resources', 'standard'), 'icon' => 'folder',    'href' => get_post_type_archive_link('resource') ?: '#'],
                    'download' => ['label' => __('Downloads', 'standard'), 'icon' => 'download',  'href' => get_post_type_archive_link('download') ?: '#'],
                ];
                foreach ($hero_nav as $type => $item) :
                ?>
                    <a href="<?php echo esc_url($item['href']); ?>"
                       class="group flex items-center justify-between gap-3 p-4 lg:p-5 bg-white border border-blue-200 no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                        <span class="flex items-center gap-3 min-w-0">
                            <?php icon($item['icon'], ['class' => 'w-5 h-5 text-blue-500 shrink-0', 'aria-hidden' => 'true']); ?>
                            <span class="font-mono font-medium uppercase tracking-widest text-caption text-blue-900 group-hover:text-blue-500 transition-colors truncate">
                                <?php echo esc_html($item['label']); ?>
                            </span>
                        </span>
                        <span class="text-blue-400 group-hover:text-blue-500 group-hover:translate-x-0.5 transition-all shrink-0" aria-hidden="true">
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>
    </section>

    <!-- Filter rail + content sections -->
    <section class="border-b border-blue-200" aria-labelledby="lc-content-heading">
        <h2 id="lc-content-heading" class="sr-only">
            <?php esc_html_e('Filter and browse Learning Center content', 'standard'); ?>
        </h2>

        <form id="<?php echo esc_attr($lc_form_id); ?>" method="get" action="<?php echo esc_url($filter_action); ?>" class="sr-only" aria-hidden="true"></form>

        <div class="container layout-with-rail py-6 lg:py-10">

            <?php
            get_template_part('templates/parts/filter-sidebar', null, [
                'groups'       => $lc_groups,
                'form_id'      => $lc_form_id,
                'apply_label'  => __('Apply filters', 'standard'),
                'reset_url'    => $lc_has_filters ? $filter_action : '',
                'reset_label'  => __('Clear filters', 'standard'),
                'drawer_label' => __('Filters', 'standard'),
                'aria_label'   => __('Learning Center filters', 'standard'),
                'show_actions' => true,
            ]);
            ?>

            <div class="grid gap-12 lg:gap-16">
                <?php foreach ($content_sections as $section) :
                    $section_query = get_section_query($section['post_type'], 3, $filters);

                    if (!$section_query->have_posts()) {
                        wp_reset_postdata();
                        continue;
                    }
                ?>
                    <section id="lc-section-<?php echo esc_attr($section['post_type']); ?>" tabindex="-1">

                        <header class="flex items-center justify-between mb-6 lg:mb-8">
                            <h2 class="font-sans font-semibold text-heading-sm lg:text-heading text-blue-900 leading-tight tracking-tight flex items-center gap-3">
                                <?php icon($section['icon'], ['class' => 'w-6 h-6 lg:w-7 lg:h-7 text-blue-400', 'aria-hidden' => 'true']); ?>
                                <?php echo esc_html($section['title']); ?>
                            </h2>
                            <?php if ($section['link']) : ?>
                                <a href="<?php echo esc_url($section['link']); ?>" class="hidden sm:inline-flex items-center gap-2 text-sm font-mono font-medium text-blue-500 hover:underline">
                                    <?php echo esc_html($section['link_text']); ?>
                                    <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                </a>
                            <?php endif; ?>
                        </header>

                        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            <?php while ($section_query->have_posts()) : $section_query->the_post(); ?>
                                <?php get_template_part('templates/parts/card-post'); ?>
                            <?php endwhile; ?>
                        </div>

                        <?php if ($section['link']) : ?>
                            <div class="mt-6 sm:hidden">
                                <a href="<?php echo esc_url($section['link']); ?>" class="inline-flex items-center gap-2 text-sm font-mono font-medium text-blue-500 hover:underline">
                                    <?php echo esc_html($section['link_text']); ?>
                                    <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                    </section>
                <?php wp_reset_postdata(); endforeach; ?>
            </div>

        </div>
    </section>

    <!-- Subscribe CTA -->
    <?php get_template_part('templates/parts/cta/subscribe'); ?>

</main>

<?php
get_footer();
