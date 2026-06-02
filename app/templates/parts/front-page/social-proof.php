<?php
/**
 * Social Proof Section Template Part
 *
 * Chrome-bar testimonial strip. Borrows the composition of
 * three-step-plan.php (top + bottom mono rails, border-x edge rails,
 * red 2x2 dot + segmented indicator) so the back half of the front
 * page reads as one chrome-bar system instead of a chrome -> island
 * -> chrome zigzag.
 *
 * Square portrait (zero border-radius, sharp crop) per DESIGN.md §4.2.
 * The dateline (CITY, STATE) reads as a spec-sheet timestamp, not a
 * SaaS-testimonial flavor line.
 *
 * Autoplay every 6.5s, pauses on hover/focus so readers can finish
 * a quote. Dot pagination is a segmented indicator, not a row of
 * round avatars.
 *
 * Photo URLs point to the production CDN; portraits are public
 * marketing assets and won't move. Theme adds a preconnect hint
 * for that origin in inc/setup.php.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see js/modules/SocialProof.js - Slider functionality
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow'    => __('From the field', 'standard'),
    'channel'    => __('Customer testimonials', 'standard'),
    'sr_title'   => __('Customer testimonials', 'standard'),
    'nav_label'  => __('Testimonial navigation', 'standard'),
    'cta_label'  => __('All customer stories', 'standard'),
    'cta_url'    => '/learning-center/category/testimonials/',
    'count_left' => __('Stories', 'standard'),
];

$cdn = 'https://newtechmachinery.com/wp-content/uploads/2025/06';

$testimonials = [
    [
        'quote'    => __('If you’re trying to jump into the metal business, contact New Tech Machinery. They’re the best! They’re going to give you the information that you need, and they’re going to help you grow your business.', 'standard'),
        'name'     => 'Danaik Garay',
        'company'  => 'Alsteel Metal Manufacturing',
        'location' => 'Fort Myers, Florida',
        'slug'     => 'Danaik-1',
    ],
    [
        'quote'    => __('What attracted me to New Tech Machinery was the quality of the machine and ease of switching out dies. If you do have a problem, there’s a sales team that you can call.', 'standard'),
        'name'     => 'Todd Andrews',
        'company'  => 'Classic Metals Inc.',
        'location' => 'Chester, South Carolina',
        'slug'     => 'Todd',
    ],
    [
        'quote'    => __('I chose New Tech Machinery over the competition because of how valued I felt as a customer. They truly valued me and appreciated me as a person, it wasn’t just another sale.', 'standard'),
        'name'     => 'Jim Averill',
        'company'  => 'Gunnison Sheet Metal',
        'location' => 'Gunnison, Colorado',
        'slug'     => 'Jim',
    ],
    [
        'quote'    => __('For me, New Tech Machinery has always been the top-of-the-line machine for gutters, so I wanted to have something that I can rely on.', 'standard'),
        'name'     => 'Abel Cisneros',
        'company'  => 'C&S Rain Gutters',
        'location' => 'Greeley, Colorado',
        'slug'     => 'Abel',
    ],
    [
        'quote'    => __('The relationship I’ve built with NTM has been fantastic. I call up Tom and tell him what I want, and I think he has me what I need on paper within the day.', 'standard'),
        'name'     => 'Mike Lemke',
        'company'  => 'Lemke Exteriors',
        'location' => 'Moorehead, Minnesota',
        'slug'     => 'Mike',
    ],
    [
        'quote'    => __('New Tech overall has been great to work with, and between the panel options and the service, that’s why we keep going back to them.', 'standard'),
        'name'     => 'Keith Ryan',
        'company'  => 'Metal Maniacs',
        'location' => 'Fort Myers, Florida',
        'slug'     => 'Keith',
    ],
];

if (empty($testimonials)) {
    return;
}

// Render through the shared chrome-bar slider so the front page, machine
// pages, and landing pages stay one composition.
get_template_part('templates/parts/testimonial-slider', null, [
    'section_id'   => 'social-proof-title',
    'testimonials' => $testimonials,
    'content'      => $content,
    'cdn'          => $cdn,
]);
return;
