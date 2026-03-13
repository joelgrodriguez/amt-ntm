<?php
/**
 * Roof & Wall Panel Machines — Final CTA
 *
 * Data wrapper for the shared final-cta template part.
 * Uses lighter overlay with text shadows for readability.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/final-cta', null, [
    'section_id'    => 'roof-wall-final-cta-title',
    'overlay_class' => 'bg-slate-950/15',
    'text_shadow'   => true,
    'content'       => [
        'title'             => __('Ready to Roll?', 'standard'),
        'text'              => __("Whether you're expanding your business or buying your first machine, we're here to help you find the right fit.", 'standard'),
        'cta_primary'       => __('Talk to a Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Build & Finance', 'standard'),
        'cta_secondary_url' => '/build-finance/',
        'image'             => content_url('/uploads/2024/02/NTM-Signage-Main_Office-Wall_2-v2.jpg'),
    ],
]);
