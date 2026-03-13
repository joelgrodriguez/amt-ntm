<?php
/**
 * Machines Page — FAQ Accordion
 *
 * Data wrapper for the shared faq-accordion template part.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 * @see js/modules/Accordion.js
 */

declare(strict_types=1);

use function Standard\MachinesData\get_faq_items;

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'faq-accordion-title',
    'content'    => [
        'eyebrow' => __('FAQ', 'standard'),
        'title'   => __('Learn More About NTM Machines', 'standard'),
        'image'   => content_url('/uploads/2023/05/Machine-lifted-onto-rooftop-2048x1536.jpg'),
    ],
    'faqs' => get_faq_items(),
]);
