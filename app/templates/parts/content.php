<?php
/**
 * Template part for displaying posts.
 *
 * Used in archive and index views to display post cards with
 * thumbnail, title, date, author, excerpt, and read more link.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Standard
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white shadow-sm overflow-hidden'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden">
            <?php the_post_thumbnail('medium_large', [
                'class' => 'w-full h-full object-cover transition-transform duration-300 hover:scale-105',
                'loading' => 'lazy',
            ]); ?>
        </a>
    <?php endif; ?>

    <div class="p-6">
        <header class="mb-4">
            <?php the_title(sprintf('<h2 class="text-xl font-semibold mb-2"><a href="%s" class="text-slate-900 no-underline hover:text-primary">', esc_url(get_permalink())), '</a></h2>'); ?>

            <div class="text-sm text-slate-500">
                <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                    <?php echo esc_html(get_the_date()); ?>
                </time>
                <span class="mx-2">&middot;</span>
                <span><?php the_author(); ?></span>
            </div>
        </header>

        <div class="prose prose-slate prose-sm mb-4">
            <?php the_excerpt(); ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="btn btn-primary text-sm">
            Read More &rarr;
        </a>
    </div>
</article>
