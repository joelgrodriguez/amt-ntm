<?php
/**
 * Seamless Gutter Machines — Comparison Table
 *
 * Full per-category specs table (four machines). Renders through the
 * shared comparison-table template part.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_machines;

get_template_part('templates/parts/comparison-table', null, [
    'section_id' => 'gutter-comparison-title',
    'content'    => [
        'eyebrow' => __('Compare', 'standard'),
        'title'   => __('Gutter Machines, Side by Side', 'standard'),
    ],
    'machines'   => get_gutter_machines(),
    'rows'       => [
        'profiles'  => __('Profile', 'standard'),
        'size'      => __('Size', 'standard'),
        'speed'     => __('Speed', 'standard'),
        'drive'     => __('Drive', 'standard'),
        'lead_time' => __('Lead Time', 'standard'),
        'best_for'  => __('Best For', 'standard'),
    ],
]);
