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
$product_slugs = $machine['accessories']['product_slugs'] ?? [];

if (!$product instanceof \WC_Product || $product_tag === '') {
    return;
}

$accessory_products = \Standard\Woo\Cache\get_products([
    'tag'    => [$product_tag],
    'limit'  => -1,
    'status' => 'publish',
]);

if (is_array($product_slugs)) {
    $products_by_id = [];
    foreach ($accessory_products as $accessory_product) {
        $products_by_id[$accessory_product->get_id()] = $accessory_product;
    }

    foreach ($product_slugs as $product_slug) {
        $product_slug = sanitize_title((string) $product_slug);
        if ($product_slug === '') {
            continue;
        }

        $product_post = get_page_by_path($product_slug, OBJECT, 'product');
        if (!$product_post instanceof \WP_Post) {
            continue;
        }

        $additional_products = \Standard\Woo\Cache\get_products([
            'include' => [$product_post->ID],
            'limit'   => 1,
            'status'  => 'publish',
        ]);

        foreach ($additional_products as $additional_product) {
            $products_by_id[$additional_product->get_id()] = $additional_product;
        }
    }

    $accessory_products = array_values($products_by_id);
}

if (empty($accessory_products)) {
    return;
}

$cards = product_cards($accessory_products);

if (empty($cards)) {
    return;
}

$accessory_count = count($cards);
$carousel_id     = 'accessories-carousel';
$title_id        = 'accessories-title';
$show_all_label  = sprintf(
    /* translators: %d is the number of compatible accessories. */
    __('See All %d Accessories', 'standard'),
    $accessory_count
);
?>

<section id="machine-accessories" class="section bg-blue-50" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content" data-expandable-list>

        <div class="flex items-end justify-between gap-4 mb-10">
            <div class="section-header-left mb-0">
                <p class="section-eyebrow"><?php esc_html_e('Accessories', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php esc_html_e('Complete Your Setup', 'standard'); ?>
                </h2>
                <?php /* TODO(copy): confirm wording with team — Evita asked for an explainer under this headline. */ ?>
                <p class="section-subtitle max-w-xl">
                    <?php esc_html_e('The carts, covers, and add-ons built to run with this machine. Add what your crew needs to work faster on site.', 'standard'); ?>
                </p>
            </div>
        </div>

        <div id="<?php echo esc_attr($carousel_id); ?>"
             data-expandable-list-content
             class="t-resize grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($cards as $index => $card) : ?>
                    <div<?php if ($index >= 4) : ?> data-expandable-list-expanded data-expandable-list-no-js-visible<?php endif; ?>>
                    <?php get_template_part('templates/parts/card-accessory', null, [
                        'card'    => $card,
                        'context' => 'grid',
                    ]); ?>
                    </div>
                <?php endforeach; ?>
        </div>

        <?php if ($accessory_count > 4) : ?>
            <?php get_template_part('templates/parts/expandable-list-toggle', null, [
                'region_id'      => $carousel_id,
                'show_label'     => $show_all_label,
                'collapse_label' => __('Collapse Accessories', 'standard'),
            ]); ?>
        <?php endif; ?>

    </div>
</section>
