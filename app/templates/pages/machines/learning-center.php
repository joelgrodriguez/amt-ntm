<?php
/**
 * Machines — Learning Center
 *
 * Data wrapper for the shared learning-center template part.
 * Shows all categories (no filter).
 *
 * @package Standard
 *
 * @usage Machines (page-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/learning-center', null, [
    'title'      => __('Rollforming Learning Center', 'standard'),
    'subtitle'   => __('Expert guides, tips, and resources to help you get the most from your equipment.', 'standard'),
    'post_count' => 4,
    'cta_url'    => '/learning-center/',
    'cta_text'   => __('View All Resources', 'standard'),
    'align'      => 'center',
]);
