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

if (!defined('ABSPATH')) {
    exit;
}

get_header();

while (have_posts()) :
    the_post();

    // Get ACF fields
    $hero_video       = function_exists('get_field') ? get_field('hero_video', false, false) : null;
    $hero_video_embed = Standard\Video\render_video_embed(is_string($hero_video) ? $hero_video : null);
    $hero_title       = get_field('hero_title');
    $hero_description = get_field('hero_description');
    $has_hero         = $hero_video_embed !== '' || $hero_title || $hero_description;
?>

<main id="primary">

    <?php if ($has_hero) : ?>
        <section class="bg-blue-900 text-white py-12 lg:py-24 relative">
            <div class="hero-overlay__grain"></div>

            <div class="container relative z-10">
                <div class="grid gap-8 lg:grid-cols-2 lg:gap-12 items-center">

                    <?php if ($hero_title || $hero_description) : ?>
                        <div class="grid gap-6">
                            <?php if ($hero_title) : ?>
                                <h1 class="text-3xl md:text-4xl lg:text-5xl font-medium font-mono">
                                    <?php echo esc_html($hero_title); ?>
                                </h1>
                            <?php endif; ?>

                            <?php if ($hero_description) : ?>
                                <p class="text-lg text-blue-300">
                                    <?php echo esc_html($hero_description); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($hero_video_embed !== '') : ?>
                        <div class="video-responsive">
                            <?php echo $hero_video_embed; ?>
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
