<?php
/**
 * Start Here — Final CTA
 *
 * Closing dark band. The reader has seen the case and picked a lane, so
 * the close pushes the ready toward the machine lineup and gives the
 * unsure a person to talk to. Reuses the shared closer.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'start-here-final-cta-title',
    'title'             => __('Ready to Start Your Machine Business?', 'standard'),
    'text'              => __('Find the machine that fits the work you want to do, or tell an NTM specialist about your plans and they will help you map the first step. No pressure, no obligation.', 'standard'),
    'cta_primary'       => __('Find your machine', 'standard'),
    'cta_primary_url'   => '/choose-your-machine/',
    'cta_secondary'     => __('Talk to a specialist', 'standard'),
    'cta_secondary_url' => '/contact/',
]);
