<?php
/**
 * Seamless Gutter Machines — Value Proposition Cards
 *
 * Three-card horizontal feature strip highlighting key gutter benefits.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow' => __('Why Portable Gutter Machines', 'standard'),
    'title'   => __('30 Years of Proven Performance', 'standard'),
];

$cards = [
    [
        'icon'  => 'settings',
        'title' => __('On-Site Fabrication', 'standard'),
        'text'  => __('Produce seamless gutters anywhere — no pre-fab joints, no shipping, no wasted material. One continuous piece from coil to install.', 'standard'),
    ],
    [
        'icon'  => 'trending-up',
        'title' => __('Industry Standard', 'standard'),
        'text'  => __('NTM pioneered polyurethane drive rollers for gutter machines. The MACH II line has been the industry benchmark for over 30 years.', 'standard'),
    ],
    [
        'icon'  => 'dollar-sign',
        'title' => __('Low Entry Cost', 'standard'),
        'text'  => __('Starting at $87,245 with flexible financing options. Most gutter contractors pay off their machine within the first year of operation.', 'standard'),
    ],
];
?>

<section class="section pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="gutter-value-prop-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>
            <div class="section-divider-center"></div>
            <h2 id="gutter-value-prop-title" class="section-title">
                <?php echo esc_html($content['title']); ?>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            <?php foreach ($cards as $card) : ?>
                <div class="grid gap-4 text-center">
                    <div class="flex justify-center">
                        <div class="w-14 h-14 rounded-full bg-[#e5f0f9] flex items-center justify-center">
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
