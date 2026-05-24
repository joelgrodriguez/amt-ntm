<?php
/**
 * Seamless Gutter Machines — Customer Story
 *
 * Data wrapper for the shared customer-story template part.
 * Abel Cisneros / C&S Rain Gutters case study.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/customer-story', null, [
    'section_id'     => 'gutter-customer-story-title',
    'image_position' => 'left',
    'content'        => [
        'eyebrow'  => __('Customer Story', 'standard'),
        'quote'    => __("For me, New Tech Machinery has always been the top of the line. Efficiency, technology, consistency — a machine that doesn't break. It keeps going.", 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'machine'  => 'MACH II Gutter Machine',
        'image'    => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-002.jpg'),
        'cta_text' => __('Watch the Full Story', 'standard'),
        'cta_url'  => '/learning-center/video/from-crew-to-business-owner-how-abel-used-ntm-gutter-machines-to-grow-his-business-video/',
        'cta_icon' => 'play',
    ],
    'stats' => [
        [
            'stat'  => '20+',
            'label' => __('Years in Business', 'standard'),
        ],
        [
            'stat'  => '3',
            'label' => __('Crews Running', 'standard'),
        ],
        [
            'stat'  => '15',
            'label' => __('Employees', 'standard'),
        ],
    ],
]);
