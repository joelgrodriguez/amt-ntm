<?php
/**
 * Accessories Page — Final CTA
 *
 * Data wrapper for the shared final-cta part. Single red moment of the page.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/final-cta', null, [
    'section_id' => 'accessories-final-cta-title',
    'content'    => [
        'title'             => __('Not Sure Which Add-Ons You Need?', 'standard'),
        'text'              => __("Tell a specialist which machine you run (or are sizing). They'll tell you what your setup is missing, and what to skip.", 'standard'),
        'expect_items'      => [
            __('A look at what your current rig is missing', 'standard'),
            __('Pricing on the add-ons that move the needle', 'standard'),
            __('Lead times by part, in stock vs. built-to-order', 'standard'),
        ],
        'testimonial' => [
            'quote'   => __("Bought the SSQ3 in March. Should have bought the dual reel rack and runout table the same week. Don't make my mistake.", 'standard'),
            'name'    => __('Crew Owner, NTM Customer', 'standard'),
            'company' => '',
        ],
        'specialist' => [
            'name'          => __('Rollforming Specialist', 'standard'),
            'role'          => __('NTM Aurora, CO', 'standard'),
            'detail'        => __('30+ years on the floor', 'standard'),
            'image'         => '/wp-content/uploads/specialist-portrait.jpg',
            'image_machine' => '',
            'image_action'  => '',
        ],
        'cta_primary'       => __('Talk to a Specialist', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Back to the Catalog', 'standard'),
        'cta_secondary_url' => '#catalog',
    ],
]);
