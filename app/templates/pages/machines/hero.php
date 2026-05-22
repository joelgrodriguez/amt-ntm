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
        // Hero video served from prod; not present in local /uploads/.
        'video'             => 'https://newtechmachinery.com/wp-content/uploads/2025/09/NTM-hero-video.mp4',
        'poster'            => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
    ],
    'meta' => [
        ['label' => __('Founded', 'standard'), 'value' => '1991'],
        ['label' => __('Countries', 'standard'), 'value' => '40+'],
        ['label' => __('Machines', 'standard'), 'value' => '10'],
    ],
]);
