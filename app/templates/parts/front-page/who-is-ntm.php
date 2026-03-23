<?php
/**
 * Who Is NTM Section — Front Page
 *
 * Data wrapper for the shared video-section template part.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see templates/parts/video-section.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_template_part('templates/parts/video-section', null, [
    'title'      => __('Who Is NTM?', 'standard'),
    'channel'    => __('Portable Rollforming Channel', 'standard'),
    'video_type' => __('Company Overview', 'standard'),
    'section_id' => 'who-is-ntm',
]);
