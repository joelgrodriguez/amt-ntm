<?php
/**
 * Start Here — FAQ
 *
 * Data wrapper for the shared faq-accordion part, which emits FAQPage
 * JSON-LD. These are the anxiety questions a true first-timer asks before
 * committing, phrased the way they search them, with a direct answer in
 * the first sentence (AEO). Money-detail answers point the reader to
 * How Buying Works and Financing rather than restating prices, keeping
 * this page on the "opportunity" side of the line.
 *
 * Answers are escaped by the part (no inline links), so cross-references
 * name the destination in plain prose.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 * @see   templates/parts/faq-accordion.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$faqs = [
    [
        'question' => __('Do I need roofing or gutter experience to start?', 'standard'),
        'answer'   => __('It helps, but it is not required. Plenty of NTM owners came from a trade and added panel or gutter work; others started fresh. Running the machine is a learnable skill, and training comes with every machine so you are producing real, sellable panels within days. The bigger question is usually whether you want to run a business, not whether you can run the machine.', 'standard'),
    ],
    [
        'question' => __('How much money do I need to start?', 'standard'),
        'answer'   => __('Seamless gutter machines are the lower-cost entry point and roof and wall panel machines cost more, so your starting number depends on which work you are chasing. Beyond the machine you will want coil, a way to haul it, and a little working capital. For the full picture on price and financing, see How Buying Works and the Financing pages, where the numbers and lease-to-own options are laid out.', 'standard'),
    ],
    [
        'question' => __('How long until the machine pays for itself?', 'standard'),
        'answer'   => __('Most NTM owners report covering the cost of the machine within one to two busy seasons. Because you are making panels or gutters yourself instead of buying them finished, every job you would have ordered from a supplier now carries that margin back to you. How fast it pays off depends on your volume and pricing, which the profit calculator lets you model on your own numbers.', 'standard'),
    ],
    [
        'question' => __('Should I start with roofing panels or seamless gutters?', 'standard'),
        'answer'   => __('Start with the work you can sell soonest. Gutters have a lower entry cost and a fast, simple sales cycle, which makes them a common first machine. Roofing panels carry a higher ticket and suit installers already doing metal roofs. Many owners start with one and add the other later. The Roof Panel vs Gutter guide walks through which fits your situation.', 'standard'),
    ],
    [
        'question' => __('What kind of training and support comes with the machine?', 'standard'),
        'answer'   => __('Every NTM machine includes operator training so you and your crew can run it correctly from day one, plus ongoing service and parts support after the sale. You are buying a machine you will own and run for years, not a one-time transaction, so the goal is to get you producing good panels quickly and keep you running.', 'standard'),
    ],
];

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'start-here-faq-title',
    'bg'         => 'bg-white',
    'content'    => [
        'eyebrow' => __('First-timer questions', 'standard'),
        'title'   => __('What New Owners Ask First', 'standard'),
        'image'   => content_url('/uploads/2026/05/ntm-customer-onsite-001.jpg'),
    ],
    'faqs'       => $faqs,
    'image_alt'  => __('NTM owner running a portable rollforming machine on a jobsite', 'standard'),
]);
