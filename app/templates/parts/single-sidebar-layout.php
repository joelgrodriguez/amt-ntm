<?php
/**
 * Single-post layout: title-card hero + content + right-rail sidebar.
 *
 * Shared by single-download.php and single-resource.php. Both have the
 * same shape: two-column hero (text + image), two-column body (prose +
 * sticky "All <Type>s" sidebar listing other items of the same post
 * type), related posts, subscribe CTA.
 *
 * Per-post-type strings (badge, sidebar title, sidebar query) come from
 * the post type itself — `get_post_type()` + `get_post_type_object()`.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\LearningCenter\get_sidebar_items_query;

$post_type     = (string) get_post_type();
$type_object   = get_post_type_object($post_type);
$singular      = $type_object?->labels->singular_name ?? ucfirst($post_type);
$plural        = $type_object?->labels->name ?? ucfirst($post_type) . 's';
/* translators: %s post-type plural label, e.g. "Downloads" or "Resources". */
$sidebar_title = sprintf(__('All %s', 'standard'), $plural);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('grid gap-6 lg:gap-12'); ?>>
    <header class="container">
        <div class="grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
            <div class="grid gap-6 justify-items-start">
                <span class="badge inline"><?php echo esc_html($singular); ?></span>

                <?php the_title('<h1 class="text-3xl md:text-4xl lg:text-5xl font-medium font-mono">', '</h1>'); ?>

                <?php if (get_the_excerpt()) : ?>
                    <p class="text-blue-600"><?php echo esc_html(get_the_excerpt()); ?></p>
                <?php endif; ?>
            </div>

            <?php if (has_post_thumbnail()) : ?>
                <figure class="featured-image">
                    <?php the_post_thumbnail('full', [
                        'loading' => 'eager',
                    ]); ?>
                </figure>
            <?php endif; ?>
        </div>
    </header>

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
        <aside class="hidden lg:block border-l border-blue-200 pl-12">
            <nav class="sticky top-16" aria-label="<?php echo esc_attr($sidebar_title); ?>">
                <p class="text-sm font-medium text-blue-900 mb-4"><?php echo esc_html($sidebar_title); ?></p>
                <ul class="grid gap-2">
                    <?php
                    $items = get_sidebar_items_query($post_type, (int) get_the_ID());

                    if ($items->have_posts()) :
                        while ($items->have_posts()) : $items->the_post();
                    ?>
                        <li>
                            <a href="<?php the_permalink(); ?>" class="block text-sm text-blue-500 no-underline hover:underline">
                                <?php the_title(); ?>
                            </a>
                        </li>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    ?>
                </ul>
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
