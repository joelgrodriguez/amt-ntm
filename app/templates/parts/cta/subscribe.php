<?php
/**
 * Template part for Learning Center subscription CTA.
 *
 * Displays a call-to-action to subscribe to the NTM Learning Center
 * for videos, articles, downloads, and resources.
 *
 * @package Standard
 *
 * @todo Refactor inline grid pattern styles to use CSS classes from patterns.css.
 *       The BEM-style classes (pattern-square-grid__overlay--top-left) were not
 *       being picked up by Tailwind/Vite. Investigate why and move styles to CSS.
 */

?>

<section class="border-t border-slate-200 bg-slate-100 relative overflow-hidden">
    <!-- Square Grid Pattern - Top Left -->
    <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(to right, #cbd5e1 1px, transparent 1px), linear-gradient(to bottom, #cbd5e1 1px, transparent 1px); background-size: 32px 32px; mask-image: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, transparent 50%); -webkit-mask-image: linear-gradient(135deg, rgba(0,0,0,0.4) 0%, transparent 50%);"></div>
    <!-- Square Grid Pattern - Bottom Right -->
    <div class="absolute inset-0 pointer-events-none" style="background-image: linear-gradient(to right, #cbd5e1 1px, transparent 1px), linear-gradient(to bottom, #cbd5e1 1px, transparent 1px); background-size: 32px 32px; mask-image: linear-gradient(315deg, rgba(0,0,0,0.4) 0%, transparent 50%); -webkit-mask-image: linear-gradient(315deg, rgba(0,0,0,0.4) 0%, transparent 50%);"></div>
    <div class="container mx-auto py-12 relative z-10">
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
