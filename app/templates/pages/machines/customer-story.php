<?php
/**
 * Machines Page — Customer Story
 *
 * Data wrapper for the shared customer-story template part.
 * Jim Averill / Gunnison Sheet Metal case study.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/customer-story', null, [
    'section_id'     => 'customer-story-title',
    'image_position' => 'right',
    'background'     => 'bg-blue-50',
    'content'        => [
        'eyebrow'  => __('Customer Story', 'standard'),
        'quote'    => __("Once I got the SSR, things really excelled. It's basically a printing press: you put coil on top, turn it on, and every foot that comes out, you're making money.", 'standard'),
        'name'     => 'Jim Averill',
        'company'  => 'Gunnison Sheet Metal',
        'machine'  => 'SSR MultiPro Jr.',
        'image'    => content_url('/uploads/2026/05/ntm-customer-onsite-002.jpg'),
        'cta_text' => __('Read the Full Story', 'standard'),
        'cta_url'  => '/learning-center/ntm-customers-roi-behind-portable-standing-seam-panel-production/',
        'cta_icon' => 'arrow-right',
    ],
    'stats' => [
        [
            'stat'  => '100+',
            'label' => __('Jobs in 3 Years', 'standard'),
        ],
        [
            'stat'  => '$200K+',
            'label' => __('Estimated Savings', 'standard'),
        ],
        [
            'stat'  => '1,000%',
            'label' => __('Business Growth', 'standard'),
        ],
    ],
]);
