<?php
/**
 * Product Card
 *
 * Always vertical: image on top, content below. Used by the front-page
 * Explore strip, the desktop mega menu, and the mobile menu panel.
 *
 * Link model: the whole card is one link to `explore_url` (expanded hit area
 * via ::after on the title anchor). Priced machines get a single inline
 * "Build & Quote" CTA that lifts above the card-wide overlay via z-index.
 * Accessories show a quieter inline arrow link to the same destination for
 * affordance parity. That arrow link is tabindex=-1 / aria-hidden since the
 * whole card already routes there.
 *
 * @package Standard
 *
 * @param array  $args {
 *     @type array $product Product data (title, image, price, urls, ...).
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

$title          = $product['title'] ?? '';
$category_label = $product['category_label'] ?? '';
$image          = $product['image'] ?? '';
$price          = $product['price'] ?? '';
$price_label    = $product['price_label'] ?? __('Starting at', 'standard');
$explore_url    = $product['explore_url'] ?? '#';
$build_url      = $product['build_url'] ?? '';
$badge          = $product['badge'] ?? '';
$is_accessory   = empty($price);

?>

<article class="card-product group relative">
    <div class="card-product__image-wrapper relative">
        <?php if ($badge) : ?>
            <!-- Flagship-style badge overlay. Mono uppercase, red
                 ground, text-blue-50 (the tinted near-white). Pinned
                 top-left over the image so the content column below
                 stays free of secondary chrome. -->
            <span class="card-product__badge absolute top-0 left-0 z-10 inline-flex items-center px-3 py-2 bg-red text-blue-50 font-mono uppercase tracking-wider text-xs font-medium">
                <?php echo esc_html($badge); ?>
            </span>
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
                    <?php esc_html_e('Build & Quote', 'standard'); ?>
                </a>
            <?php else : ?>
                <!-- Fallback CTA when no configurator exists. Promoted
                     to a real outline button so the card has the same
                     visual weight as siblings with Build & Quote. The
                     relative+z-10 lifts it above the card-wide ::after
                     overlay so it stays independently clickable. -->
                <a href="<?php echo esc_url($explore_url); ?>" class="btn btn-sm btn-outline-dark relative z-10">
                    <?php esc_html_e('Explore', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>
