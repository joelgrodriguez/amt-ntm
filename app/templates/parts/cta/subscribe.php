<?php
/**
 * Template part for Learning Center subscription CTA.
 *
 * Displays a call-to-action to subscribe to the NTM Learning Center
 * for videos, articles, downloads, and resources.
 *
 * @package Standard
 */

?>

<section class="pattern-square-grid border-t border-slate-200 bg-slate-100">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>
    <div class="container mx-auto py-12">
        <div class="grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
            <!-- Content -->
            <div class="grid gap-6">
                <span class="text-xs font-mono uppercase tracking-widest text-secondary">
                    <?php esc_html_e('Stay Informed', 'standard'); ?>
                </span>
                <h2 class="text-2xl lg:text-3xl font-bold font-mono text-slate-900">
                    <?php esc_html_e('Subscribe to the Learning Center', 'standard'); ?>
                </h2>
                <p class="text-slate-600 max-w-lg">
                    <?php esc_html_e('Get new videos, articles, downloads, and other valuable resources sent directly to your inbox.', 'standard'); ?>
                </p>
                <div class="flex flex-wrap gap-4 mt-2">
                    <a href="#subscribe" class="inline-flex items-center gap-2 px-6 py-3 bg-secondary text-white font-medium hover:bg-slate-700 transition-colors">
                        <?php esc_html_e('Subscribe Now', 'standard'); ?>
                        <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>

            <!-- Image -->
            <div class="hidden lg:block">
                <img
                    src="https://newtechmachinery.com/wp-content/uploads/2023/01/NTM-rollforming-learning-center.png"
                    alt="<?php esc_attr_e('NTM Learning Center Resources', 'standard'); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                />
            </div>
        </div>
    </div>
</section>
