<?php
/**
 * Template part for YouTube channel subscription CTA.
 *
 * Closing moment for video posts. Mirrors the Learning Center subscribe
 * CTA layout (pattern-dot-grid dark, headline + meta + arrow CTA) but
 * uses red as the accent — YouTube's brand color — instead of blue.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'  => __('YouTube', 'standard'),
    'title'    => __('Subscribe to the Portable Rollforming Channel.', 'standard'),
    'meta'     => __('Machine tutorials, maintenance tips, industry insights.', 'standard'),
    'cta_text' => __('Subscribe', 'standard'),
    'cta_url'  => 'https://www.youtube.com/@NewTechMachinery',
];
?>

<section class="pattern-dot-grid pattern-dot-grid--dark bg-blue-900 border-t border-blue-800 overflow-hidden">
    <div class="container py-24 lg:py-32">
        <div class="grid lg:grid-cols-[1fr_auto] gap-12 lg:gap-16 items-end">
            <div class="grid gap-6 max-w-3xl">
                <span class="text-caption font-mono uppercase tracking-widest text-red">
                    <?php echo esc_html($content['eyebrow']); ?>
                </span>
                <h2 class="font-sans font-semibold text-heading lg:text-heading-lg text-white leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>
                <p class="font-mono text-blue-300 text-sm uppercase tracking-wider">
                    <?php echo esc_html($content['meta']); ?>
                </p>
            </div>

            <div class="flex lg:justify-end">
                <a
                    href="<?php echo esc_url($content['cta_url']); ?>"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group inline-flex items-center gap-3 px-8 py-4 min-h-14 bg-red border border-red text-white font-mono font-medium text-sm uppercase tracking-widest hover:bg-[#A40D13] hover:border-[#A40D13] focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-4 focus-visible:ring-offset-blue-900 transition-colors"
                >
                    <?php echo esc_html($content['cta_text']); ?>
                    <span class="transition-transform duration-200 group-hover:translate-x-1">
                        <?php icon('arrow-right', ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</section>
