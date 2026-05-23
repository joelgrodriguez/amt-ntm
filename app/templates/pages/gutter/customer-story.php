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

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/customer-story', null, [
    'section_id'     => 'gutter-customer-story-title',
    'image_position' => 'left',
    'content'        => [
        'eyebrow'  => __('Customer Story', 'standard'),
        'quote'    => __("Going from running someone else's crew to owning my own business — the NTM gutter machine made that possible. I'm fabricating on-site, controlling my schedule, and keeping every dollar I used to hand to a supplier.", 'standard'),
        'name'     => 'Abel',
        'company'  => 'Abel Gutter Install',
        'machine'  => 'MACH II Gutter Machine',
        'image'    => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-002.jpg'),
        'cta_text' => __('Watch the Full Story', 'standard'),
        'cta_url'  => '/learning-center/video/from-crew-to-business-owner-how-abel-used-ntm-gutter-machines-to-grow-his-business-video/',
        'cta_icon' => 'play',
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
