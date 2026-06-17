<?php
/**
 * Accessory Card
 *
 * Single canonical accessory card used wherever accessories are listed:
 * catalog grid on the Accessories landing, "Built for the <machine>"
 * default-accessories carousel, "Related" strip on single-accessory,
 * and the mega menu's Accessories tab.
 *
 * 16:9 image well on blue-50, mono-less heading, price_html as subtitle.
 * Hover shifts the heading color (same affordance as `card-product`).
 *
 * @package Standard
 *
 * @param array{
 *   url: string,
 *   image_id: int,
 *   title: string,
 *   subtitle: string|null
 * } $card
 * @param string $context 'grid' (default) or 'carousel'. carousel adds the
 *                        .carousel__card hook so the card carries snap +
 *                        responsive widths inside .carousel__track.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$card    = $args['card'] ?? null;
$context = $args['context'] ?? 'grid';

if (!is_array($card) || empty($card['url']) || empty($card['title'])) {
    return;
}

// .carousel__card on the anchor itself so the card snaps + sizes inside
// .carousel__track. Visual identity stays on .card-accessory; the
// carousel chassis is stripped via carousel.css.
$root_classes = 'card-accessory group';
if ($context === 'carousel') {
    $root_classes .= ' carousel__card';
}
?>

<a href="<?php echo esc_url($card['url']); ?>" class="<?php echo esc_attr($root_classes); ?>">
    <div class="card-accessory__image">
        <?php if (!empty($card['image_id'])) : ?>
            <?php echo wp_get_attachment_image((int) $card['image_id'], 'product-card', false, [
                'class' => 'w-full h-full object-contain p-2 transition-transform group-hover:scale-105',
                'alt'   => $card['title'],
            ]); ?>
        <?php else : ?>
            <span class="card-accessory__placeholder"><?php echo esc_html($card['title']); ?></span>
        <?php endif; ?>
    </div>
    <div class="card-accessory__body">
        <h3 class="card-accessory__title">
            <?php echo esc_html($card['title']); ?>
        </h3>
        <?php if (!empty($card['subtitle'])) : ?>
            <p class="card-accessory__subtitle"><?php echo wp_kses_post($card['subtitle']); ?></p>
        <?php endif; ?>
    </div>
</a>
