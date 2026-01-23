<?php
/**
 * Template part for post navigation.
 *
 * Displays previous/next post links.
 * Wrap in a container if needed at the template level.
 *
 * @package Standard
 */

$prev_post = get_previous_post();
$next_post = get_next_post();

if (!$prev_post && !$next_post) {
    return;
}
?>

<nav class="pt-6 lg:pt-12 border-t border-slate-200" aria-label="<?php esc_attr_e('Post navigation', 'Standard'); ?>">
    <div class="grid md:grid-cols-2 gap-6">
        <?php if ($prev_post) : ?>
            <a href="<?php echo esc_url(get_permalink($prev_post)); ?>" class="block p-4 border border-slate-300 bg-white no-underline hover:bg-slate-50 transition-colors">
                <span class="flex items-center gap-2 text-xs text-slate-500 font-mono uppercase tracking-wide mb-2">
                    <?php icon('arrow-left', ['class' => 'w-3 h-3']); ?>
                    <span class="font-mono"><?php esc_html_e('Previous', 'standard'); ?></span>
                </span>
                <span class="block text-base text-primary line-clamp-2"><?php echo esc_html($prev_post->post_title); ?></span>
            </a>
        <?php else : ?>
            <div></div>
        <?php endif; ?>

        <?php if ($next_post) : ?>
            <a href="<?php echo esc_url(get_permalink($next_post)); ?>" class="block p-4 border border-slate-300 bg-white no-underline hover:bg-slate-50 transition-colors text-right">
                <span class="flex items-center justify-end gap-2 text-xs text-slate-500 font-mono uppercase tracking-wide mb-2">
                    <span class="font-mono"><?php esc_html_e('Next', 'standard'); ?></span>
                    <?php icon('arrow-right', ['class' => 'w-3 h-3']); ?>
                </span>
                <span class="block text-base text-primary line-clamp-2"><?php echo esc_html($next_post->post_title); ?></span>
            </a>
        <?php endif; ?>
    </div>
</nav>
