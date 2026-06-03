<?php
/**
 * Choose Your Machine — Final CTA
 *
 * Closing dark band. The reader has either found their machine or narrowed
 * it down, so the close offers the site's persistent next step (talk to a
 * specialist) and a route to the side-by-side spec comparison for anyone
 * still deciding between two. Reuses the shared closer.
 *
 * @package Standard
 *
 * @usage Choose Your Machine (page-choose-your-machine.php)
 * @see   templates/parts/cta/closer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'section_id'        => 'choose-final-cta-title',
    'title'             => __('Narrowed It Down? Talk It Through.', 'standard'),
    'text'              => __('Tell an NTM specialist the work you do and the machine you are leaning toward. They will confirm the fit, walk through profiles and pricing, and answer anything the page did not. No pressure, no obligation.', 'standard'),
    'cta_primary'       => __('Talk to a specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('Compare roof panel machines', 'standard'),
    'cta_secondary_url' => '/compare-roof-panel-machines/',
]);
