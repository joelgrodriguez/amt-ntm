<?php
/**
 * Final CTA Section Template Part
 *
 * Closing call-to-action with dual options for different buyer stages.
 * Dark background for visual weight before footer.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);
?>

<section class="py-16 bg-slate-900 md:py-20 lg:py-24" aria-labelledby="final-cta-title">
    <div class="container text-center">

        <h2 id="final-cta-title" class="text-3xl font-bold text-white mb-4 md:text-4xl lg:text-5xl">
            <?php esc_html_e('Ready to Take Control of Your Business?', 'standard'); ?>
        </h2>

        <p class="text-lg text-slate-300 max-w-2xl mx-auto mb-10">
            <?php esc_html_e('Join thousands of contractors who stopped waiting on suppliers and started rolling their own profits.', 'standard'); ?>
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/configurator/" class="btn btn-secondary btn-lg">
                <?php esc_html_e('Configure Your Machine', 'standard'); ?>
                <?php icon('arrow--right', ['class' => 'w-5 h-5 ml-2']); ?>
            </a>
            <a href="#contact" class="btn btn-outline-light btn-lg">
                <?php esc_html_e('Talk to a Specialist', 'standard'); ?>
            </a>
        </div>

    </div>
</section>
