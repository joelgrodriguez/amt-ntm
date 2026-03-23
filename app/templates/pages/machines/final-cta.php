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
        'testimonial'       => [
            'quote'   => __("I didn't know which machine I needed. They walked me through everything and I was rolling panels within 60 days.", 'standard'),
            'name'    => __('placeholder', 'standard'),
            'company' => __('placeholder', 'standard'),
        ],
        'specialist'        => [
            'name'         => __('John Doe', 'standard'),
            'role'         => __('Account Specialist', 'standard'),
            'detail'       => __('12 Years · Southwest Region', 'standard'),
            'image'        => get_theme_file_uri('assets/images/specialist-placeholder.jpg'),
            'image_machine' => content_url('/uploads/2025/10/SSQ3_For-Render_Trailer_Flattened-SQUARE.png'),
            'image_action'  => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
        ],
        'cta_primary'       => __('Talk to a Machine Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Build & Price Your Machine', 'standard'),
        'cta_secondary_url' => '/build-finance/',
    ],
]);
