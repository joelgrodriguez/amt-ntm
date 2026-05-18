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

$tools = [
    [
        'icon'  => 'settings',
        'title' => __('Build & Finance', 'standard'),
        'url'   => '/build-finance/',
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
        'title' => __('Financing Options', 'standard'),
        'url'   => '/machines/leasing-financing/',
    ],
];
?>

<section class="section-compact bg-blue-50" aria-labelledby="tools-title">
    <div class="container grid gap-8 lg:gap-10">
        <h2 id="tools-title" class="section-title">
            <?php echo esc_html($content['title']); ?>
        </h2>

        <div class="grid grid-cols-2 border border-blue-200 md:grid-cols-4">
            <?php foreach ($tools as $index => $tool) : ?>
                <a
                    href="<?php echo esc_url(\Standard\Url\internal($tool['url'])); ?>"
                    class="group flex flex-col justify-between p-6 no-underline border-blue-200 transition-colors duration-200 hover:bg-blue-100 focus-visible:outline-2 focus-visible:outline-blue-500 focus-visible:outline-offset-[-2px] <?php echo $index % 2 === 1 ? 'border-l' : ''; ?> <?php echo $index >= 2 ? 'border-t md:border-t-0' : ''; ?> <?php echo $index >= 1 ? 'md:border-l' : ''; ?>"
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
