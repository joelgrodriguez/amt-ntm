<?php
/**
 * Finance Center — Hero
 *
 * Two-column dark hero in the established page-video-hero vocabulary:
 * SEO H1 + lede on the left, the "How to Finance Your NTM Machine" Wistia
 * video on the right. The video is the answer to "show me, don't tell me"
 * for a buyer doing money math.
 *
 * The video source is read from the page's hero_video field (set in the DB
 * and captured in scripts/db/), falling back to the known finance Wistia
 * media ID so the hero never renders video-less if the field is cleared.
 *
 * @package Standard
 *
 * @usage Finance Center (page-finance-center.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Video\is_wistia_url;
use function Standard\Video\render_video_embed;

$post_id = get_the_ID();

// Fallback Wistia media for "How to Finance Your NTM Machine Video".
// hero_video (set in the DB) wins; this guarantees the hero ships with the
// video even before the capture script has run on a fresh environment.
$fallback_video = 'https://newtechmachinery.wistia.com/medias/hesm0txl1n';
$video = \Standard\PageTemplates\get_page_field($post_id, ['hero_video'], $fallback_video, false);

$embed_html = render_video_embed($video);
$has_video  = $embed_html !== '';

if ($has_video && is_wistia_url($video)) {
    wp_enqueue_script('wistia-external', 'https://fast.wistia.net/assets/external/E-v1.js', [], null, true);
}
?>

<section
    class="finance-hero bg-blue-900 text-white border-b border-blue-800"
    aria-labelledby="finance-hero-title"
>
    <div class="container section py-12 md:py-16 lg:py-20">
        <div class="grid gap-10 lg:gap-16 <?php echo $has_video ? 'lg:grid-cols-2 lg:items-center' : 'max-w-4xl'; ?>">

            <div class="grid gap-6 content-start">
                <p class="section-eyebrow text-blue-300">
                    <?php esc_html_e('NTM Finance Center', 'standard'); ?>
                </p>

                <h1
                    id="finance-hero-title"
                    class="font-sans text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight text-white leading-none text-balance"
                >
                    <?php esc_html_e('Financing for portable rollforming machines.', 'standard'); ?>
                </h1>

                <p class="text-lg md:text-xl text-blue-200 max-w-2xl text-pretty">
                    <?php esc_html_e('Every way to pay for your metal roofing or seamless gutter machine, in one place. Apply online in minutes, claim the Section 179 deduction, or work with NTM’s preferred lender. We don’t finance in-house, so we point you straight at the people who do.', 'standard'); ?>
                </p>

                <div class="flex flex-col sm:flex-row flex-wrap gap-4 pt-2">
                    <a
                        href="https://app.corbelpay.com/reception/newtechmachinery/applications/spot?p="
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-emphasis btn--commit w-full justify-center sm:w-auto"
                    >
                        <?php esc_html_e('Apply for financing', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a
                        href="#finance-paths"
                        class="btn btn-outline-light w-full justify-center sm:w-auto"
                    >
                        <?php esc_html_e('See all options', 'standard'); ?>
                    </a>
                </div>
            </div>

            <?php if ($has_video) : ?>
                <figure class="grid gap-3 m-0">
                    <div class="border border-blue-700 bg-black p-2">
                        <div class="video-responsive">
                            <?php echo $embed_html; ?>
                        </div>
                    </div>
                    <figcaption class="font-mono text-xs uppercase tracking-mono-label text-blue-400">
                        <?php esc_html_e('Watch — How financing an NTM machine works', 'standard'); ?>
                    </figcaption>
                </figure>
            <?php endif; ?>

        </div>
    </div>
</section>
