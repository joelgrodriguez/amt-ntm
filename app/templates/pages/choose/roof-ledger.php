<?php
/**
 * Choose Your Machine — Roof & Wall Panel Ledger
 *
 * Renders the roof-family fit ledger from the assembled catalog. The fork's
 * "roof & wall" lane jumps here (#roof-ledger).
 *
 * @package Standard
 *
 * @usage Choose Your Machine (page-choose-your-machine.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Rows are assembled once in the page template and passed down (see
// page-choose-your-machine.php) so the catalog is hydrated a single time.
$rows = $args['rows'] ?? [];

get_template_part('templates/pages/choose/fit-ledger', null, [
    'section_id' => 'roof-ledger',
    'eyebrow'    => __('Roof & wall panel machines', 'standard'),
    'title'      => __('Pick Your Roof & Wall Panel Machine', 'standard'),
    'subtitle'   => __('Six machines, ordered from the high-volume flagship down to the entry machine. Read each by the work it suits and the budget it lands in, then step into its full page.', 'standard'),
    'rows'       => $rows,
    'secondary'  => [
        ['label' => __('Which roof panel machine? (Quiz)', 'standard'), 'url' => '/roof-panel-machine-assessment-quiz/'],
        ['label' => __('Compare roof panel machines', 'standard'), 'url' => '/compare-roof-panel-machines/'],
    ],
]);
