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

$tools = [
    [
        'icon'        => 'settings',
        'title'       => __('Build & Finance', 'standard'),
        'description' => __('Configure your machine', 'standard'),
        'url'         => '/build-finance/',
    ],
    [
        'icon'        => 'help--outline',
        'title'       => __('Machine Quiz', 'standard'),
        'description' => __('Find your perfect machine', 'standard'),
        'url'         => '/roof-panel-machine-assessment-quiz/',
    ],
    [
        'icon'        => 'predictive',
        'title'       => __('Profit Calculator', 'standard'),
        'description' => __('Estimate your ROI', 'standard'),
        'url'         => '/learning-center/download/portable-rollforming-profit-calculator/',
    ],
    [
        'icon'        => 'finance',
        'title'       => __('Financing Options', 'standard'),
        'description' => __('Flexible payment plans', 'standard'),
        'url'         => '/machines/leasing-financing/',
    ],
];
?>

<section class="py-12 bg-white md:py-16" aria-labelledby="tools-title">
    <div class="container">
        <h2 id="tools-title" class="text-2xl font-bold text-center text-slate-900 mb-8 md:text-3xl md:mb-12">
            <?php esc_html_e('Tools to Help You Decide', 'standard'); ?>
        </h2>

        <div class="grid grid-cols-2 gap-6 md:grid-cols-4 md:gap-8 lg:gap-12">
            <?php foreach ($tools as $tool) : ?>
                <a
                    href="<?php echo esc_url($tool['url']); ?>"
                    class="group flex flex-col items-center text-center no-underline transition-transform duration-200 hover:-translate-y-1 motion-reduce:transition-none motion-reduce:hover:translate-y-0 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-4"
                >
                    <div class="flex items-center justify-center mb-4 w-20 h-20 bg-slate-100 transition-colors duration-200 group-hover:bg-slate-200 motion-reduce:transition-none md:w-24 md:h-24">
                        <?php icon($tool['icon'], ['class' => 'w-10 h-10 text-slate-700 transition-colors duration-200 group-hover:text-secondary motion-reduce:transition-none md:w-12 md:h-12']); ?>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 mb-1 md:text-lg">
                        <?php echo esc_html($tool['title']); ?>
                    </h3>
                    <p class="text-sm text-slate-500 leading-snug hidden md:block">
                        <?php echo esc_html($tool['description']); ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
