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

use function Standard\LearningCenter\get_content_sections;
use function Standard\LearningCenter\filter_content_sections;
use function Standard\LearningCenter\get_active_filters;
use function Standard\LearningCenter\get_featured_query;
use function Standard\LearningCenter\get_learning_center_url;
use function Standard\LearningCenter\get_section_query;
use function Standard\LearningCenter\get_type_cta;
use function Standard\LearningCenter\get_type_icon;
use function Standard\LearningCenter\get_type_label;

get_header();

$filters = get_active_filters();
$featured_query = get_featured_query($filters);
$categories = get_categories([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
]);

$machine_tags = get_tags([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
]);

$content_sections = filter_content_sections(get_content_sections(), $filters);
$filter_action    = get_learning_center_url();
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

                <!-- Post-type quick-nav: links to the matching post-type archive. -->
                <nav class="mt-2 grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4" aria-label="<?php esc_attr_e('Browse by content type', 'standard'); ?>">
                    <?php
                    $hero_nav = [
                        'post'     => ['label' => __('Articles', 'standard'),  'icon' => 'file-text', 'href' => get_learning_center_url()],
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


    <!-- Quick Filters -->
    <section id="lc-filters" class="border-b border-blue-200 bg-blue-50" aria-labelledby="lc-filters-heading">
        <div class="container py-6">
            <h2 id="lc-filters-heading" class="sr-only">
                <?php esc_html_e('Filter Learning Center content', 'standard'); ?>
            </h2>
            <form
                class="flex flex-wrap items-center justify-center gap-4 md:gap-6"
                method="get"
                action="<?php echo esc_url($filter_action); ?>"
            >
                <span class="text-sm font-medium text-blue-700 flex items-center gap-2" aria-hidden="true">
                    <?php icon('filter', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                    <?php esc_html_e('Filters:', 'standard'); ?>
                </span>

                <!-- Category -->
                <div class="flex items-center gap-2">
                    <label for="lc-filter-category" class="flex items-center gap-1.5 text-sm text-blue-600">
                        <?php icon('folder', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                        <?php esc_html_e('Category', 'standard'); ?>
                    </label>
                    <div class="relative">
                        <select
                            id="lc-filter-category"
                            name="lc_category"
                            class="appearance-none bg-white border border-blue-200 text-blue-700 text-sm font-mono pl-3 pr-8 py-2.5 min-h-11 cursor-pointer hover:border-blue-300 focus-visible:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                            data-lc-filter
                        >
                            <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($filters['category'], $cat->slug); ?>>
                                    <?php echo esc_html($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-400', 'aria-hidden' => 'true']); ?>
                        </div>
                    </div>
                </div>

                <!-- Resource Type -->
                <div class="flex items-center gap-2">
                    <label for="lc-filter-type" class="flex items-center gap-1.5 text-sm text-blue-600">
                        <?php icon('file-text', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                        <?php esc_html_e('Resource Type', 'standard'); ?>
                    </label>
                    <div class="relative">
                        <select
                            id="lc-filter-type"
                            name="lc_type"
                            class="appearance-none bg-white border border-blue-200 text-blue-700 text-sm font-mono pl-3 pr-8 py-2.5 min-h-11 cursor-pointer hover:border-blue-300 focus-visible:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                            data-lc-filter
                        >
                            <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                            <option value="post"     <?php selected($filters['type'], 'post'); ?>><?php esc_html_e('Articles', 'standard'); ?></option>
                            <option value="video"    <?php selected($filters['type'], 'video'); ?>><?php esc_html_e('Videos', 'standard'); ?></option>
                            <option value="resource" <?php selected($filters['type'], 'resource'); ?>><?php esc_html_e('Resources', 'standard'); ?></option>
                            <option value="download" <?php selected($filters['type'], 'download'); ?>><?php esc_html_e('Downloads', 'standard'); ?></option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-400', 'aria-hidden' => 'true']); ?>
                        </div>
                    </div>
                </div>

                <!-- Machine -->
                <?php if (!empty($machine_tags)) : ?>
                    <div class="flex items-center gap-2">
                        <label for="lc-filter-machine" class="flex items-center gap-1.5 text-sm text-blue-600">
                            <?php icon('settings', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </label>
                        <div class="relative">
                            <select
                                id="lc-filter-machine"
                                name="lc_machine"
                                class="appearance-none bg-white border border-blue-200 text-blue-700 text-sm font-mono pl-3 pr-8 py-2.5 min-h-11 cursor-pointer hover:border-blue-300 focus-visible:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                data-lc-filter
                            >
                                <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                                <?php foreach ($machine_tags as $tag) : ?>
                                    <option value="<?php echo esc_attr($tag->slug); ?>" <?php selected($filters['machine'], $tag->slug); ?>>
                                        <?php echo esc_html($tag->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-400', 'aria-hidden' => 'true']); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <noscript>
                    <button type="submit" class="inline-flex items-center px-4 py-2.5 min-h-11 bg-blue-500 text-white font-mono text-sm">
                        <?php esc_html_e('Apply', 'standard'); ?>
                    </button>
                </noscript>
            </form>
        </div>
    </section>

    <!-- Content Sections by Post Type -->
    <?php foreach ($content_sections as $section) :
        $section_query = get_section_query($section['post_type'], 4, $filters);

        if (!$section_query->have_posts()) {
            wp_reset_postdata();
            continue;
        }
    ?>
        <section id="lc-section-<?php echo esc_attr($section['post_type']); ?>" class="py-12 lg:py-16 border-b border-blue-200" tabindex="-1">
            <div class="container">

                <header class="flex items-center justify-between mb-8">
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

                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
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

            </div>
        </section>
    <?php wp_reset_postdata(); endforeach; ?>

    <!-- Subscribe CTA -->
    <?php get_template_part('templates/parts/cta/subscribe'); ?>

</main>

<script>
(function () {
    var form = document.querySelector('form[action][method="get"] [data-lc-filter]');
    if (!form) return;
    var filterForm = form.closest('form');
    if (!filterForm) return;

    filterForm.querySelectorAll('[data-lc-filter]').forEach(function (select) {
        select.addEventListener('change', function () {
            if (typeof filterForm.requestSubmit === 'function') {
                filterForm.requestSubmit();
            } else {
                filterForm.submit();
            }
        });
    });
})();
</script>

<?php
get_footer();
