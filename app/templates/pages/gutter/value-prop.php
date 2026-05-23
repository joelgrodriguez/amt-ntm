<?php
/**
 * Seamless Gutter Machines — Value Proposition
 *
 * Data wrapper for the shared value-prop-cards template part.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/value-prop-cards', null, [
    'section_id' => 'gutter-value-prop',
    'content'    => [
        'eyebrow' => __('Why Portable Gutter Machines', 'standard'),
        'title'   => __('30 Years of Proven Performance', 'standard'),
    ],
    'cards' => [
        [
            'icon'  => 'settings',
            'title' => __('On-Site Fabrication', 'standard'),
            'text'  => __('Produce seamless gutters anywhere, no pre-fab joints, no shipping, no wasted material. One continuous piece from coil to install.', 'standard'),
        ],
        [
            'icon'  => 'trending-up',
            'title' => __('Industry Standard', 'standard'),
            'text'  => __('NTM pioneered polyurethane drive rollers for gutter machines. The MACH II line has been the industry benchmark for over 30 years.', 'standard'),
        ],
        [
            'icon'  => 'dollar-sign',
            'title' => __('Low Entry Cost', 'standard'),
            'text'  => __('Starting at $9,800 with flexible financing options. Most gutter contractors pay off their machine within the first year of operation.', 'standard'),
        ],
    ],
]);
