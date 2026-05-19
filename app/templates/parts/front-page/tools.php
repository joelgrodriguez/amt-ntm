<?php
/**
 * Tools Section Template Part
 *
 * Editorial slab: large display title and supporting copy on the left,
 * four numbered link rows on the right, separated by a full-height
 * hairline (DESIGN.md §8.5). One pinpoint of red on a short rule below
 * the copy block earns the brand's saturated-color moment for this
 * section. No tiles, no icon-card grid, no row eyebrows.
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
    'title'   => __('Decision Tools.', 'standard'),
    'lead'    => __('Four ways to size up the buy before you pick up the phone.', 'standard'),
];

$items = [
    [
        'index' => '01',
        'title' => __('Compare Models', 'standard'),
        'value' => __('Side-by-side specs across every machine.', 'standard'),
        'url'   => '/machines/',
    ],
    [
        'index' => '02',
        'title' => __('Manuals & Specs', 'standard'),
        'value' => __('Operator manuals and datasheets, English and Spanish.', 'standard'),
        'url'   => '/manuals/',
    ],
    [
        'index' => '03',
        'title' => __('Machine Quiz', 'standard'),
        'value' => __('Not sure which machine fits? Three questions, one answer.', 'standard'),
        'url'   => '/roof-panel-machine-assessment-quiz/',
    ],
    [
        'index' => '04',
        'title' => __('Profit Calculator', 'standard'),
        'value' => __('Run the ROI numbers your accountant will want to see.', 'standard'),
        'url'   => '/learning-center/download/portable-rollforming-profit-calculator/',
    ],
];
?>

<section class="section bg-white" aria-labelledby="tools-title">
    <div class="container">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-16">

            <div class="lg:col-span-5 lg:pr-8 lg:border-r lg:border-blue-200">
                <p class="section-eyebrow">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>
                <h2 id="tools-title" class="mt-4 font-sans font-medium text-blue-900 leading-[1.05] tracking-tight text-4xl md:text-5xl lg:text-6xl">
                    <?php echo esc_html($content['title']); ?>
                </h2>
                <p class="mt-8 max-w-md font-sans text-blue-600 text-base leading-relaxed lg:text-lg">
                    <?php echo esc_html($content['lead']); ?>
                </p>
                <div class="mt-10 w-16 h-0.5 bg-red"></div>
            </div>

            <ol class="lg:col-span-7">
                <?php foreach ($items as $i => $item) : ?>
                    <li class="<?php echo $i === 0 ? 'border-t border-blue-200' : ''; ?> border-b border-blue-200">
                        <a
                            href="<?php echo esc_url(\Standard\Url\internal($item['url'])); ?>"
                            class="group grid grid-cols-[3rem_1fr_auto] items-center gap-x-6 py-7 md:py-8 no-underline transition-colors duration-200 hover:bg-blue-50 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px]"
                        >
                            <span class="font-mono text-sm text-blue-400 tracking-wider self-start pt-1">
                                <?php echo esc_html($item['index']); ?>
                            </span>
                            <div class="grid gap-1">
                                <h3 class="font-sans text-xl font-medium text-blue-900 leading-tight transition-colors duration-200 group-hover:text-blue-500 md:text-2xl">
                                    <?php echo esc_html($item['title']); ?>
                                </h3>
                                <p class="font-sans text-sm text-blue-600 leading-relaxed md:text-base">
                                    <?php echo esc_html($item['value']); ?>
                                </p>
                            </div>
                            <span class="self-center text-blue-400 transition-all duration-200 group-hover:text-blue-500 group-hover:translate-x-1">
                                <?php icon('arrow-right', [
                                    'class'       => 'w-5 h-5',
                                    'aria-hidden' => 'true',
                                ]); ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ol>

        </div>
    </div>
</section>
