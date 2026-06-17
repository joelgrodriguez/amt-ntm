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
        // Who Is NTM brand video — system default. Embeds inline in the
        // hero right column (16:9 Wistia, native player, no poster facade).
        'video'             => 'https://fast.wistia.net/embed/iframe/kdv2kphni1?seo=false&videoFoam=true',
    ],
    'meta' => [
        ['label' => __('Founded', 'standard'), 'value' => '1991'],
        ['label' => __('Countries', 'standard'), 'value' => '40+'],
        ['label' => __('Machines', 'standard'), 'value' => '10'],
    ],
]);
