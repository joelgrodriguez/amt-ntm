<?php
/**
 * Category Hero — Shared Template Part
 *
 * Full-bleed image/video with dark overlay and centered content.
 * Used for category landing pages (machines, gutter, roof/wall).
 * Matches the product page hero treatment but with centered text
 * to visually distinguish category pages from individual products.
 *
 * @package Standard
 *
 * @param array  $content    {eyebrow, title, subtitle, cta_primary, cta_primary_url, cta_secondary, cta_secondary_url, video, poster}
 * @param array  $stats      Array of {value, label} stat items.
 * @param string $section_id ID used for aria-labelledby.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content    = $args['content'] ?? [];
$stats      = $args['stats'] ?? [];
$section_id = $args['section_id'] ?? 'hero';
$video      = $content['video'] ?? '';
$poster     = $content['poster'] ?? '';
?>

<section class="relative min-h-[60vh] lg:min-h-[70vh] flex items-center justify-center overflow-hidden bg-blue-800" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">

    <?php if (!empty($video)) : ?>
        <video
            class="absolute inset-0 w-full h-full object-cover"
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            poster="<?php echo esc_url($poster); ?>"
        >
            <source src="<?php echo esc_url($video); ?>" type="video/mp4">
        </video>
    <?php endif; ?>

    <?php if (!empty($poster)) : ?>
        <?php \Standard\Images\responsive_image($poster, '', 'full', [
            'class'         => 'absolute inset-0 w-full h-full object-cover',
            'loading'       => 'eager',
            'fetchpriority' => 'high',
        ]); ?>
    <?php endif; ?>

    <div class="hero-overlay"></div>
    <div class="hero-overlay__grain"></div>

    <div class="relative z-10 container text-center grid gap-6 py-20 lg:py-28">

        <?php if (!empty($content['eyebrow'])) : ?>
            <p>
                <span class="inline-block bg-red text-white text-xs font-medium uppercase tracking-wider px-3 py-1">
                    <?php echo esc_html($content['eyebrow']); ?>
                </span>
            </p>
        <?php endif; ?>

        <h1 id="<?php echo esc_attr($section_id); ?>-title" class="text-3xl font-semibold tracking-tight text-white md:text-5xl lg:text-6xl max-w-4xl mx-auto">
            <?php echo esc_html($content['title']); ?>
        </h1>

        <?php if (!empty($content['subtitle'])) : ?>
            <p class="text-lg text-blue-200 md:text-xl max-w-2xl mx-auto">
                <?php echo esc_html($content['subtitle']); ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($stats)) : ?>
            <div class="flex justify-center gap-8 sm:gap-12 border-t border-white/20 pt-6 mt-2 max-w-lg mx-auto">
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
        <?php endif; ?>

        <div class="flex flex-col sm:flex-row justify-center gap-4 pt-2">
            <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>" class="btn btn-primary btn-lg">
                <?php echo esc_html($content['cta_primary']); ?>
                <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
            </a>
            <?php if (!empty($content['cta_secondary'])) : ?>
                <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-light btn-lg">
                    <?php echo esc_html($content['cta_secondary']); ?>
                </a>
            <?php endif; ?>
        </div>

    </div>

</section>
