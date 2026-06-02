<?php
/**
 * Roof Panel vs Gutter — Hero
 *
 * Decision-page hero. Unlike the category heroes (which carry an
 * sr-only H1 + an H2 marketing line + a product video), this is an
 * educational page, so the visible marketing line IS the H1 for SEO.
 * No video: the job here is to orient, not to showcase a machine.
 *
 * Single-column centered stack on a dark band, matching the category
 * hero's dark surface so the page reads as part of the same family.
 *
 * @package Standard
 *
 * @usage Roof Panel vs Gutter (page-roof-panel-vs-gutter.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="relative overflow-hidden bg-blue-900 text-white" aria-labelledby="vs-hero-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="mx-auto grid max-w-3xl gap-6 text-center lg:gap-8">

            <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                <?php esc_html_e('Start here · Which machine?', 'standard'); ?>
            </p>

            <h1
                id="vs-hero-title"
                class="font-sans font-medium tracking-tight text-balance text-white text-4xl md:text-5xl"
            >
                <?php esc_html_e('Roof Panel or Gutter Machine? Here’s How to Tell.', 'standard'); ?>
            </h1>

            <p class="mx-auto max-w-2xl text-lg text-blue-200 lg:text-xl">
                <?php esc_html_e('New Tech Machinery builds two families of portable rollformers. One forms the metal panels that become a roof and walls. The other forms seamless gutters that drain it. Which one you need comes down to one thing: what you make on the jobsite.', 'standard'); ?>
            </p>

            <div class="mt-2 flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#the-fork" class="btn btn-primary">
                    <?php esc_html_e('Find your machine', 'standard'); ?>
                    <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal('/choose-your-machine/')); ?>" class="btn btn-outline-light">
                    <?php esc_html_e('Take the machine quiz', 'standard'); ?>
                </a>
            </div>

        </div>
    </div>
</section>
