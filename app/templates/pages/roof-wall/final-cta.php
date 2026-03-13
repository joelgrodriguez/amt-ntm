<?php
/**
 * Roof & Wall Panel Machines — Final CTA
 *
 * Closing call-to-action with background image and very faint overlay
 * to let the image breathe while keeping text readable.
 *
 * @package Standard
 *
 * @usage Roof & Wall Panel Machines (page-roof-wall-panel-machines.php)
 */

declare(strict_types=1);

$content = [
    'title'             => __('Ready to Roll?', 'standard'),
    'text'              => __("Whether you're expanding your business or buying your first machine, we're here to help you find the right fit.", 'standard'),
    'cta_primary'       => __('Talk to a Specialist', 'standard'),
    'cta_primary_url'   => '/contact/',
    'cta_secondary'     => __('Build & Finance', 'standard'),
    'cta_secondary_url' => '/build-finance/',
    'image'             => content_url('/uploads/2024/02/NTM-Signage-Main_Office-Wall_2-v2.jpg'),
];
?>

<section class="relative section overflow-hidden" aria-labelledby="roof-wall-final-cta-title">
    <img
        src="<?php echo esc_url($content['image']); ?>"
        alt=""
        class="absolute inset-0 w-full h-full object-cover"
        loading="lazy"
    >
    <div class="absolute inset-0 bg-slate-950/15"></div>

    <div class="relative z-10 container grid gap-8 lg:gap-10 text-center">

        <div class="grid gap-4">
            <h2 id="roof-wall-final-cta-title" class="text-3xl font-bold text-white md:text-4xl lg:text-5xl drop-shadow-lg">
                <?php echo esc_html($content['title']); ?>
            </h2>
            <p class="text-lg text-white/90 max-w-2xl mx-auto drop-shadow-md">
                <?php echo esc_html($content['text']); ?>
            </p>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="<?php echo esc_url($content['cta_primary_url']); ?>" class="btn btn-secondary btn-lg">
                <?php echo esc_html($content['cta_primary']); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <a href="<?php echo esc_url($content['cta_secondary_url']); ?>" class="btn btn-outline-light btn-lg">
                <?php echo esc_html($content['cta_secondary']); ?>
            </a>
        </div>

    </div>
</section>
