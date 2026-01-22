<?php
/**
 * Three Step Plan Section Template Part
 *
 * Three-column grid showing the simple path to working with NTM.
 * Each step has a numbered badge, title, and description.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 */

declare(strict_types=1);

$steps = [
    [
        'number' => 1,
        'title'  => __('Schedule a Call', 'standard'),
        'text'   => __('Connect with an account specialist to discuss your equipment needs and budget.', 'standard'),
    ],
    [
        'number' => 2,
        'title'  => __('Get a Tailored Solution', 'standard'),
        'text'   => __('We\'ll design a custom financing package that fits your business requirements.', 'standard'),
    ],
    [
        'number' => 3,
        'title'  => __('Achieve Your Goals', 'standard'),
        'text'   => __('Gain efficiency, increase productivity, and grow your business with the right equipment.', 'standard'),
    ],
];
?>

<section class="py-16 bg-slate-100 pattern-square-grid md:py-20 lg:py-24" aria-labelledby="three-step-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container text-center">
        <p class="text-sm font-semibold uppercase tracking-wider text-secondary mb-2">
            <?php esc_html_e('The 3 Step Plan', 'standard'); ?>
        </p>
        <div class="w-12 h-1 bg-secondary mx-auto mb-6"></div>

        <h2 id="three-step-title" class="text-3xl font-bold text-slate-900 mb-4 md:text-4xl">
            <?php esc_html_e('Your Path to Better Equipment Financing', 'standard'); ?>
        </h2>
        <p class="text-lg text-slate-600 mb-12 max-w-2xl mx-auto">
            <?php esc_html_e('Working with NTM is simple, transparent, and designed around your needs.', 'standard'); ?>
        </p>

        <div class="grid gap-8 pt-6 md:grid-cols-3 md:gap-6 lg:gap-8">
            <?php foreach ($steps as $step) : ?>
                <div class="bg-white border border-slate-200 p-8">
                    <span class="inline-flex items-center justify-center w-14 h-14 bg-secondary text-white text-2xl font-bold -mt-14 mb-4">
                        <?php echo esc_html($step['number']); ?>
                    </span>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">
                        <?php echo esc_html($step['title']); ?>
                    </h3>
                    <p class="text-slate-600">
                        <?php echo esc_html($step['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mt-12">
            <a href="/contact/" class="btn btn-primary">
                <?php esc_html_e('Schedule a Call', 'standard'); ?>
            </a>
        </div>
    </div>
</section>
