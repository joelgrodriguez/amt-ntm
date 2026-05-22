<?php
/**
 * Seamless Gutter Machines — Hero
 *
 * Data wrapper for the shared hero-category template part.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'gutter-hero',
    'content'    => [
        'kicker'            => __('NTM // SEAMLESS GUTTER MACHINES', 'standard'),
        'title'             => __('Seamless Gutters. Fabricated On-Site. Ready for Install.', 'standard'),
        'subtitle'          => __('Portable gutter machines that produce seamless K-style and box gutters right on the jobsite, from raw coil to finished product.', 'standard'),
        'cta_primary'       => __('See the Machines', 'standard'),
        'cta_primary_url'   => '#product-grid',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        'video'             => 'https://newtechmachinery.com/wp-content/uploads/2025/09/NTM-hero-video.mp4',
        'poster'            => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
    ],
    'meta' => [
        ['label' => __('Since', 'standard'),    'value' => '1994'],
        ['label' => __('Machines', 'standard'), 'value' => '4'],
        ['label' => __('Starting', 'standard'), 'value' => '$87K'],
    ],
]);
