<?php
/**
 * MACH II Family — Customer Story
 *
 * Reuses the shared customer-story template part with Abel Cisneros
 * / C&S Rain Gutters verbatim from gutter/customer-story.php. Image
 * on the left so the rhythm differs from the workflow grid above.
 *
 * Kept on bg-blue-50 rather than dark; the shared part hard-codes
 * the quote color and the page already has three dark surrounds.
 * Adding a fourth would crowd the rhythm.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/customer-story', null, [
    'section_id'     => 'machii-customer-story-title',
    'image_position' => 'left',
    'content'        => [
        'eyebrow'  => __('Customer Story', 'standard'),
        'quote'    => __("For me, New Tech Machinery has always been the top of the line. Efficiency, technology, consistency, a machine that doesn't break. It keeps going.", 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'machine'  => 'MACH II Gutter Machine',
        'image'    => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-002.jpg'),
        'cta_text' => __('Watch the Full Story', 'standard'),
        'cta_url'  => '/learning-center/video/from-crew-to-business-owner-how-abel-used-ntm-gutter-machines-to-grow-his-business-video/',
        'cta_icon' => 'play',
    ],
    'stats' => [
        ['stat' => '20+', 'label' => __('Years in Business', 'standard')],
        ['stat' => '3',   'label' => __('Crews Running',     'standard')],
        ['stat' => '15',  'label' => __('Employees',         'standard')],
    ],
]);
