<?php
/**
 * Machine Product — FAQ Accordion
 *
 * Bold, high-impact FAQ section using the site-wide accordion pattern.
 * Matches the style from the machines landing page.
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
    <div class="container section-content">

        <div class="section-header-left">
            <p class="section-eyebrow"><?php esc_html_e('FAQ', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="faq-title" class="section-title"><?php esc_html_e('Frequently Asked Questions', 'standard'); ?></h2>
        </div>

        <div class="max-w-4xl" data-accordion>
            <?php foreach ($faqs as $i => $faq) : ?>
                <div class="border-t border-slate-200 last:border-b" data-accordion-item <?php echo $i === 0 ? 'data-accordion-open' : ''; ?>>
                    <button type="button"
                            class="cds-accordion-trigger flex items-center justify-between gap-4 w-full py-5 text-left text-lg font-bold text-slate-900 hover:bg-slate-50 transition-colors duration-150 cursor-pointer"
                            data-accordion-trigger
                            aria-expanded="<?php echo $i === 0 ? 'true' : 'false'; ?>">
                        <span><?php echo esc_html($faq['question']); ?></span>
                        <svg class="cds-accordion-icon shrink-0 w-5 h-5 text-slate-500 transition-transform duration-200 ease-out" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out" data-accordion-content>
                        <div class="pb-6 pr-8 text-base text-slate-600 leading-relaxed border-l-2 border-primary pl-4 ml-0">
                            <?php echo wp_kses_post($faq['answer']); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
