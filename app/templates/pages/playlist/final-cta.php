<?php
/**
 * First-Time Buyer Playlist — Final CTA
 *
 * Closing dark band. The playlist is top of funnel, so the close is soft:
 * talk to a specialist or take the quiz. No configurator here, by the
 * same rule the other top-of-funnel pages follow (the configurator is a
 * bottom-of-funnel destination; see docs/handoff/03-mega-menu-spec.md).
 *
 * @package Standard
 *
 * @usage First-Time Buyer Playlist (page-first-time-buyer-playlist.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'playlist-final-cta-title',
    'title'             => __('Watched Enough to Have Questions?', 'standard'),
    'text'              => __('That is exactly the point. Tell an NTM specialist what you are trying to build, and we will help you take the next step. No pressure, no obligation.', 'standard'),
    'cta_primary'       => __('Talk to a Specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('Take the machine quiz', 'standard'),
    'cta_secondary_url' => '/choose-your-machine/',
]);
