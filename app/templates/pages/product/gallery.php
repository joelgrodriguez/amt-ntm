<?php
/**
 * Machine Product — Gallery / Product Rotator
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section" aria-labelledby="gallery-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">[360° View]</p>
            <h2 id="gallery-title" class="section-title">[See Every Angle]</h2>
        </div>

        <div class="bg-slate-100 aspect-video max-w-4xl mx-auto flex items-center justify-center">
            <span class="text-slate-400 text-sm font-mono">[Product rotator / multi-angle gallery]</span>
        </div>

        <div class="flex justify-center gap-3 max-w-4xl mx-auto">
            <?php for ($i = 1; $i <= 5; $i++) : ?>
                <div class="w-16 h-16 bg-slate-200 flex items-center justify-center">
                    <span class="text-slate-400 text-xs"><?php echo $i; ?></span>
                </div>
            <?php endfor; ?>
        </div>

    </div>
</section>
