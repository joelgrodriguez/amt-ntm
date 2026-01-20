<?php
/**
 * Template part for displaying related posts.
 *
 * Shows up to 4 related posts based on shared categories.
 * Displays posts from any post type.
 *
 * @package Standard
 */

$related = \Standard\get_related_posts(4);

if (!$related->have_posts()) {
    return;
}
?>

<section class="related-posts mt-12 pt-8 border-t border-slate-200">
    <h2 class="text-2xl font-bold font-mono mb-8"><?php esc_html_e('Related', 'standard'); ?></h2>

    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
        <?php while ($related->have_posts()) : $related->the_post(); ?>
            <article class="border border-slate-300 bg-white">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_permalink(); ?>" class="block border-b border-slate-200">
                        <?php the_post_thumbnail('card-thumbnail', [
                            'class' => 'w-full',
                            'loading' => 'lazy',
                        ]); ?>
                    </a>
                <?php endif; ?>

                <div class="p-4 grid gap-4">
                    <?php
                    $categories = get_the_category();
                    if (!empty($categories)) :
                    ?>
                        <span class="text-xs text-slate-500 font-mono uppercase tracking-wide"><?php echo esc_html($categories[0]->name); ?></span>
                    <?php endif; ?>

                    <?php the_title(sprintf('<h3 class="text-base font-semibold line-clamp-2"><a href="%s" class="text-slate-900 no-underline hover:text-primary">', esc_url(get_permalink())), '</a></h3>'); ?>

                    <div class="flex items-center justify-between text-xs text-slate-500 font-mono">
                        <span class="flex items-center gap-1">
                            <?php icon('calendar', ['class' => 'w-3 h-3']); ?>
                            <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                <?php echo esc_html(get_the_date('M j, Y')); ?>
                            </time>
                        </span>
                        <a href="<?php the_permalink(); ?>" class="text-slate-400 hover:text-primary" aria-label="<?php esc_attr_e('Read more', 'theme'); ?>">
                            <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</section>

<?php
wp_reset_postdata();
