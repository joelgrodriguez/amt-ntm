<?php
/**
 * Start Here — Hero
 *
 * The opportunity opener. This page is the business-starting front door:
 * the reader is new to the industry and weighing whether to start a
 * business making metal roofing panels or seamless gutters on the
 * jobsite. The hero sells the opportunity, not a machine, and routes
 * down to the "which path is me" chooser.
 *
 * Like the vs/ hero, this is an educational landing page, so the visible
 * marketing line IS the <h1> for SEO (target: "start a metal roofing /
 * rollforming business"). Dark band with the shared dot-grid backdrop
 * (pattern-dot-grid--dark, the same one hero-category uses) so it reads
 * as part of the category-page family. A jobsite photo sits beside the
 * text on desktop and below it on mobile, so a first-timer sees the work
 * before reading about it.
 *
 * We reuse the dot-grid pattern, not the whole hero-category part: that
 * part renders an sr-only <h1> + an <h2> headline, but this page needs
 * the marketing line to be the real <h1>, and it has no video slot to
 * fill. Reusing the two pattern classes gets the look without the cost.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$hero_image = content_url('/uploads/2026/05/ntm-customer-onsite-002.jpg');
$hero_alt   = __('An NTM owner running a portable rollforming machine on a jobsite', 'standard');
?>

<section class="relative overflow-hidden bg-blue-900 text-white pattern-dot-grid pattern-dot-grid--dark" aria-labelledby="start-here-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="grid items-center gap-10 lg:grid-cols-[1.1fr_1fr] lg:gap-16">

            <div class="grid max-w-2xl gap-6 lg:gap-8">

                <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                    <?php esc_html_e('Start here · Your first machine business', 'standard'); ?>
                </p>

                <h1
                    id="start-here-title"
                    class="font-sans font-medium tracking-tight text-balance text-white text-4xl md:text-5xl"
                >
                    <?php esc_html_e('Start a Metal Roofing &amp; Gutter Business of Your Own', 'standard'); ?>
                </h1>

                <p class="max-w-xl text-lg text-blue-200 lg:text-xl">
                    <?php esc_html_e('A portable rollformer turns flat coil into finished metal roofing panels and seamless gutters right on the jobsite. That is a real, ownable business: you make the product, you keep the margin, and you do not wait on a supplier. Here is what it takes to start, and where to go next.', 'standard'); ?>
                </p>

                <div class="mt-2">
                    <a href="#which-path" class="btn btn-primary">
                        <?php esc_html_e('See if this is for you', 'standard'); ?>
                        <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                    </a>
                </div>

            </div>

            <div class="relative aspect-video overflow-hidden border border-white/10 bg-blue-800">
                <?php
                \Standard\Images\responsive_image(
                    $hero_image,
                    $hero_alt,
                    'large',
                    ['class' => 'h-full w-full object-cover']
                );
                ?>
            </div>

        </div>
    </div>
</section>
