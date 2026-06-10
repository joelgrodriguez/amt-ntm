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
        'kicker'            => __('ROOF & WALL PANEL MACHINES', 'standard'),
        'title'             => __('Fabricate Panels On-Site. Cut Lead Times by 75%.', 'standard'),
        'subtitle'          => __('Portable rollformers that produce standing seam, flush wall, and board & batten panels right on the jobsite.', 'standard'),
        'cta_primary'       => __('See the Machines', 'standard'),
        'cta_primary_url'   => '#product-grid',
        // video removed — Adam Copel scoped Q2 off the machines promotion flow.
        // Poster renders as a static image only; no play icon, no Wistia embed.
        'poster'            => content_url('/uploads/2026/05/ntm-customer-onsite-001.jpg'),
        'poster_alt'        => __('NTM customer running a roof panel machine on-site', 'standard'),
    ],
    'meta' => [
        ['label' => __('Faster', 'standard'),      'value' => '75%'],
        ['label' => __('Profiles', 'standard'),    'value' => '16'],
        ['label' => __('Saved/Sq Ft', 'standard'), 'value' => '$2.25'],
    ],
]);
