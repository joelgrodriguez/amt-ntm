<?php
/**
 * Roof & Wall Panel Machines — Product Grid
 *
 * Data wrapper for the shared product-grid template part.
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

get_template_part('templates/parts/product-grid', null, [
    'section_id' => 'product-grid',
    'cols'       => 3,
    'machines'   => get_roof_wall_machines(),
    'content'    => [
        'eyebrow' => __('The Lineup', 'standard'),
        'title'   => __('Roof & Wall Panel Machines', 'standard'),
    ],
]);
