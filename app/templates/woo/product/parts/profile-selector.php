<?php
/**
 * Machine Product — Profile Carousel
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

$machine   = $args['machine'] ?? [];
$tag_slugs = $machine['profiles']['tag_slugs'] ?? [];

if (empty($tag_slugs)) {
    return;
}

get_template_part('templates/woo/product/parts/carousel-section', null, [
    'query_type'    => 'post',
    'post_type'     => 'profile',
    'tag_slugs'     => $tag_slugs,
    'taxonomy'      => 'post_tag',
    'subtitle_tax'  => 'category',
    'section_class' => 'bg-blue-50 border-y border-blue-200',
    'carousel_id'   => 'profiles-carousel',
    'eyebrow'       => __('Panel Profiles', 'standard'),
    'title'         => __('Your Panels, Your Way', 'standard'),
    'title_id'      => 'profiles-title',
    'prev_label'    => __('Previous profiles', 'standard'),
    'next_label'    => __('Next profiles', 'standard'),
]);
