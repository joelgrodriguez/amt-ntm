<?php
/**
 * METALCON 2026 presentation schedule.
 *
 * The sign-up itself is a HubSpot form (HubSpot\METALCON_2026_FORM_ID);
 * this file only owns the schedule the page renders.
 *
 * @package Standard
 */

declare(strict_types=1);

namespace Standard;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Presentation sessions shown on the page. Keep in step with the time-slot
 * dropdown on the HubSpot form; HubSpot owns the field, this owns the copy.
 *
 * @return list<array{date: string, times: list<string>}>
 */
function metalcon_presentation_schedule(): array {
    return [
        [
            'date'  => __('Wednesday, October 7', 'standard'),
            'times' => ['10:30 a.m.', '1:30 p.m.', '3:00 p.m.'],
        ],
        [
            'date'  => __('Thursday, October 8', 'standard'),
            'times' => ['10:30 a.m.', '1:30 p.m.', '3:00 p.m.'],
        ],
        [
            'date'  => __('Friday, October 9', 'standard'),
            'times' => ['9:30 a.m.'],
        ],
    ];
}
