<?php
/**
 * MACH II Family — FAQ
 *
 * Six highest-intent questions from get_gutter_faq_items(), picked
 * for a buyer who's already chosen the MACH II family and is
 * deciding between models or whether to pull the trigger now. Cost,
 * lead time, warranty, financing, support, training. Renders via
 * the shared faq-accordion part (FAQPage JSON-LD is emitted inside
 * that part, so the page gets schema for free).
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachinesData\get_gutter_faq_items;

$all = get_gutter_faq_items();

$wanted_keywords = [
    'how much',
    'how long does delivery',
    'warranty',
    'financing',
    'how long will it take',
    'support does ntm',
];

$picked = [];
foreach ($wanted_keywords as $keyword) {
    foreach ($all as $faq) {
        $q = strtolower($faq['question'] ?? '');
        if ($q !== '' && str_contains($q, $keyword) && !in_array($faq, $picked, true)) {
            $picked[] = $faq;
            break;
        }
    }
}

if (count($picked) < 6) {
    foreach ($all as $faq) {
        if (!in_array($faq, $picked, true)) {
            $picked[] = $faq;
            if (count($picked) >= 6) {
                break;
            }
        }
    }
}

get_template_part('templates/parts/faq-accordion', null, [
    'section_id' => 'machii-faq-title',
    'content'    => [
        'eyebrow' => '',
        'title'   => __('What buyers ask before they sign.', 'standard'),
        'image'   => content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-001.jpg'),
    ],
    'image_alt'  => __('NTM MACH II machine staged on a residential jobsite', 'standard'),
    'faqs'       => $picked,
]);
