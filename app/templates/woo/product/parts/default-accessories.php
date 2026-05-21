<?php
/**
 * Default Machine — Compatible Accessories
 *
 * Tag-driven query: looks up the accessory tag from accessory-tag-map.php
 * by product slug, then queries all products with that tag. Renders via
 * the existing product-card-link partial for visual parity with other
 * machine surfaces.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Woo\AccessoryTagMap\tag_for_slug;
use function Standard\Woo\Accessories\product_cards;

$product = $args['product'] ?? null;
if (!$product instanceof \WC_Product) {
    return;
}

$tag = tag_for_slug($product->get_slug());
if ($tag === null) {
    return;
}

$accessory_ids = get_posts([
    'post_type'              => 'product',
    'post_status'            => 'publish',
    'posts_per_page'         => 12,
    'fields'                 => 'ids',
    'no_found_rows'          => true,
    'update_post_term_cache' => false,
    'tax_query'              => [
        [
            'taxonomy' => 'product_tag',
            'field'    => 'slug',
            'terms'    => $tag,
        ],
    ],
]);

if (empty($accessory_ids)) {
    return;
}

$accessory_products = array_filter(array_map('wc_get_product', $accessory_ids));
if (empty($accessory_products)) {
    return;
}

$cards = product_cards($accessory_products);
?>

<section id="machine-accessories" class="section" aria-labelledby="default-accessories-title">
    <div class="container section-content">

        <div class="section-header-left mb-12">
            <p class="section-eyebrow"><?php esc_html_e('Compatible Accessories', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="default-accessories-title" class="section-title">
                <?php echo esc_html(sprintf(
                    /* translators: %s is the machine name. */
                    __('Built for the %s', 'standard'),
                    $product->get_name()
                )); ?>
            </h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <?php foreach ($cards as $card) : ?>
                <?php get_template_part('templates/woo/product/parts/product-card-link', null, compact('card')); ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
