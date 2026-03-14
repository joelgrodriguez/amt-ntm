<?php
/**
 * Machine Product — Profile Selector
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section bg-slate-50" aria-labelledby="profiles-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">[Panel Profiles]</p>
            <div class="section-divider-center"></div>
            <h2 id="profiles-title" class="section-title">[Your Panels, Your Way]</h2>
        </div>

        <div class="flex justify-center gap-3 mb-4">
            <?php foreach (['All', 'Mechanical Seam', 'Snap-Lock', 'Flanged', 'Specialty'] as $tab) : ?>
                <span class="px-4 py-2 text-sm font-medium border border-slate-200 bg-white text-slate-600"><?php echo esc_html($tab); ?></span>
            <?php endforeach; ?>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto">
            <?php for ($i = 1; $i <= 8; $i++) : ?>
                <div class="border border-slate-200 bg-white p-4 text-center grid gap-2">
                    <div class="bg-slate-100 h-20 flex items-center justify-center">
                        <span class="text-slate-400 text-xs font-mono">[Profile SVG]</span>
                    </div>
                    <span class="text-sm font-semibold text-slate-900">[Profile Name <?php echo $i; ?>]</span>
                    <span class="text-xs text-slate-500">[Seam type]</span>
                </div>
            <?php endfor; ?>
        </div>

    </div>
</section>
