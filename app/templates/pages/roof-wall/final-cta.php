<?php
/**
 * Roof & Wall Panel Machines — Final CTA
 *
 * Data wrapper for the shared final-cta template part.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/final-cta', null, [
    'section_id' => 'roof-wall-final-cta-title',
    'content'    => [
        'title'             => __('Tired of Paying Supplier Markups?', 'standard'),
        'text'              => __("Most contractors recoup their machine investment within 12–18 months. Talk to us — we'll run the numbers for your business.", 'standard'),
        'expect_items'      => [
            __('Estimate your per-panel savings vs. supplier pricing', 'standard'),
            __('Find the right machine for your most common profiles', 'standard'),
            __('Build a custom quote with financing options', 'standard'),
        ],
        'testimonial'       => [
            'quote'   => __("We were spending a fortune on panels from our supplier. Within a year of getting our machine, it had already paid for itself.", 'standard'),
            'name'    => __('placeholder', 'standard'),
            'company' => __('placeholder', 'standard'),
        ],
        'specialist'        => [
            'name'         => __('John Doe', 'standard'),
            'role'         => __('Account Specialist', 'standard'),
            'detail'       => __('12 Years · Southwest Region', 'standard'),
            'image'        => get_theme_file_uri('assets/images/specialist-placeholder.jpg'),
            'image_machine' => content_url('/uploads/2025/09/20250911_NTM_SSH_1000x1000.png'),
            'image_action'  => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
        ],
        'cta_primary'       => __('Talk to a Machine Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Build & Price Your Machine', 'standard'),
        'cta_secondary_url' => '/build-finance/',
    ],
]);
