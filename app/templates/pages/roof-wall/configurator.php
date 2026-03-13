<?php
/**
 * Roof & Wall Panel Machines — Configurator
 *
 * Data wrapper for the shared configurator template part.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

get_template_part('templates/parts/configurator', null, [
    'section_id' => 'roof-wall-configurator',
    'title'      => __('Configure Your Roof & Wall Panel Machine', 'standard'),
    'text'       => __('Choose your profiles, coil width, and options — see real pricing and apply for financing, all online.', 'standard'),
]);
