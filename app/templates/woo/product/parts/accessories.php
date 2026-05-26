<?php
/**
 * Machine Product — Accessories Carousel
 *
 * Thin wrapper that passes machine config to the generic carousel-section.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine     = $args['machine'] ?? [];
$product_tag = $machine['accessories']['product_tag'] ?? '';

if (empty($product_tag)) {
    return;
}

get_template_part('templates/woo/product/parts/carousel-section', null, [
    'query_type'    => 'product',
    'product_tag'   => $product_tag,
    'limit'         => 12,
    'section_class' => 'bg-blue-50 border-y border-blue-200',
    'section_id'    => 'machine-accessories',
    'carousel_id'   => 'accessories-carousel',
    'eyebrow'       => __('Accessories', 'standard'),
    'title'         => __('Complete Your Setup', 'standard'),
    'title_id'      => 'accessories-title',
    'prev_label'    => __('Previous accessories', 'standard'),
    'next_label'    => __('Next accessories', 'standard'),
]);
