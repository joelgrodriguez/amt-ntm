<?php
/**
 * About — Manifesto (Dark Category-Style Hero)
 *
 * Follows the shared category hero pattern (templates/parts/hero-category.php,
 * as seen on the Seamless Gutter Machines page): dark blue field, two-column
 * grid with a text rail on the left and a 16:9 video panel on the right, and a
 * mono meta strip on a hairline rail at the foot of the rail.
 *
 * Differences from the category hero: the marketing headline stays the page's
 * visible H1 (this is the top of the About page, no category title to hide),
 * there is no CTA, and the meta strip carries the company metrics
 * (years / countries / facilities / category firsts).
 *
 * The video embeds directly (no facade, no lazy load) so it is ready to play
 * the moment the page renders.
 *
 * @package Standard
 * @usage About Page (page-about.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Video\render_video_embed;
use function Standard\Video\is_wistia_url;

$content = [
    'eyebrow'   => __('About New Tech Machinery', 'standard'),
    'title'     => __('We design, engineer, manufacture, and support every NTM machine.', 'standard'),
    'subhead'   => __('Since 1991', 'standard'),
    'lede'      => __('Engineers, builders, service techs, and support staff, all in and committed to your success.', 'standard'),
    'video_url' => 'https://fast.wistia.net/embed/iframe/kdv2kphni1?seo=false&videoFoam=true',
];

// Render directly and force eager loading — this video is not lazy-loaded.
$embed_html = str_replace('loading="lazy"', 'loading="eager"', render_video_embed($content['video_url']));

$metrics = [
    ['value' => '34+',  'label' => __('Years',           'standard')],
    ['value' => '40+',  'label' => __('Countries',       'standard')],
    ['value' => '2',    'label' => __('Facilities',      'standard')],
    ['value' => '10+',  'label' => __('Category firsts', 'standard')],
];
?>

<section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="about-manifesto-title">
    <div class="container py-16 lg:py-20 xl:py-24">
        <div class="grid gap-10 lg:grid-cols-12 lg:gap-12 lg:items-center">

            <!-- Left rail: kicker + title + subhead + lede + mono metrics -->
            <div class="grid gap-8 lg:col-span-6 lg:gap-10">

                <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                    <?php echo esc_html($content['eyebrow']); ?>
                </p>

                <div class="grid gap-3">
                    <h1 id="about-manifesto-title" class="font-sans font-medium tracking-tight text-white text-4xl lg:text-5xl">
                        <?php echo esc_html($content['title']); ?>
                    </h1>
                    <p class="font-mono uppercase tracking-wider text-sm md:text-base text-blue-300">
                        <?php echo esc_html($content['subhead']); ?>
                    </p>
                </div>

                <p class="text-lg text-blue-200 max-w-xl lg:text-xl">
                    <?php echo esc_html($content['lede']); ?>
                </p>

                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-4 border-t border-white/15 pt-6 mt-2 max-w-md">
                    <?php foreach ($metrics as $metric) : ?>
                        <div class="grid gap-1 min-w-0">
                            <dd class="font-sans font-medium text-white text-3xl md:text-4xl leading-none tracking-tight">
                                <?php echo esc_html($metric['value']); ?>
                            </dd>
                            <dt class="font-mono text-[10px] uppercase tracking-mono-meta text-blue-300">
                                <?php echo esc_html($metric['label']); ?>
                            </dt>
                        </div>
                    <?php endforeach; ?>
                </dl>

            </div>

            <!-- Right panel: 16:9 video -->
            <?php if ($embed_html !== '') : ?>
                <div class="video-responsive lg:col-span-6 bg-blue-800 overflow-hidden">
                    <?php echo $embed_html; ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</section>

<?php if ($embed_html !== '' && is_wistia_url($content['video_url'])) : ?>
    <script src="https://fast.wistia.net/assets/external/E-v1.js" async></script>
<?php endif; ?>
