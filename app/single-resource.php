<?php
/**
 * The template for displaying single resource posts.
 *
 * Same shell as single-download.php: fading dot-grid page, article hero,
 * full-width content with a fixed-width left rail that lists every other
 * resource of the same type via get_sidebar_items_query().
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_sidebar_items_query;

get_header();

$post_type   = 'resource';
$type_object = get_post_type_object($post_type);
$plural      = $type_object?->labels->name ?? __('Resources', 'standard');
/* translators: %s post-type plural label, e.g. "Resources". */
$rail_title  = sprintf(__('All %s', 'standard'), $plural);
$archive_url = get_post_type_archive_link($post_type);
?>

<main id="primary" class="pattern-dot-grid pb-6 lg:pb-12">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>

            <div class="container">
                <?php get_template_part('templates/parts/single/article-hero', null, [
                    'show_meta' => false,
                ]); ?>

                <div class="article-layout">
                    <aside class="hidden lg:block" aria-label="<?php echo esc_attr($rail_title); ?>">
                        <nav class="sticky top-24">
                            <p class="font-mono font-medium uppercase tracking-widest text-caption text-blue-500 m-0 mb-6">
                                <?php echo esc_html($rail_title); ?>
                            </p>
                            <ul class="grid gap-0 m-0 p-0 list-none border-t border-blue-100">
                                <?php
                                $items = get_sidebar_items_query($post_type, (int) get_the_ID());

                                if ($items->have_posts()) :
                                    while ($items->have_posts()) : $items->the_post();
                                ?>
                                    <li class="border-b border-blue-100">
                                        <a href="<?php the_permalink(); ?>"
                                           class="group flex items-start gap-3 py-3 text-sm leading-snug text-blue-900 no-underline hover:text-blue-500 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                            <span class="mt-1.5 w-1 h-1 shrink-0 bg-blue-300 group-hover:bg-blue-500 transition-colors" aria-hidden="true"></span>
                                            <span class="min-w-0"><?php the_title(); ?></span>
                                        </a>
                                    </li>
                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                                ?>
                            </ul>

                            <?php if ($archive_url) :
                                /* translators: %s post-type plural label, e.g. "Resources". */
                                $view_all_label = sprintf(__('View all %s', 'standard'), strtolower($plural));
                            ?>
                                <a href="<?php echo esc_url($archive_url); ?>"
                                   class="group mt-6 inline-flex items-center gap-2 font-mono uppercase tracking-widest text-caption text-blue-500 no-underline hover:text-blue-700 transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                                    <?php echo esc_html($view_all_label); ?>
                                    <span class="transition-transform duration-200 group-hover:translate-x-1">
                                        <?php icon('arrow-right', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                                    </span>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </aside>

                    <div class="min-w-0">
                        <div class="prose prose-lg max-w-full">
                            <?php the_content(); ?>
                        </div>

                        <?php get_template_part('templates/parts/disclaimer'); ?>
                    </div>
                </div>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/post-navigation'); ?>
            </div>

            <div class="container">
                <?php get_template_part('templates/parts/related-posts'); ?>
            </div>
        </article>
    <?php endwhile; ?>
</main>

<?php get_template_part('templates/parts/cta/subscribe'); ?>

<?php get_footer(); ?>
