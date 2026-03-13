<?php
/**
 * Roof & Wall Panel Machines — Hero
 *
 * Data wrapper for the shared hero-asymmetric template part.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/hero-asymmetric', null, [
    'section_id'       => 'roof-wall-hero',
    'content_position' => 'left',
    'content'          => [
        'eyebrow'           => __('Roof & Wall Panel Machines', 'standard'),
        'title'             => __('Fabricate Panels On-Site. Cut Lead Times by 75%.', 'standard'),
        'subtitle'          => __('Portable rollformers that produce standing seam, flush wall, and board & batten panels right on the jobsite.', 'standard'),
        'cta_primary'       => __('See the Machines', 'standard'),
        'cta_primary_url'   => '#product-grid',
        'cta_secondary'     => __('Talk to a Specialist', 'standard'),
        'cta_secondary_url' => '/contact/',
        'video'             => 'https://newtechmachinery.com/wp-content/uploads/2025/09/NTM-hero-video.mp4',
        'poster'            => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
    ],
    'stats' => [
        ['value' => '75%',   'label' => __('Faster', 'standard')],
        ['value' => '16',    'label' => __('Profiles', 'standard')],
        ['value' => '$2.25', 'label' => __('Saved/Sq Ft', 'standard')],
    ],
]);
