<?php
/**
 * Roof & Wall Panel Machines — Value Proposition
 *
 * Data wrapper for the shared value-prop-cards template part.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/value-prop-cards', null, [
    'section_id' => 'roof-wall-value-prop',
    'content'    => [
        'eyebrow' => __('Why Portable Rollforming', 'standard'),
        'title'   => __('Freedom from Factory Constraints', 'standard'),
    ],
    'cards' => [
        [
            'icon'  => 'settings',
            'title' => __('On-Site Fabrication', 'standard'),
            'text'  => __('Produce panels right on the jobsite. No factory lead times, no shipping damage, no wasted trips waiting on deliveries.', 'standard'),
        ],
        [
            'icon'  => 'trending-up',
            'title' => __('Multi-Profile Versatility', 'standard'),
            'text'  => __('Up to 16 profiles from a single machine — standing seam roof, flush wall, and board & batten siding panels on demand.', 'standard'),
        ],
        [
            'icon'  => 'dollar-sign',
            'title' => __('Proven ROI', 'standard'),
            'text'  => __('Save up to $2.25/sq ft versus factory panels. Most contractors pay off their machine within the first 1–2 years.', 'standard'),
        ],
    ],
]);
