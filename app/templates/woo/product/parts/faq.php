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
            <h2 id="faq-title" class="section-title"><?php esc_html_e('Frequently Asked Questions', 'standard'); ?></h2>
        </div>

        <div class="max-w-4xl mx-auto grid gap-0">
            <?php foreach ($faqs as $i => $faq) : ?>
                <details class="accordion" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary class="py-6 text-xl font-bold">
                        <?php echo esc_html($faq['question']); ?>
                        <span class="accordion__icon shrink-0 ml-4">&#9660;</span>
                    </summary>
                    <div class="accordion__body text-base text-slate-600 leading-relaxed border-l-2 border-primary ml-6 bg-white">
                        <div class="pl-4">
                            <?php echo wp_kses_post($faq['answer']); ?>
                        </div>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

    </div>
</section>
