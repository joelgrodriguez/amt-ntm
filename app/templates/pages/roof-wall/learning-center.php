<?php
/**
 * Roof & Wall Panel Machines — Learning Center
 *
 * Data wrapper for the shared learning-center template part.
 * Filters posts by roof & wall panel category.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/learning-center', null, [
    'title'         => __('Roof & Wall Panel Learning Center', 'standard'),
    'subtitle'      => __('Guides, videos, and tips to help you get the most from your roof and wall panel machines.', 'standard'),
    'category_slug' => 'metal-roof-wall-panel-rollforming-machines',
    'post_count'    => 4,
    'cta_url'       => '/learning-center/?category=metal-roof-wall-panel-rollforming-machines',
    'cta_text'      => __('View All Roof & Wall Resources', 'standard'),
]);
