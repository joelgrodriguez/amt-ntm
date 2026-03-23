<?php
/**
 * Seamless Gutter Machines — Configurator
 *
 * Data wrapper for the shared configurator template part.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/configurator', null, [
    'section_id' => 'gutter-configurator',
    'title'      => __('Configure Your Gutter Machine', 'standard'),
    'text'       => __('Choose your gutter profile, coil width, and options — see real pricing and apply for financing, all online.', 'standard'),
]);
