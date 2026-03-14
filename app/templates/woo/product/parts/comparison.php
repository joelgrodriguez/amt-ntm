<?php
/**
 * Machine Product — Machine Comparison
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section bg-slate-50" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">[Compare]</p>
            <div class="section-divider-center"></div>
            <h2 id="comparison-title" class="section-title">[Which Machine Is Right for You?]</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-6 max-w-5xl mx-auto">
            <div class="border-2 border-secondary bg-white p-6 grid gap-3 text-center relative">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-secondary text-white text-xs font-semibold px-3 py-1 uppercase tracking-wider">You're Viewing</span>
                <div class="bg-slate-100 aspect-square flex items-center justify-center mt-4">
                    <span class="text-slate-400 text-xs font-mono">[Machine image]</span>
                </div>
                <h3 class="text-lg font-bold text-slate-900">[Current Machine]</h3>
                <p class="text-sm text-slate-500">[Best for: High-volume commercial]</p>
                <span class="text-sm font-semibold text-slate-900">[Price range]</span>
            </div>

            <?php for ($i = 1; $i <= 2; $i++) : ?>
                <div class="border border-slate-200 bg-white p-6 grid gap-3 text-center">
                    <div class="bg-slate-100 aspect-square flex items-center justify-center">
                        <span class="text-slate-400 text-xs font-mono">[Machine image]</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">[Comparison Machine <?php echo $i; ?>]</h3>
                    <p class="text-sm text-slate-500">[Best for: ...]</p>
                    <span class="text-sm font-semibold text-slate-900">[Price range]</span>
                    <a href="#" class="btn btn-sm btn-outline-dark mx-auto">Explore</a>
                </div>
            <?php endfor; ?>
        </div>

    </div>
</section>
