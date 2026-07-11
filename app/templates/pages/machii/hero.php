<?php
/**
 * MACH II Family — Hero
 *
 * Full-bleed video background hero. The MP4 plays muted/looped behind
 * a left-aligned text rail: eyebrow + display headline + subhead + a
 * single primary CTA jumping to the family-portrait anchor. A linear
 * scrim from blue-900 at left to transparent at right keeps the text
 * legible while leaving the right half of the video clean.
 *
 * Stats meta strip is hidden in this iteration; the structure is
 * commented out below so it can be brought back without rebuilding
 * the array.
 *
 * @package Standard
 *
 * @usage MACH II Family (page-machii.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$video      = content_url('/uploads/2026/05/20260511_NTM_Abel-Highlight-MACH-II-In-Action-Video_V1-720p.mp4');
$poster     = content_url('/uploads/2026/05/ntm-mach2-gutter-install-abel-001.jpg');
$poster_alt = __('NTM MACH II seamless gutter machine in action', 'standard');

$page_title = function_exists('get_the_title') ? get_the_title() : '';
?>

<section class="relative overflow-hidden bg-blue-900 text-white" aria-labelledby="machii-hero-title">

    <?php if ($page_title !== '') : ?>
        <h1 class="sr-only"><?php echo esc_html($page_title); ?></h1>
    <?php endif; ?>

    <!-- Background video. Lazy-decoded, autoplay muted loop. Poster
         carries the visual until the video is buffered. -->
    <video
        class="absolute inset-0 w-full h-full object-cover z-0"
        autoplay
        muted
        loop
        playsinline
        preload="metadata"
        poster="<?php echo esc_url($poster); ?>"
        aria-hidden="true"
    >
        <source src="<?php echo esc_url($video); ?>" type="video/mp4">
    </video>

    <!-- Solid overlay + grain, same system as the homepage hero slider.
         The overlay is a 42% black wash (not a gradient) so the video
         reads behind it evenly across the whole frame. -->
    <div class="hero-overlay hero-overlay--machii"></div>
    <div class="hero-overlay__grain"></div>

    <div class="container relative z-10 py-24 lg:py-32 xl:py-40 min-h-[80vh] lg:min-h-[88vh] flex items-center">
        <div class="grid gap-8 max-w-2xl lg:gap-10">

            <span class="hero__eyebrow">
                <span class="hero__eyebrow-dot" aria-hidden="true"></span>
                <span><?php esc_html_e('MACH II Family · Seamless Gutter Machines', 'standard'); ?></span>
            </span>

            <h2
                id="machii-hero-title"
                class="font-sans font-medium leading-[0.95] tracking-tight text-white text-4xl md:text-5xl lg:text-6xl xl:text-7xl"
            >
                <?php esc_html_e('The standard in portable gutter machines.', 'standard'); ?>
            </h2>

            <p class="text-lg text-blue-100 max-w-xl lg:text-xl">
                <?php esc_html_e('NTM built the MACH II in 1994 and didn\'t stop. Three decades later, it\'s still the machine on every reputable gutter truck on seven continents.', 'standard'); ?>
            </p>

            <div>
                <a href="#machii-family-portrait" class="btn btn-primary">
                    <?php esc_html_e('See the Models', 'standard'); ?>
                    <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                </a>
            </div>

            <?php /* Stats meta strip — hidden in this iteration. To
                    restore, uncomment the dl below and the $meta array.
            $meta = [
                ['label' => __('Since',     'standard'), 'value' => '1994'],
                ['label' => __('Models',    'standard'), 'value' => '3'],
                ['label' => __('Lead Time', 'standard'), 'value' => '6–8 wks'],
                ['label' => __('Patent',    'standard'), 'value' => 'US 5,394,722'],
            ];
            ?>
            <dl class="grid grid-cols-2 gap-x-8 gap-y-4 border-t border-white/15 pt-6 mt-2 max-w-md sm:grid-cols-4">
                <?php foreach ($meta as $item) : ?>
                    <div class="grid gap-1">
                        <dt class="font-mono text-[10px] uppercase tracking-[0.15em] text-blue-300">
                            <?php echo esc_html($item['label']); ?><span class="sr-only">:</span>
                        </dt>
                        <dd class="font-mono text-base text-white">
                            <?php echo esc_html($item['value']); ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>
            <?php */ ?>

        </div>
    </div>
</section>
