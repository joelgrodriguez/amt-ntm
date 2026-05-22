<?php
/**
 * Roof & Wall Panel Machines — Final CTA
 *
 * Closing CTA for /roof-wall-panel-machines/. Uses the lean centered
 * closer (matches the /machines parent and the front page) instead of
 * the heavy two-column with a specialist photo collage.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'roof-wall-final-cta-title',
    'title'             => __('Stop Paying Supplier Markups.', 'standard'),
    'text'              => __('Most contractors recoup their machine investment within 12 to 18 months. Build a quote in your browser, or get one of our specialists on the phone.', 'standard'),
    'cta_primary'       => __('Build & Price Your Machine', 'standard'),
    'cta_primary_url'   => '/build-finance/',
    'cta_secondary'     => __('Talk to a Specialist', 'standard'),
    'cta_secondary_url' => '/contact/',
]);
