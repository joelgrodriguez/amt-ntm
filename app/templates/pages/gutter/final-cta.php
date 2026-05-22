<?php
/**
 * Seamless Gutter Machines — Final CTA
 *
 * Closing CTA for /seamless-gutter-machines/. Uses the lean centered
 * closer (matches the /machines parent and the front page) instead of
 * the heavy two-column with a specialist photo collage.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'gutter-final-cta-title',
    'title'             => __('Pick the Machine. Start Making Money.', 'standard'),
    'text'              => __('Gutter contractors running NTM machines typically break even within the first year. Build a quote in your browser, or get one of our specialists on the phone.', 'standard'),
    'cta_primary'       => __('Build & Price Your Machine', 'standard'),
    'cta_primary_url'   => '/build-finance/',
    'cta_secondary'     => __('Talk to a Specialist', 'standard'),
    'cta_secondary_url' => '/contact/',
]);
