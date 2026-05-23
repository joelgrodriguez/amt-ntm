<?php
/**
 * Machines Page — Comparison Table (full lineup)
 *
 * Surfaces every non-dormant machine in one side-by-side table. The
 * shared comparison-table part uses `table-fixed` to equalize column
 * widths and falls back to horizontal scroll below `md`, so this
 * scales to the full lineup without breaking on smaller desktops.
 *
 * SSQ II is excluded automatically because the machines-data entry is
 * marked `dormant => true`; get_all_machines() filters it out by
 * default.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_all_machines;

get_template_part('templates/parts/comparison-table', null, [
    'section_id' => 'comparison-title',
    'content'    => [
        'eyebrow' => __('Compare', 'standard'),
        'title'   => __('Every Machine, Side by Side', 'standard'),
    ],
    'machines'   => get_all_machines(),
    'rows'       => [
        'profiles' => __('Profiles', 'standard'),
        'speed'    => __('Speed', 'standard'),
        'power'    => __('Power', 'standard'),
        'shear'    => __('Shear', 'standard'),
        'best_for' => __('Best For', 'standard'),
    ],
]);
