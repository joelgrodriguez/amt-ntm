<?php
/**
 * Single-post layout: editorial hero + content + right-rail sidebar.
 *
 * Shared by single-download.php and single-resource.php. Renders the
 * shared editorial post-hero (no eyebrow, no meta strip — just title,
 * excerpt, and featured image on bg-blue-50 chrome) followed by a
 * two-column body: prose + sticky "All <Type>s" rail listing other
 * items of the same post type. Related posts and the subscribe CTA
 * follow.
 *
 * The sidebar title and query are derived from the current post type
 * via `get_post_type()` + `get_post_type_object()`.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_sidebar_items_query;

$post_type     = (string) get_post_type();
$plural        = get_post_type_object($post_type)?->labels->name ?? ucfirst($post_type) . 's';
/* translators: %s post-type plural label, e.g. "Downloads" or "Resources". */
$sidebar_title = sprintf(__('All %s', 'standard'), $plural);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
    <?php get_template_part('templates/parts/post-hero', null, [
        'eyebrow_kind' => 'none',
        'meta_items'   => [],
    ]); ?>

    <!-- Two-column layout: Content + Same-type Sidebar -->
    <div class="container lg:grid lg:grid-cols-[1fr_300px] lg:gap-12">
        <!-- Content -->
        <div>
            <div class="prose prose-lg max-w-full">
                <?php the_content(); ?>
            </div>

            <?php get_template_part('templates/parts/disclaimer'); ?>
        </div>

        <!-- Same-type Sidebar -->
        <aside class="hidden lg:block border-l border-blue-200 pl-10">
            <nav class="sticky top-24" aria-label="<?php echo esc_attr($sidebar_title); ?>">
                <p class="font-mono font-medium uppercase tracking-widest text-caption text-blue-500 m-0 mb-6">
                    <?php echo esc_html($sidebar_title); ?>
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

                <?php
                $archive_url = get_post_type_archive_link($post_type);
                if ($archive_url) :
                    /* translators: %s post-type plural label, e.g. "Downloads". */
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
    </div>

    <div class="container">
        <?php get_template_part('templates/parts/post-navigation'); ?>
    </div>

    <!-- Related Posts -->
    <div class="container">
        <?php get_template_part('templates/parts/related-posts'); ?>
    </div>
</article>
