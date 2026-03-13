<?php
/**
 * Seamless Gutter Machines — Asymmetric Video Hero (Content Right)
 *
 * Full-width video background with angled dark overlay wedge on the RIGHT.
 * Content sits on the wedge; video is visible on the left.
 * Flipped layout from roof-wall hero.
 *
 * @package Standard
 *
 * @usage Seamless Gutter Machines (page-seamless-gutter-machines.php)
 */

declare(strict_types=1);

$content = [
    'eyebrow'       => __('Seamless Gutter Machines', 'standard'),
    'title'         => __('Seamless Gutters. Fabricated On-Site. Ready for Install.', 'standard'),
    'subtitle'      => __('Portable gutter machines that produce seamless K-style and box gutters right on the jobsite — from raw coil to finished product.', 'standard'),
    'cta_primary'   => __('See the Machines', 'standard'),
    'cta_primary_url' => '#product-grid',
    'cta_secondary' => __('Talk to a Specialist', 'standard'),
    'cta_secondary_url' => '/contact/',
    'video'         => 'https://newtechmachinery.com/wp-content/uploads/2025/09/NTM-hero-video.mp4',
    'poster'        => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
];

$stats = [
    [
        'value' => '30+',
        'label' => __('Yrs', 'standard'),
    ],
    [
        'value' => '4',
        'label' => __('Machines', 'standard'),
    ],
    [
        'value' => '$87K',
        'label' => __('Starting', 'standard'),
    ],
];
?>

<section class="relative min-h-[70vh] lg:min-h-[80vh] flex items-end lg:items-center overflow-hidden" aria-labelledby="gutter-hero-title">

    <!-- Video background -->
    <video
        class="absolute inset-0 w-full h-full object-cover"
        autoplay
        muted
        loop
        playsinline
        preload="metadata"
        poster="<?php echo esc_url($content['poster']); ?>"
    >
        <source src="<?php echo esc_url($content['video']); ?>" type="video/mp4">
    </video>

    <!-- Poster fallback -->
    <img
        src="<?php echo esc_url($content['poster']); ?>"
        alt=""
        class="absolute inset-0 w-full h-full object-cover"
        fetchpriority="high"
    >

    <!-- Mobile: full dark overlay -->
    <div class="absolute inset-0 bg-slate-950/90 lg:hidden"></div>

    <!-- Desktop: angled dark wedge overlay (RIGHT side) -->
    <div
        class="hidden lg:block absolute inset-0 bg-slate-950/90"
        style="clip-path: polygon(55% 0, 100% 0, 100% 100%, 40% 100%);"
    ></div>

    <!-- Square-grid texture on the wedge (desktop only) -->
    <div
        class="hidden lg:block absolute inset-0 pattern-square-grid pattern-square-grid--dark opacity-20"
        style="clip-path: polygon(55% 0, 100% 0, 100% 100%, 40% 100%);"
    >
        <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-left"></div>
    </div>

    <!-- Subtle gradient bleed at the wedge edge for softness -->
    <div
        class="hidden lg:block absolute inset-0"
        style="background: linear-gradient(105deg, transparent 42%, rgba(0,0,0,0.4) 48%, transparent 55%);"
    ></div>

    <!-- Content (right-aligned) -->
    <div class="relative z-10 container py-16 lg:py-24">
        <div class="max-w-xl lg:max-w-lg xl:max-w-xl grid gap-6 lg:ml-auto">

            <!-- Eyebrow -->
            <p class="text-sm font-semibold uppercase tracking-wider text-secondary">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>

            <!-- Title -->
            <h1 id="gutter-hero-title" class="text-3xl font-bold font-mono text-white md:text-4xl lg:text-[2.75rem] xl:text-5xl leading-tight">
                <?php echo esc_html($content['title']); ?>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg text-slate-300 lg:text-xl max-w-lg">
                <?php echo esc_html($content['subtitle']); ?>
            </p>

            <!-- Stats bar -->
            <div class="flex gap-6 sm:gap-8 border-t border-white/20 pt-6">
                <?php foreach ($stats as $stat) : ?>
                    <div class="grid gap-0.5">
                        <span class="text-2xl font-bold text-white lg:text-3xl">
                            <?php echo esc_html($stat['value']); ?>
                        </span>
                        <span class="text-xs text-slate-400 uppercase tracking-wider">
                            <?php echo esc_html($stat['label']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <a href="<?php echo esc_url($content['cta_primary_url']); ?>" class="btn btn-primary btn-lg">
                    <?php echo esc_html($content['cta_primary']); ?>
                    <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light btn-lg">
                    <?php echo esc_html($content['cta_secondary']); ?>
                </a>
            </div>

        </div>
    </div>

</section>
