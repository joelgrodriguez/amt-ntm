<?php
/**
 * Shared Template Part — FAQ Accordion
 *
 * Two-column layout: accordion on the left, large image on the right.
 * Uses native <details>/<summary> with .accordion--lg styling.
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args:
 *   - content: array (eyebrow, title, image)
 *   - faqs: array of {question, answer}
 *   - section_id: string for aria-labelledby
 *   - image_alt: string (optional)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content    = $args['content'] ?? [];
$faqs       = $args['faqs'] ?? [];
$section_id = $args['section_id'] ?? 'faq-accordion-title';
$image_alt  = $args['image_alt'] ?? __('NTM machine being lifted onto a rooftop', 'standard');

if (empty($content) || empty($faqs)) {
    return;
}
?>

<section class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container">
        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 md:items-start">

            <div class="grid gap-8 content-start">
                <div class="section-header-left">
                    <p class="section-eyebrow">
                        <?php echo esc_html($content['eyebrow']); ?>
                    </p>
                    <div class="section-divider"></div>
                    <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title">
                        <?php echo esc_html($content['title']); ?>
                    </h2>
                </div>

                <div data-accordion-group>
                    <?php foreach ($faqs as $i => $faq) : ?>
                        <details class="accordion accordion--lg" <?php echo $i === 0 ? 'open' : ''; ?>>
                            <summary>
                                <span class="leading-snug">
                                    <?php echo esc_html($faq['question']); ?>
                                </span>
                                <span class="accordion__icon">
                                    <?php icon('chevron-down', ['class' => 'w-5 h-5']); ?>
                                </span>
                            </summary>
                            <div class="accordion__body text-base text-blue-600 leading-relaxed">
                                <p><?php echo esc_html($faq['answer']); ?></p>
                            </div>
                        </details>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="hidden md:block lg:sticky lg:top-24">
                <img
                    src="<?php echo esc_url($content['image']); ?>"
                    alt="<?php echo esc_attr($image_alt); ?>"
                    class="w-full h-[300px] lg:h-[500px] xl:h-[600px] object-cover"
                    loading="lazy"
                >
            </div>

        </div>
    </div>
</section>
