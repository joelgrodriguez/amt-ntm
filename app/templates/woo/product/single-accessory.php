<?php
/**
 * Single Accessory Product — Branded Product Page
 *
 * Custom template for accessory products (trailers, motors, add-ons).
 * Standard shop layout with theme branding, quote CTA, and
 * compatible machines reverse lookup.
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

get_header();
?>

<main id="primary" class="accessory-product">

    <?php do_action('woocommerce_before_single_product'); ?>

    <?php get_template_part('templates/woo/product/parts/accessory-hero', null, compact('product')); ?>

    <?php get_template_part('templates/woo/product/parts/accessory-tabs', null, compact('product')); ?>

    <?php get_template_part('templates/woo/product/parts/compatible-machines', null, compact('product')); ?>

    <?php $related_cards = \Standard\Woo\Accessories\get_related_accessory_cards($product, 8); ?>

    <?php if ($related_cards !== []) :
        $related_carousel_id = 'related-accessories-' . $product->get_id();
    ?>
    <section class="section" aria-labelledby="related-accessories-title">
        <div class="container section-content">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
                <div>
                    <p class="section-eyebrow mb-2"><?php esc_html_e('Related', 'standard'); ?></p>
                    <h2 id="related-accessories-title" class="section-title"><?php esc_html_e('More accessories', 'standard'); ?></h2>
                </div>
                <div class="flex gap-2 shrink-0 self-end md:self-auto">
                    <button type="button"
                            data-carousel-prev="<?php echo esc_attr($related_carousel_id); ?>"
                            class="carousel__nav"
                            aria-label="<?php esc_attr_e('Previous accessories', 'standard'); ?>">
                        <?php icon('arrow-left', ['class' => 'w-4 h-4 text-blue-700']); ?>
                    </button>
                    <button type="button"
                            data-carousel-next="<?php echo esc_attr($related_carousel_id); ?>"
                            class="carousel__nav"
                            aria-label="<?php esc_attr_e('Next accessories', 'standard'); ?>">
                        <?php icon('arrow-right', ['class' => 'w-4 h-4 text-blue-700']); ?>
                    </button>
                </div>
            </div>

            <div id="<?php echo esc_attr($related_carousel_id); ?>" class="carousel__track">
                <?php foreach ($related_cards as $card) : ?>
                    <a href="<?php echo esc_url($card['url']); ?>" class="carousel__card group">
                        <div class="carousel__card-image">
                            <?php if (!empty($card['image_id'])) : ?>
                                <?php echo wp_get_attachment_image((int) $card['image_id'], 'product-card', false, [
                                    'class' => 'w-full h-full object-contain p-3 transition-transform',
                                    'alt'   => $card['title'],
                                ]); ?>
                            <?php else : ?>
                                <span class="text-blue-400 text-sm font-mono"><?php echo esc_html($card['title']); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="grid gap-1">
                            <h3 class="text-sm font-medium text-blue-900 group-hover:text-blue-500 transition-colors leading-tight">
                                <?php echo esc_html($card['title']); ?>
                            </h3>
                            <?php if (!empty($card['subtitle'])) : ?>
                                <p class="text-xs text-blue-500"><?php echo wp_kses_post($card['subtitle']); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        </div>
    </section>
    <?php endif; ?>

    <?php get_template_part('templates/parts/cta/closer', null, [
        'title'           => sprintf(__('Questions about the %s?', 'standard'), $product->get_name()),
        'text'            => __('Get part numbers, compatibility, or pricing. One conversation gets you set up.', 'standard'),
        'cta_primary'     => __('Talk to a Specialist', 'standard'),
        'cta_primary_url' => '/contact/',
        'section_id'      => 'accessory-closer-title',
    ]); ?>

</main>

<?php
// Generate WooCommerce structured data (JSON-LD).
if (method_exists(WC()->structured_data, 'generate_product_data')) {
    WC()->structured_data->generate_product_data();
}
do_action('woocommerce_after_single_product');

get_footer();
