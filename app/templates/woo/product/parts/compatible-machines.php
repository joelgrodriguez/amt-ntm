<?php
/**
 * Accessory Product — Compatible Machines Carousel
 *
 * Mirrors the default-accessories carousel pattern: section header
 * with eyebrow + title on the left, prev/next arrows on the right,
 * horizontal scrolling card track using carousel__* classes.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
if (!$product instanceof \WC_Product) {
    return;
}

$cards = \Standard\Woo\Accessories\get_compatible_machine_cards(8);

if ($cards === []) {
    return;
}

$carousel_id = 'compatible-machines-' . $product->get_id();
$title_id    = 'compatible-machines-title';
?>

<section class="section bg-blue-50 border-b border-blue-200" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="section-eyebrow mb-2"><?php esc_html_e('Compatibility', 'standard'); ?></p>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php esc_html_e('Works with these machines', 'standard'); ?>
                </h2>
            </div>
            <div class="flex gap-2 shrink-0 self-end md:self-auto">
                <button type="button"
                        data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Previous machines', 'standard'); ?>">
                    <?php icon('arrow-left', ['class' => 'w-4 h-4 text-blue-700']); ?>
                </button>
                <button type="button"
                        data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Next machines', 'standard'); ?>">
                    <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-700']); ?>
                </button>
            </div>
        </div>

        <div id="<?php echo esc_attr($carousel_id); ?>" class="carousel__track">
            <?php foreach ($cards as $card) : ?>
                <a href="<?php echo esc_url($card['url']); ?>" class="carousel__card group">
                    <div class="carousel__card-image">
                        <?php if (!empty($card['image_id'])) : ?>
                            <?php echo wp_get_attachment_image((int) $card['image_id'], 'product-card', false, [
                                'class' => 'w-full h-full object-contain p-3 transition-transform',
                                'alt'   => $card['title'],
                            ]); ?>
                        <?php else : ?>
                            <span class="text-blue-400 text-sm font-mono"><?php echo esc_html($card['title']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="grid gap-1">
                        <h3 class="text-sm font-medium text-blue-900 group-hover:text-blue-500 transition-colors leading-tight">
                            <?php echo esc_html($card['title']); ?>
                        </h3>
                        <?php if (!empty($card['subtitle'])) : ?>
                            <p class="text-xs text-blue-500"><?php echo wp_kses_post($card['subtitle']); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
