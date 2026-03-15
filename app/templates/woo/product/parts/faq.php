<?php
/**
 * Machine Product — FAQ Accordion
 *
 * Bold, high-impact FAQ section. Centered header, large question text.
 * Same accordion style as the Full Details (specs) section.
 *
 * @package Standard
 * @var array{machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? null;
$faqs    = $machine['faq'] ?? [];

if (empty($faqs)) {
    return;
}
?>

<section class="section bg-slate-100 pattern-square-grid" aria-labelledby="faq-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left" aria-hidden="true"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right" aria-hidden="true"></div>
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('FAQ', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="faq-title" class="text-4xl font-bold font-mono text-slate-900 md:text-5xl"><?php esc_html_e('Frequently Asked Questions', 'standard'); ?></h2>
        </div>

        <div class="max-w-4xl mx-auto grid gap-0">
            <?php foreach ($faqs as $i => $faq) : ?>
                <details class="border border-slate-200 -mt-px group" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary class="px-6 py-6 cursor-pointer flex items-center justify-between bg-white hover:bg-slate-50 transition-colors text-xl font-bold text-slate-900">
                        <?php echo esc_html($faq['question']); ?>
                        <span class="text-slate-400 transition-transform group-open:rotate-180 shrink-0 ml-4">&#9660;</span>
                    </summary>
                    <div class="px-6 pb-8 pt-2 border-t border-slate-200 text-base text-slate-600 leading-relaxed border-l-2 border-primary ml-6 bg-white">
                        <div class="pl-4">
                            <?php echo wp_kses_post($faq['answer']); ?>
                        </div>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

    </div>
</section>
