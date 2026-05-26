<?php
/**
 * Seamless Gutter Machines — MACH II Family Callout
 *
 * Inline deep-link to the dedicated /machines/machii/ landing. Sits
 * between Abel's customer story and the "which machine" decision
 * helper — the buyer just heard a MACH II testimonial and is the most
 * likely to want a closer look at that family before they pick a
 * model. Single primary CTA, no secondary, so the click target is
 * unambiguous.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$image     = content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-001.jpg');
$image_alt = __('NTM MACH II seamless gutter machine on a jobsite', 'standard');
?>

<section class="section bg-blue-50 border-y border-blue-200" aria-labelledby="machii-callout-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-2 lg:gap-16 lg:items-center">

            <div class="grid gap-6 order-2 lg:order-1">
                <div class="section-header-left">
                    <p class="section-eyebrow"><?php esc_html_e('MACH II 5"/6" Combo', 'standard'); ?></p>
                    <div class="section-divider"></div>
                    <h2 id="machii-callout-title" class="section-title">
                        <?php esc_html_e('Take a closer look at the MACH II Combo.', 'standard'); ?>
                    </h2>
                    <p class="section-subtitle max-w-xl">
                        <?php esc_html_e('Specs, options, workflow, customer story, FAQ — everything you need on the 5"/6" Combo gutter machine NTM has been refining since 1994.', 'standard'); ?>
                    </p>
                </div>

                <div>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/machines/gutter-machines/mach-ii-5-6-combo-gutter-machine/')); ?>" class="btn btn-primary">
                        <?php esc_html_e('Explore the MACH II Combo', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>
            </div>

            <figure class="m-0 order-1 lg:order-2">
                <img
                    src="<?php echo esc_url($image); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    class="w-full h-auto object-cover aspect-video border border-blue-200"
                    loading="lazy"
                    decoding="async"
                >
            </figure>

        </div>
    </div>
</section>
