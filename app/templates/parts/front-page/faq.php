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
    'eyebrow' => __('Portable Rollforming FAQs', 'standard'),
    'title'   => __('Portable rollforming machine questions, answered.', 'standard'),
    'lede'    => __('Get clear answers about portable rollforming machine costs, lead times, machine selection, financing, and producing roof panels or seamless gutters on-site.', 'standard'),
];

$faqs = [
    [
        'question' => __('What does a portable rollforming machine do?', 'standard'),
        'answer'   => __('A portable rollforming machine turns metal coil into finished roof panels or seamless gutters at the jobsite. It produces panels to the exact required length, reducing factory lead times, transport limits, and mid-panel seams.', 'standard'),
    ],
    [
        'question' => __('How much does a portable rollforming machine cost?', 'standard'),
        'answer'   => __('NTM portable rollforming machines currently start at $9,800 for a MACH II seamless gutter machine. Roof panel machines start at $44,900 for the SSR MultiPro Jr.; the flagship SSQ3 MultiPro starts at $85,000, with its trailer sold separately.', 'standard'),
    ],
    [
        'question' => __('How long does it take to receive an NTM rollforming machine?', 'standard'),
        'answer'   => __('Current NTM lead time is 6 to 10 weeks from order. Financing can be completed during the purchase process, and week-one onboarding includes running panels with the NTM team on-site.', 'standard'),
    ],
    [
        'question' => __('Which portable rollforming machine is right for my business?', 'standard'),
        'answer'   => __('Choose a machine based on the profiles and products your business sells. MACH II machines serve K-style gutter work; SSR, SSH, and SSQ3 MultiPro machines serve standing seam roofing. Use the 10-question machine quiz or talk to an NTM specialist for a job-specific recommendation.', 'standard'),
    ],
];

if ($faqs === []) {
    return;
}
?>

<section class="section bg-white" aria-labelledby="faq-title">
    <div class="container grid gap-12 lg:grid-cols-[minmax(0,0.8fr)_minmax(0,1.2fr)] lg:items-start lg:gap-16 xl:gap-24">

        <div class="lg:sticky lg:top-28">
            <?php get_template_part('templates/parts/section-header', null, [
                'id'             => 'faq-title',
                'eyebrow'        => $content['eyebrow'],
                'eyebrow_dot'    => false,
                'title'          => $content['title'],
                'lede'           => $content['lede'],
                'max_width'      => 'max-w-xl',
                'lede_max_width' => 'max-w-lg',
                'cta'            => [
                    'label' => __('View all rollforming FAQs', 'standard'),
                    'url'   => \Standard\Url\internal('/faq/'),
                    'class' => 'btn btn-outline-dark',
                ],
            ]); ?>
        </div>

        <div data-accordion-group>
            <?php foreach ($faqs as $index => $faq) : ?>
                <details class="accordion"<?php echo $index === 0 ? ' open' : ''; ?>>
                    <summary>
                        <?php echo esc_html($faq['question']); ?>
                        <span class="accordion__icon" aria-hidden="true">
                            <?php icon('chevron-down', ['class' => 'w-5 h-5']); ?>
                        </span>
                    </summary>
                    <div class="accordion__body text-base leading-relaxed text-blue-700">
                        <p class="m-0"><?php echo esc_html($faq['answer']); ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>

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
