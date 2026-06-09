<?php
/**
 * Contact FAQ List
 *
 * Bare accordion list (no section wrapper, no image rail) for inline use
 * inside the contact lead-form grid. Answers accept inline HTML via
 * wp_kses_post so existing links survive.
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args:
 *   - faqs: list<array{question: string, answer: string}>
 *   - heading_id: string (optional, for aria-labelledby on caller)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$faqs = $args['faqs'] ?? [];

if (empty($faqs)) {
    return;
}
?>

<div data-accordion-group>
    <?php foreach ($faqs as $faq) : ?>
        <details class="accordion">
            <summary>
                <span class="text-base leading-snug pr-4">
                    <?php echo esc_html($faq['question']); ?>
                </span>
                <span class="accordion__icon">
                    <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
                </span>
            </summary>
            <!-- [&_li+li]:mt-2 + [&_p+p]:mt-3 space multi-line/multi-item answers
                 so stacked steps don't read as one cramped block (Evita). -->
            <div class="accordion__body text-base text-blue-600 leading-relaxed [&_li+li]:mt-2 [&_p+p]:mt-3">
                <?php echo wp_kses_post($faq['answer']); ?>
            </div>
        </details>
    <?php endforeach; ?>
</div>
