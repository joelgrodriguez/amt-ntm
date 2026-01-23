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

// Get product data from args or use WooCommerce product
$product = $args['product'] ?? null;

if (!$product) {
    return;
}

$title         = $product['title'] ?? '';
$tagline       = $product['tagline'] ?? '';
$year          = $product['year'] ?? '';
$image         = $product['image'] ?? '';
$price         = $product['price'] ?? '';
$price_label   = $product['price_label'] ?? __('Starting at', 'standard');
$explore_url   = $product['explore_url'] ?? '#';
$build_url     = $product['build_url'] ?? '#';
$badge         = $product['badge'] ?? '';
$is_accessory  = empty($price);
?>

<article class="card-product group">
    <div class="card-product__image-wrapper">
        <?php if ($badge) : ?>
            <span class="card-product__badge"><?php echo esc_html($badge); ?></span>
        <?php endif; ?>

        <?php if ($image) : ?>
            <img
                src="<?php echo esc_url($image); ?>"
                alt="<?php echo esc_attr($title); ?>"
                class="card-product__image"
                loading="lazy"
            >
        <?php endif; ?>
    </div>

    <div class="card-product__content">
        <?php if ($year) : ?>
            <span class="card-product__year"><?php echo esc_html($year); ?></span>
        <?php endif; ?>

        <?php if ($title) : ?>
            <h3 class="card-product__title"><?php echo esc_html($title); ?></h3>
        <?php endif; ?>

        <?php if ($price) : ?>
            <div class="card-product__price">
                <span class="card-product__price-value"><?php echo esc_html($price); ?></span>
                <span class="card-product__price-label"><?php echo esc_html($price_label); ?></span>
            </div>
        <?php endif; ?>

        <div class="card-product__cta">
            <?php if ($is_accessory) : ?>
                <a href="<?php echo esc_url($explore_url); ?>" class="btn btn-sm btn-outline-dark">
                    <?php esc_html_e('View', 'standard'); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($explore_url); ?>" class="btn btn-sm btn-outline-dark">
                    <?php esc_html_e('Explore', 'standard'); ?>
                </a>
                <a href="<?php echo esc_url($build_url); ?>" class="btn btn-sm btn-ghost">
                    <?php esc_html_e('Build', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>
