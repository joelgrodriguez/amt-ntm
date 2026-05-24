<?php
/**
 * Machines Page — Brand Statement
 *
 * Data wrapper for the shared brand-statement template part.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 * @see   templates/parts/brand-statement.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/brand-statement', null, [
    'section_id' => 'brand-statement-title',
    'title'      => __('Stop Buying Panels.<br class="hidden md:inline"> Start Making Profit.', 'standard'),
    'text'       => __(
        "Every panel you buy from a supplier is profit you're giving away. NTM portable rollformers let you fabricate standing seam roofing and seamless gutters on-site, cutting material costs in half, winning more bids, and controlling your own schedule. New Tech Machinery has been the industry leader in portable rollformers since 1991, trusted by contractors on all seven continents.",
        'standard'
    ),
    'image'      => content_url('/uploads/2026/01/Screenshot-2026-01-07-at-9.37.43-AM.png'),
    'image_alt'  => __('NTM portable rollformer in the field', 'standard'),
]);
