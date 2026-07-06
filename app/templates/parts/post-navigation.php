<?php
/**
 * Template part for post navigation.
 *
 * Displays previous/next post links. Single-side mode (only prev or only
 * next) aligns the surviving card to the appropriate edge instead of
 * filling the empty slot with a placeholder.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$prev_post = get_previous_post();
$next_post = get_next_post();

if (!$prev_post && !$next_post) {
    return;
}

$single_side = (!$prev_post || !$next_post);
?>

<nav class="pt-6 lg:pt-12 border-t border-blue-200" aria-label="<?php esc_attr_e('Post navigation', 'standard'); ?>">
    <div class="grid md:grid-cols-2 gap-6">
        <?php if ($prev_post) : ?>
            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>"
               class="block p-6 border border-blue-200 bg-white no-underline transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 <?php echo $single_side ? 'md:max-w-md' : ''; ?>">
                <span class="flex items-center gap-2 text-caption text-blue-500 font-mono uppercase tracking-widest mb-3">
                    <?php icon('arrow-left', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                    <span><?php esc_html_e('Previous', 'standard'); ?></span>
                </span>
                <span class="block font-mono font-medium text-base text-blue-900 leading-snug line-clamp-2"><?php echo esc_html($prev_post->post_title); ?></span>
            </a>
        <?php endif; ?>

        <?php if ($next_post) : ?>
            <a href="<?php echo esc_url(get_permalink($next_post)); ?>"
               class="block p-6 border border-blue-200 bg-white no-underline text-right transition-colors duration-200 hover:border-blue-500 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 <?php echo $single_side ? 'md:max-w-md md:justify-self-end' : ''; ?>">
                <span class="flex items-center justify-end gap-2 text-caption text-blue-500 font-mono uppercase tracking-widest mb-3">
                    <span><?php esc_html_e('Next', 'standard'); ?></span>
                    <?php icon('arrow-right', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                </span>
                <span class="block font-mono font-medium text-base text-blue-900 leading-snug line-clamp-2"><?php echo esc_html($next_post->post_title); ?></span>
            </a>
        <?php endif; ?>
    </div>
</nav>
