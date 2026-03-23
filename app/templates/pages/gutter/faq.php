<?php
/**
 * Seamless Gutter Machines — FAQ Accordion
 *
 * Data wrapper for the shared faq-accordion template part.
 * Uses gutter-specific FAQ items.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_faq_items;

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'gutter-faq-title',
    'content'    => [
        'eyebrow' => __('FAQ', 'standard'),
        'title'   => __('Seamless Gutter Machine Questions', 'standard'),
        'image'   => content_url('/uploads/2023/05/Machine-lifted-onto-rooftop-2048x1536.jpg'),
    ],
    'faqs'      => get_gutter_faq_items(),
    'image_alt' => __('NTM gutter machine on a jobsite', 'standard'),
]);
