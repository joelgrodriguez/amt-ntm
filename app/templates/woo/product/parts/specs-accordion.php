<?php
/**
 * Machine Product — Specifications Accordion
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
?>

<section class="section" aria-labelledby="specs-title">
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow">[Technical Specifications]</p>
            <div class="section-divider"></div>
            <h2 id="specs-title" class="section-title">[Full Details]</h2>
        </div>

        <div class="max-w-4xl grid gap-0">
            <?php foreach (['Machine Dimensions', 'Performance Specs', 'Materials Formed', 'Coil Specifications', 'Power Options', 'Warranty & Patents'] as $section) : ?>
                <details class="border border-slate-200 -mt-px group">
                    <summary class="px-6 py-4 cursor-pointer flex items-center justify-between bg-white hover:bg-slate-50 transition-colors font-semibold text-slate-900">
                        <?php echo esc_html($section); ?>
                        <span class="text-slate-400 transition-transform group-open:rotate-180">&#9660;</span>
                    </summary>
                    <div class="px-6 py-6 border-t border-slate-200 text-sm text-slate-600">
                        [<?php echo esc_html($section); ?> data table placeholder]
                    </div>
                </details>
            <?php endforeach; ?>

            <div class="mt-4">
                <a href="#" class="btn btn-sm btn-outline-dark">Download Full Spec Sheet</a>
            </div>
        </div>

    </div>
</section>
