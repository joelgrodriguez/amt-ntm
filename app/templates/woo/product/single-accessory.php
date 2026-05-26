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
                    <?php get_template_part('templates/parts/card-accessory', null, [
                        'card'    => $card,
                        'context' => 'carousel',
                    ]); ?>
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
if (method_exists(WC()->structured_data, 'generate_product_data')) {
    WC()->structured_data->generate_product_data();
}
do_action('woocommerce_after_single_product');

get_footer();
