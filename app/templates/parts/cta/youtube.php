<?php
/**
 * Template part for YouTube channel subscription CTA.
 *
 * Displays a call-to-action to subscribe to the Portable Rollforming Channel
 * on YouTube. Used on video post types.
 *
 * @package Standard
 */

?>

<section class="bg-slate-950 border-t border-slate-800">
    <div class="container mx-auto py-12">
        <div class="grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
            <!-- Content -->
            <div class="grid gap-6">
                <span class="text-xs font-mono uppercase tracking-widest text-red-500">
                    <?php esc_html_e('YouTube', 'standard'); ?>
                </span>
                <h2 class="text-2xl lg:text-3xl font-bold font-mono text-white">
                    <?php esc_html_e('Subscribe to the Portable Rollforming Channel', 'standard'); ?>
                </h2>
                <p class="text-slate-400 max-w-lg">
                    <?php esc_html_e('Get notified when we release new videos covering machine tutorials, maintenance tips, and industry insights.', 'standard'); ?>
                </p>
                <div class="flex flex-wrap gap-4 mt-2">
                    <a href="https://www.youtube.com/@NewTechMachinery" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-medium hover:bg-red-700 transition-colors">
                        <?php esc_html_e('Subscribe on YouTube', 'standard'); ?>
                        <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>

            <!-- Image -->
            <div class="hidden lg:block">
                <img
                    src="https://placehold.co/600x400/1e293b/475569?text=YouTube+Channel"
                    alt="<?php esc_attr_e('Portable Rollforming Channel on YouTube', 'standard'); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                />
            </div>
        </div>
    </div>
</section>
