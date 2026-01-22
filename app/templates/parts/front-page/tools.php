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
 * @styles css/tools.css
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

<section class="tools py-12 bg-white md:py-16" aria-labelledby="tools-title">
    <div class="container">
        <h2 id="tools-title" class="sr-only">
            <?php esc_html_e('Shopping Tools', 'standard'); ?>
        </h2>

        <div class="tools__grid grid grid-cols-2 gap-6 md:grid-cols-4 md:gap-8 lg:gap-12">
            <?php foreach ($tools as $tool) : ?>
                <a href="<?php echo esc_url($tool['url']); ?>" class="tools__item group">
                    <div class="tools__icon-wrapper">
                        <?php icon($tool['icon'], ['class' => 'tools__icon w-10 h-10 md:w-12 md:h-12']); ?>
                    </div>
                    <h3 class="tools__title">
                        <?php echo esc_html($tool['title']); ?>
                    </h3>
                    <p class="tools__description">
                        <?php echo esc_html($tool['description']); ?>
                    </p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
