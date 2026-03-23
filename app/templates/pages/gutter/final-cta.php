<?php
/**
 * Seamless Gutter Machines — Final CTA
 *
 * Data wrapper for the shared final-cta template part.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/final-cta', null, [
    'section_id' => 'gutter-final-cta-title',
    'content'    => [
        'title'             => __('What If the Machine Pays for Itself?', 'standard'),
        'text'              => __("Gutter contractors running NTM machines typically break even within their first year. Let's figure out what the math looks like for you.", 'standard'),
        'expect_items'      => [
            __('Estimate your revenue potential with in-house gutters', 'standard'),
            __('Get matched to the right gutter machine for your market', 'standard'),
            __('Walk through pricing and financing options', 'standard'),
        ],
        'testimonial'       => [
            'quote'   => __("Adding gutters doubled our revenue per job. The machine paid for itself before the first year was up.", 'standard'),
            'name'    => __('placeholder', 'standard'),
            'company' => __('placeholder', 'standard'),
        ],
        'specialist'        => [
            'name'         => __('John Doe', 'standard'),
            'role'         => __('Account Specialist', 'standard'),
            'detail'       => __('12 Years · Southwest Region', 'standard'),
            'image'        => get_theme_file_uri('assets/images/specialist-placeholder.jpg'),
            'image_machine' => content_url('/uploads/2025/09/20250911_NTM_MACH-II-5_1000x1000.png'),
            'image_action'  => content_url('/uploads/2025/09/Machine-on-rooftop-scaled.jpg'),
        ],
        'cta_primary'       => __('Talk to a Machine Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Build & Price Your Machine', 'standard'),
        'cta_secondary_url' => '/build-finance/',
    ],
]);
