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
use function Standard\LearningCenter\get_recent_query;
use function Standard\LearningCenter\get_section_query;
use function Standard\LearningCenter\get_type_cta;
use function Standard\LearningCenter\get_type_icon;
use function Standard\LearningCenter\get_type_label;

get_header();

$filters = get_active_filters();
$featured_query = get_featured_query($filters);
$featured_id = $featured_query->have_posts() ? $featured_query->posts[0]->ID : 0;
$recent_query = get_recent_query((int) $featured_id, 4, $filters);

// Get categories for filter
$categories = get_categories([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
]);

// Get machine tags for filter
$machine_tags = get_tags([
    'hide_empty' => true,
    'orderby'    => 'count',
    'order'      => 'DESC',
    'number'     => 8,
]);

$content_sections = filter_content_sections(get_content_sections(), $filters);
?>

<main id="primary">

    <!-- Hero Section -->
    <section class="pattern-dot-grid gradient-fade-bottom-sm border-b border-blue-200">
        <div class="container py-8 lg:py-12">

            <!-- Header -->
            <header class="mb-8 lg:mb-12">
                <span class="text-xs font-mono uppercase tracking-widest text-red">
                    <?php esc_html_e('Learning Center', 'standard'); ?>
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-medium font-mono mt-2">
                    <?php esc_html_e('The Rollforming Learning Center', 'standard'); ?>
                </h1>
                <p class="text-blue-600 mt-4 max-w-2xl">
                    <?php esc_html_e('Articles, videos, and resources to help you get the most out of your portable rollforming equipment.', 'standard'); ?>
                </p>
            </header>

            <!-- Featured + Recent Grid -->
            <div class="grid lg:grid-cols-[2fr_1fr] gap-6">

                <!-- Featured Post (Large) -->
                <?php if ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                    <article class="group border border-blue-200 bg-white">
                        <a href="<?php the_permalink(); ?>" class="block no-underline">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="aspect-video overflow-hidden border-b border-blue-200">
                                    <?php the_post_thumbnail('large', [
                                        'class' => 'w-full h-full object-cover group-hover:scale-105 transition-transform duration-300',
                                    ]); ?>
                                </div>
                            <?php endif; ?>
                            <div class="p-6">
                                <?php
                                $featured_cta = get_type_cta((string) get_post_type());
                                ?>
                                <span class="inline-flex items-center px-3 py-1 bg-blue-500 text-white text-xs font-mono uppercase tracking-wider mb-4">
                                    <?php esc_html_e('Latest', 'standard'); ?>
                                </span>
                                <h2 class="text-xl lg:text-2xl font-medium font-mono mb-3 text-blue-900 group-hover:text-blue-500 transition-colors">
                                    <?php the_title(); ?>
                                </h2>
                                <p class="text-blue-600 text-sm line-clamp-2 mb-4">
                                    <?php echo esc_html(get_the_excerpt()); ?>
                                </p>
                                <div class="flex items-center gap-4 text-xs text-blue-500 font-mono mb-4">
                                    <span class="flex items-center gap-1.5">
                                        <?php icon('calendar', ['class' => 'w-3 h-3']); ?>
                                        <?php echo esc_html(get_the_date()); ?>
                                    </span>
                                    <span>&middot;</span>
                                    <span class="flex items-center gap-1.5">
                                        <?php icon('user', ['class' => 'w-3 h-3']); ?>
                                        <?php the_author(); ?>
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-2 text-sm font-medium text-blue-500">
                                    <?php echo esc_html($featured_cta); ?>
                                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                </span>
                            </div>
                        </a>
                    </article>
                <?php wp_reset_postdata(); endif; ?>

                <!-- Recent Posts (Stacked) + Subscribe CTA -->
                <div class="flex flex-col gap-3">
                    <?php if ($recent_query->have_posts()) : ?>
                        <?php while ($recent_query->have_posts()) : $recent_query->the_post();
                            $recent_post_type = get_post_type();
                            $recent_icon = get_type_icon((string) $recent_post_type);
                        ?>
                            <article class="group bg-white border border-blue-200 hover:border-blue-300 transition-colors shrink-0">
                                <a href="<?php the_permalink(); ?>" class="flex items-center gap-4 p-4 no-underline">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="shrink-0 w-24 h-24 overflow-hidden">
                                            <?php the_post_thumbnail('thumbnail', [
                                                'class' => 'w-full h-full object-cover',
                                            ]); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1 min-w-0">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-blue-400 font-mono uppercase tracking-wide mb-1">
                                            <?php icon($recent_icon, ['class' => 'w-3 h-3']); ?>
                                            <?php
                                            echo esc_html(get_type_label((string) $recent_post_type));
                                            ?>
                                        </span>
                                        <h3 class="font-medium text-blue-900 group-hover:text-blue-500 transition-colors line-clamp-2 text-base">
                                            <?php the_title(); ?>
                                        </h3>
                                    </div>
                                </a>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    <?php endif; ?>

                    <!-- Subscribe CTA -->
                    <div class="bg-blue-500 p-5 flex-1 flex flex-col justify-center">
                        <span class="text-xs font-mono uppercase tracking-widest text-white/70 mb-2">
                            <?php esc_html_e('Stay Updated', 'standard'); ?>
                        </span>
                        <h3 class="text-white font-medium font-mono text-lg mb-2">
                            <?php esc_html_e('Subscribe to the Learning Center', 'standard'); ?>
                        </h3>
                        <p class="text-white/80 text-sm mb-4">
                            <?php esc_html_e('Get the latest articles, videos, and resources delivered to your inbox.', 'standard'); ?>
                        </p>
                        <a href="#subscribe" class="inline-flex items-center gap-2 bg-white text-blue-500 px-4 py-2 text-sm font-medium hover:bg-blue-100 transition-colors w-fit">
                            <?php icon('mail', ['class' => 'w-4 h-4']); ?>
                            <?php esc_html_e('Subscribe Now', 'standard'); ?>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- Quick Filters -->
    <section class="border-b border-blue-200 bg-blue-50 ">
        <div class="container py-6">
            <form class="flex flex-wrap items-center justify-center gap-8" method="get" action="<?php echo esc_url(get_learning_center_url()); ?>">
                <span class="text-sm font-medium text-blue-700 flex items-center gap-2">
                    <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                    <?php esc_html_e('Filters:', 'standard'); ?>
                </span>

                <!-- Category Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-sm text-blue-600">
                        <?php icon('folder', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Category', 'standard'); ?>
                    </label>
                    <div class="relative">
                        <select name="lc_category" class="appearance-none bg-white border border-blue-200 text-blue-700 text-sm font-mono pl-3 pr-8 py-2 cursor-pointer hover:border-blue-300 focus:border-blue-500 focus:outline-none" onchange="this.form.submit()">
                            <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                            <?php foreach ($categories as $cat) : ?>
                                <option value="<?php echo esc_attr($cat->slug); ?>" <?php selected($filters['category'], $cat->slug); ?>>
                                    <?php echo esc_html($cat->name); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-400']); ?>
                        </div>
                    </div>
                </div>

                <!-- Resource Type Dropdown -->
                <div class="flex items-center gap-2">
                    <label class="flex items-center gap-1.5 text-sm text-blue-600">
                        <?php icon('file-text', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Resource Type', 'standard'); ?>
                    </label>
                    <div class="relative">
                        <select name="lc_type" class="appearance-none bg-white border border-blue-200 text-blue-700 text-sm font-mono pl-3 pr-8 py-2 cursor-pointer hover:border-blue-300 focus:border-blue-500 focus:outline-none" onchange="this.form.submit()">
                            <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                            <option value="post" <?php selected($filters['type'], 'post'); ?>><?php esc_html_e('Articles', 'standard'); ?></option>
                            <option value="video" <?php selected($filters['type'], 'video'); ?>><?php esc_html_e('Videos', 'standard'); ?></option>
                            <option value="resource" <?php selected($filters['type'], 'resource'); ?>><?php esc_html_e('Resources', 'standard'); ?></option>
                            <option value="download" <?php selected($filters['type'], 'download'); ?>><?php esc_html_e('Downloads', 'standard'); ?></option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                            <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-400']); ?>
                        </div>
                    </div>
                </div>

                <!-- Machine Dropdown -->
                <?php if (!empty($machine_tags)) : ?>
                    <div class="flex items-center gap-2">
                        <label class="flex items-center gap-1.5 text-sm text-blue-600">
                            <?php icon('settings', ['class' => 'w-4 h-4']); ?>
                            <?php esc_html_e('Machine', 'standard'); ?>
                        </label>
                        <div class="relative">
                            <select name="lc_machine" class="appearance-none bg-white border border-blue-200 text-blue-700 text-sm font-mono pl-3 pr-8 py-2 cursor-pointer hover:border-blue-300 focus:border-blue-500 focus:outline-none" onchange="this.form.submit()">
                                <option value=""><?php esc_html_e('All', 'standard'); ?></option>
                                <?php foreach ($machine_tags as $tag) : ?>
                                    <option value="<?php echo esc_attr($tag->slug); ?>" <?php selected($filters['machine'], $tag->slug); ?>>
                                        <?php echo esc_html($tag->name); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
                                <?php icon('chevron-down', ['class' => 'w-3 h-3 text-blue-400']); ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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

                <!-- Section Header -->
                <header class="flex items-center justify-between mb-8">
                    <h2 class="text-xl lg:text-2xl font-medium font-mono flex items-center gap-3">
                        <?php icon($section['icon'], ['class' => 'w-6 h-6 text-blue-400']); ?>
                        <?php echo esc_html($section['title']); ?>
                    </h2>
                    <?php if ($section['link']) : ?>
                        <a href="<?php echo esc_url($section['link']); ?>" class="hidden sm:inline-flex items-center gap-2 text-sm font-medium text-blue-500 hover:underline">
                            <?php echo esc_html($section['link_text']); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    <?php endif; ?>
                </header>

                <!-- Posts Grid -->
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <?php while ($section_query->have_posts()) : $section_query->the_post(); ?>
                        <?php get_template_part('templates/parts/card-post'); ?>
                    <?php endwhile; ?>
                </div>

                <!-- Mobile View All Link -->
                <?php if ($section['link']) : ?>
                    <div class="mt-6 sm:hidden">
                        <a href="<?php echo esc_url($section['link']); ?>" class="inline-flex items-center gap-2 text-sm font-medium text-blue-500 hover:underline">
                            <?php echo esc_html($section['link_text']); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    </div>
                <?php endif; ?>

            </div>
        </section>
    <?php wp_reset_postdata(); endforeach; ?>

    <!-- Subscribe CTA -->
    <?php get_template_part('templates/parts/cta/subscribe'); ?>

</main>

<?php
get_footer();
