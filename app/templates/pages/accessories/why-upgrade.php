<?php
/**
 * Accessories Page — Why-Upgrade Thesis Section
 *
 * The page's argument. One eyebrow, one short headline, one paragraph.
 * No icons, no three-column trio. Hard-left aligned over a blue-50 band
 * separated from sections above and below by a full-bleed hairline.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="bg-blue-50 border-y border-blue-200" aria-labelledby="why-upgrade-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="grid gap-8 lg:grid-cols-12 lg:gap-16 items-start">

            <div class="lg:col-span-4">
                <p class="section-eyebrow">
                    <?php esc_html_e('The Premise', 'standard'); ?>
                </p>
                <div class="section-divider mt-4"></div>
            </div>

            <div class="lg:col-span-8">
                <h2 id="why-upgrade-title" class="font-sans font-medium tracking-tight text-blue-900 text-2xl md:text-3xl lg:text-4xl leading-tight">
                    <?php esc_html_e('A rollformer without a reel stand is a rollformer you can\'t actually run.', 'standard'); ?>
                </h2>
                <p class="mt-6 text-blue-600 text-base md:text-lg max-w-prose">
                    <?php esc_html_e('Every machine on this site is the headline. The accessories are the line items that turn it into a working setup: how the coil feeds, how the panel runs out, how the operator drives the length, how it gets from the truck to the job. The question isn\'t whether you\'ll upgrade. It\'s when, and which.', 'standard'); ?>
                </p>
            </div>

        </div>
    </div>
</section>
