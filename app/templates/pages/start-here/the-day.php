<?php
/**
 * Start Here — What the Work Looks Like
 *
 * Reassurance for the nervous first-timer: the job is a short, learnable
 * sequence, not a mystery. Delegates to the shared value-prop-cards part,
 * whose baked-in 01/02/03 numbering is honest here because this genuinely
 * is an ordered sequence (source coil, roll on-site, install and bill).
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 * @see   templates/parts/value-prop-cards.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/value-prop-cards', null, [
    'section_id' => 'start-here-day',
    'content'    => [
        'eyebrow' => __('What the work looks like', 'standard'),
        'title'   => __('From Coil to Cash, on the Jobsite', 'standard'),
    ],
    'cards'      => [
        [
            'title' => __('Bring the coil to the job', 'standard'),
            'text'  => __('You buy flat metal coil by the pound, far cheaper than finished panels, and haul it to the site on a trailer. No factory, no warehouse, no waiting on a supplier’s lead time.', 'standard'),
        ],
        [
            'title' => __('Roll the exact panels you need', 'standard'),
            'text'  => __('The machine forms each panel to length on the spot, only what the job calls for. Training comes with the machine, so you are running real panels within days, not months.', 'standard'),
        ],
        [
            'title' => __('Install it and get paid', 'standard'),
            'text'  => __('You install the panels or gutters you just made and bill for the finished work. The margin that used to go to a panel supplier stays in your pocket on every job.', 'standard'),
        ],
    ],
]);
