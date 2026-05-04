<?php
/**
 * Woo Product — Linked Card
 *
 * @package Standard
 * @var array{card?: array{url: string, image_id: int, title: string, subtitle: string|null}} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$card = $args['card'] ?? null;

if (!is_array($card) || empty($card['url']) || empty($card['title'])) {
    return;
}
?>

<a href="<?php echo esc_url($card['url']); ?>" class="block border border-blue-200 bg-white p-4 grid gap-3 hover:border-blue-400 transition-all group">
    <div class="bg-blue-50 aspect-square flex items-center justify-center overflow-hidden">
        <?php if (!empty($card['image_id'])) : ?>
            <?php echo wp_get_attachment_image((int) $card['image_id'], 'product-card', false, [
                'class' => 'w-full h-full object-contain p-3 transition-transform group-hover:scale-105',
                'alt'   => $card['title'],
            ]); ?>
        <?php else : ?>
            <span class="text-blue-400 text-sm font-mono"><?php echo esc_html($card['title']); ?></span>
        <?php endif; ?>
    </div>
    <div class="grid gap-1">
        <h3 class="text-sm font-medium text-blue-900 group-hover:text-blue-500 transition-colors leading-tight"><?php echo esc_html($card['title']); ?></h3>
        <?php if (!empty($card['subtitle'])) : ?>
            <p class="text-xs text-blue-500"><?php echo wp_kses_post($card['subtitle']); ?></p>
        <?php endif; ?>
    </div>
</a>
