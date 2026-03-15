<?php
/**
 * Machine Product — Accessories Carousel
 *
 * Queries accessories by product_tag, then delegates to the shared carousel part.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine     = $args['machine'] ?? [];
$product_tag = $machine['accessories']['product_tag'] ?? '';

if (empty($product_tag)) {
    return;
}

$accessories = wc_get_products([
    'tag'    => [$product_tag],
    'limit'  => 12,
    'status' => 'publish',
]);

if (empty($accessories)) {
    return;
}

// Build standardized card data for the carousel — same shape as profiles
$cards = [];
foreach ($accessories as $accessory) {
    /** @var \WC_Product $accessory */
    $image_html = $accessory->get_image_id()
        ? wp_get_attachment_image($accessory->get_image_id(), 'medium')
        : '';

    $cards[] = [
        'url'        => $accessory->get_permalink(),
        'image_html' => $image_html,
        'title'      => $accessory->get_name(),
        'subtitle'   => $accessory->get_price_html() ?: null,
    ];
}
?>

<section class="section pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="accessories-title">
    <div class="container section-content">
        <?php get_template_part('templates/woo/product/parts/carousel', null, [
            'carousel_id' => 'accessories-carousel',
            'eyebrow'     => __('Accessories', 'standard'),
            'title'       => __('Complete Your Setup', 'standard'),
            'title_id'    => 'accessories-title',
            'prev_label'  => __('Previous accessories', 'standard'),
            'next_label'  => __('Next accessories', 'standard'),
            'cards'       => $cards,
        ]); ?>
    </div>
</section>
