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

    <!-- Hero: editorial split (copy left, featured right) -->
    <section class="pattern-dot-grid border-b border-blue-200">
        <div class="container py-10 lg:py-14">

            <div class="grid lg:grid-cols-[1.4fr_1fr] gap-10 lg:gap-16 items-start">

                <!-- Hero copy -->
                <header class="grid gap-5 lg:gap-6 lg:pt-2">
                    <span class="text-caption font-mono uppercase tracking-widest text-blue-500">
                        <?php esc_html_e('Learning Center', 'standard'); ?>
                    </span>
                    <h1 class="font-mono font-medium text-heading-lg lg:text-display text-blue-900 leading-tight tracking-tight">
                        <?php esc_html_e('The Rollforming Learning Center', 'standard'); ?>
                    </h1>
                    <p class="text-blue-600 text-base lg:text-lg max-w-xl leading-relaxed">
                        <?php esc_html_e('Articles, videos, and resources to help you get the most out of your portable rollforming equipment.', 'standard'); ?>
                    </p>
                </header>

                <!-- Featured Post (compact) -->
                <?php if ($featured_query->have_posts()) : $featured_query->the_post();
                    $featured_cta   = get_type_cta((string) get_post_type());
                    $featured_label = sprintf(
                        /* translators: %1$s post title, %2$s post-type-specific verb. */
                        __('%1$s. %2$s.', 'standard'),
                        wp_strip_all_tags(get_the_title()),
                        $featured_cta
                    );
                ?>
                    <article class="group relative grid grid-rows-[auto_auto] bg-white border border-blue-200 transition-colors duration-200 hover:border-blue-500">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="aspect-[16/9] overflow-hidden border-b border-blue-200 transition-colors duration-200 group-hover:border-blue-500">
                                <?php the_post_thumbnail('card-thumbnail', [
                                    'class'         => 'w-full h-full object-cover',
                                    'loading'       => 'eager',
                                    'fetchpriority' => 'high',
                                    'alt'           => esc_attr(wp_strip_all_tags(get_the_title())),
                                ]); ?>
                            </div>
                        <?php endif; ?>
                        <div class="p-5 lg:p-6 grid gap-2.5 content-start">
                            <span class="font-mono uppercase tracking-widest text-caption text-blue-500">
                                <?php esc_html_e('Latest', 'standard'); ?>
                            </span>
                            <?php the_title(sprintf(
                                '<h2 class="font-mono font-medium text-heading-sm text-blue-900 leading-snug tracking-tight group-hover:text-blue-500 transition-colors"><a href="%s" class="text-inherit no-underline hover:no-underline after:absolute after:inset-0 after:content-[\'\'] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" aria-label="%s">',
                                esc_url(get_permalink()),
                                esc_attr($featured_label)
                            ), '</a></h2>'); ?>
                            <span class="inline-flex items-center gap-2 text-sm font-mono font-medium text-blue-500 mt-1">
                                <?php echo esc_html($featured_cta); ?>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                            </span>
                        </div>
                    </article>
                <?php wp_reset_postdata(); endif; ?>

            </div>

        </div>
    </section>


    <!-- Quick Filters -->
    <section class="border-b border-blue-200 bg-blue-50" aria-labelledby="lc-filters-heading">
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
        <section class="py-12 lg:py-16 border-b border-blue-200">
            <div class="container">

                <header class="flex items-center justify-between mb-8">
                    <h2 class="font-mono font-medium text-heading-sm lg:text-heading text-blue-900 leading-tight tracking-tight flex items-center gap-3">
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
