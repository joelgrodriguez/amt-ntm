<?php
/**
 * Category Hero — Shared Template Part
 *
 * Asymmetric, left-anchored hero for category landing pages
 * (machines, gutter, roof/wall). Distinct from the centered
 * SaaS-default; this commits to product-catalog rhythm with
 * a 60/40 split: left rail of mono metadata + display headline,
 * right panel of video/poster imagery.
 *
 * @package Standard
 *
 * @param array  $content    {kicker, title, subtitle, cta_primary,
 *                            cta_primary_url, cta_secondary,
 *                            cta_secondary_url, video, poster}
 *                           Note: `kicker` (mono caps) replaces the
 *                           old `eyebrow` (pill).
 * @param array  $meta       Array of {label, value} mono rail items
 *                           rendered in the left rail. Replaces the
 *                           old `stats` array.
 * @param string $section_id ID used for aria-labelledby.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$content    = $args['content'] ?? [];
$meta       = $args['meta'] ?? [];
$section_id = $args['section_id'] ?? 'hero';
$video      = $content['video'] ?? '';
$poster     = $content['poster'] ?? '';
$kicker     = $content['kicker'] ?? ($content['eyebrow'] ?? '');
?>

<section class="relative overflow-hidden bg-blue-900 text-white" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container py-16 lg:py-24 xl:py-32">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 lg:items-end">

            <!-- Left rail: kicker + display title + subtitle + CTAs + mono meta -->
            <div class="grid gap-8 lg:col-span-7 lg:gap-10">

                <?php if (!empty($kicker)) : ?>
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-300">
                        <?php echo esc_html($kicker); ?>
                    </p>
                <?php endif; ?>

                <h1
                    id="<?php echo esc_attr($section_id); ?>-title"
                    class="font-medium leading-[0.95] tracking-tight text-white text-5xl sm:text-6xl lg:text-7xl xl:text-8xl"
                >
                    <?php echo esc_html($content['title']); ?>
                </h1>

                <?php if (!empty($content['subtitle'])) : ?>
                    <p class="text-lg text-blue-200 max-w-xl lg:text-xl">
                        <?php echo esc_html($content['subtitle']); ?>
                    </p>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>" class="btn btn-primary">
                        <?php echo esc_html($content['cta_primary']); ?>
                        <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <?php if (!empty($content['cta_secondary'])) : ?>
                        <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-light">
                            <?php echo esc_html($content['cta_secondary']); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($meta)) : ?>
                    <dl class="grid grid-cols-2 gap-x-8 gap-y-4 border-t border-white/15 pt-6 mt-2 max-w-md sm:grid-cols-3">
                        <?php foreach ($meta as $item) : ?>
                            <div class="grid gap-1">
                                <dt class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-300">
                                    <?php echo esc_html($item['label']); ?>
                                </dt>
                                <dd class="font-mono text-base text-white sm:text-lg">
                                    <?php echo esc_html($item['value']); ?>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>

            </div>

            <!-- Right panel: video / poster, off-center -->
            <div class="relative aspect-[4/3] lg:col-span-5 lg:aspect-[3/4] xl:aspect-[4/5] overflow-hidden bg-blue-800">
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
                <?php elseif (!empty($poster)) : ?>
                    <?php \Standard\Images\responsive_image($poster, '', 'full', [
                        'class'         => 'absolute inset-0 w-full h-full object-cover',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                    ]); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
