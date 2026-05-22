<?php
/**
 * Category Hero — Shared Template Part
 *
 * Two-column hero for category landing pages (machines, gutter,
 * roof/wall). Text rail left, 16:9 video panel right. Video can be:
 * - a Wistia embed iframe URL (preferred for marketing video)
 * - a Wistia share URL (wistia.com/medias/…)
 * - an MP4 URL (self-hosted, autoplay/muted/loop)
 * - just a poster image (no video)
 *
 * The right panel always renders at 16:9 because that's the aspect
 * the marketing videos ship in.
 *
 * @package Standard
 *
 * @param array  $content    {kicker, title, subtitle, cta_primary,
 *                            cta_primary_url, cta_secondary,
 *                            cta_secondary_url, video, poster,
 *                            poster_alt}
 * @param array  $meta       Array of {label, value} mono rail items.
 * @param string $section_id ID used for aria-labelledby.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Video\render_video_embed;
use function Standard\Video\is_wistia_url;

$content    = $args['content'] ?? [];
$meta       = $args['meta'] ?? [];
$section_id = $args['section_id'] ?? 'hero';
$video      = $content['video'] ?? '';
$poster     = $content['poster'] ?? '';
$poster_alt = $content['poster_alt'] ?? '';
$kicker     = $content['kicker'] ?? ($content['eyebrow'] ?? '');

// Decide which video pipeline to use.
$is_mp4   = $video !== '' && preg_match('/\.mp4($|\?)/i', $video);
$embed    = ($video !== '' && !$is_mp4) ? render_video_embed($video) : '';
$has_iframe_video = $embed !== '';
$has_mp4_video    = (bool) $is_mp4;
?>

<section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <div class="container py-16 lg:py-20 xl:py-24">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 lg:items-center">

            <!-- Left rail: kicker + title + subtitle + CTAs + mono meta -->
            <div class="grid gap-8 lg:col-span-6 lg:gap-10">

                <?php if (!empty($kicker)) : ?>
                    <p class="font-mono text-xs uppercase tracking-[0.18em] text-blue-300">
                        <?php echo esc_html($kicker); ?>
                    </p>
                <?php endif; ?>

                <h1
                    id="<?php echo esc_attr($section_id); ?>-title"
                    class="font-sans font-medium leading-[0.95] tracking-tight text-white text-3xl md:text-4xl lg:text-5xl xl:text-6xl"
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

            <!-- Right panel: 16:9 video or poster -->
            <div class="video-responsive lg:col-span-6 bg-blue-800">
                <?php if ($has_iframe_video) : ?>
                    <?php echo $embed; ?>
                <?php elseif ($has_mp4_video) : ?>
                    <video
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                        <?php if ($poster) : ?>poster="<?php echo esc_url($poster); ?>"<?php endif; ?>
                    >
                        <source src="<?php echo esc_url($video); ?>" type="video/mp4">
                    </video>
                <?php elseif ($poster) : ?>
                    <?php \Standard\Images\responsive_image($poster, $poster_alt, 'full', [
                        'class'         => 'object-cover',
                        'loading'       => 'eager',
                        'fetchpriority' => 'high',
                    ]); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>

<?php if ($has_iframe_video && is_wistia_url($video)) : ?>
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
<?php endif; ?>
