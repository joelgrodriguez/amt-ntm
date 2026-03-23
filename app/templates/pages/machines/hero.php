<?php
/**
 * Machines Page — Hero Banner
 *
 * Full-width hero image with headline overlay.
 *
 * @package Standard
 *
 * @usage Machines Page (page-machines.php)
 */

declare(strict_types=1);

$content = [
    'title'    => __('Make More Money on Every Metal Roof Job', 'standard'),
    'subtitle' => __('Save up to $2.25/sq ft by fabricating panels on-site with NTM portable rollformers.', 'standard'),
    'cta_text' => __('Explore the Lineup', 'standard'),
    'cta_url'  => '#lineup',
    'image'    => 'https://newtechmachinery.com/wp-content/uploads/2025/09/Machine-on-rooftop-scaled.jpg',
];
?>

<section class="relative min-h-[60vh] lg:min-h-[70vh] flex items-center justify-center overflow-hidden" aria-labelledby="machines-hero-title">
    <img
        src="<?php echo esc_url($content['image']); ?>"
        alt=""
        class="absolute inset-0 w-full h-full object-cover"
        fetchpriority="high"
    >
    <div class="absolute inset-0 bg-slate-950/45"></div>
    <div class="pattern-png-texture" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-bg-pattern-bg.png'); ?>');"></div>

    <div class="relative z-10 container text-center grid gap-6 py-20">
        <h1 id="machines-hero-title" class="text-3xl font-bold text-white md:text-5xl lg:text-6xl max-w-4xl mx-auto">
            <?php echo esc_html($content['title']); ?>
        </h1>
        <p class="text-lg text-slate-200 md:text-xl max-w-2xl mx-auto">
            <?php echo esc_html($content['subtitle']); ?>
        </p>
        <div>
            <a href="<?php echo esc_url($content['cta_url']); ?>" class="btn btn-secondary btn-lg">
                <?php echo esc_html($content['cta_text']); ?>
                <?php icon('arrow-down', ['class' => 'w-5 h-5']); ?>
            </a>
        </div>
    </div>
</section>
