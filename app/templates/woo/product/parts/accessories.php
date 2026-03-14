<?php
/**
 * Machine Product — Accessories & Equipment
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section" aria-labelledby="accessories-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">[Accessories]</p>
            <div class="section-divider-center"></div>
            <h2 id="accessories-title" class="section-title">[Complete Your Setup]</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach (['UNIQ Control System', 'Dual Reel Stand', 'Trailer', 'Free Standing Decoiler'] as $name) : ?>
                <div class="border border-slate-200 bg-white p-6 text-center grid gap-2">
                    <div class="bg-slate-100 aspect-square mb-2 flex items-center justify-center">
                        <span class="text-slate-400 text-xs font-mono">[Photo]</span>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900"><?php echo esc_html($name); ?></h3>
                    <p class="text-xs text-slate-500">[Short description]</p>
                    <span class="text-sm font-semibold text-slate-700">[Price]</span>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
