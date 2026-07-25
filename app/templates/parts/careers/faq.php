<?php
/**
 * Careers — FAQ
 *
 * Concise candidate FAQ. The $faqs array drives both the visible
 * accordion and FAQPage JSON-LD so they cannot drift. Every answer is
 * limited to original careers copy or current official NTM/Mazzella facts.
 *
 * @package Standard
 * @usage Careers (templates/template-careers.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Careers FAQ', 'standard'),
    'title'   => __('Common questions about working at NTM', 'standard'),
    'lede'    => __('Quick answers on location, role types, benefits, hiring requirements, and how to apply.', 'standard'),
];

$faqs = [
    [
        'question' => __('Where is New Tech Machinery located?', 'standard'),
        'answer'   => __('New Tech Machinery is headquartered in Aurora, Colorado, with manufacturing there and a second manufacturing facility in Hermosillo, Mexico.', 'standard'),
    ],
    [
        'question' => __('What kinds of jobs does NTM hire for?', 'standard'),
        'answer'   => __('Openings change over time. Roles commonly include engineering, production and manufacturing, service, and customer support around portable rollforming machines.', 'standard'),
    ],
    [
        'question' => __('How do I apply for a New Tech Machinery job?', 'standard'),
        'answer'   => __('Browse current openings on the Mazzella Search All Jobs board and apply there. That board is the destination for live New Tech Machinery positions.', 'standard'),
    ],
    [
        'question' => __('Does New Tech Machinery offer benefits?', 'standard'),
        'answer'   => __('Yes. NTM offers a comprehensive benefits package. Specific plan details are shared during the hiring process for a given role.', 'standard'),
    ],
    [
        'question' => __('What are NTM\'s hiring requirements?', 'standard'),
        'answer'   => __('NTM is committed to equal employment practices and is a drug-free workplace. All applicants are required to successfully complete a drug screen as a condition of employment. The company looks for people with a strong work ethic who are team- and customer-service oriented.', 'standard'),
    ],
];

if ($faqs === []) {
    return;
}
?>

<section class="bg-white py-16 lg:py-24 border-t border-blue-200" aria-labelledby="careers-faq-title">
    <div class="container">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16 lg:items-start">

            <div class="lg:col-span-5 grid gap-6 content-start lg:sticky lg:top-28">
                <p class="font-mono uppercase tracking-wider text-xs text-blue-500 m-0">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <h2 id="careers-faq-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight text-balance m-0">
                    <?php echo esc_html($content['title']); ?>
                </h2>
                <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-xl m-0">
                    <?php echo esc_html($content['lede']); ?>
                </p>
            </div>

            <div class="lg:col-span-7" data-accordion-group>
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
    </div>
</section>

<?php
// FAQPage JSON-LD from the same $faqs array so structured data cannot
// disagree with the visible answers.
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
    . wp_json_encode($faq_schema, \Standard\Seo\SCHEMA_JSON_FLAGS)
    . '</script>' . "\n";
