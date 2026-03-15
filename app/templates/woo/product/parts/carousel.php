<?php
/**
 * Machine Product — Reusable Card Carousel
 *
 * Shared carousel for profiles, accessories, and any card-based scroller.
 * Styling via carousel__* classes in components.css.
 *
 * Expected $args:
 *   'carousel_id'  => string  Unique ID for this carousel instance
 *   'eyebrow'      => string  Section eyebrow text
 *   'title'        => string  Section heading
 *   'title_id'     => string  ID for aria-labelledby
 *   'prev_label'   => string  Aria label for prev button
 *   'next_label'   => string  Aria label for next button
 *   'cards'        => array[] Each card: [url, image_url, title, subtitle?]
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

$carousel_id = $args['carousel_id'] ?? 'carousel';
$eyebrow     = $args['eyebrow'] ?? '';
$title       = $args['title'] ?? '';
$title_id    = $args['title_id'] ?? 'carousel-title';
$prev_label  = $args['prev_label'] ?? __('Previous', 'standard');
$next_label  = $args['next_label'] ?? __('Next', 'standard');
$cards       = $args['cards'] ?? [];

if (empty($cards)) {
    return;
}
?>

<div class="flex items-end justify-between gap-4 mb-10">
    <div class="section-header-left mb-0">
        <?php if ($eyebrow) : ?>
            <p class="section-eyebrow"><?php echo esc_html($eyebrow); ?></p>
        <?php endif; ?>
        <div class="section-divider"></div>
        <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title"><?php echo esc_html($title); ?></h2>
    </div>
    <div class="flex gap-2 shrink-0">
        <button type="button"
                data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                class="carousel__nav"
                aria-label="<?php echo esc_attr($prev_label); ?>">
            <span class="text-slate-600">&larr;</span>
        </button>
        <button type="button"
                data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                class="carousel__nav"
                aria-label="<?php echo esc_attr($next_label); ?>">
            <span class="text-slate-600">&rarr;</span>
        </button>
    </div>
</div>

<div id="<?php echo esc_attr($carousel_id); ?>" class="carousel__track">
    <?php foreach ($cards as $card) : ?>
        <a href="<?php echo esc_url($card['url']); ?>" class="carousel__card group">
            <div class="carousel__card-image">
                <?php if (!empty($card['image_url'])) : ?>
                    <img src="<?php echo esc_url($card['image_url']); ?>"
                         alt="<?php echo esc_attr($card['title']); ?>"
                         loading="lazy">
                <?php else : ?>
                    <span class="text-slate-400 text-sm font-mono"><?php echo esc_html($card['title']); ?></span>
                <?php endif; ?>
            </div>
            <div class="grid gap-1">
                <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors leading-tight"><?php echo esc_html($card['title']); ?></h3>
                <?php if (!empty($card['subtitle'])) : ?>
                    <p class="text-xs text-slate-500"><?php echo wp_kses_post($card['subtitle']); ?></p>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>
