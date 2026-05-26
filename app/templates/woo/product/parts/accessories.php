<?php
/**
 * Flagship Machine — Compatible Accessories Carousel
 *
 * Tag-driven query: pulls products tagged via the machine's accessories
 * product_tag, then renders through the canonical card-accessory partial
 * for visual parity with default-accessories, single-accessory "Related",
 * the accessories landing grid, and the mega menu.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Woo\Accessories\product_cards;

$product     = $args['product'] ?? null;
$machine     = $args['machine'] ?? [];
$product_tag = $machine['accessories']['product_tag'] ?? '';

if (!$product instanceof \WC_Product || $product_tag === '') {
    return;
}

$accessory_products = \Standard\Woo\Cache\get_products([
    'tag'    => [$product_tag],
    'limit'  => 12,
    'status' => 'publish',
]);

if (empty($accessory_products)) {
    return;
}

$cards = product_cards($accessory_products);

if (empty($cards)) {
    return;
}

$carousel_id = 'accessories-carousel';
$title_id    = 'accessories-title';
?>

<section id="machine-accessories" class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content">

        <div class="flex items-end justify-between gap-4 mb-10">
            <div class="section-header-left mb-0">
                <p class="section-eyebrow"><?php esc_html_e('Accessories', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php esc_html_e('Complete Your Setup', 'standard'); ?>
                </h2>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="button"
                        data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Previous accessories', 'standard'); ?>">
                    <span class="text-blue-600">&larr;</span>
                </button>
                <button type="button"
                        data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                        class="carousel__nav"
                        aria-label="<?php esc_attr_e('Next accessories', 'standard'); ?>">
                    <span class="text-blue-600">&rarr;</span>
                </button>
            </div>
        </div>

        <div id="<?php echo esc_attr($carousel_id); ?>" class="carousel__track">
            <?php foreach ($cards as $card) : ?>
                <?php get_template_part('templates/parts/card-accessory', null, [
                    'card'    => $card,
                    'context' => 'carousel',
                ]); ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
