<?php
/**
 * Machines Page — Final CTA
 *
 * Closing CTA for /machines. Uses the lean centered closer (no
 * specialist photo, no bullets) because we don't have an approved
 * portrait yet and the customer-story section already carries the
 * page's emotional peak. Two doors: Specialist + Build & Price.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'machines-final-cta-title',
    'title'             => __('Pick the Machine. Start Making Money.', 'standard'),
    'text'              => __('Build a quote in your browser, or get one of our specialists on the phone. Free 30-minute call. No obligation.', 'standard'),
    'cta_primary'       => __('Build & Price Your Machine', 'standard'),
    'cta_primary_url'   => '/configurator/',
    'cta_primary_new_tab' => true,
    'cta_secondary'     => __('Talk to a Specialist', 'standard'),
    'cta_secondary_url' => '/contact/',
]);
