<?php
/**
 * Single Machine — Default Template
 *
 * Used for machine products without a dedicated landing page data file
 * in app/data/machines/. Lighter scroll layout: gallery + summary,
 * accordion section for features and specs, accessories grid, profiles
 * grid, closing CTA. Brand voice via existing component vocabulary;
 * tabs intentionally avoided to stay consistent with single-machine.php.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/** @var \WC_Product|false $product */
$product = wc_get_product(get_the_ID());

if (!$product) {
    return;
}

// Resolve product video. Current ACF schema groups video fields under
// 'product_media'; legacy top-level 'product_video' still exists on some
// products as a fallback. (The top-level 'video' field is a separate
// CPT-scoped field and does not apply to products.)
$video_url   = null;
$video_title = null;
$video_sub   = null;
if (function_exists('get_field')) {
    $media = get_field('product_media', $product->get_id());
    if (is_array($media)) {
        $video_url   = $media['product_video'] ?? null;
        $video_title = $media['product_video_title'] ?? null;
        $video_sub   = $media['product_video_sub'] ?? null;
    }
    if (!is_string($video_url) || $video_url === '') {
        $legacy = get_field('product_video', $product->get_id());
        if (is_string($legacy) && $legacy !== '') {
            $video_url = $legacy;
        }
    }
}

// Demote Add-to-Cart and the surrounding stock summary actions — NTM
// machine sales are quote / dealer driven, not e-commerce. Keep title,
// price, excerpt; drop the cart button, sharing, and the default meta
// row. Scoped to this render only.
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

get_header();
?>

<main id="primary" class="machine-default">

    <?php do_action('woocommerce_before_single_product'); ?>

    <section class="section machine-default__fold" aria-labelledby="machine-default-title">
        <div class="container section-content">
            <div class="grid lg:grid-cols-2 gap-10 lg:gap-16 items-start">

                <div class="machine-default__gallery">
                    <?php
                    // Render gallery directly. Woo's default gallery action depends on
                    // a flexslider/zoom/photoswipe stack the theme doesn't opt into;
                    // without that JS, Woo's markup degrades to stacked full-size
                    // thumbs. Build our own grid from the gallery IDs instead.
                    $main_id = $product->get_image_id();
                    $gallery_ids = array_filter(array_map('intval', $product->get_gallery_image_ids()));
                    ?>
                    <?php if ($main_id) : ?>
                        <figure class="machine-default__gallery-main">
                            <?php echo wp_get_attachment_image($main_id, 'large', false, [
                                'class' => 'w-full h-auto',
                                'alt'   => $product->get_name(),
                            ]); ?>
                        </figure>
                    <?php endif; ?>
                    <?php if (!empty($gallery_ids)) :
                        $thumbs_id = 'machine-default-thumbs-' . $product->get_id();
                    ?>
                        <div class="machine-default__thumbs-row">
                            <div id="<?php echo esc_attr($thumbs_id); ?>" class="machine-default__thumbs carousel__track">
                                <?php foreach ($gallery_ids as $gid) :
                                    $full = wp_get_attachment_image_url($gid, 'large');
                                    if (!$full) continue;
                                ?>
                                    <a href="<?php echo esc_url($full); ?>" target="_blank" rel="noopener" class="machine-default__thumb">
                                        <?php echo wp_get_attachment_image($gid, 'medium', false, [
                                            'class' => 'w-full h-full object-contain',
                                            'alt'   => $product->get_name(),
                                            'loading' => 'lazy',
                                        ]); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <?php if (count($gallery_ids) > 4) : ?>
                                <div class="machine-default__thumbs-nav">
                                    <button type="button"
                                            data-carousel-prev="<?php echo esc_attr($thumbs_id); ?>"
                                            class="carousel__nav"
                                            aria-label="<?php esc_attr_e('Previous image', 'standard'); ?>">
                                        <?php icon('arrow-left', ['class' => 'w-4 h-4 text-blue-700']); ?>
                                    </button>
                                    <button type="button"
                                            data-carousel-next="<?php echo esc_attr($thumbs_id); ?>"
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
                    <p class="section-eyebrow mb-2"><?php
                        $categories = wp_get_post_terms($product->get_id(), 'product_cat', ['fields' => 'names']);
                        echo esc_html(is_array($categories) && !empty($categories) ? $categories[0] : __('Machine', 'standard'));
                    ?></p>

                    <h1 id="machine-default-title" class="machine-default__title">
                        <?php echo esc_html($product->get_name()); ?>
                    </h1>

                    <?php $short = $product->get_short_description(); ?>
                    <?php if ($short) : ?>
                        <div class="machine-default__excerpt prose prose-blue max-w-none">
                            <?php echo wp_kses_post(wpautop($short)); ?>
                        </div>
                    <?php endif; ?>

                    <div class="machine-default__actions">
                        <a href="<?php echo esc_url(\Standard\Url\internal('/configurator/' . $product->get_slug() . '/')); ?>" class="btn btn-primary">
                            <?php esc_html_e('Build & Quote', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                        </a>
                        <a href="<?php echo esc_url(\Standard\Url\internal('/contact/')); ?>" class="btn btn-secondary">
                            <?php esc_html_e('Talk to a Specialist', 'standard'); ?>
                        </a>
                    </div>

                    <?php
                    $sku = $product->get_sku();
                    $tags = wp_get_post_terms($product->get_id(), 'product_tag', ['fields' => 'names']);
                    ?>
                    <?php if ($sku || (is_array($tags) && !empty($tags))) : ?>
                        <dl class="machine-default__meta">
                            <?php if ($sku) : ?>
                                <div>
                                    <dt><?php esc_html_e('Model', 'standard'); ?></dt>
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

    <?php get_template_part('templates/woo/product/parts/default-specs', null, compact('product')); ?>

    <?php
    get_template_part('templates/parts/video-section', null, [
        'title'      => is_string($video_title) && $video_title !== '' ? $video_title : $product->get_name(),
        'video_url'  => is_string($video_url) ? $video_url : null,
        'video_type' => is_string($video_sub) && $video_sub !== '' ? $video_sub : __('Product Video', 'standard'),
        'section_id' => 'machine-default-video',
    ]);
    ?>

    <?php get_template_part('templates/woo/product/parts/default-accessories', null, compact('product')); ?>

    <?php get_template_part('templates/woo/product/parts/default-profiles', null, compact('product')); ?>

    <?php get_template_part('templates/parts/cta/closer', null, [
        'title'           => sprintf(__('Configure your %s', 'standard'), $product->get_name()),
        'text'            => __('Pick a configuration, get pricing, and route a specialist to your build. One flow.', 'standard'),
        'cta_primary'     => __('Build & Quote', 'standard'),
        'cta_primary_url' => '/configurator/' . $product->get_slug() . '/',
        'section_id'      => 'machine-default-closer-title',
    ]); ?>

</main>

<?php
if (method_exists(WC()->structured_data, 'generate_product_data')) {
    WC()->structured_data->generate_product_data();
}
do_action('woocommerce_after_single_product');

get_footer();
