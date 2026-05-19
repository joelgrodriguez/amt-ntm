<?php
/**
 * Tools Section Template Part
 *
 * Four decision-support destinations in a tile strip.
 * 4 columns at md+, 2 columns on mobile. Tiles separated by hairline
 * dividers. Each tile: title top, icon + arrow bottom row.
 *
 * Hover state: tile fills brand blue; title, icon, and arrow all
 * turn white in lockstep so the whole tile reads as one ignited target.
 *
 * Editorial heading block sits above the strip with a short red rule
 * (the section's 10% color moment).
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
    'eyebrow' => __('Know Before You Buy', 'standard'),
    'title'   => __('Decision Tools.', 'standard'),
];

$tools = [
    [
        'icon'  => 'filter',
        'title' => __('Compare Models', 'standard'),
        'url'   => '/machines/',
    ],
    [
        'icon'  => 'help-circle',
        'title' => __('Machine Quiz', 'standard'),
        'url'   => '/roof-panel-machine-assessment-quiz/',
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Profit Calculator', 'standard'),
        'url'   => '/learning-center/download/portable-rollforming-profit-calculator/',
    ],
    [
        'icon'  => 'file-text',
        'title' => __('Manuals & Specs', 'standard'),
        'url'   => '/manuals/',
    ],
];
?>

<section class="section bg-blue-50 border-y border-blue-200" aria-labelledby="tools-title">
    <div class="container grid gap-12 lg:gap-16">

        <div class="max-w-2xl">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <h2 id="tools-title" class="section-title mt-4">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <div class="mt-8 section-divider"></div>
        </div>

        <div class="grid grid-cols-2 border border-blue-200 md:grid-cols-4">
            <?php foreach ($tools as $i => $tool) : ?>
                <a
                    href="<?php echo esc_url(\Standard\Url\internal($tool['url'])); ?>"
                    class="group flex flex-col justify-between p-6 bg-blue-50 no-underline transition-colors duration-200 hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px] <?php
                        // Right divider: every tile except the last in its row.
                        // Mobile (2 cols): items 0 and 2 are left column, so right border on them.
                        // Desktop (4 cols): items 0, 1, 2 have a right neighbor.
                        echo $i % 2 === 0 ? 'border-r border-blue-200 ' : '';
                        echo $i === 1 ? 'md:border-r md:border-blue-200 ' : '';
                        // Bottom divider: only on first-row items, only below md (where grid has 2 rows).
                        // max-md: ensures the border is never painted at desktop.
                        echo $i < 2 ? 'max-md:border-b max-md:border-blue-200 ' : '';
                    ?>"
                >
                    <h3 class="font-mono text-sm font-medium uppercase tracking-wider text-blue-700 mb-12 transition-colors duration-200 group-hover:text-white md:text-base">
                        <?php echo esc_html($tool['title']); ?>
                    </h3>
                    <div class="flex items-end justify-between">
                        <?php icon($tool['icon'], [
                            'class'       => 'w-8 h-8 text-blue-700 transition-colors duration-200 group-hover:text-white md:w-10 md:h-10',
                            'aria-hidden' => 'true',
                        ]); ?>
                        <?php icon('arrow-right', [
                            'class'       => 'w-5 h-5 text-blue-400 transition-colors duration-200 group-hover:text-white',
                            'aria-hidden' => 'true',
                        ]); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
