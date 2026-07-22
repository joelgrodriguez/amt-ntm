<?php
/**
 * FAQ Section — Front Page
 *
 * A short buyer-FAQ sampler that closes the EDUCATE block. The homepage is
 * fact-dense but had no question-formatted content; answer engines (ChatGPT,
 * Perplexity, Google AI Overviews) match conversational queries against
 * structured Q&A, so this turns facts already on the page into directly
 * quotable answers and reassures buyers before the CLOSE sections.
 *
 * One source of truth: the $faqs array drives BOTH the visible markup and the
 * FAQPage JSON-LD, so they can never drift. Answers are statically rendered
 * (no JS-gated disclosure) and each is self-contained — an LLM can quote one
 * answer alone.
 *
 * Prices are hand-verified against app/data/machines/ (finance.price_range):
 * MACH II 5" $9,800+, SSR MultiPro Jr. $44,900+, WAV $237,300+, SSQ3 $85K base
 * (trailer sold separately). When those change, update the cost answer here.
 * This is a 4–6 question sampler, not the full /faq/ page — keep it short.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Common Questions', 'standard'),
    'title'   => __('Rollforming questions, answered.', 'standard'),
];

$faqs = [
    [
        'question' => __('What is a portable rollforming machine?', 'standard'),
        'answer'   => __('A portable rollforming machine forms metal roof panels or seamless gutters from raw coil right on the jobsite. Because the panel is made on-site, it can run any length the roof needs — no factory length limits, no mid-panel seams, and no waiting on a supplier.', 'standard'),
    ],
    [
        'question' => __('How much does a portable rollforming machine cost?', 'standard'),
        'answer'   => __('NTM seamless gutter machines start at $9,800. Roof panel machines range from $44,900 for the SSR MultiPro Jr. to $237,300+ for the commercial WAV wall panel machine. The flagship SSQ3 MultiPro starts at $85K, trailer sold separately.', 'standard'),
    ],
    [
        'question' => __('How long does it take to get an NTM machine?', 'standard'),
        'answer'   => __('Lead time is 6 to 10 weeks from order. Financing is applied for in the same flow, and your crew runs panels with our team on-site during week one.', 'standard'),
    ],
    [
        'question' => __('Which NTM machine is right for my business?', 'standard'),
        'answer'   => __('It depends on what you sell: K-style gutters point to the MACH II line, and standing seam roofing to the SSR, SSH, or SSQ3 MultiPro. Take the 10-question machine quiz or talk to a specialist to match a machine to your jobs.', 'standard'),
    ],
];

if ($faqs === []) {
    return;
}
?>

<section class="section bg-white" aria-labelledby="faq-title">
    <div class="container grid gap-12 lg:gap-16">

        <?php get_template_part('templates/parts/section-header', null, [
            'id'          => 'faq-title',
            'eyebrow'     => $content['eyebrow'],
            'eyebrow_dot' => false,
            'title'       => $content['title'],
            'max_width'   => 'max-w-2xl',
            'cta'         => [
                'label' => __('See all FAQs', 'standard'),
                'url'   => \Standard\Url\internal('/faq/'),
                'class' => 'btn btn-outline-dark',
            ],
        ]); ?>

        <dl class="grid gap-px border border-blue-200 bg-blue-200">
            <?php foreach ($faqs as $faq) : ?>
                <div class="bg-white p-6 sm:p-8" data-reveal="fade">
                    <dt>
                        <h3 class="font-sans text-lg font-medium tracking-tight text-blue-900 md:text-xl m-0">
                            <?php echo esc_html($faq['question']); ?>
                        </h3>
                    </dt>
                    <dd class="mt-3 m-0 font-sans text-base leading-relaxed text-blue-700 max-w-3xl">
                        <?php echo esc_html($faq['answer']); ?>
                    </dd>
                </div>
            <?php endforeach; ?>
        </dl>

    </div>
</section>

<?php
// FAQPage JSON-LD, built from the same $faqs array so the structured data can
// never disagree with the visible answers. A JSON-LD script in the body is
// valid. Machine product pages emit their own per-URL FAQPage; this is the
// homepage's, and there is only one FAQPage node per URL.
$faq_entities = [];
foreach ($faqs as $faq) {
    $faq_entities[] = [
        '@type'          => 'Question',
        'name'           => $faq['question'],
        'acceptedAnswer' => [
            '@type' => 'Answer',
            'text'  => wp_strip_all_tags($faq['answer']),
        ],
    ];
}

$faq_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => $faq_entities,
];

echo '<script type="application/ld+json">'
    . wp_json_encode($faq_schema, \Standard\MachineSchema\SCHEMA_JSON_FLAGS)
    . '</script>' . "\n";
