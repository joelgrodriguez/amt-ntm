<?php
/**
 * Seamless Gutter Machines — Customer Story
 *
 * Data wrapper for the shared customer-story template part.
 * Placeholder content — no real gutter customer quote available yet.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/customer-story', null, [
    'section_id'     => 'gutter-customer-story-title',
    'image_position' => 'left',
    'content'        => [
        'eyebrow'  => __('Customer Story', 'standard'),
        'quote'    => __("We switched to NTM gutter machines three years ago and haven't looked back. The MACH II runs all day without issues, and our install crews love that they can fabricate on-site instead of hauling pre-made sections.", 'standard'),
        'name'     => 'Gutter Pro Contractor',
        'company'  => 'Placeholder — Real Story Coming Soon',
        'machine'  => 'MACH II 5"/6" Combo',
        'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/04/Nate-training-East-Kentucky-Metal-9-scaled.jpg',
        'cta_text' => __('Read the Full Story', 'standard'),
        'cta_url'  => '/learning-center/',
        'cta_icon' => 'arrow-right',
    ],
    'stats' => [
        [
            'stat'  => '30+',
            'label' => __('Years Industry Standard', 'standard'),
        ],
        [
            'stat'  => '$87K',
            'label' => __('Starting Investment', 'standard'),
        ],
        [
            'stat'  => '1-2 Wk',
            'label' => __('Lead Time', 'standard'),
        ],
    ],
]);
