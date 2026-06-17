<?php
/**
 * Service Hub — FAQ
 *
 * The most-asked owner questions, surfaced before the search firehose so the
 * common stuff is answered without opening a ticket. Delegates to the shared
 * faq-accordion part, which also emits FAQPage JSON-LD for AEO.
 *
 * TODO(copy): placeholder Q&A. Replace with the real top-10 owner questions
 * the service team identified when the support section first launched.
 *
 * @package Standard
 *
 * @usage Service Hub (template-service-hub.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// TODO(copy): replace all four with the curated top-10 owner Q&A.
$faqs = [
    [
        'question' => __('How do I open a service request?', 'standard'),
        'answer'   => __('TODO(copy): placeholder answer. Walk the owner through opening a request and what to have ready (machine model, serial, photos).', 'standard'),
    ],
    [
        'question' => __('Where do I find the manual for my machine?', 'standard'),
        'answer'   => __('TODO(copy): placeholder answer. Point to the per-machine page and the Manuals filter.', 'standard'),
    ],
    [
        'question' => __('How do I update my UNIQ control system?', 'standard'),
        'answer'   => __('TODO(copy): placeholder answer. Link the UNIQ section / software page.', 'standard'),
    ],
    [
        'question' => __('What should I do before calling support?', 'standard'),
        'answer'   => __('TODO(copy): placeholder answer. Steer toward troubleshooting articles and the search library first.', 'standard'),
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
