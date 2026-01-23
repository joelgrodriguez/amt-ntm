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

$content = [
    'eyebrow'  => __('The 3 Step Plan', 'standard'),
    'title'    => __('Your Path to Better Equipment Financing', 'standard'),
    'text'     => __('Working with NTM is simple, transparent, and designed around your needs.', 'standard'),
    'cta_text' => __('Schedule a Call', 'standard'),
    'cta_url'  => '/contact/',
];

$steps = [
    [
        'number' => 1,
        'title'  => __('Schedule a Call', 'standard'),
        'text'   => __('Connect with an account specialist to discuss your equipment needs and budget.', 'standard'),
    ],
    [
        'number' => 2,
        'title'  => __('Get a Tailored Solution', 'standard'),
        'text'   => __("We'll design a custom financing package that fits your business requirements.", 'standard'),
    ],
    [
        'number' => 3,
        'title'  => __('Achieve Your Goals', 'standard'),
        'text'   => __('Gain efficiency, increase productivity, and grow your business with the right equipment.', 'standard'),
    ],
];
?>

<section class="section bg-slate-100 pattern-square-grid" aria-labelledby="three-step-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

    <div class="container section-content text-center">
        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="three-step-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="section-subtitle-centered">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="grid gap-6 md:grid-cols-3 lg:gap-8">
            <?php foreach ($steps as $step) : ?>
                <div class="grid gap-3 justify-items-center bg-white border border-slate-200 p-8">
                    <span class="inline-flex items-center justify-center w-14 h-14 bg-slate-200 text-slate-800 text-2xl font-bold">
                        <?php echo esc_html($step['number']); ?>
                    </span>
                    <h3 class="text-xl font-semibold text-slate-900">
                        <?php echo esc_html($step['title']); ?>
                    </h3>
                    <p class="text-slate-600">
                        <?php echo esc_html($step['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

        <div>
            <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-primary">
                <?php echo esc_html($content['cta_text']); ?>
            </a>
        </div>
    </div>
</section>
