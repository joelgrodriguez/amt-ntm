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
        'eyebrow'           => __('Portable Rollforming Machines', 'standard'),
        'title'             => __('Make More Money on Every Metal Roof Job', 'standard'),
        'subtitle'          => __('Save up to $2.25/sq ft by fabricating panels on-site with NTM portable rollformers.', 'standard'),
        'cta_primary'       => __('Explore the Lineup', 'standard'),
        'cta_primary_url'   => '#lineup',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        'video'             => 'https://newtechmachinery.com/wp-content/uploads/2025/09/NTM-hero-video.mp4',
        'poster'            => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
    ],
    'stats' => [
        ['value' => '$2.25', 'label' => __('Saved/Sq Ft', 'standard')],
        ['value' => '16',    'label' => __('Max Profiles', 'standard')],
        ['value' => '30+',   'label' => __('Years', 'standard')],
    ],
]);
