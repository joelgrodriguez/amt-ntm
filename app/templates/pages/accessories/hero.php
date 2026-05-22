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
        'eyebrow'           => __('Accessories · Upgrades', 'standard'),
        'title'             => __('No Machine Ships Finished.', 'standard'),
        'subtitle'          => __('Every NTM machine has a setup behind it. Here is what makes it run.', 'standard'),
        'cta_primary'       => __('Browse the Catalog', 'standard'),
        'cta_primary_url'   => '#catalog',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        'video'             => '',
        'poster'            => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
    ],
    'stats' => [],
]);
