<?php
/**
 * Template part for displaying a post card.
 *
 * Reusable card component for displaying posts in grids.
 * Shows thumbnail, category, title, date, and arrow link.
 *
 * @package Standard
 */

?>

<article class="border border-slate-300 bg-white h-full grid grid-rows-[auto_1fr_auto]">
    <a href="<?php the_permalink(); ?>" class="block border-b border-slate-200">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('card-thumbnail', [
                'class' => 'w-full',
                'loading' => 'lazy',
            ]); ?>
        <?php endif; ?>
    </a>

    <a href="<?php the_permalink(); ?>" class="p-4 grid gap-4 content-start no-underline">
        <?php
        $categories = get_the_category();
        if (!empty($categories)) :
        ?>
            <span class="text-xs text-slate-500 font-mono uppercase tracking-wide"><?php echo esc_html($categories[0]->name); ?></span>
        <?php endif; ?>

        <?php the_title('<h3 class="text-base font-semibold line-clamp-2 text-slate-900 hover:text-primary">', '</h3>'); ?>
    </a>

    <a href="<?php the_permalink(); ?>" class="flex items-center justify-between text-xs text-slate-500 font-mono p-4 border-t border-slate-200 no-underline hover:bg-slate-50">
        <span class="flex items-center gap-1">
            <?php icon('calendar', ['class' => 'w-3 h-3']); ?>
            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                <?php echo esc_html(get_the_date('M j, Y')); ?>
            </time>
        </span>
        <span class="text-slate-400">
            <?php icon('arrow--right', ['class' => 'w-3 h-3']); ?>
        </span>
    </a>
</article>
