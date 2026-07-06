<?php
/**
 * Service Hub — FAQ
 *
 * The most-asked owner questions, surfaced before the search firehose so the
 * common stuff is answered without opening a ticket. Delegates to the shared
 * faq-accordion part, which also emits FAQPage JSON-LD for AEO.
 *
 * Curated from the service team's top-asked owner questions.
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
        'question' => __('Where do I find the manual for my machine?', 'standard'),
        'answer'   => __('Pick your machine from the directory below to jump to its manuals, troubleshooting articles, and videos. Or filter the search results on this page for "Manuals".', 'standard'),
    ],
    [
        'question' => __('What should I do before calling support?', 'standard'),
        'answer'   => __('Search the service content library first—most fixes are already documented. Have your machine model and serial number ready. The more detail you can share, the faster we can help.', 'standard'),
    ],
    [
        'question' => __('How do I update my UNIQ control system?', 'standard'),
        'answer'   => __('Use the UNIQ Automatic Control System section below for the latest firmware and step-by-step update instructions. The same resources appear on SSQ II, SSQ3, and WAV machine pages.', 'standard'),
    ],
    [
        'question' => __('How do I open a service request?', 'standard'),
        'answer'   => __('Use the "Open a service request" button on this page or visit /service-hub/request/. Include your machine model, serial number, and a clear description of the issue—photos or video help the team diagnose faster.', 'standard'),
    ],
    [
        'question' => __('How often does my machine need to be serviced?', 'standard'),
        'answer'   => __('Refer to the maintenance section in your machine manual for daily, weekly, monthly, and yearly schedules. The Learning Center also has checklists and how-to videos.', 'standard'),
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
