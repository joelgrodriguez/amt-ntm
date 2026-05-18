<?php
/**
 * Product Card
 *
 * One template, two layout variants. Same data shape either way.
 *
 *   variant: 'carousel' (default)
 *     Vertical on mobile, horizontal image-left / content-right on tablet+.
 *     Used by the front-page Explore strip.
 *
 *   variant: 'grid'
 *     Vertical always. Image on top, content below.
 *     Used by the desktop mega menu and the mobile menu panel, where cards
 *     sit in a fixed multi-column grid and need to stay readable at 240px.
 *
 * Link model: the whole card is one link to `explore_url` (expanded hit area
 * via ::after on the title anchor). Priced machines get a single inline
 * "Build" CTA that lifts above the card-wide overlay via z-index. Accessories
 * show a quieter inline arrow link to the same destination so affordance
 * parity holds across categories.
 *
 * @package Standard
 *
 * @param array  $args {
 *     @type array  $product Product data (title, image, price, urls, ...).
 *     @type string $variant 'carousel' | 'grid'. Default 'carousel'.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
if (!$product) {
    return;
}

$variant = ($args['variant'] ?? 'carousel') === 'grid' ? 'grid' : 'carousel';

$title          = $product['title'] ?? '';
$category_label = $product['category_label'] ?? '';
$image          = $product['image'] ?? '';
$price          = $product['price'] ?? '';
$price_label    = $product['price_label'] ?? __('Starting at', 'standard');
$explore_url    = $product['explore_url'] ?? '#';
$build_url      = $product['build_url'] ?? '';
$badge          = $product['badge'] ?? '';
$is_accessory   = empty($price);

$root_classes = 'card-product card-product--' . $variant . ' group relative';
?>

<article class="<?php echo esc_attr($root_classes); ?>">
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
        <?php if ($category_label) : ?>
            <p class="card-product__category"><?php echo esc_html($category_label); ?></p>
        <?php endif; ?>

        <?php if ($title) : ?>
            <h3 class="card-product__title">
                <a href="<?php echo esc_url($explore_url); ?>" class="card-product__title-link">
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

        <div class="card-product__cta">
            <?php if (!$is_accessory && $build_url) : ?>
                <a href="<?php echo esc_url($build_url); ?>" class="btn btn-sm btn-primary card-product__cta-build">
                    <?php esc_html_e('Build', 'standard'); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($explore_url); ?>" class="card-product__cta-explore" tabindex="-1" aria-hidden="true">
                    <?php esc_html_e('Explore', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>
