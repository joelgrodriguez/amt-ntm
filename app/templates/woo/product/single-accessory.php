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

    <?php $related_cards = \Standard\Woo\Accessories\get_related_accessory_cards($product, 4); ?>

    <?php if ($related_cards !== []) : ?>
    <section class="section" aria-labelledby="related-accessories-title">
        <div class="container section-content">
            <div class="section-header-left mb-10">
                <p class="section-eyebrow"><?php esc_html_e('Related', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="related-accessories-title" class="section-title"><?php esc_html_e('More Accessories', 'standard'); ?></h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach ($related_cards as $card) : ?>
                    <?php get_template_part('templates/woo/product/parts/product-card-link', null, compact('card')); ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="bg-blue-950 text-white py-12 lg:py-16">
        <div class="container text-center grid gap-4">
            <p class="text-sm font-mono uppercase tracking-wider text-blue-400"><?php esc_html_e('Need help choosing the right accessories?', 'standard'); ?></p>
            <h2 class="text-2xl font-medium font-mono md:text-3xl"><?php esc_html_e('Talk to a Specialist', 'standard'); ?></h2>
            <p class="text-blue-400 max-w-xl mx-auto"><?php esc_html_e('Our team can help you find the right setup for your machine and jobsite.', 'standard'); ?></p>
            <div class="flex justify-center gap-4 mt-2">
                <a href="/contact/" class="btn btn-primary"><?php esc_html_e('Contact Us', 'standard'); ?></a>
                <a href="tel:+13032943553" class="btn btn-outline-light">
                    <?php icon('phone', ['class' => 'w-4 h-4']); ?>
                    <?php esc_html_e('(303) 294-3553', 'standard'); ?>
                </a>
            </div>
        </div>
    </section>

</main>

<?php
// Generate WooCommerce structured data (JSON-LD).
if (method_exists(WC()->structured_data, 'generate_product_data')) {
    WC()->structured_data->generate_product_data();
}
do_action('woocommerce_after_single_product');

get_footer();
