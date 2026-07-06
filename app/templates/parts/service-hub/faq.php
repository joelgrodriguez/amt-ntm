<?php
/**
 * Service Hub — FAQ
 *
 * The most-asked owner questions, surfaced before the search firehose so the
 * common stuff is answered without opening a ticket. Delegates to the shared
 * faq-accordion part, which also emits FAQPage JSON-LD for AEO.
 *
 * @package Standard
 *
 * @usage Service Hub (template-service-hub.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$faqs = [
    [
        'question' => __('How do I open a service request?', 'standard'),
        'answer'   => __('Use the "Open a service request" button on this page or visit /service-hub/request/. Include your machine and serial number if you have them.', 'standard'),
    ],
    [
        'question' => __('Where do I find the manual for my machine?', 'standard'),
        'answer'   => __('Pick your machine from the directory on this page. Each machine opens its manuals, troubleshooting, and videos. Or filter the search results for Manuals.', 'standard'),
    ],
    [
        'question' => __('How do I update my UNIQ control system?', 'standard'),
        'answer'   => __('See the UNIQ Automatic Control System section below for field-update instructions and the supplement manual. The full UNIQ control system page has the video library.', 'standard'),
    ],
    [
        'question' => __('What should I do before calling support?', 'standard'),
        'answer'   => __('Check the Service Hub and FAQ first. Most questions are already answered in the Service Hub and our FAQ. Search the knowledge base for manuals, articles, and videos.', 'standard'),
    ],
];

// faq-accordion renders the image unconditionally, so a real src is required.
// Reuse the service-department poster this template already ships in the hero
// (a known-present upload), so the band never renders a broken <img>.
get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'service-hub-faq-title',
    'content'    => [
        'eyebrow' => __('Common questions', 'standard'),
        'title'   => __('Answers before you open a ticket.', 'standard'),
        'image'   => content_url('/uploads/2022/04/service-department-working-on-SSQ-II.jpg'),
    ],
    'image_alt'    => __('NTM service team working on an SSQ machine', 'standard'),
    'image_aspect' => 'video',
    // bg-white so the FAQ doesn't double the Talk-To-Us blue-50 band directly
    // above it; the alternation continues blue-50 (UNIQ) -> white (search).
    'bg'           => 'bg-white',
    'faqs'         => $faqs,
]);
