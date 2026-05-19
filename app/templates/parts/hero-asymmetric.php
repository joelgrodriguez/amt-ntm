<?php
/**
 * Asymmetric Video Hero — Shared Template Part
 *
 * Full-width video background with angled dark overlay wedge.
 * Content sits on the wedge; video is visible on the opposite side.
 * Falls back to poster image. Stacks on mobile.
 *
 * @package Standard
 *
 * @param array  $content          {eyebrow, title, subtitle, cta_primary, cta_primary_url, cta_secondary, cta_secondary_url, video, poster}
 * @param array  $stats            Array of {value, label} stat items.
 * @param string $content_position 'left' (default) or 'right'.
 * @param string $section_id       ID used for aria-labelledby.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content          = $args['content'] ?? [];
$stats            = $args['stats'] ?? [];
$content_position = $args['content_position'] ?? 'left';
$section_id       = $args['section_id'] ?? 'hero';

// Position-dependent values
$is_right   = ($content_position === 'right');
$clip_path  = $is_right
    ? 'polygon(55% 0, 100% 0, 100% 100%, 40% 100%)'
    : 'polygon(0 0, 60% 0, 45% 100%, 0% 100%)';
$content_ml = $is_right ? ' lg:ml-auto' : '';
?>

<section class="relative min-h-[70vh] lg:min-h-[80vh] flex items-end lg:items-center overflow-hidden" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">

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
    <?php \Standard\Images\responsive_image((string) ($content['poster'] ?? ''), '', 'full', [
        'class'         => 'absolute inset-0 w-full h-full object-cover',
        'loading'       => 'eager',
        'fetchpriority' => 'high',
    ]); ?>

    <!-- Mobile: solid dark overlay -->
    <div class="absolute inset-0 bg-blue-950/75 lg:hidden"></div>

    <!-- Desktop: angled dark wedge overlay -->
    <div
        class="hidden lg:block absolute inset-0 bg-blue-950/75"
        style="clip-path: <?php echo esc_attr($clip_path); ?>;"
    ></div>

    <!-- Subtle gradient bleed at the wedge edge for softness -->
    <div
        class="hidden lg:block absolute inset-0"
        style="background: linear-gradient(105deg, transparent 42%, rgba(0,0,0,0.4) 48%, transparent 55%);"
    ></div>

    <!-- Grain texture -->
    <div class="hero-overlay__grain"></div>

    <!-- Content -->
    <div class="relative z-10 container py-16 lg:py-24">
        <div class="max-w-xl lg:max-w-lg xl:max-w-xl grid gap-6<?php echo esc_attr($content_ml); ?>">

            <!-- Eyebrow -->
            <p class="text-sm font-medium uppercase tracking-wider text-red">
                <?php echo esc_html($content['eyebrow']); ?>
            </p>

            <!-- Title -->
            <h1 id="<?php echo esc_attr($section_id); ?>-title" class="text-3xl font-medium tracking-tight text-white md:text-4xl lg:text-[2.75rem] xl:text-5xl leading-tight">
                <?php echo esc_html($content['title']); ?>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg text-blue-300 lg:text-xl max-w-lg">
                <?php echo esc_html($content['subtitle']); ?>
            </p>

            <!-- Stats bar -->
            <div class="flex gap-6 sm:gap-8 border-t border-white/20 pt-6">
                <?php foreach ($stats as $stat) : ?>
                    <div class="grid gap-0.5">
                        <span class="text-2xl font-medium text-white lg:text-3xl">
                            <?php echo esc_html($stat['value']); ?>
                        </span>
                        <span class="text-xs text-blue-400 uppercase tracking-wider">
                            <?php echo esc_html($stat['label']); ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>" class="btn btn-primary">
                    <?php echo esc_html($content['cta_primary']); ?>
                    <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-light">
                    <?php echo esc_html($content['cta_secondary']); ?>
                </a>
            </div>

        </div>
    </div>

</section>
