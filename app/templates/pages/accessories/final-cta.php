<?php
/**
 * Accessories Page — Final CTA
 *
 * Data wrapper for the shared closer CTA. Same partial used by
 * single-accessory.php so the listing and the detail page close on the
 * same chrome.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/cta/closer', null, [
    'title'             => __('Not Sure Which Upgrades You Need?', 'standard'),
    'text'              => __('Tell a specialist which machine you run. They\'ll walk you through the add-ons that move the needle, and the ones you can skip.', 'standard'),
    'cta_primary'       => __('Talk to a Specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('Back to the Catalog', 'standard'),
    'cta_secondary_url' => '#catalog',
    'section_id'        => 'accessories-final-cta-title',
]);
