<?php
/**
 * The template for displaying search results.
 *
 * Displays search results in a grid layout with pagination.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Standard
 */

declare(strict_types=1);

$content = [
    'title'    => __('Search Results for: %s', 'standard'),
    'prev'     => __('Previous', 'standard'),
    'next'     => __('Next', 'standard'),
];

get_header();
?>

<main id="primary" class="container py-8">
    <header class="mb-8">
        <h1 class="text-3xl font-bold">
            <?php printf(esc_html($content['title']), '<span class="text-primary">' . esc_html(get_search_query()) . '</span>'); ?>
        </h1>
    </header>

    <?php if (have_posts()) : ?>
        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php while (have_posts()) : the_post(); ?>
                <?php get_template_part('templates/parts/content', 'search'); ?>
            <?php endwhile; ?>
        </div>

        <nav class="mt-8">
            <?php the_posts_pagination([
                'mid_size'  => 2,
                'prev_text' => '&larr; ' . esc_html($content['prev']),
                'next_text' => esc_html($content['next']) . ' &rarr;',
            ]); ?>
        </nav>
    <?php else : ?>
        <?php get_template_part('templates/parts/content', 'none'); ?>
    <?php endif; ?>
</main>

<?php
get_footer();
