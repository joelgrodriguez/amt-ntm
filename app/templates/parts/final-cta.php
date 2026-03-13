<?php
/**
 * Shared Template Part — Final CTA
 *
 * Closing call-to-action section with background image and overlay.
 * Overlay opacity and text treatment are configurable.
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args:
 *   - content: array (title, text, cta_primary, cta_primary_url, cta_secondary, cta_secondary_url, image)
 *   - overlay_class: string (default: 'bg-slate-950/70')
 *   - text_shadow: bool (default: false)
 *   - section_id: string for aria-labelledby
 */

declare(strict_types=1);

$content       = $args['content'] ?? [];
$overlay_class = $args['overlay_class'] ?? 'bg-slate-950/70';
$text_shadow   = $args['text_shadow'] ?? false;
$section_id    = $args['section_id'] ?? 'final-cta-title';

if (empty($content)) {
    return;
}

$title_classes = 'text-3xl font-bold text-white md:text-4xl lg:text-5xl';
$text_classes  = 'text-lg max-w-2xl mx-auto';

if ($text_shadow) {
    $title_classes .= ' drop-shadow-lg';
    $text_classes  .= ' text-white/90 drop-shadow-md';
} else {
    $text_classes  .= ' text-slate-300';
}
?>

<section class="relative section overflow-hidden" aria-labelledby="<?php echo esc_attr($section_id); ?>">
    <img
        src="<?php echo esc_url($content['image']); ?>"
        alt=""
        class="absolute inset-0 w-full h-full object-cover"
        loading="lazy"
    >
    <div class="absolute inset-0 <?php echo esc_attr($overlay_class); ?>"></div>

    <div class="relative z-10 container grid gap-8 lg:gap-10 text-center">

        <div class="grid gap-4">
            <h2 id="<?php echo esc_attr($section_id); ?>" class="<?php echo esc_attr($title_classes); ?>">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="<?php echo esc_attr($text_classes); ?>">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url($content['cta_primary_url']); ?>" class="btn btn-secondary btn-lg">
                <?php echo esc_html($content['cta_primary']); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light btn-lg">
                <?php echo esc_html($content['cta_secondary']); ?>
            </a>
        </div>

    </div>
</section>
