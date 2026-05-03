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

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? null;
$faqs    = $machine['faq'] ?? [];

if (empty($faqs)) {
    return;
}
?>

<section id="machine-faq" class="section bg-blue-100 pattern-square-grid" aria-labelledby="faq-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left" aria-hidden="true"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right" aria-hidden="true"></div>
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('FAQ', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="faq-title" class="section-title"><?php esc_html_e('Frequently Asked Questions', 'standard'); ?></h2>
        </div>

        <div class="max-w-4xl mx-auto" data-accordion-group>
            <?php foreach ($faqs as $i => $faq) : ?>
                <details class="accordion accordion--lg" <?php echo $i === 0 ? 'open' : ''; ?>>
                    <summary>
                        <?php echo esc_html($faq['question']); ?>
                        <span class="accordion__icon">
                            <?php icon('chevron-down', ['class' => 'w-5 h-5']); ?>
                        </span>
                    </summary>
                    <div class="accordion__body text-base text-blue-600 leading-relaxed">
                        <?php echo wp_kses_post($faq['answer']); ?>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

    </div>
</section>
