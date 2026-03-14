<?php
/**
 * Machine Product — Gallery / Product Rotator
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine = $args['machine'] ?? [];
$gallery = $machine['gallery'] ?? [];
$images  = $gallery['images'] ?? [];
$rotator = $gallery['rotator'] ?? [];

if (empty($images) && empty($rotator)) {
    return;
}
?>

<section class="section" aria-labelledby="gallery-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">360&deg; View</p>
            <h2 id="gallery-title" class="section-title">See Every Angle</h2>
        </div>

        <div class="bg-slate-100 aspect-video max-w-4xl mx-auto flex items-center justify-center overflow-hidden">
            <?php if (!empty($images[0])) : ?>
                <img src="<?php echo esc_url($images[0]); ?>"
                     alt="Gallery main"
                     class="w-full h-full object-cover">
            <?php else : ?>
                <span class="text-slate-400 text-sm font-mono">Product gallery</span>
            <?php endif; ?>
        </div>

        <?php
        $thumbnails = array_slice($images, 0, 5);
        if (!empty($thumbnails)) : ?>
            <div class="flex justify-center gap-3 max-w-4xl mx-auto">
                <?php foreach ($thumbnails as $i => $thumb) : ?>
                    <div class="w-16 h-16 bg-slate-200 flex items-center justify-center overflow-hidden">
                        <img src="<?php echo esc_url($thumb); ?>"
                             alt="<?php echo esc_attr('Thumbnail ' . ($i + 1)); ?>"
                             class="w-full h-full object-cover">
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</section>
