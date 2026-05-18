<?php
/**
 * Tools Section Template Part
 *
 * Four decision-support destinations grouped by intent.
 * Spec-shoppers get Compare Models and Manuals & Specs.
 * Newcomers and ROI-builders get Machine Quiz and Profit Calculator.
 *
 * Layout: two-row editorial table. Per-row eyebrow names the intent.
 * Hairline borders mark the structural grid (DESIGN.md §8.5). No tiles,
 * no fills, no icon-card grid: this is a directory, not a feature grid.
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
    'eyebrow' => __('Before You Configure', 'standard'),
    'title'   => __('Decide With Data', 'standard'),
];

$rows = [
    [
        'eyebrow' => __('For The Spec Shopper', 'standard'),
        'items'   => [
            [
                'index' => '01',
                'title' => __('Compare Models', 'standard'),
                'value' => __('Side-by-side specs across every machine.', 'standard'),
                'url'   => '/machines/',
            ],
            [
                'index' => '02',
                'title' => __('Manuals & Specs', 'standard'),
                'value' => __('Operator manuals and full datasheets, English and Spanish.', 'standard'),
                'url'   => '/manuals/',
            ],
        ],
    ],
    [
        'eyebrow' => __('For The Buyer Building A Case', 'standard'),
        'items'   => [
            [
                'index' => '03',
                'title' => __('Machine Quiz', 'standard'),
                'value' => __("Not sure which machine fits? Three questions, one answer.", 'standard'),
                'url'   => '/roof-panel-machine-assessment-quiz/',
            ],
            [
                'index' => '04',
                'title' => __('Profit Calculator', 'standard'),
                'value' => __('Run the ROI numbers your accountant will want to see.', 'standard'),
                'url'   => '/learning-center/download/portable-rollforming-profit-calculator/',
            ],
        ],
    ],
];
?>

<section class="section-compact bg-blue-50" aria-labelledby="tools-title">
    <div class="container grid gap-12 lg:gap-16">

        <div class="section-header-left">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider"></div>
            <h2 id="tools-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="border-t border-blue-200">
            <?php foreach ($rows as $row) : ?>
                <div class="grid border-b border-blue-200 md:grid-cols-[14rem_1fr]">

                    <p class="font-mono font-medium uppercase tracking-wider text-blue-500 text-xs pt-6 pb-2 md:py-8 md:pr-8 md:border-r md:border-blue-200">
                        <?php echo esc_html($row['eyebrow']); ?>
                    </p>

                    <ul class="grid md:grid-cols-2">
                        <?php foreach ($row['items'] as $i => $item) : ?>
                            <li class="<?php echo $i === 1 ? 'md:border-l md:border-blue-200' : ''; ?>">
                                <a
                                    href="<?php echo esc_url(\Standard\Url\internal($item['url'])); ?>"
                                    class="group grid gap-3 py-6 md:p-8 no-underline transition-colors duration-200 hover:bg-white focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px]"
                                >
                                    <span class="font-mono text-xs text-blue-400 tracking-wider">
                                        <?php echo esc_html($item['index']); ?>
                                    </span>
                                    <h3 class="font-sans text-xl font-medium text-blue-700 leading-tight transition-colors duration-200 group-hover:text-blue-500">
                                        <?php echo esc_html($item['title']); ?>
                                        <?php icon('arrow-right', [
                                            'class'       => 'inline-block w-4 h-4 ml-1 -mt-0.5 text-blue-400 transition-transform duration-200 group-hover:translate-x-1 group-hover:text-blue-500',
                                            'aria-hidden' => 'true',
                                        ]); ?>
                                    </h3>
                                    <p class="font-sans text-sm text-blue-600 leading-relaxed">
                                        <?php echo esc_html($item['value']); ?>
                                    </p>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
