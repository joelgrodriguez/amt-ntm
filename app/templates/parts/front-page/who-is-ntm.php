<?php
/**
 * Who Is NTM Section Template Part
 *
 * Video section showcasing New Tech Machinery company overview.
 * Uses the same utilitarian video player style as single-video.php.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @see single-video.php - Uses same video player styling
 * @see css/media.css - Video responsive styles
 */

declare(strict_types=1);

$content = [
    'title'         => __('Who Is NTM?', 'standard'),
    'channel'       => __('Portable Rollforming Channel', 'standard'),
    'video_title'   => __('Who Is NTM? Video', 'standard'),
    'video_type'    => __('Company Overview', 'standard'),
    'company_name'  => __('New Tech Machinery', 'standard'),
    'video_url'     => 'https://fast.wistia.net/embed/iframe/kdv2kphni1?seo=false&videoFoam=true',
];
?>

<section class="who-is-ntm bg-slate-950 text-slate-500" aria-labelledby="who-is-ntm-title">
    <!-- Top Bar -->
    <div class="border-b border-slate-800">
        <div class="border-x border-slate-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-3 pl-3">
                    <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                    <span id="who-is-ntm-title"><?php echo esc_html($content['title']); ?></span>
                </div>
                <div class="flex items-center gap-3 pr-3">
                    <span><?php echo esc_html($content['channel']); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Video Embed -->
    <div class="border-x border-slate-800 container py-6 lg:py-12">
        <div class="max-w-5xl mx-auto">
            <div class="video-responsive">
                <iframe
                    src="<?php echo esc_url($content['video_url']); ?>"
                    title="<?php echo esc_attr($content['video_title']); ?>"
                    allow="autoplay; fullscreen"
                    allowtransparency="true"
                    frameborder="0"
                    scrolling="no"
                    class="wistia_embed"
                    name="wistia_embed"
                    msallowfullscreen
                    width="100%"
                    height="100%"
                ></iframe>
            </div>
        </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-slate-800">
        <div class="border-x border-slate-800 container">
            <div class="flex items-center justify-between py-3 text-xs font-mono uppercase tracking-wider">
                <div class="flex items-center gap-2 pl-3">
                    <?php icon('play--solid', ['class' => 'w-3 h-3 fill-current']); ?>
                    <span><?php echo esc_html($content['video_type']); ?></span>
                </div>
                <div class="flex items-center gap-4 pr-3">
                    <span><?php echo esc_html($content['company_name']); ?></span>
                    <div class="flex gap-1">
                        <span class="w-1 h-3 bg-slate-700"></span>
                        <span class="w-1 h-3 bg-slate-700"></span>
                        <span class="w-1 h-3 bg-slate-600"></span>
                        <span class="w-1 h-3 bg-slate-500"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
