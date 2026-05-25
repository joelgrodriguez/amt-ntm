<?php
/**
 * MACH II Family — Comparison Table
 *
 * Same shared comparison-table part the /seamless-gutter-machines/
 * page uses. Rows match the gutter category schema (profile / size /
 * speed / drive / lead time / best for). Mobile gets horizontal
 * scroll with first column sticky.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_machines;

get_template_part('templates/parts/comparison-table', null, [
    'section_id' => 'machii-comparison-title',
    'content'    => [
        'eyebrow' => __('Compare', 'standard'),
        'title'   => __('All four, side by side.', 'standard'),
    ],
    'machines'   => get_gutter_machines(),
    'rows'       => [
        'profiles'  => __('Profile',  'standard'),
        'size'      => __('Size',     'standard'),
        'speed'     => __('Speed',    'standard'),
        'drive'     => __('Drive',    'standard'),
        'lead_time' => __('Lead Time','standard'),
        'best_for'  => __('Best For', 'standard'),
    ],
]);
