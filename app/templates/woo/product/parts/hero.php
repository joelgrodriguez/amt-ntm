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

$hero    = $machine['hero'] ?? [];
$finance = $machine['finance'] ?? [];

$headline = !empty($hero['headline']) ? $hero['headline'] : $product->get_name();
$subtitle = !empty($hero['subtitle']) ? $hero['subtitle'] : $product->get_short_description();
$image    = $hero['hero_image'] ?? $hero['image'] ?? '';

$price_display = !empty($finance['price_range']) ? $finance['price_range'] : $product->get_price_html();
$machine_name  = $product->get_name();
?>

<section id="machine-hero" class="hero" aria-labelledby="machine-hero-title">
    <div class="hero__photo">
        <?php if (!empty($image)) : ?>
            <?php \Standard\Images\responsive_image($image, $headline, 'full', [
                'class'         => 'hero__media',
                'loading'       => 'eager',
                'fetchpriority' => 'high',
            ]); ?>
        <?php endif; ?>

        <div class="hero-overlay"></div>
        <div class="hero-overlay__grain"></div>

        <p class="hero__watermark hero__watermark--top-left"><?php echo esc_html($machine_name); ?></p>

        <div class="hero__content">
            <div class="container hero__content-inner">
                <h1 id="machine-hero-title" class="hero__title">
                    <?php echo esc_html($headline); ?>
                </h1>
                <?php if (!empty($subtitle)) : ?>
                    <p class="hero__slogan"><?php echo wp_kses_post($subtitle); ?></p>
                <?php endif; ?>
                <?php if (!empty($price_display)) : ?>
                    <p class="hero__meta">
                        <?php esc_html_e('Starting at', 'standard'); ?>
                        <span class="hero__meta-value"><?php echo wp_kses_post($price_display); ?></span>
                    </p>
                <?php endif; ?>
                <div class="hero__cta">
                    <a href="#machine-breakdown" class="btn btn-primary">
                        <?php esc_html_e('Explore', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
