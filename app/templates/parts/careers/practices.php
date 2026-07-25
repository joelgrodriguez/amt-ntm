<?php
/**
 * Careers — Hiring practices
 *
 * Fact-only statements from the original careers page: equal employment,
 * comprehensive benefits package (no perk list), drug-free workplace,
 * and pre-employment drug screen. No invented benefits.
 *
 * @package Standard
 * @usage Careers (templates/template-careers.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content = [
    'eyebrow' => __('Hiring practices', 'standard'),
    'title'   => __('What candidates should know before applying', 'standard'),
    'lede'    => __('Our baseline employment requirements and commitments. Specific plan details for a role are shared during hiring.', 'standard'),
];

$practices = [
    [
        'title' => __('Equal employment', 'standard'),
        'text'  => __('NTM is committed to equal employment practices.', 'standard'),
    ],
    [
        'title' => __('Benefits package', 'standard'),
        'text'  => __('NTM offers a comprehensive benefits package. Specific plan details vary by role and are shared with candidates during the hiring process.', 'standard'),
    ],
    [
        'title' => __('Drug-free workplace', 'standard'),
        'text'  => __('NTM is a drug-free workplace. All applicants are required to successfully complete a drug screen as a condition of employment.', 'standard'),
    ],
];
?>

<section class="bg-blue-50 py-16 lg:py-24 border-t border-blue-200" aria-labelledby="careers-practices-title">
    <div class="container">
        <div class="max-w-3xl mb-12 lg:mb-16">
            <p class="font-mono uppercase tracking-wider text-xs text-blue-500 mb-5">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="careers-practices-title" class="font-sans font-medium text-blue-900 text-2xl md:text-3xl lg:text-[2.5rem] leading-tight tracking-tight text-balance mb-6">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="font-sans text-blue-700 text-base lg:text-lg leading-relaxed max-w-2xl">
                <?php echo esc_html($content['lede']); ?>
            </p>
        </div>

        <ul class="grid grid-cols-1 md:grid-cols-3 gap-0 border-t border-blue-200" role="list">
            <?php foreach ($practices as $practice) : ?>
                <li class="grid gap-3 py-8 md:py-10 md:px-8 border-b border-blue-200 md:border-b-0 md:border-l md:first:border-l-0 md:first:pl-0">
                    <h3 class="font-sans font-medium text-blue-900 text-xl leading-tight m-0">
                        <?php echo esc_html($practice['title']); ?>
                    </h3>
                    <p class="font-sans text-blue-700 text-base leading-relaxed m-0 max-w-prose">
                        <?php echo esc_html($practice['text']); ?>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
