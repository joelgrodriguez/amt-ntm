<?php
/**
 * Machine Product — Hero Section
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];

if (!$product) {
    return;
}

$hero     = $machine['hero'] ?? [];
$finance  = $machine['finance'] ?? [];

$headline  = !empty($hero['headline']) ? $hero['headline'] : $product->get_name();
$subtitle  = !empty($hero['subtitle']) ? $hero['subtitle'] : $product->get_short_description();
$image     = $hero['hero_image'] ?? $hero['image'] ?? '';
$video     = $hero['video'] ?? '';

$price_display = '';
if (!empty($finance['price_range'])) {
    $price_display = $finance['price_range'];
} else {
    $price_display = $product->get_price_html();
}

$configurator_url = '/configurator/' . $product->get_slug() . '/';
$machine_name     = $product->get_name();
?>

<section id="machine-hero" class="relative flex-1 flex items-end overflow-hidden bg-slate-800" aria-labelledby="machine-hero-title">
    <?php if (!empty($image)) : ?>
        <img src="<?php echo esc_url($image); ?>"
             alt="<?php echo esc_attr($headline); ?>"
             class="absolute inset-0 w-full h-full object-cover">
    <?php endif; ?>

    <div class="absolute inset-0 bg-slate-950/45"></div>
    <div class="pattern-png-texture" style="background-image: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/hero-bg-pattern-bg.png'); ?>');"></div>

    <p class="absolute top-8 right-8 z-10 text-sm font-bold uppercase tracking-widest text-white md:top-10 md:right-12"><?php echo esc_html($machine_name); ?></p>

    <div class="container relative z-10 pb-16 pt-32">
        <div class="grid gap-6 max-w-xl" style="text-shadow: 0 2px 4px rgba(0,0,0,0.5);">
            <h1 id="machine-hero-title" class="text-3xl font-bold font-mono text-white md:text-4xl lg:text-5xl">
                <?php echo esc_html($headline); ?>
            </h1>
            <?php if (!empty($subtitle)) : ?>
                <p class="text-lg text-slate-200 md:text-xl"><?php echo wp_kses_post($subtitle); ?></p>
            <?php endif; ?>
            <?php if (!empty($price_display)) : ?>
                <p class="text-sm text-slate-300 uppercase tracking-wider">Starting at <span class="text-white font-semibold"><?php echo wp_kses_post($price_display); ?></span></p>
            <?php endif; ?>
            <div class="flex gap-4 mt-2" style="text-shadow: none;">
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-secondary">Build &amp; Quote</a>
                <a href="#machine-breakdown" class="btn btn-outline-light">Explore</a>
            </div>
        </div>
    </div>
</section>
