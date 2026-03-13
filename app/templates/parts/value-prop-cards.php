<?php
/**
 * Value Proposition Cards — Shared Template Part
 *
 * Three-card horizontal feature strip highlighting key benefits.
 *
 * @package Standard
 *
 * @param array  $content    {eyebrow, title}
 * @param array  $cards      Array of {icon, title, text}.
 * @param string $section_id ID used for aria-labelledby.
 */

declare(strict_types=1);

$content    = $args['content'] ?? [];
$cards      = $args['cards'] ?? [];
$section_id = $args['section_id'] ?? 'value-prop';
?>

<section class="section pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="<?php echo esc_attr($section_id); ?>-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <?php foreach ($cards as $card) : ?>
                <div class="grid gap-4 text-center">
                    <div class="flex justify-center">
                        <div class="w-14 h-14 rounded-full bg-[#e5f0f9] flex items-center justify-center">
                            <?php icon($card['icon'], ['class' => 'w-6 h-6 text-primary']); ?>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">
                        <?php echo esc_html($card['title']); ?>
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        <?php echo esc_html($card['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
