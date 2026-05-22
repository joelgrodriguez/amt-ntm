<?php
/**
 * Machines — Configurator
 *
 * Data wrapper for the shared configurator template part.
 *
 * @package Standard
 *
 * @usage Machines (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/configurator', null, [
    'section_id' => 'machines-configurator',
    'title'      => __('Configure Your Machine Online', 'standard'),
    'text'       => __('Design your perfect rollformer, see exactly what it costs, and apply for financing, all from your browser.', 'standard'),
]);
