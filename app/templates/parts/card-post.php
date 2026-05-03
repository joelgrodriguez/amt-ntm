<?php
/**
 * Template part for displaying a post card.
 *
 * Reusable card component for displaying posts in grids.
 * Shows thumbnail, category, title, and post type CTA.
 *
 * @package Standard
 */

if (!defined('ABSPATH')) {
    exit;
}

// Determine post type icon and CTA text
$post_type = get_post_type();
$post_type_config = [
    'post'     => ['icon' => 'file-text', 'cta' => __('Read Article', 'standard')],
    'video'    => ['icon' => 'play', 'cta' => __('Watch Video', 'standard')],
    'download' => ['icon' => 'download', 'cta' => __('View Download', 'standard')],
    'resource' => ['icon' => 'folder', 'cta' => __('View Resource', 'standard')],
    'product'  => ['icon' => 'shopping-cart', 'cta' => __('View Product', 'standard')],
    'profile'  => ['icon' => 'user', 'cta' => __('View Profile', 'standard')],
];

$icon = $post_type_config[$post_type]['icon'] ?? 'link';
$cta_text = $post_type_config[$post_type]['cta'] ?? __('View', 'standard');

?>

<article class="border border-blue-300 bg-white h-full grid grid-rows-[auto_1fr_auto]">
    <a href="<?php the_permalink(); ?>" class="block border-b border-blue-200">
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
            <span class="text-xs text-blue-500 font-mono uppercase tracking-wide"><?php echo esc_html($categories[0]->name); ?></span>
        <?php endif; ?>

        <?php the_title('<h3 class="text-base font-medium line-clamp-2 text-blue-900 hover:text-blue-500">', '</h3>'); ?>
    </a>

    <a href="<?php the_permalink(); ?>" class="flex items-center justify-between text-xs text-blue-500 font-mono py-3 px-4 border-t border-blue-200 no-underline hover:bg-blue-50">
        <span class="flex items-center gap-1.5">
            <?php icon($icon, ['class' => 'w-4 h-4']); ?>
            <span><?php echo esc_html($cta_text); ?></span>
        </span>
        <span class="text-blue-400">
            <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
        </span>
    </a>
</article>
