<?php
/**
 * Front-page section: decision-support tile links.
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
    'title'   => __('Compare & Decide.', 'standard'),
];

// Buyer-facing decision tools only. Owner reference (manuals, profiles, UL,
// service hub) lives in its own front-page/owner-resources section so people
// who already own a machine aren't hunting through the sales funnel (#105).
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
];
?>

<section class="section bg-blue-50" aria-labelledby="tools-title">
    <div class="container grid gap-12 lg:gap-16">

        <?php get_template_part('templates/parts/section-header', null, [
            'id'          => 'tools-title',
            'eyebrow'     => $content['eyebrow'],
            'eyebrow_dot' => false,
            'title'       => $content['title'],
            'max_width'   => 'max-w-2xl',
        ]); ?>

        <div class="grid grid-cols-2 gap-px border border-blue-200 bg-blue-200 md:grid-cols-3">
            <?php foreach ($tools as $tool) : ?>
            <a
                href="<?php echo esc_url(\Standard\Url\internal($tool['url'])); ?>"
                class="group flex min-h-[9rem] flex-col justify-between bg-blue-50 p-5 no-underline transition-colors duration-200 hover:bg-blue-500 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px] sm:p-6"
                >
                    <h3 class="font-mono text-sm font-medium uppercase tracking-wider text-blue-700 transition-colors duration-200 group-hover:text-blue-50 md:text-base">
                        <?php echo esc_html($tool['title']); ?>
                    </h3>
                    <div class="flex items-end justify-between">
                        <?php icon($tool['icon'], [
                            'class'       => 'w-8 h-8 text-blue-700 transition-colors duration-200 group-hover:text-blue-50 md:w-10 md:h-10',
                            'aria-hidden' => 'true',
                        ]); ?>
                        <?php icon('arrow-right', [
                            'class'       => 'w-5 h-5 text-blue-400 transition-colors duration-200 group-hover:text-blue-50',
                            'aria-hidden' => 'true',
                        ]); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
