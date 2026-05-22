<?php
/**
 * Accessories Page — Hero Banner
 *
 * Data wrapper for the shared hero-category part.
 *
 * No stats strip. The hero-metric template is a shared-laws absolute ban;
 * the headline carries the hero on its own.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'accessories-hero',
    'content'    => [
        'kicker'            => __('NTM // ACCESSORIES & UPGRADES', 'standard'),
        'title'             => __('More Out of Every Machine.', 'standard'),
        'subtitle'          => __('Engineered upgrades that help your crew run faster, cleaner, and on more jobs.', 'standard'),
        'cta_primary'       => __('Browse the Catalog', 'standard'),
        'cta_primary_url'   => '#catalog',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        'video'             => '',
        'poster'            => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
    ],
]);
