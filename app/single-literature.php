<?php
/**
 * The template for displaying single literature posts.
 *
 * Same shell as single-manual.php: profile-style hero, two-column body
 * with a taxonomy filter sidebar (category + machine tag) on the left
 * and the literature content on the right.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\ContentTaxonomy\get_terms_for_post_type;

$content = [
    'badge'          => __('Literature', 'standard'),
    'filter_type'    => __('Filter by Type', 'standard'),
    'filter_machine' => __('Filter by Machine', 'standard'),
    'view_all'       => __('View All Literature', 'standard'),
];

get_header();

$categories   = get_the_terms(get_the_ID(), 'category');
$machine_tags = get_the_tags();
?>

<main id="primary">
    <?php while (have_posts()) : the_post(); ?>
        <?php get_template_part('templates/parts/single/profile-style-hero', null, [
            'eyebrow' => $content['badge'],
        ]); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12 py-6 lg:py-12'); ?>>

            <div class="container layout-with-rail">

                <?php
                get_template_part('templates/parts/taxonomy-filter-sidebar', null, [
                    'sections' => [
                        [
                            'title'         => $content['filter_type'],
                            'icon'          => 'filter',
                            'terms'         => get_terms_for_post_type('literature', 'category'),
                            'current_terms' => $categories,
                        ],
                        [
                            'title'         => $content['filter_machine'],
                            'icon'          => 'settings',
                            'terms'         => get_terms_for_post_type('literature', 'post_tag'),
                            'current_terms' => $machine_tags,
                        ],
                    ],
                    'back_url'   => get_post_type_archive_link('literature') ?: '',
                    'back_label' => $content['view_all'],
                ]);
                ?>

                <div class="grid gap-8">
                    <section>
                        <div class="prose prose-lg max-w-full">
                            <?php the_content(); ?>
                        </div>
                    </section>
                </div>

            </div>

        </article>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
