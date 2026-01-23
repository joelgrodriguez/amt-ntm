<?php
/**
 * Template Name: Articles Archive
 *
 * Displays blog posts with category filter sidebar.
 * Select this template in the Page editor to use.
 *
 * @package Standard
 */

declare(strict_types=1);

$content = [
    'eyebrow'          => __('Learning Center', 'standard'),
    'title'            => __('Articles', 'standard'),
    'filter_title'     => __('Filter by Category', 'standard'),
    'back_link'        => __('Learning Center', 'standard'),
    'back_url'         => home_url('/learning-center/'),
];

get_header();

// Get current page for pagination
$paged = get_query_var('paged') ? get_query_var('paged') : 1;

// Query blog posts
$args = [
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 12,
    'paged'          => $paged,
];

$articles_query = new WP_Query($args);

// Get all categories for filter
$categories = get_categories([
    'hide_empty' => true,
    'orderby'    => 'name',
    'order'      => 'ASC',
]);
?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-6 lg:py-12">

    <!-- Header -->
    <header class="container mb-6 lg:mb-12">
        <div class="grid gap-4 justify-items-start">
            <span class="text-xs font-mono uppercase tracking-widest text-secondary"><?php echo esc_html($content['eyebrow']); ?></span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono"><?php echo esc_html($content['title']); ?></h1>
        </div>
    </header>

    <!-- Two-column layout: Filter Sidebar + Content -->
    <div class="container lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">

        <!-- Filter Sidebar -->
        <aside class="hidden lg:block border-r border-slate-200 pr-8">
            <nav class="sticky top-16 grid gap-8">

                <!-- Filter by Category -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-900 mb-4 flex items-center gap-2">
                        <?php icon('filter', ['class' => 'w-4 h-4']); ?>
                        <?php echo esc_html($content['filter_title']); ?>
                    </h3>
                    <ul class="grid gap-1 border-l border-slate-200">
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $cat) : ?>
                                <li>
                                    <a href="<?php echo esc_url(get_category_link($cat->term_id)); ?>" class="flex items-center justify-between text-sm py-2 pl-4 border-l-2 -ml-px border-transparent text-slate-600 hover:text-slate-900 hover:border-slate-300">
                                        <span><?php echo esc_html($cat->name); ?></span>
                                        <span class="text-xs text-slate-400"><?php echo esc_html($cat->count); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- Back to Learning Center -->
                <a href="<?php echo esc_url($content['back_url']); ?>" class="flex items-center gap-2 text-sm font-medium text-primary hover:underline">
                    <?php icon('arrow--left', ['class' => 'w-4 h-4']); ?>
                    <?php echo esc_html($content['back_link']); ?>
                </a>

            </nav>
        </aside>

        <!-- Main Content -->
        <div class="grid gap-8">
            <?php if ($articles_query->have_posts()) : ?>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while ($articles_query->have_posts()) : $articles_query->the_post(); ?>
                        <?php get_template_part('templates/parts/card-post'); ?>
                    <?php endwhile; ?>
                </div>

                <?php
                // Set up pagination
                $GLOBALS['wp_query'] = $articles_query;
                \Standard\Walkers\Pagination::render();
                wp_reset_postdata();
                ?>
            <?php else : ?>
                <?php get_template_part('templates/parts/content', 'none'); ?>
            <?php endif; ?>
        </div>

    </div>

</main>

<?php
get_footer();
