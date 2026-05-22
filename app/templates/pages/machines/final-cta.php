<?php
/**
 * Machines Page — Final CTA
 *
 * Data wrapper for the shared final-cta template part.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/final-cta', null, [
    'section_id' => 'machines-final-cta-title',
    'content'    => [
        'title'             => __('Not Sure Which Machine Is Right for You?', 'standard'),
        'text'              => __("That's exactly why our specialists exist. One call, and we'll match you to the right machine for your jobs, your volume, and your budget.", 'standard'),
        'expect_items'      => [
            __('Review your current jobs and production goals', 'standard'),
            __('Get matched to the right machine and profile', 'standard'),
            __('Walk through pricing and financing options', 'standard'),
        ],
        // Peak-end: fold the Jim Averill quote in here so the page closes
        // on its strongest moment rather than peaking mid-scroll.
        'testimonial'       => [
            'quote'   => __("Once I got the SSR, things really excelled. It's basically a printing press: you put coil on top, turn it on, and every foot that comes out, you're making money.", 'standard'),
            'name'    => 'Jim Averill',
            'company' => __('Gunnison Sheet Metal · SSR MultiPro Jr.', 'standard'),
        ],
        // Specialist portrait + name card intentionally omitted until a
        // real portrait is approved. Shared template skips the right panel.
        'cta_primary'       => __('Talk to a Machine Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Build & Price Your Machine', 'standard'),
        'cta_secondary_url' => '/build-finance/',
    ],
]);
