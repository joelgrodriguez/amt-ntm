<?php
/**
 * Roof & Wall Panel Machines — FAQ Accordion
 *
 * Data wrapper for the shared faq-accordion template part.
 * Uses category-specific FAQ items.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 * @see js/modules/Accordion.js
 */

declare(strict_types=1);

use function Standard\MachinesData\get_roof_wall_faq_items;

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'roof-wall-faq-title',
    'content'    => [
        'eyebrow' => __('FAQ', 'standard'),
        'title'   => __('Roof & Wall Panel Machine Questions', 'standard'),
        'image'   => content_url('/uploads/2023/05/Machine-lifted-onto-rooftop-2048x1536.jpg'),
    ],
    'faqs' => get_roof_wall_faq_items(),
]);
