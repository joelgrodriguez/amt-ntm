<?php
/**
 * Seamless Gutter Machines — Final CTA
 *
 * Data wrapper for the shared final-cta template part.
 * Uses lighter overlay with text shadows for readability.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/final-cta', null, [
    'section_id'    => 'gutter-final-cta-title',
    'overlay_class' => 'bg-slate-950/15',
    'text_shadow'   => true,
    'content'       => [
        'title'             => __('Ready to Start Your Gutter Business?', 'standard'),
        'text'              => __("Whether you're adding gutters to your service lineup or scaling an existing operation, we'll help you find the right machine.", 'standard'),
        'cta_primary'       => __('Talk to a Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Build & Finance', 'standard'),
        'cta_secondary_url' => '/build-finance/',
        'image'             => content_url('/uploads/2024/02/NTM-Signage-Main_Office-Wall_2-v2.jpg'),
    ],
]);
