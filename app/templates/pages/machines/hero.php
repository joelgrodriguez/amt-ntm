<?php
/**
 * Machines Page — Hero Banner
 *
 * Data wrapper for the shared hero-category template part.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/hero-category', null, [
    'section_id' => 'machines-hero',
    'content'    => [
        'kicker'            => __('NTM // PORTABLE ROLLFORMING // SINCE 1991', 'standard'),
        'title'             => __('Make More Money on Every Metal Roof Job', 'standard'),
        'subtitle'          => __('Fabricate panels and gutters on-site with NTM portable rollformers. Up to $2.25 saved on every square foot.', 'standard'),
        'cta_primary'       => __('See the Lineup', 'standard'),
        'cta_primary_url'   => '#lineup',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        // Who Is NTM brand video — system default. The video panel
        // renders inline in the hero right column (16:9 Wistia embed).
        'video'             => 'https://fast.wistia.net/embed/iframe/kdv2kphni1?seo=false&videoFoam=true',
        'poster'            => content_url('/uploads/2026/05/ntm-standing-seam-roof-007.jpg'),
        'poster_alt'        => __('NTM machine on a rooftop', 'standard'),
    ],
    'meta' => [
        ['label' => __('Founded', 'standard'), 'value' => '1991'],
        ['label' => __('Countries', 'standard'), 'value' => '40+'],
        ['label' => __('Machines', 'standard'), 'value' => '10'],
    ],
]);
