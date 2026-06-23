<?php
/**
 * Finance Center — FAQ
 *
 * The legacy page buried these in nested ordered lists. Here they're clean
 * Q&A through the shared faq-accordion part, which also emits FAQPage
 * JSON-LD for AEO. Questions cover the things a buyer actually hesitates on:
 * is Corbel a lender, what are the rates, will applying hurt my credit,
 * do I need a final invoice, who qualifies.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$faqs = [
    [
        'question' => __('Is Corbel a lender?', 'standard'),
        'answer'   => __('No. Corbel is a technology company that partners with equipment vendors and top-tier finance providers to maximize your chance of approval at the best rate. It runs your one application against several of the industry’s best lenders.', 'standard'),
    ],
    [
        'question' => __('What are the financing terms and rates?', 'standard'),
        'answer'   => __('Once approved, you can choose a payment plan over 36, 48, or 60 months. Rates start at 8% APR and are set by the personal and commercial credit behind each application.', 'standard'),
    ],
    [
        'question' => __('What financing or leasing options do you offer?', 'standard'),
        'answer'   => __('A variety of structures, including Equipment Financing Agreements (EFAs) and Fair Market Value (FMV) leases. The Customer Success team works with you to find the structure that fits your business.', 'standard'),
    ],
    [
        'question' => __('What types of businesses qualify?', 'standard'),
        'answer'   => __('Most commercial buyers, from small and mid-size businesses to sole proprietors running a business out of their home with no formal entity.', 'standard'),
    ],
    [
        'question' => __('Do I need a final invoice before I apply?', 'standard'),
        'answer'   => __('No. You can apply at any point in the sale. A final invoice can be supplied after you receive your approval.', 'standard'),
    ],
    [
        'question' => __('Will applying hurt my credit?', 'standard'),
        'answer'   => __('No. The financing partners use a soft inquiry, so applying won’t impact your personal credit score.', 'standard'),
    ],
];

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'finance-faq-title',
    'content'    => [
        'eyebrow' => __('Financing questions', 'standard'),
        'title'   => __('What buyers ask before they apply.', 'standard'),
        'image'   => content_url('/uploads/2026/05/ntm-customer-onsite-001.jpg'),
    ],
    'image_alt'    => __('NTM owner running a portable rollforming machine on a jobsite', 'standard'),
    'image_aspect' => 'video',
    'bg'           => 'bg-white',
    'faqs'         => $faqs,
]);
