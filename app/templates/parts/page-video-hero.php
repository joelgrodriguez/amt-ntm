<?php
/**
 * Shared video/text hero for reusable page templates.
 *
 * @package Standard
 *
 * @param array $args {
 *     @type array  $hero       Hero data from Standard\PageTemplates\get_hero_data().
 *     @type string $section_id Section ID.
 * }
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Video\is_wistia_url;
use function Standard\Video\render_video_embed;

$hero = is_array($args['hero'] ?? null) ? $args['hero'] : [];
$section_id = (string) ($args['section_id'] ?? 'page-video-hero');

$eyebrow = (string) ($hero['eyebrow'] ?? '');
$title = (string) ($hero['title'] ?? '');
$description = (string) ($hero['description'] ?? '');
$legacy_content = (string) ($hero['legacy_content'] ?? '');
$video = (string) ($hero['video'] ?? '');
$embed_html = render_video_embed($video);
$has_content = $title !== '' || $description !== '' || $legacy_content !== '';
$has_video = $embed_html !== '';

if (!$has_content && !$has_video) {
    return;
}

if ($has_video && is_wistia_url($video)) {
    wp_enqueue_script('wistia-external', 'https://fast.wistia.net/assets/external/E-v1.js', [], null, true);
}
?>

<section
    class="bg-blue-900 text-white border-b border-blue-800"
    <?php if ($title !== '') : ?>
        aria-labelledby="<?php echo esc_attr($section_id . '-title'); ?>"
    <?php else : ?>
        aria-label="<?php echo esc_attr(get_the_title()); ?>"
    <?php endif; ?>
>
    <div class="container section py-12 md:py-16 lg:py-20">
        <div class="grid gap-8 lg:gap-16 <?php echo $has_video ? 'lg:grid-cols-2 lg:items-center' : 'max-w-4xl'; ?>">
            <?php if ($has_content) : ?>
                <div class="grid gap-6 content-start">
                    <?php if ($legacy_content !== '') : ?>
                        <?php if ($eyebrow !== '') : ?>
                            <p class="section-eyebrow text-blue-300"><?php echo esc_html($eyebrow); ?></p>
                        <?php endif; ?>
                        <div class="prose prose-lg prose-invert max-w-none prose-headings:font-medium prose-headings:tracking-tight prose-p:text-blue-200 prose-a:text-white">
                            <?php echo wp_kses_post($legacy_content); ?>
                        </div>
                    <?php else : ?>
                        <div class="section-header-left">
                            <?php if ($eyebrow !== '') : ?>
                                <p class="section-eyebrow text-blue-300"><?php echo esc_html($eyebrow); ?></p>
                            <?php endif; ?>

                            <h1 id="<?php echo esc_attr($section_id . '-title'); ?>" class="font-sans text-4xl md:text-5xl lg:text-6xl font-medium tracking-tight text-white leading-none">
                                <?php echo esc_html($title); ?>
                            </h1>

                            <?php if ($description !== '') : ?>
                                <p class="text-lg md:text-xl text-blue-200 max-w-2xl">
                                    <?php echo esc_html($description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($has_video) : ?>
                <div class="border border-blue-700 bg-black p-2">
                    <div class="video-responsive">
                        <?php echo $embed_html; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
