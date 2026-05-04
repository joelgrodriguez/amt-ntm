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

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'          => __('Learning Center', 'standard'),
    'title'            => __('Articles', 'standard'),
    'filter_title'     => __('Filter by Category', 'standard'),
    'back_link'        => __('Learning Center', 'standard'),
    'back_url'         => \Standard\Url\internal('/learning-center/'),
];

get_header();

// Get current page for pagination
$paged = max(1, (int) get_query_var('paged'));

// Query blog posts
$args = [
    'post_type'           => 'post',
    'post_status'         => 'publish',
    'posts_per_page'      => 12,
    'paged'               => $paged,
    'ignore_sticky_posts' => true,
];

$articles_query = new WP_Query($args);

?>

<main id="primary" class="pattern-dot-grid gradient-fade-bottom-sm py-6 lg:py-12">

    <!-- Header -->
    <header class="container mb-6 lg:mb-12">
        <div class="grid gap-4 justify-items-start">
            <span class="text-xs font-mono uppercase tracking-widest text-red"><?php echo esc_html($content['eyebrow']); ?></span>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-medium font-mono"><?php echo esc_html($content['title']); ?></h1>
        </div>
    </header>

    <!-- Two-column layout: Filter Sidebar + Content -->
    <div class="container lg:grid lg:grid-cols-[240px_1fr] lg:gap-12">

        <?php
        get_template_part('templates/parts/taxonomy-filter-sidebar', null, [
            'sections' => [
                [
                    'title'         => $content['filter_title'],
                    'icon'          => 'filter',
                    'terms'         => \Standard\ContentTaxonomy\get_terms_for_post_type('post', 'category'),
                    'current_terms' => [],
                ],
            ],
            'back_url'   => $content['back_url'],
            'back_label' => $content['back_link'],
        ]);
        ?>

        <!-- Main Content -->
        <div class="grid gap-8">
            <?php if ($articles_query->have_posts()) : ?>
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <?php while ($articles_query->have_posts()) : $articles_query->the_post(); ?>
                        <?php get_template_part('templates/parts/card-post'); ?>
                    <?php endwhile; ?>
                </div>

                <?php
                \Standard\Walkers\Pagination::render($articles_query);
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
