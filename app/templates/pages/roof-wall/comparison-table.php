<?php
/**
 * Roof & Wall Panel Machines — Comparison Table
 *
 * Full per-category specs table (six machines). Renders through the
 * shared comparison-table template part.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_roof_wall_machines;

get_template_part('templates/parts/comparison-table', null, [
    'section_id' => 'roof-wall-comparison-title',
    'content'    => [
        'eyebrow' => __('Compare', 'standard'),
        'title'   => __('Roof & Wall Machines, Side by Side', 'standard'),
    ],
    'machines'   => get_roof_wall_machines(),
    'rows'       => [
        'profiles' => __('Profiles', 'standard'),
        'speed'    => __('Speed', 'standard'),
        'power'    => __('Power', 'standard'),
        'shear'    => __('Shear', 'standard'),
        'best_for' => __('Best For', 'standard'),
    ],
]);
