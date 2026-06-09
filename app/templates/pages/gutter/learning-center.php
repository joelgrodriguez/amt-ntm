<?php
/**
 * Seamless Gutter Machines — Learning Center
 *
 * Data wrapper for the shared learning-center template part.
 * Filters posts by seamless gutter category.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/learning-center', null, [
    'title'         => __('Seamless Gutter Learning Center', 'standard'),
    'subtitle'      => __('Guides, videos, and tips to help you get the most from your gutter machines.', 'standard'),
    'category_slug' => 'seamless-gutter-rollforming-machines',
    'post_count'    => 4,
    'cta_url'       => '/learning-center/?category=seamless-gutter-rollforming-machines',
    'cta_text'      => __('View All Gutter Resources', 'standard'),
    'align'         => 'center',
]);
