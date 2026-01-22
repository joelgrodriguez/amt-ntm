<?php
/**
 * Template part for displaying search results.
 *
 * Displays individual search result items with title, date,
 * post type indicator, and excerpt.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white border border-slate-200 p-6'); ?>>
    <header class="mb-4">
        <?php the_title(sprintf('<h2 class="text-xl font-semibold mb-2"><a href="%s" class="text-slate-900 no-underline hover:text-primary">', esc_url(get_permalink())), '</a></h2>'); ?>

        <div class="text-sm text-slate-500">
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date()); ?>
            </time>
            <span class="mx-2">&middot;</span>
            <span><?php echo esc_html(get_post_type_object(get_post_type())->labels->singular_name); ?></span>
        </div>
    </header>

    <div class="text-slate-600">
        <?php the_excerpt(); ?>
    </div>
</article>
