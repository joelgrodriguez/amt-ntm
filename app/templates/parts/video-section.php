<?php
/**
 * Video Section — Shared Template Part
 *
 * Utilitarian video player with top/bottom chrome bars.
 * Supports Wistia plus allowed oEmbed video providers.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type string $title        Top bar left text.            Default 'Who Is NTM?'.
 *     @type string $channel      Top bar right text.           Default 'Portable Rollforming Channel'.
 *     @type string $video_url    Video URL or legacy embed HTML. URLs are preferred.
 *     @type string $video_type   Bottom bar left label.        Default 'Company Overview'.
 *     @type string $company_name Bottom bar right text.        Default 'New Tech Machinery'.
 *     @type string $section_id   Unique ID for aria/anchoring. Default 'video-section'.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Video\render_video_embed;
use function Standard\Video\is_wistia_url;

$defaults = [
    'title'        => __('Who Is NTM?', 'standard'),
    'channel'      => __('Portable Rollforming Channel', 'standard'),
    'video_url'    => 'https://fast.wistia.net/embed/iframe/kdv2kphni1?seo=false&videoFoam=true',
    'video_type'   => __('Company Overview', 'standard'),
    'company_name' => __('New Tech Machinery', 'standard'),
    'section_id'   => 'video-section',
];

$args = wp_parse_args($args ?? [], $defaults);
$embed_html = render_video_embed($args['video_url'] ?? null);

if ($embed_html === '') {
    return;
}
?>

<section class="bg-blue-950 text-blue-500" aria-labelledby="<?php echo esc_attr($args['section_id'] . '-title'); ?>">
    <!-- Top Bar -->
    <div class="border-b border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red animate-pulse"></span>
                    <span id="<?php echo esc_attr($args['section_id'] . '-title'); ?>"><?php echo esc_html($args['title']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($args['channel']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Embed -->
    <div class="border-x border-blue-800 container py-6 lg:py-12">
        <div class="max-w-5xl mx-auto">
            <div class="video-responsive">
                <?php echo $embed_html; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-blue-800">
        <div class="border-x border-blue-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-2 pl-3">
                    <?php icon('play', ['class' => 'w-3 h-3 fill-current']); ?>
                    <span><?php echo esc_html($args['video_type']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span><?php echo esc_html($args['company_name']); ?></span>
                    <div class="flex gap-1">
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-blue-700"></span>
                        <span class="w-1 h-3 bg-blue-600"></span>
                        <span class="w-1 h-3 bg-blue-500"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (is_wistia_url($args['video_url'] ?? null)) : ?>
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
<?php endif; ?>
