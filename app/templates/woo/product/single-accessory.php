<?php
/**
 * Single Accessory Product.
 *
 * Traditional WooCommerce layout (image left, summary right, tabs,
 * related) wired through native Woo hooks so plugins keep working.
 * Branded via inc/woo/accessories.php and woo/accessory-product.css.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="accessory-product">

    <?php do_action('woocommerce_before_main_content'); ?>

    <?php while (have_posts()) : the_post(); ?>
        <?php global $product; ?>
        <?php $product = wc_get_product(get_the_ID()); ?>

        <?php if ($product instanceof \WC_Product) : ?>
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class('accessory-product__article', $product); ?>>

                <?php do_action('woocommerce_before_single_product'); ?>

                <section class="section pattern-dot-grid gradient-fade-bottom">
                    <div class="container">
                        <div class="grid md:grid-cols-2 gap-8 lg:gap-16 items-start">

                            <div class="accessory-product__images">
                                <?php
                                /**
                                 * Native Woo gallery (flexslider + zoom + lightbox).
                                 */
                                do_action('woocommerce_before_single_product_summary');
                                ?>
                            </div>

                            <div class="accessory-product__summary entry-summary grid gap-5 content-start">
                                <?php
                                /**
                                 * Native Woo summary stack. Hook surgery in
                                 * inc/woo/accessories.php swaps add-to-cart for
                                 * the quote CTA and adds the eyebrow.
                                 */
                                do_action('woocommerce_single_product_summary');
                                ?>
                            </div>

                        </div>
                    </div>
                </section>

                <section class="section accessory-product__tabs-section bg-blue-50">
                    <div class="container">
                        <?php
                        /**
                         * Native Woo tabs (Description / Additional Information /
                         * Reviews). Filter via `woocommerce_product_tabs`.
                         */
                        if (function_exists('woocommerce_output_product_data_tabs')) {
                            woocommerce_output_product_data_tabs();
                        }
                        ?>
                    </div>
                </section>

                <section class="section accessory-product__related-section">
                    <div class="container">
                        <?php
                        /**
                         * Native Woo related products. Args filtered in
                         * inc/woo/accessories.php (4 cols, 4 posts).
                         */
                        if (function_exists('woocommerce_output_related_products')) {
                            woocommerce_output_related_products();
                        }
                        ?>
                    </div>
                </section>

                <?php do_action('woocommerce_after_single_product'); ?>

            </div>
        <?php endif; ?>
    <?php endwhile; ?>

    <?php do_action('woocommerce_after_main_content'); ?>

</main>

<?php
get_footer();
