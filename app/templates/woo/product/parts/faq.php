<?php
/**
 * Machine Product — FAQ Accordion
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

<section class="section bg-white" aria-labelledby="faq-title">
    <div class="container section-content max-w-3xl">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('FAQ', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="faq-title" class="section-title"><?php esc_html_e('Frequently Asked Questions', 'standard'); ?></h2>
        </div>

        <div class="grid gap-0 border-t border-slate-200">
            <?php foreach ($faqs as $i => $faq) : ?>
                <details class="group border-b border-slate-200" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary class="flex items-center justify-between gap-4 py-5 cursor-pointer list-none text-left font-semibold text-slate-900 hover:text-primary transition-colors">
                        <span><?php echo esc_html($faq['question']); ?></span>
                        <span class="shrink-0 text-slate-400 group-open:rotate-45 transition-transform text-xl leading-none">+</span>
                    </summary>
                    <div class="pb-5 text-slate-600 leading-relaxed">
                        <?php echo wp_kses_post($faq['answer']); ?>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

    </div>
</section>
