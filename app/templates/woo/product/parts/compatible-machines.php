<?php
/**
 * Accessory Product — Compatible Machines Carousel
 *
 * Section header with eyebrow + title on the left, prev/next arrows on
 * the right, horizontal scrolling track of canonical card-product cards.
 * Cards render through templates/parts/card-product.php in 'carousel'
 * context so they snap + size inside .carousel__track without forking
 * a separate card variant.
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

$cards = \Standard\Woo\Accessories\get_compatible_machine_product_cards($product, 8);

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
                <?php get_template_part('templates/parts/card-product', null, [
                    'product' => $card,
                    'context' => 'carousel',
                ]); ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
