<?php
/**
 * MACH II Family — Final CTA
 *
 * Dark full-bleed closer. One headline, one body line, one red
 * primary button (the page's single 10%-rule red moment), one
 * ghost secondary. No specialist photo, no expect bullets, no
 * testimonial. The closer is the punctuation mark, not a second
 * pitch; the page already made the case.
 *
 * Build & Finance link points at /configurator/machii/, the
 * MACH II–specific configurator route.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="section bg-blue-900 border-t border-blue-800" aria-labelledby="machii-final-cta-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 lg:items-end">

            <div class="grid gap-6 lg:col-span-7">
                <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-500">
                    <?php esc_html_e('Next Step', 'standard'); ?>
                </p>
                <h2
                    id="machii-final-cta-title"
                    class="font-sans font-medium leading-[1] tracking-tight text-white text-3xl md:text-5xl lg:text-6xl"
                >
                    <?php esc_html_e('Pick your MACH II.', 'standard'); ?>
                </h2>
                <p class="text-blue-200 text-lg max-w-xl">
                    <?php esc_html_e('Build a quote in the configurator, or get a specialist on the phone in under a day. Financing available on every model.', 'standard'); ?>
                </p>
            </div>

            <div class="grid gap-4 lg:col-span-5 lg:justify-self-end">
                <p class="font-mono text-xs uppercase tracking-wider text-blue-300">
                    <?php esc_html_e('Free 30-min call · No obligation', 'standard'); ?>
                </p>
                <a href="<?php echo esc_url(\Standard\Url\internal('/configurator/machii/')); ?>" class="btn btn-primary btn-xl" target="_blank" rel="noopener">
                    <?php esc_html_e('Build & Finance Your MACH II', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-outline-light btn-xl">
                    <?php esc_html_e('Talk to a Specialist', 'standard'); ?>
                </a>
            </div>

        </div>
    </div>
</section>
