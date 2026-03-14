<?php
/**
 * Machine Product — Social Proof
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section bg-slate-900" aria-labelledby="social-proof-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">[Customer Stories]</p>
            <h2 id="social-proof-title" class="text-3xl font-bold text-white md:text-4xl">[Trusted by Contractors Nationwide]</h2>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <?php for ($i = 1; $i <= 3; $i++) : ?>
                <blockquote class="border border-slate-700 p-6 grid gap-4">
                    <p class="text-slate-300 italic">"[Customer testimonial quote <?php echo $i; ?>]"</p>
                    <footer class="text-sm text-slate-400">
                        <strong class="text-white">[Name]</strong>, [Company], [Location]
                    </footer>
                </blockquote>
            <?php endfor; ?>
        </div>

    </div>
</section>
