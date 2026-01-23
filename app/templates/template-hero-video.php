<?php
/**
 * Template Name: Hero Video
 *
 * Page template with a video hero section.
 * Uses ACF fields for hero video, title, and description.
 *
 * ACF Fields required:
 * - hero_video (oEmbed) - Video embed
 * - hero_title (Text) - Hero heading
 * - hero_description (Textarea) - Hero description text
 *
 * @package Standard
 */

declare(strict_types=1);

get_header();

while (have_posts()) :
    the_post();

    // Get ACF fields
    $hero_video       = get_field('hero_video');
    $hero_title       = get_field('hero_title');
    $hero_description = get_field('hero_description');
    $has_hero         = $hero_video || $hero_title || $hero_description;
?>

<main id="primary">

    <?php if ($has_hero) : ?>
        <section class="pattern-square-grid pattern-square-grid--dark bg-slate-900 text-white py-12 lg:py-24 relative">
            <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left"></div>
            <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right"></div>

            <div class="container relative z-10">
                <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-center">

                    <?php if ($hero_title || $hero_description) : ?>
                        <div class="grid gap-6">
                            <?php if ($hero_title) : ?>
                                <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-mono">
                                    <?php echo esc_html($hero_title); ?>
                                </h1>
                            <?php endif; ?>

                            <?php if ($hero_description) : ?>
                                <p class="text-lg text-slate-300">
                                    <?php echo esc_html($hero_description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($hero_video) : ?>
                        <div class="video-responsive">
                            <?php echo render_video_embed($hero_video); ?>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="py-12 lg:py-24">
        <div class="container">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="prose prose-lg max-w-4xl mx-auto">
                    <?php the_content(); ?>
                </div>
            </article>
        </div>
    </section>

</main>

<?php
endwhile;

get_footer();

/**
 * Render video embed from various sources.
 *
 * Handles iframe embeds, Wistia URLs, YouTube, Vimeo, and generic oEmbed.
 *
 * @param string $video The video URL or embed code.
 * @return string The rendered embed HTML.
 */
function render_video_embed(string $video): string {
    // Already an embed (iframe or embed tag)
    if (str_contains($video, '<iframe') || str_contains($video, '<embed')) {
        return $video;
    }

    // Strip any HTML tags to get clean URL
    $url = wp_strip_all_tags($video);

    // Wistia
    if (str_contains($url, 'wistia.com/medias/')) {
        if (preg_match('/medias\/([a-zA-Z0-9]+)/', $url, $matches)) {
            return sprintf(
                '<iframe src="https://fast.wistia.net/embed/iframe/%s?videoFoam=true" allowtransparency="true" frameborder="0" scrolling="no" name="wistia_embed" allow="autoplay; fullscreen" allowfullscreen loading="lazy"></iframe>',
                esc_attr($matches[1])
            );
        }
    }

    // YouTube, Vimeo, or other oEmbed providers
    $embed = wp_oembed_get($url);

    return $embed ?: '';
}
