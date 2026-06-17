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
 *                            cta_primary_url, cta_primary_icon,
 *                            cta_secondary, cta_secondary_url, video,
 *                            poster, poster_alt}
 *                           cta_primary_icon defaults to 'arrow-down'.
 *                           Pass 'arrow-right' for navigation CTAs.
 *                           Wistia videos embed directly with their native
 *                           player (no click-to-play poster facade); the
 *                           poster is used only when there is no video.
 * @param array  $meta       Array of {label, value} mono rail items.
 * @param string $section_id ID used for aria-labelledby.
 * @param bool   $pattern    Render the dot-grid backdrop. Default true
 *                           for category landing pages; product detail
 *                           pages should pass false.
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Video\render_video_embed;
use function Standard\Video\is_wistia_url;

$content          = $args['content'] ?? [];
$meta             = $args['meta'] ?? [];
$section_id       = $args['section_id'] ?? 'hero';
$pattern          = $args['pattern'] ?? true;
$video            = $content['video'] ?? '';
$poster           = $content['poster'] ?? '';
$poster_alt       = $content['poster_alt'] ?? '';
$kicker           = $content['kicker'] ?? ($content['eyebrow'] ?? '');
$cta_primary_icon = $content['cta_primary_icon'] ?? 'arrow-down';

// Decide which video pipeline to use.
// Wistia embeds directly with its native player (no click-to-play poster
// facade) so it behaves like the About hero. Force eager loading — these
// heroes are above the fold. Self-hosted mp4 still autoplays inline.
$is_mp4          = $video !== '' && preg_match('/\.mp4($|\?)/i', $video);
$is_wistia       = $video !== '' && !$is_mp4 && is_wistia_url($video);
$wistia_embed    = $is_wistia ? str_replace('loading="lazy"', 'loading="eager"', render_video_embed($video)) : '';
$other_embed     = ($video !== '' && !$is_mp4 && !$is_wistia) ? render_video_embed($video) : '';
$has_wistia      = $wistia_embed !== '';
$has_other_embed = $other_embed !== '';
$has_mp4_video   = (bool) $is_mp4;
?>

<?php
$section_classes = 'relative overflow-hidden bg-blue-900 text-white';
if ($pattern) {
    $section_classes .= ' pattern-dot-grid pattern-dot-grid--dark';
}
?>
<section class="<?php echo esc_attr($section_classes); ?>" aria-labelledby="<?php echo esc_attr($section_id); ?>-title">
    <?php
    // Screen-reader-only H1 carrying the WP page title (e.g. "Roof and
    // Wall Panel Machines"). Mirrors the old theme's pattern and gives
    // search engines the direct category keyword as the page's primary
    // heading; the visible marketing headline below is an H2.
    $page_title = function_exists('get_the_title') ? get_the_title() : '';
    if ($page_title !== '') :
    ?>
        <h1 class="sr-only"><?php echo esc_html($page_title); ?></h1>
    <?php endif; ?>

    <div class="container py-16 lg:py-20 xl:py-24">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 lg:items-center">

            <!-- Left rail: kicker + title + subtitle + CTAs + mono meta -->
            <div class="grid gap-8 lg:col-span-6 lg:gap-10">

                <?php if (!empty($kicker)) : ?>
                    <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                        <?php echo esc_html($kicker); ?>
                    </p>
                <?php endif; ?>

                <h2
                    id="<?php echo esc_attr($section_id); ?>-title"
                    class="font-sans font-medium tracking-tight text-white text-4xl lg:text-5xl"
                >
                    <?php echo wp_kses($content['title'], ['br' => ['class' => []]]); ?>
                </h2>

                <?php if (!empty($content['subtitle'])) : ?>
                    <p class="text-lg text-blue-200 max-w-xl lg:text-xl">
                        <?php echo esc_html($content['subtitle']); ?>
                    </p>
                <?php endif; ?>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_primary_url'])); ?>" class="btn btn-primary">
                        <?php echo esc_html($content['cta_primary']); ?>
                        <?php icon($cta_primary_icon, ['class' => 'w-5 h-5']); ?>
                    </a>
                    <?php if (!empty($content['cta_secondary'])) : ?>
                        <a href="<?php echo esc_url(\Standard\Url\internal($content['cta_secondary_url'])); ?>" class="btn btn-outline-light">
                            <?php echo esc_html($content['cta_secondary']); ?>
                        </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($meta)) :
                    $meta_cols = count($meta) === 3 ? 'grid-cols-3' : 'grid-cols-2 sm:grid-cols-3';
                ?>
                    <dl class="grid <?php echo esc_attr($meta_cols); ?> gap-x-6 gap-y-4 border-t border-white/15 pt-6 mt-2 max-w-md">
                        <?php foreach ($meta as $item) : ?>
                            <div class="grid gap-1 min-w-0">
                                <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-300">
                                    <?php echo esc_html($item['label']); ?><span class="sr-only">:</span>
                                </dt>
                                <dd class="font-mono text-sm text-white sm:text-base lg:text-lg break-words">
                                    <?php echo esc_html($item['value']); ?>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>

            </div>

            <!-- Right panel: 16:9 video or poster -->
            <div class="video-responsive lg:col-span-6 bg-blue-800">
                <?php if ($has_wistia) : ?>
                    <?php echo $wistia_embed; ?>
                <?php elseif ($has_other_embed) : ?>
                    <?php echo $other_embed; ?>
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

<?php if ($has_wistia) : ?>
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
<?php endif; ?>
