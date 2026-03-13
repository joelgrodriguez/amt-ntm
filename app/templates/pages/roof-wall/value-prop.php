<?php
/**
 * Roof & Wall Panel Machines — Value Proposition Cards
 *
 * Three-card horizontal feature strip highlighting key benefits.
 * Distinct from the machines page centered text block.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow' => __('Why Portable Rollforming', 'standard'),
    'title'   => __('Freedom from Factory Constraints', 'standard'),
];

$cards = [
    [
        'icon'  => 'settings',
        'title' => __('On-Site Fabrication', 'standard'),
        'text'  => __('Produce panels right on the jobsite. No factory lead times, no shipping damage, no wasted trips waiting on deliveries.', 'standard'),
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Multi-Profile Versatility', 'standard'),
        'text'  => __('Up to 16 profiles from a single machine — standing seam roof, flush wall, and board & batten siding panels on demand.', 'standard'),
    ],
    [
        'icon'  => 'dollar-sign',
        'title' => __('Proven ROI', 'standard'),
        'text'  => __('Save up to $2.25/sq ft versus factory panels. Most contractors pay off their machine within the first 1–2 years.', 'standard'),
    ],
];
?>

<section class="section pattern-dot-grid relative" aria-labelledby="roof-wall-value-prop-title">
    <div class="gradient-fade-bottom"></div>
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="roof-wall-value-prop-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <?php foreach ($cards as $card) : ?>
                <div class="grid gap-4 text-center">
                    <div class="flex justify-center">
                        <div class="w-14 h-14 rounded-full bg-primary/10 flex items-center justify-center">
                            <?php icon($card['icon'], ['class' => 'w-6 h-6 text-primary']); ?>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">
                        <?php echo esc_html($card['title']); ?>
                    </h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        <?php echo esc_html($card['text']); ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
