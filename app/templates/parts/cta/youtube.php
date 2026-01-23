<?php
/**
 * Template part for YouTube channel subscription CTA.
 *
 * Displays a call-to-action to subscribe to the Portable Rollforming Channel
 * on YouTube. Used on video post types.
 *
 * @package Standard
 */

declare(strict_types=1);

$content = [
    'eyebrow'   => __('YouTube', 'standard'),
    'title'     => __('Subscribe to the Portable Rollforming Channel', 'standard'),
    'text'      => __('Get notified when we release new videos covering machine tutorials, maintenance tips, and industry insights.', 'standard'),
    'cta_text'  => __('Subscribe on YouTube', 'standard'),
    'cta_url'   => 'https://www.youtube.com/@NewTechMachinery',
    'image'     => 'https://placehold.co/600x400/1e293b/475569?text=YouTube+Channel',
    'image_alt' => __('Portable Rollforming Channel on YouTube', 'standard'),
];
?>

<section class="bg-slate-950 border-t border-slate-800">
    <div class="container py-12">
        <div class="grid lg:grid-cols-2 gap-6 lg:gap-12 items-center">
            <!-- Content -->
            <div class="grid gap-6">
                <span class="text-xs font-mono uppercase tracking-widest text-red-500">
                    <?php echo esc_html($content['eyebrow']); ?>
                </span>
                <h2 class="text-2xl lg:text-3xl font-bold font-mono text-white">
                    <?php echo esc_html($content['title']); ?>
                </h2>
                <p class="text-slate-400 max-w-lg">
                    <?php echo esc_html($content['text']); ?>
                </p>
                <div class="flex flex-wrap gap-4 mt-2">
                    <a href="<?php echo esc_url($content['cta_url']); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-medium hover:bg-red-700 transition-colors">
                        <?php echo esc_html($content['cta_text']); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>

            <!-- Image -->
            <div class="hidden lg:block">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($content['image_alt']); ?>"
                    class="w-full h-auto"
                    loading="lazy"
                />
            </div>
        </div>
    </div>
</section>
