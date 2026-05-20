<?php
/**
 * Template part for Learning Center subscription CTA.
 *
 * Full-bleed drenched section linking to the HubSpot subscription
 * landing page. Designed as the page's closing moment — quiet,
 * committed, no form noise.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'  => __('Stay Informed', 'standard'),
    'title'    => __('The Rollforming Learning Center, in your inbox.', 'standard'),
    'meta'     => __('Monthly digest. Unsubscribe anytime.', 'standard'),
    'cta_text' => __('Subscribe', 'standard'),
    'cta_url'  => 'https://cta-redirect.hubspot.com/cta/redirect/4478417/cc113e10-3511-4ad8-bade-11d3285e9f69',
];
?>

<section class="pattern-dot-grid pattern-dot-grid--dark bg-blue-900 border-t border-blue-800 overflow-hidden">
    <div class="container py-24 lg:py-32">
        <div class="grid lg:grid-cols-[1fr_auto] gap-12 lg:gap-16 items-end">
            <div class="grid gap-6 max-w-3xl">
                <span class="text-caption font-mono uppercase tracking-widest text-blue-300">
                    <?php echo esc_html($content['eyebrow']); ?>
                </span>
                <h2 class="font-mono font-medium text-heading lg:text-heading-lg text-white leading-tight tracking-tight">
                    <?php echo esc_html($content['title']); ?>
                </h2>
                <p class="font-mono text-blue-300 text-sm uppercase tracking-wider">
                    <?php echo esc_html($content['meta']); ?>
                </p>
            </div>

            <div class="flex lg:justify-end">
                <a
                    href="<?php echo esc_url($content['cta_url']); ?>"
                    class="group inline-flex items-center gap-3 px-8 py-4 min-h-14 bg-transparent border border-blue-200/40 text-white font-mono font-medium text-sm uppercase tracking-widest hover:border-white hover:bg-white hover:text-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-4 focus-visible:ring-offset-blue-900 transition-colors"
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
