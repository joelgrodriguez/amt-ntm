<?php
/**
 * Trailer Page — Final CTA
 *
 * Data wrapper for the shared final-cta part. Closes the loop with the
 * machine-page pricing note: the trailer is a separate line because it's a
 * separately engineered piece of equipment. Routes to a specialist quote.
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/final-cta', null, [
    'section_id' => 'trailer-final-cta-title',
    'content'    => [
        'title' => __('Spec the Trailer with Your Machine', 'standard'),
        'text'  => __('The trailer is its own line on the quote because it\'s its own piece of engineering. An account manager can match the right configuration to your truck and your machine in one conversation.', 'standard'),
        'expect_items' => [
            __('Which trailer fits your truck and your machine', 'standard'),
            __('How the trailer factors into financing', 'standard'),
            __('Lead time and delivery to your yard', 'standard'),
        ],
        'cta_primary'       => __('Request a Quote', 'standard'),
        'cta_primary_url'   => '/contact/',
        'cta_secondary'     => __('Read: 7 reasons to invest in an NTM trailer', 'standard'),
        'cta_secondary_url' => '/learning-center/7-reasons-to-invest-in-an-ntm-trailer-for-your-machine/',
    ],
]);
