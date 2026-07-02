<?php
/**
 * Footprints — Final CTA
 *
 * Closing dark band. The reader has been measuring shop bays and
 * trailer decks, so the close offers the site's persistent next step
 * (talk to a specialist) plus a route back to the full lineup. Reuses
 * the shared closer.
 *
 * @package Standard
 *
 * @usage Footprints landing (page-footprints.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'footprints-final-cta-title',
    'title'             => __('Planning Your Shop or Trailer Setup?', 'standard'),
    'text'              => __('Tell an NTM specialist the space you are working with. They will talk through the footprint, the setup, and the machine you are considering before you commit to anything.', 'standard'),
    'cta_primary'       => __('Talk to a specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('See all machines', 'standard'),
    'cta_secondary_url' => '/machines/',
]);
