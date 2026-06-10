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
        'kicker'            => __('PORTABLE ROLLFORMING // SINCE 1991', 'standard'),
        'title'             => __('Make More Money<br class="hidden lg:inline"> on Every Metal Roof Job.', 'standard'),
        'subtitle'          => __('Fabricate panels and gutters on-site with NTM portable rollformers. Up to $2.25 saved on every square foot.', 'standard'),
        'cta_primary'       => __('View All NTM Machines', 'standard'),
        'cta_primary_url'   => '#lineup',
        // Who Is NTM brand video — system default. The video panel
        // renders inline in the hero right column (16:9 Wistia embed).
        'video'             => 'https://fast.wistia.net/embed/iframe/kdv2kphni1?seo=false&videoFoam=true',
        // TODO(asset): Alex to deliver "Who Is NTM?" video thumbnail (Monday pre-demo).
        // Drop file into uploads and update poster path below.
        'poster'            => content_url('/uploads/2026/05/ntm-standing-seam-roof-007.jpg'),
        'poster_alt'        => __('Who Is NTM? — Portable rollforming channel company overview thumbnail', 'standard'),
    ],
    'meta' => [
        ['label' => __('Founded', 'standard'), 'value' => '1991'],
        ['label' => __('Countries', 'standard'), 'value' => '40+'],
        ['label' => __('Machines', 'standard'), 'value' => '10'],
    ],
]);
