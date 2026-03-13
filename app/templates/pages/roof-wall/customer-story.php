<?php
/**
 * Roof & Wall Panel Machines — Customer Story
 *
 * Data wrapper for the shared customer-story template part.
 * Riley Hays case study with image on the left.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/customer-story', null, [
    'section_id'     => 'roof-wall-customer-story-title',
    'image_position' => 'left',
    'content'        => [
        'eyebrow'  => __('Customer Story', 'standard'),
        'quote'    => __("With NTM equipment, we produce panels faster, cut costs, and reduced our lead times by about 75%. We're winning more metal roofing jobs because we can offer faster delivery than anyone relying on a factory.", 'standard'),
        'name'     => 'Riley Hays',
        'company'  => 'Riley Hays Roofing & Construction',
        'machine'  => 'SSQ II MultiPro',
        'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
        'cta_text' => __('Watch the Full Story', 'standard'),
        'cta_url'  => '/learning-center/video/how-riley-hays-cut-lead-times-by-75-percent-with-ntm-video/',
        'cta_icon' => 'play',
    ],
    'stats' => [
        [
            'stat'  => '75%',
            'label' => __('Shorter Lead Times', 'standard'),
        ],
        [
            'stat'  => '2x',
            'label' => __('More Jobs Won', 'standard'),
        ],
        [
            'stat'  => 'Year 1',
            'label' => __('Machine Paid Off', 'standard'),
        ],
    ],
]);
