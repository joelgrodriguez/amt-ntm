<?php
/**
 * Roof Panel vs Gutter: FAQ
 *
 * Data wrapper for the shared faq-accordion part. The part emits
 * FAQPage JSON-LD, so these questions double as answer-engine (AEO)
 * targets: each one is phrased the way a first-time buyer actually
 * searches the roof-panel-vs-gutter question, with a direct,
 * self-contained answer in the first sentence.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 * @see   templates/parts/faq-accordion.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$faqs = [
    [
        'question' => __('What is the difference between a roof panel machine and a gutter machine?', 'standard'),
        'answer'   => __('A roof and wall panel machine forms the long metal panels that become a building’s roof and walls, such as standing seam roofing, flush wall, and board & batten siding. A seamless gutter machine forms one continuous gutter, with no joints, to drain that roof. They are different tools for different products: one makes the surface, the other makes the drainage. New Tech Machinery builds both.', 'standard'),
    ],
    [
        'question' => __('Which machine do I need for standing seam metal roofing?', 'standard'),
        'answer'   => __('You need a roof and wall panel machine. NTM’s roof panel lineup (the SSQ3™ MultiPro, SSH™, SSR™, and 5V Crimp) rollforms standing seam and exposed-fastener roof panels on the jobsite. A gutter machine cannot make roof panels. If standing seam roofing is your main work, start on the roof and wall panel machines page.', 'standard'),
    ],
    [
        'question' => __('Which machine do I need to make seamless gutters?', 'standard'),
        'answer'   => __('You need a seamless gutter machine. NTM’s MACH II™ line makes seamless K-style gutters in 5", 6", and a 5"/6" combo, and the BG7 makes box gutters. Seamless means the gutter is formed in one continuous run cut to length on site, so there are no joints to leak. Roof panel machines do not make gutters.', 'standard'),
    ],
    [
        'question' => __('Can one machine make both roof panels and gutters?', 'standard'),
        'answer'   => __('No. Roof panels and gutters are formed by separate machines because the profiles are completely different. Many NTM owners run both, a roof panel machine and a MACH II™ gutter machine, to serve roofing and gutter work from the same crew. They are bought and operated as two machines, not one combination unit.', 'standard'),
    ],
    [
        'question' => __('How much do NTM machines cost?', 'standard'),
        'answer'   => __('Seamless gutter machines are the lower-cost entry point, starting at $9,800 for the MACH II™ 5". Roof and wall panel machines start at $44,900 for the SSR™ MultiPro Jr. and run higher for flagship multi-profile machines like the SSQ3™. NTM offers financing, including lease-to-own and seasonal plans, on both families.', 'standard'),
    ],
    [
        'question' => __('I am new to portable rollforming. Where should I start?', 'standard'),
        'answer'   => __('Start with the work you already do or want to do. If you install metal roofs or panels, look at the roof and wall panel machines. If you run gutters and exteriors, look at the seamless gutter machines, where the lower entry cost makes it a common first machine. If you’re still unsure, take the machine quiz or talk to an NTM specialist and they’ll point you to the right family.', 'standard'),
    ],
];

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'vs-faq-title',
    'content'    => [
        'eyebrow' => __('FAQ', 'standard'),
        'title'   => __('Roof Panel vs Gutter, Answered', 'standard'),
        'image'   => content_url('/uploads/2026/05/ntm-customer-onsite-001.jpg'),
    ],
    'faqs'       => $faqs,
    'image_alt'  => __('NTM customer running a portable rollforming machine on a jobsite', 'standard'),
]);
