<?php
/**
 * Machine Product — Hero Section
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];

if (!$product) {
    return;
}

$hero     = $machine['hero'] ?? [];
$finance  = $machine['finance'] ?? [];

$headline  = !empty($hero['headline']) ? $hero['headline'] : $product->get_name();
$subtitle  = !empty($hero['subtitle']) ? $hero['subtitle'] : $product->get_short_description();
$image     = $hero['image'] ?? '';
$video     = $hero['video'] ?? '';

$price_display = '';
if (!empty($finance['price_range'])) {
    $price_display = $finance['price_range'];
} else {
    $price_display = $product->get_price_html();
}

$configurator_url = '/configurator/' . $product->get_slug() . '/';
?>

<section id="machine-hero" class="relative min-h-[70vh] flex items-end overflow-hidden bg-slate-800" aria-labelledby="machine-hero-title">
    <?php if (!empty($image)) : ?>
        <img src="<?php echo esc_url($image); ?>"
             alt="<?php echo esc_attr($headline); ?>"
             class="absolute inset-0 w-full h-full object-cover">
    <?php endif; ?>

    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/40 to-transparent"></div>

    <div class="container relative z-10 pb-16 pt-32">
        <p class="absolute top-8 right-8 text-xs font-mono uppercase tracking-widest text-white/60 hidden lg:block"><?php echo esc_html($product->get_name()); ?></p>
        <div class="grid gap-6 max-w-xl">
            <h1 id="machine-hero-title" class="text-4xl font-bold font-mono text-white md:text-5xl lg:text-6xl">
                <?php echo esc_html($headline); ?>
            </h1>
            <?php if (!empty($subtitle)) : ?>
                <p class="text-lg text-slate-200 md:text-xl"><?php echo wp_kses_post($subtitle); ?></p>
            <?php endif; ?>
            <?php if (!empty($price_display)) : ?>
                <p class="text-sm text-slate-300 uppercase tracking-wider">Starting at <span class="text-white font-semibold"><?php echo wp_kses_post($price_display); ?></span></p>
            <?php endif; ?>
            <div class="flex gap-4 mt-2">
                <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-primary">Build &amp; Quote</a>
                <a href="#machine-breakdown" class="btn btn-outline-light">Explore</a>
            </div>
        </div>
    </div>
</section>
