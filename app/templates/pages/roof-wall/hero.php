<?php
/**
 * Roof & Wall Panel Machines — Hero
 *
 * Data wrapper for the shared hero-category template part.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'roof-wall-hero',
    'content'    => [
        'kicker'            => __('NTM // ROOF & WALL PANEL MACHINES', 'standard'),
        'title'             => __('Fabricate Panels On-Site. Cut Lead Times by 75%.', 'standard'),
        'subtitle'          => __('Portable rollformers that produce standing seam, flush wall, and board & batten panels right on the jobsite.', 'standard'),
        'cta_primary'       => __('See the Machines', 'standard'),
        'cta_primary_url'   => '#product-grid',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        // Category overview video (Wistia 16:9, plays inline in hero right column).
        'video'             => 'https://fast.wistia.net/embed/iframe/7wwvl1pwh8?seo=false&videoFoam=true',
        'poster'            => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
        'poster_alt'        => __('NTM roof and wall panel machine on a jobsite', 'standard'),
    ],
    'meta' => [
        ['label' => __('Faster', 'standard'),      'value' => '75%'],
        ['label' => __('Profiles', 'standard'),    'value' => '16'],
        ['label' => __('Saved/Sq Ft', 'standard'), 'value' => '$2.25'],
    ],
]);
