<?php
/**
 * Seamless Gutter Machines — Product Grid
 *
 * Data wrapper for the shared product-grid template part.
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

get_template_part('templates/parts/product-grid', null, [
    'section_id'   => 'product-grid',
    'category_key' => 'gutter',
    'machines'     => get_gutter_machines(),
    'content'      => [
        'eyebrow' => __('The Lineup', 'standard'),
        'title'   => __('Seamless Gutter Machines', 'standard'),
    ],
]);
