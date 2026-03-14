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

$product = $args['product'] ?? null;
$machine = $args['machine'] ?? [];
$gallery = $machine['gallery'] ?? [];
$images  = $gallery['images'] ?? [];
$rotator = $gallery['rotator'] ?? [];
$name    = $product ? $product->get_name() : '';
?>

<section class="section bg-slate-50" aria-labelledby="gallery-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('360° View', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="gallery-title" class="section-title"><?php esc_html_e('See Every Angle', 'standard'); ?></h2>
        </div>

        <!-- Main rotator area -->
        <div class="bg-white border border-slate-200 aspect-[16/9] max-w-5xl mx-auto flex items-center justify-center overflow-hidden rounded">
            <?php if (!empty($rotator)) : ?>
                <!-- TODO: Wire up 360° rotator with frame sequence -->
                <img src="<?php echo esc_url($rotator[0]); ?>"
                     alt="<?php echo esc_attr($name . ' — 360° view'); ?>"
                     class="w-full h-full object-contain p-8">
            <?php elseif (!empty($images[0])) : ?>
                <img src="<?php echo esc_url($images[0]); ?>"
                     alt="<?php echo esc_attr($name); ?>"
                     class="w-full h-full object-contain p-8">
            <?php else : ?>
                <div class="text-center grid gap-3">
                    <span class="text-slate-300 text-6xl">&#8635;</span>
                    <span class="text-slate-400 text-sm font-mono"><?php esc_html_e('360° product rotator', 'standard'); ?></span>
                    <span class="text-slate-400 text-xs"><?php esc_html_e('Interactive view coming soon', 'standard'); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <!-- Thumbnail strip -->
        <?php if (!empty($images)) : ?>
            <div class="flex justify-center gap-3 max-w-5xl mx-auto">
                <?php foreach (array_slice($images, 0, 6) as $i => $thumb) : ?>
                    <div class="w-20 h-20 bg-white border border-slate-200 flex items-center justify-center overflow-hidden rounded cursor-pointer hover:border-slate-400 transition-colors">
                        <img src="<?php echo esc_url($thumb); ?>"
                             alt="<?php echo esc_attr($name . ' — angle ' . ($i + 1)); ?>"
                             class="w-full h-full object-contain p-1">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
