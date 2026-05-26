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
                    $main_id = $product->get_image_id();
                    $gallery_ids = array_filter(array_map('intval', $product->get_gallery_image_ids()));
                    $all_ids = [];
                    if ($main_id) {
                        $all_ids[] = $main_id;
                    }
                    foreach ($gallery_ids as $gid) {
                        if (!in_array($gid, $all_ids, true)) {
                            $all_ids[] = $gid;
                        }
                    }
                    $gallery_carousel_id = 'machine-default-gallery-' . $product->get_id();
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

                    <?php $sku = $product->get_sku(); ?>
                    <?php if ($sku) : ?>
                        <dl class="machine-default__meta">
                            <div>
                                <dt><?php esc_html_e('Model', 'standard'); ?></dt>
                                <dd><?php echo esc_html($sku); ?></dd>
                            </div>
                        </dl>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </section>

    <?php get_template_part('templates/woo/product/parts/default-specs', null, compact('product')); ?>

    <?php
    $video_sku = $product->get_sku();
    get_template_part('templates/parts/video-section', null, [
        'title'            => $video_sku !== '' ? $video_sku : $product->get_name(),
        'video_url'        => is_string($video_url) ? $video_url : null,
        'video_type'       => __('Video', 'standard'),
        'bottom_left_icon' => 'play',
        'section_id'       => 'machine-default-video',
    ]);
    ?>

    <?php get_template_part('templates/woo/product/parts/default-accessories', null, compact('product')); ?>

    <?php get_template_part('templates/woo/product/parts/default-profiles', null, compact('product')); ?>

    <?php get_template_part('templates/parts/configurator-cta', null, compact('product')); ?>

    <?php get_template_part('templates/parts/cta/closer', null, [
        'title'           => __('Built for contractors. Backed by NTM.', 'standard'),
        'text'            => __('Limited three-year part and in-house labor warranty. Service and training out of Aurora, Colorado. Machines shipped to 40+ countries since 1991.', 'standard'),
        'cta_primary'     => __('Talk to a Specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'machine-default-closer-title',
    ]); ?>

</main>

<?php
if (method_exists(WC()->structured_data, 'generate_product_data')) {
    WC()->structured_data->generate_product_data();
}
do_action('woocommerce_after_single_product');

get_footer();
