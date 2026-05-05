<?php
/**
 * Product Card Template Part
 *
 * Horizontal card for displaying products/machines.
 * Shows image on left, details on right (title, tagline, price, CTAs).
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Get product data from args or use WooCommerce product
$product = $args['product'] ?? null;

if (!$product) {
    return;
}

$title         = $product['title'] ?? '';
$tagline       = $product['tagline'] ?? '';
// descriptor field available but not displayed
$image         = $product['image'] ?? '';
$price         = $product['price'] ?? '';
$price_label   = $product['price_label'] ?? __('Starting at', 'standard');
$explore_url   = $product['explore_url'] ?? '#';
$build_url     = $product['build_url'] ?? '#';
$badge         = $product['badge'] ?? '';
$is_accessory  = empty($price);
?>

<article class="card-product group relative">
    <div class="card-product__image-wrapper">
        <?php if ($badge) : ?>
            <span class="card-product__badge"><?php echo esc_html($badge); ?></span>
        <?php endif; ?>

        <?php if ($image) : ?>
            <?php \Standard\Images\responsive_image($image, '', 'product-card', [
                'class' => 'card-product__image',
            ]); ?>
        <?php endif; ?>
    </div>

    <div class="card-product__content">
        <?php if ($title) : ?>
            <h3 class="card-product__title">
                <a href="<?php echo esc_url($explore_url); ?>" class="after:content-[''] after:absolute after:inset-0">
                    <?php echo esc_html($title); ?>
                </a>
            </h3>
        <?php endif; ?>

        <?php if ($price) : ?>
            <div class="card-product__price">
                <span class="card-product__price-value"><?php echo esc_html($price); ?></span>
                <span class="card-product__price-label"><?php echo esc_html($price_label); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!$is_accessory) : ?>
            <div class="card-product__cta">
                <?php if ($build_url) : ?>
                    <a href="<?php echo esc_url($build_url); ?>" class="btn btn-sm btn-primary relative z-10">
                        <?php esc_html_e('Build', 'standard'); ?>
                    </a>
                <?php endif; ?>
                <a href="<?php echo esc_url($explore_url); ?>" class="btn btn-sm btn-outline-dark relative z-10">
                    <?php esc_html_e('View', 'standard'); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</article>
