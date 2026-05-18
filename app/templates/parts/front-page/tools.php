<?php
/**
 * Tools Section Template Part
 *
 * Quick-access tools grid for the front page.
 * Links to configurator, quizzes, calculators, and financing info.
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
    'title' => __('Tools to Help You Decide', 'standard'),
];

// Four destinations that DON'T overlap with the hero or the configurator.
// Compare and Manuals serve the spec-shopper. Quiz serves the
// owner-operator who's not sure which machine fits. Profit Calculator
// serves the buyer building the ROI case for their accountant.
$tools = [
    [
        'icon'  => 'settings',
        'title' => __('Compare Models', 'standard'),
        'url'   => '/machines/',
    ],
    [
        'icon'  => 'help-circle',
        'title' => __('Machine Quiz', 'standard'),
        'url'   => '/roof-panel-machine-assessment-quiz/',
    ],
    [
        'icon'  => 'dollar-sign',
        'title' => __('Profit Calculator', 'standard'),
        'url'   => '/learning-center/download/portable-rollforming-profit-calculator/',
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Manuals & Specs', 'standard'),
        'url'   => '/manuals/',
    ],
];
?>

<section class="section-compact bg-blue-50" aria-labelledby="tools-title">
    <div class="container grid gap-8 lg:gap-10">
        <h2 id="tools-title" class="section-title">
            <?php echo esc_html($content['title']); ?>
        </h2>

        <div class="grid grid-cols-2 gap-px bg-blue-200 border border-blue-200 md:grid-cols-4">
            <?php foreach ($tools as $tool) : ?>
                <a
                    href="<?php echo esc_url(\Standard\Url\internal($tool['url'])); ?>"
                    class="group flex flex-col justify-between p-6 bg-white no-underline transition-colors duration-200 hover:bg-blue-100 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px]"
                >
                    <h3 class="text-base font-medium text-blue-700 mb-12 transition-colors duration-200 group-hover:text-blue-500 md:text-lg">
                        <?php echo esc_html($tool['title']); ?>
                    </h3>
                    <div class="flex items-end justify-between">
                        <?php icon($tool['icon'], ['class' => 'w-8 h-8 text-blue-700 transition-colors duration-200 group-hover:text-blue-500 md:w-10 md:h-10']); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5 text-blue-400 transition-colors duration-200 group-hover:text-blue-500']); ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
