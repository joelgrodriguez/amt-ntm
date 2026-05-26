<?php
/**
 * Shared Template Part — FAQ Accordion
 *
 * Two-column layout: accordion on the left, large image on the right.
 * Uses native <details>/<summary> with the standard .accordion size.
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

$content      = $args['content'] ?? [];
$faqs         = $args['faqs'] ?? [];
$section_id   = $args['section_id'] ?? 'faq-accordion-title';
$image_alt    = $args['image_alt'] ?? __('NTM machine being lifted onto a rooftop', 'standard');
// 'video' (default): image keeps a 16:9 composition and sits sticky
// alongside the accordions. Stable framing as accordions open/close.
// 'fill': image stretches to match the accordion column's height via
// object-cover. Caused image to reflow on accordion toggle, so it's no
// longer the default — keep it available for any future caller that
// explicitly opts in.
$image_aspect = $args['image_aspect'] ?? 'video';
$is_fill      = $image_aspect === 'fill';

if (empty($content) || empty($faqs)) {
    return;
}
$faq_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map(static function (array $faq): array {
        return [
            '@type'          => 'Question',
            'name'           => $faq['question'] ?? '',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text'  => $faq['answer'] ?? '',
            ],
        ];
    }, $faqs),
];
?>

<script type="application/ld+json">
<?php echo wp_json_encode($faq_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>
</script>

<section class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <div class="container section-content">
        <div class="section-header-left">
            <?php if (!empty($content['eyebrow'])) : ?>
                <p class="section-eyebrow">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <div class="section-divider"></div>
            <?php endif; ?>
            <h2 id="<?php echo esc_attr($section_id); ?>" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid gap-12 md:grid-cols-2 md:gap-12 lg:gap-16 <?php echo $is_fill ? 'md:items-stretch' : 'md:items-start'; ?>">

            <div data-accordion-group>
                <?php foreach ($faqs as $faq) : ?>
                    <details class="accordion">
                        <summary>
                            <?php echo esc_html($faq['question']); ?>
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
            <?php if ($is_fill) : ?>
                <div class="hidden md:block md:h-full md:min-h-0 md:overflow-hidden">
                    <img
                        src="<?php echo esc_url($content['image']); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                </div>
            <?php else : ?>
                <div class="hidden md:block md:sticky md:top-24">
                    <img
                        src="<?php echo esc_url($content['image']); ?>"
                        alt="<?php echo esc_attr($image_alt); ?>"
                        class="w-full h-auto object-cover aspect-video"
                        loading="lazy"
                    >
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>
