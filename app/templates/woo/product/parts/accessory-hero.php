<?php
/**
 * Accessory Product — Hero Section
 *
 * Standard product layout: image left, summary right.
 * Uses WooCommerce product data only — no custom data files.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;

if (!$product) {
    return;
}

$image_id    = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$sku         = $product->get_sku();
$price_html  = $product->get_price_html();
$excerpt     = $product->get_short_description();
$slug        = $product->get_slug();
?>

<section class="border-b border-blue-200" aria-labelledby="accessory-title">
    <div class="container pt-6 lg:pt-12 pb-6 lg:pb-12">
        <div class="grid md:grid-cols-2 gap-8 lg:gap-16 items-start">

            <!-- Product Image -->
            <div class="accessory-gallery">
                <div class="bg-blue-50 overflow-hidden aspect-square flex items-center justify-center p-8">
                    <?php if ($image_id) : ?>
                        <?php echo wp_get_attachment_image($image_id, 'large', false, [
                            'class'         => 'w-full h-full object-contain',
                            'id'            => 'accessory-main-image',
                            'fetchpriority' => 'high',
                        ]); ?>
                    <?php else : ?>
                        <div class="text-center grid gap-4">
                            <svg class="w-16 h-16 text-blue-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0022.5 18.75V5.25A2.25 2.25 0 0020.25 3H3.75A2.25 2.25 0 001.5 5.25v13.5A2.25 2.25 0 003.75 21z" />
                            </svg>
                            <span class="text-blue-400 text-sm font-mono"><?php esc_html_e('No image', 'standard'); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($gallery_ids)) : ?>
                    <div class="flex gap-2 mt-3 overflow-x-auto">
                        <?php if ($image_id) : ?>
                            <button
                                type="button"
                                class="accessory-gallery__thumb accessory-gallery__thumb--active shrink-0 w-16 h-16 border-2 border-blue-500 overflow-hidden bg-blue-50 cursor-pointer"
                                data-image-url="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'large')); ?>"
                            >
                                <?php echo wp_get_attachment_image($image_id, 'thumbnail', false, ['class' => 'w-full h-full object-contain']); ?>
                            </button>
                        <?php endif; ?>
                        <?php foreach ($gallery_ids as $gid) : ?>
                            <button
                                type="button"
                                class="accessory-gallery__thumb shrink-0 w-16 h-16 border-2 border-blue-200 overflow-hidden bg-blue-50 cursor-pointer hover:border-blue-400 transition-colors"
                                data-image-url="<?php echo esc_url(wp_get_attachment_image_url($gid, 'large')); ?>"
                            >
                                <?php echo wp_get_attachment_image($gid, 'thumbnail', false, ['class' => 'w-full h-full object-contain']); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Summary -->
            <div class="grid gap-5 content-start">
                <div>
                    <p class="section-eyebrow mb-2"><?php esc_html_e('Accessories', 'standard'); ?></p>
                    <h1 id="accessory-title" class="text-2xl font-semibold tracking-tight text-blue-900 md:text-3xl lg:text-4xl">
                        <?php echo esc_html($product->get_name()); ?>
                    </h1>
                </div>

                <?php if (!empty($price_html)) : ?>
                    <p class="text-xl font-medium text-blue-900"><?php echo wp_kses_post($price_html); ?></p>
                <?php endif; ?>

                <?php if (!empty($excerpt)) : ?>
                    <div class="text-base text-blue-600 leading-relaxed prose prose-sm max-w-none">
                        <?php echo wp_kses_post(wpautop($excerpt)); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($sku)) : ?>
                    <p class="text-xs text-blue-400 uppercase tracking-wider font-mono">
                        <?php esc_html_e('SKU:', 'standard'); ?> <?php echo esc_html($sku); ?>
                    </p>
                <?php endif; ?>

                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="<?php echo esc_url(\Standard\Url\with_query('/contact/', ['product' => $slug])); ?>" class="btn btn-primary">
                        <?php esc_html_e('Request a Quote', 'standard'); ?>
                    </a>
                    <a href="tel:+13032943553" class="btn btn-outline-dark">
                        <?php icon('phone', ['class' => 'w-4 h-4']); ?>
                        <?php esc_html_e('Call Us', 'standard'); ?>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<?php if (!empty($gallery_ids)) : ?>
<script>
/** Accessory gallery thumbnail switcher */
(function() {
    var thumbs = document.querySelectorAll('.accessory-gallery__thumb');
    var main = document.getElementById('accessory-main-image');
    if (!main || !thumbs.length) return;
    thumbs.forEach(function(btn) {
        btn.addEventListener('click', function() {
            main.src = btn.getAttribute('data-image-url');
            thumbs.forEach(function(t) {
                t.classList.remove('accessory-gallery__thumb--active', 'border-blue-500');
                t.classList.add('border-blue-200');
            });
            btn.classList.add('accessory-gallery__thumb--active', 'border-blue-500');
            btn.classList.remove('border-blue-200');
        });
    });
})();
</script>
<?php endif; ?>
