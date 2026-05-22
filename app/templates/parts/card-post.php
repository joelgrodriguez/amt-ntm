<?php
/**
 * Post Card
 *
 * Single-anchor card for content listings: front-page learning-center,
 * single-machine related-posts, archive grids, home, template-articles,
 * and the mega-menu learning panel. Eight surfaces; one component.
 *
 * Link model: the entire card routes to one URL. Only the title is a
 * real <a>; its `::after` pseudo-element expands to cover the whole
 * article so the click target is card-sized. Thumbnail and footer are
 * non-interactive. Screen readers announce one link per card, with the
 * accessible name being the post title (the most informative label).
 *
 * Hover affordance follows DESIGN.md §8.8: border shifts blue-200 ->
 * blue-500 on group-hover; title gains a secondary blue-500 tint as
 * a reinforcing signal.
 *
 * Per-post-type icon, CTA label, and aria copy come from
 * Standard\PostTypes\get_display_config().
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\PostTypes\get_display_config;
use function Standard\PostTypes\get_primary_category;

$post_type = get_post_type();
$config    = get_display_config((string) $post_type);
$category  = get_primary_category();
?>

<article class="group relative grid grid-rows-[auto_1fr_auto] h-full bg-white border border-blue-200 transition-colors duration-200 hover:border-blue-500">

    <div class="aspect-[16/9] overflow-hidden border-b border-blue-200 transition-colors duration-200 group-hover:border-blue-500">
        <?php if (has_post_thumbnail()) : ?>
            <?php the_post_thumbnail('card-thumbnail', [
                'class'   => 'w-full h-full block object-cover',
                'loading' => 'lazy',
                'alt'     => '',
            ]); ?>
        <?php endif; ?>
    </div>

    <div class="p-5 lg:p-6 grid gap-2 content-start">
        <?php if ($category) : ?>
            <span class="font-mono uppercase tracking-wider text-caption text-blue-700 transition-colors duration-200 group-hover:text-blue-500">
                <?php echo esc_html($category->name); ?>
            </span>
        <?php endif; ?>

        <?php the_title(sprintf(
            '<h3 class="font-medium text-lg leading-snug text-blue-900 transition-colors duration-200 group-hover:text-blue-500"><a href="%s" class="text-inherit no-underline hover:no-underline after:absolute after:inset-0 after:content-[\'\'] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2" aria-label="%s">',
            esc_url(get_permalink()),
            esc_attr(sprintf(
                /* translators: %1$s post title, %2$s post-type-specific verb (e.g. "Read full article"). */
                __('%1$s. %2$s.', 'standard'),
                wp_strip_all_tags(get_the_title()),
                $config['label']
            ))
        ), '</a></h3>'); ?>
    </div>

    <div class="flex items-center justify-between text-xs text-blue-500 font-mono py-3 px-4 border-t border-blue-200 transition-colors duration-200 group-hover:border-blue-500">
        <span class="flex items-center gap-1.5">
            <?php icon($config['icon'], ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
            <span><?php echo esc_html($config['cta']); ?></span>
        </span>
        <span class="text-blue-400" aria-hidden="true">
            <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
        </span>
    </div>
</article>
