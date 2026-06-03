<?php
/**
 * First-Time Buyer Playlist — Hero
 *
 * Sets the promise: this is not the whole video library, it is a short
 * path watched in order. The visible marketing line is the real <h1>
 * (SEO target: "portable rollforming videos for beginners / first-time
 * buyers"). Dark band with the shared dot-grid backdrop so it reads as
 * part of the category-page family, matching the vs/ and start-here
 * heroes.
 *
 * No hero image here on purpose: the chapters below are wall-to-wall
 * video thumbnails, so a seventh thumbnail up top would just dilute the
 * first real one. The hero stays type-only and points down.
 *
 * @package Standard
 *
 * @usage First-Time Buyer Playlist (page-first-time-buyer-playlist.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="playlist-hero-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="grid max-w-3xl gap-6 lg:gap-8">

            <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                <?php esc_html_e('Start here · Watch in order', 'standard'); ?>
            </p>

            <h1
                id="playlist-hero-title"
                class="font-sans font-medium tracking-tight text-balance text-white text-4xl md:text-5xl"
            >
                <?php esc_html_e('The First-Time Buyer Playlist', 'standard'); ?>
            </h1>

            <p class="max-w-xl text-lg text-blue-200 lg:text-xl">
                <?php esc_html_e('Thinking about a portable rollformer but not sure where to start? We pulled the videos that answer a first-time buyer’s real questions and put them in order. Start at the top, work down, and by the end you will know what these machines do, whether the business fits you, and which one to look at first.', 'standard'); ?>
            </p>

            <div class="mt-2 flex flex-col gap-4 sm:flex-row">
                <a href="#chapter-1" class="btn btn-primary">
                    <?php esc_html_e('Start watching', 'standard'); ?>
                    <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal('/learning-center/')); ?>" class="btn btn-outline-light">
                    <?php esc_html_e('Browse the full library', 'standard'); ?>
                </a>
            </div>

        </div>
    </div>
</section>
