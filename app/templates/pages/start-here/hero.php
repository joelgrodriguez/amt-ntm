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
 * rollforming business"). Dark band matches the category-page family.
 *
 * @package Standard
 *
 * @usage Start Here (page-start-here.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}
?>

<section class="relative overflow-hidden bg-blue-900 text-white" aria-labelledby="start-here-title">
    <div class="container py-16 md:py-20 lg:py-24">
        <div class="mx-auto grid max-w-3xl gap-6 text-center lg:gap-8">

            <p class="font-mono text-xs uppercase tracking-mono-label text-blue-300">
                <?php esc_html_e('Start here · Your first machine business', 'standard'); ?>
            </p>

            <h1
                id="start-here-title"
                class="font-sans font-medium tracking-tight text-balance text-white text-4xl md:text-5xl"
            >
                <?php esc_html_e('Start a Metal Roofing &amp; Gutter Business of Your Own', 'standard'); ?>
            </h1>

            <p class="mx-auto max-w-2xl text-lg text-blue-200 lg:text-xl">
                <?php esc_html_e('A portable rollformer turns flat coil into finished metal roofing panels and seamless gutters right on the jobsite. That is a real, ownable business: you make the product, you keep the margin, and you do not wait on a supplier. Here is what it takes to start, and where to go next.', 'standard'); ?>
            </p>

            <div class="mt-2 flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#which-path" class="btn btn-primary">
                    <?php esc_html_e('See if this is for you', 'standard'); ?>
                    <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-outline-light">
                    <?php esc_html_e('Talk to a specialist', 'standard'); ?>
                </a>
            </div>

        </div>
    </div>
</section>
