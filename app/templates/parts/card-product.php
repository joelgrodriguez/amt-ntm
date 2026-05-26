<?php
/**
 * Product Card
 *
 * Canonical machine card. Used on the front-page Explore strip, /machines
 * lineup grid, accessory Compatibility carousels, the desktop mega menu,
 * and the mobile menu panel. One card, no variants.
 *
 * Body copy is a single short sentence (`description`) — no bullet lists.
 * Description clamps to 2 lines so varying lengths stay aligned in a grid.
 *
 * Link model: the whole card is one link to `explore_url` (expanded hit area
 * via ::after on the title anchor). Machines get a single inline filled
 * "Build & Quote" CTA pointing at `explore_url` — the Woo single-machine
 * template hosts the configurator entry point, not the card. Accessories get
 * a quieter outline "Explore" CTA to the same destination. Either CTA lifts
 * above the card-wide ::after overlay via z-index.
 *
 * @package Standard
 *
 * @param array  $args {
 *     @type array $product {
 *         @type string $title          Required. Card title.
 *         @type string $image          Image URL.
 *         @type string $explore_url    Required. Product page URL — every CTA points here.
 *         @type string $category_label Eyebrow label above title.
 *         @type string $description    One-sentence body copy (2-line clamp).
 *         @type string $price          Display price ('' for accessories).
 *         @type string $price_label    Defaults to "Starting at".
 *         @type string $badge          Optional badge text ("Flagship", "Featured", ...).
 *     }
 *     @type string $context          Layout hint. 'carousel' adds .carousel__card.
 *     @type bool   $show_description Default true. Pass false in dense surfaces (mega menu).
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

// Strip trademark glyphs (™ ®) from card titles. The marks belong on
// product pages and legal copy, not in every card across the site —
// they add visual noise and break the title's clean alignment.
$title          = str_replace(["\u{2122}", "\u{00AE}"], '', $product['title'] ?? '');
$category_label = $product['category_label'] ?? '';
$description    = $product['description'] ?? ($product['descriptor'] ?? '');
$image          = $product['image'] ?? '';
$price          = $product['price'] ?? '';
$price_label    = $product['price_label'] ?? __('Starting at', 'standard');
$explore_url    = $product['explore_url'] ?? '#';
$badge          = $product['badge'] ?? '';
// Accessory vs machine determines CTA style (outline Explore vs filled
// Build & Quote). Honor an explicit flag if the producer set one; fall
// back to "no price === accessory" for legacy callers. A machine without
// a price is still a machine — explicit beats inferred.
$is_accessory   = $product['is_accessory'] ?? empty($price);

// Context is a *layout* hint, not a card variant. 'carousel' adds the
// .carousel__card hook so the card snaps + sizes inside .carousel__track.
// The card's visual style is identical in every context.
$context     = $args['context'] ?? '';
$root_class  = 'card-product group relative';
if ($context === 'carousel') {
    $root_class .= ' carousel__card';
}

// Surface-level toggle. Cards in dense surfaces (mega menu) opt out of
// the body copy so the title + price + CTA carry the row.
$show_description = $args['show_description'] ?? true;

?>

<article class="<?php echo esc_attr($root_class); ?>">
    <div class="card-product__image-wrapper">
        <?php if ($badge) : ?>
            <!-- Flagship badge: styling lives on .card-product__badge
                 in woo/product-card.css. Mono uppercase, bg-red,
                 text-blue-50, pinned top-right over the image. -->
            <span class="card-product__badge"><?php echo esc_html($badge); ?></span>
        <?php endif; ?>

        <?php if ($image) : ?>
            <?php \Standard\Images\responsive_image($image, '', 'product-card', [
                'class' => 'card-product__image',
            ]); ?>
        <?php else : ?>
            <?php \Standard\Images\fallback_image(['class' => 'card-product__image']); ?>
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

        <?php if ($description && $show_description) : ?>
            <p class="card-product__description"><?php echo esc_html($description); ?></p>
        <?php endif; ?>

        <div class="card-product__footer">
            <?php if ($price) : ?>
                <div class="card-product__price">
                    <span class="card-product__price-value"><?php echo esc_html($price); ?></span>
                    <span class="card-product__price-label"><?php echo esc_html($price_label); ?></span>
                </div>
            <?php endif; ?>

            <div class="card-product__cta">
                <?php if (!$is_accessory) : ?>
                    <a href="<?php echo esc_url($explore_url); ?>" class="btn btn-sm btn-outline-dark card-product__cta-build relative z-10">
                        <?php esc_html_e('Build & Quote', 'standard'); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url($explore_url); ?>" class="btn btn-sm btn-outline-dark relative z-10">
                        <?php esc_html_e('Explore', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</article>
