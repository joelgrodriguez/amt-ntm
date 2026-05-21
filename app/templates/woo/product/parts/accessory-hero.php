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
$tags        = wp_get_post_terms($product->get_id(), 'product_tag', ['fields' => 'names']);
?>

<section class="section machine-default__fold" aria-labelledby="accessory-title">
    <div class="container section-content">
        <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">

            <div class="machine-default__gallery">
                <?php if ($main_id) : ?>
                    <figure class="machine-default__gallery-main">
                        <?php echo wp_get_attachment_image($main_id, 'large', false, [
                            'class'         => 'w-full h-auto',
                            'alt'           => $product->get_name(),
                            'fetchpriority' => 'high',
                        ]); ?>
                    </figure>
                <?php endif; ?>
                <?php if (!empty($gallery_ids)) : ?>
                    <ul class="machine-default__gallery-thumbs">
                        <?php foreach ($gallery_ids as $gid) :
                            $full = wp_get_attachment_image_url($gid, 'large');
                            if (!$full) continue;
                        ?>
                            <li>
                                <a href="<?php echo esc_url($full); ?>" target="_blank" rel="noopener">
                                    <?php echo wp_get_attachment_image($gid, 'medium', false, [
                                        'class' => 'w-full h-auto',
                                        'alt'   => $product->get_name(),
                                        'loading' => 'lazy',
                                    ]); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
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

                <?php if ($sku || (is_array($tags) && !empty($tags))) : ?>
                    <dl class="machine-default__meta">
                        <?php if ($sku) : ?>
                            <div>
                                <dt><?php esc_html_e('SKU', 'standard'); ?></dt>
                                <dd><?php echo esc_html($sku); ?></dd>
                            </div>
                        <?php endif; ?>
                        <?php if (is_array($tags) && !empty($tags)) : ?>
                            <div>
                                <dt><?php esc_html_e('Tags', 'standard'); ?></dt>
                                <dd><?php echo esc_html(implode(', ', $tags)); ?></dd>
                            </div>
                        <?php endif; ?>
                    </dl>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
