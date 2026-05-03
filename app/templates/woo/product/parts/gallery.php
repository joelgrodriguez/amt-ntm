<?php
/**
 * Machine Product — Gallery / Product Rotator
 *
 * 360° rotator section. Always renders with placeholder so the
 * layout spot is visible even before images are provided.
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
$gallery = $machine['gallery'] ?? [];
$images  = $gallery['images'] ?? [];
$rotator = $gallery['rotator'] ?? [];
$name    = $product ? $product->get_name() : '';

// Use WC featured image as primary, fall back to product render from data file
$featured_url = '';
if ($product && $product->get_image_id()) {
    $featured_url = wp_get_attachment_image_url($product->get_image_id(), 'full');
}
if (empty($featured_url)) {
    $featured_url = $machine['hero']['image'] ?? '';
}
?>

<section id="machine-gallery" class="section bg-blue-100 pattern-square-grid" aria-labelledby="gallery-title">
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--top-left" aria-hidden="true"></div>
    <div class="pattern-square-grid__overlay pattern-square-grid__overlay--bottom-right" aria-hidden="true"></div>
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('360° View', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="gallery-title" class="section-title"><?php esc_html_e('See Every Angle', 'standard'); ?></h2>
        </div>

        <!-- Main rotator area -->
        <div class="max-w-md mx-auto">
            <?php if (!empty($rotator)) : ?>
                <?php \Standard\Images\responsive_image($rotator[0], $name . ' - 360 view', 'full', [
                    'class' => 'max-w-full max-h-full object-contain',
                ]); ?>
            <?php elseif (!empty($featured_url)) : ?>
                <?php \Standard\Images\responsive_image($featured_url, $name, 'large', [
                    'class' => 'max-w-full max-h-full object-contain',
                ]); ?>
            <?php elseif (!empty($images[0])) : ?>
                <?php \Standard\Images\responsive_image($images[0], $name, 'large', [
                    'class' => 'max-w-full max-h-full object-contain',
                ]); ?>
            <?php else : ?>
                <div class="text-center grid gap-3">
                    <span class="text-blue-300 text-6xl">&#8635;</span>
                    <span class="text-blue-400 text-sm font-mono"><?php esc_html_e('360° product rotator', 'standard'); ?></span>
                    <span class="text-blue-400 text-xs"><?php esc_html_e('Interactive view coming soon', 'standard'); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <p class="text-center text-sm text-blue-400 flex items-center justify-center gap-2">
            <span>&larr;</span>
            <?php esc_html_e('Drag to Rotate', 'standard'); ?>
            <span>&rarr;</span>
        </p>

        <!-- Thumbnail strip -->
        <?php if (!empty($images)) : ?>
            <div class="flex justify-center gap-3 max-w-5xl mx-auto">
                <?php foreach (array_slice($images, 0, 6) as $i => $thumb) : ?>
                    <div class="w-20 h-20 flex items-center justify-center overflow-hidden cursor-pointer hover:opacity-75 transition-opacity">
                        <?php \Standard\Images\responsive_image($thumb, $name . ' - angle ' . ($i + 1), 'thumbnail', [
                            'class' => 'w-full h-full object-contain',
                        ]); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
