<?php
/**
 * Single Machine Product — Landing Page Template
 *
 * Custom template for machine product pages (roof/wall panel, gutter).
 * Loaded via template_include filter in inc/machine-product-template.php.
 * Accessories use the default WooCommerce single product template.
 *
 * @package Standard
 */

declare(strict_types=1);

use function Standard\MachineProductData\get_machine_product_data;

get_header();

/** @var \WC_Product $product */
$product = wc_get_product(get_the_ID());
$machine = $product ? get_machine_product_data($product->get_slug()) : null;

// Fallback to default WooCommerce if no machine data exists
if (!$machine) {
    while (have_posts()) {
        the_post();
        wc_get_template_part('content', 'single-product');
    }
    get_footer();
    return;
}
?>

<main id="primary" class="machine-product">

    <?php get_template_part('templates/pages/product/hero', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/stats-bar', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/machine-breakdown', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/blueprint', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/gallery', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/profile-selector', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/social-proof', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/comparison', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/accessories', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/specs-accordion', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/resources', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/final-cta', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/sticky-cta', null, compact('product', 'machine')); ?>

</main>

<?php
// Preserve WooCommerce structured data (JSON-LD for Google rich snippets)
do_action('woocommerce_after_single_product');

get_footer();
