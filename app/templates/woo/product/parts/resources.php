<?php
/**
 * Machine Product — Resources & Support
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section bg-slate-50" aria-labelledby="resources-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">[Resources]</p>
            <div class="section-divider-center"></div>
            <h2 id="resources-title" class="section-title">[Downloads & Support]</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-3xl mx-auto">
            <?php foreach (['Machine Manual', 'Product Brochure', 'Service & Training'] as $resource) : ?>
                <div class="border border-slate-200 bg-white p-6 text-center grid gap-2 hover:border-slate-400 transition-colors">
                    <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($resource); ?></span>
                    <span class="text-xs text-slate-500">[Download PDF]</span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
