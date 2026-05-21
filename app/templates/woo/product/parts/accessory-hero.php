<?php
/**
 * Accessory Product — Hero (Fold)
 *
 * Mirrors the default-machine fold for visual parity: two-column
 * gallery + summary, eyebrow → title → excerpt → CTAs → meta. The
 * machine-default__* classes are intentionally reused; they describe a
 * standard product fold regardless of category.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;

if (!$product instanceof \WC_Product) {
    return;
}

$main_id     = $product->get_image_id();
$gallery_ids = array_filter(array_map('intval', $product->get_gallery_image_ids()));
$sku         = $product->get_sku();
$excerpt     = $product->get_short_description();
$price_html  = $product->get_price_html();
?>

<section class="section machine-default__fold" aria-labelledby="accessory-title">
    <div class="container section-content">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">

            <div class="machine-default__gallery">
                <?php
                $all_ids = [];
                if ($main_id) {
                    $all_ids[] = $main_id;
                }
                foreach ($gallery_ids as $gid) {
                    if (!in_array($gid, $all_ids, true)) {
                        $all_ids[] = $gid;
                    }
                }
                $gallery_carousel_id = 'accessory-gallery-' . $product->get_id();
                $has_multiple = count($all_ids) >= 2;
                ?>
                <?php if (!empty($all_ids)) : ?>
                    <div class="machine-default__gallery-frame">
                        <div id="<?php echo esc_attr($gallery_carousel_id); ?>" class="machine-default__gallery-track">
                            <?php foreach ($all_ids as $i => $gid) : ?>
                                <figure class="machine-default__gallery-slide">
                                    <?php echo wp_get_attachment_image($gid, 'large', false, [
                                        'class'   => 'w-full h-full object-contain',
                                        'alt'     => $product->get_name(),
                                        'loading' => $i === 0 ? 'eager' : 'lazy',
                                        'fetchpriority' => $i === 0 ? 'high' : null,
                                    ]); ?>
                                </figure>
                            <?php endforeach; ?>
                        </div>
                        <?php if ($has_multiple) : ?>
                            <div class="machine-default__gallery-nav">
                                <button type="button"
                                        data-carousel-prev="<?php echo esc_attr($gallery_carousel_id); ?>"
                                        class="carousel__nav"
                                        aria-label="<?php esc_attr_e('Previous image', 'standard'); ?>">
                                    <?php icon('arrow-left', ['class' => 'w-4 h-4 text-blue-700']); ?>
                                </button>
                                <button type="button"
                                        data-carousel-next="<?php echo esc_attr($gallery_carousel_id); ?>"
                                        class="carousel__nav"
                                        aria-label="<?php esc_attr_e('Next image', 'standard'); ?>">
                                    <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-700']); ?>
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="machine-default__summary">
                <p class="section-eyebrow mb-2"><?php esc_html_e('Accessory', 'standard'); ?></p>

                <h1 id="accessory-title" class="machine-default__title">
                    <?php echo esc_html($product->get_name()); ?>
                </h1>

                <?php if (!empty($price_html)) : ?>
                    <p class="machine-default__excerpt text-blue-900 font-medium" style="font-size: var(--text-heading-sm);">
                        <?php echo wp_kses_post($price_html); ?>
                    </p>
                <?php endif; ?>

                <?php if (!empty($excerpt)) : ?>
                    <div class="machine-default__excerpt prose prose-blue max-w-none">
                        <?php echo wp_kses_post(wpautop($excerpt)); ?>
                    </div>
                <?php endif; ?>

                <div class="machine-default__actions">
                    <a href="<?php echo esc_url(\Standard\Url\with_query('/contact/', ['product' => $product->get_slug()])); ?>" class="btn btn-primary">
                        <?php esc_html_e('Request a Quote', 'standard'); ?>
                        <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                    </a>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-secondary">
                        <?php esc_html_e('Talk to a Specialist', 'standard'); ?>
                    </a>
                </div>

                <?php if ($sku) : ?>
                    <dl class="machine-default__meta">
                        <div>
                            <dt><?php esc_html_e('SKU', 'standard'); ?></dt>
                            <dd><?php echo esc_html($sku); ?></dd>
                        </div>
                    </dl>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
