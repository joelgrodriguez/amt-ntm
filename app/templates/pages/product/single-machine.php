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

/** @var \WC_Product $product */
$product = wc_get_product(get_the_ID());
$machine = $product ? get_machine_product_data($product->get_slug()) : null;

// DEBUG: remove after wiring up slugs
if (current_user_can('manage_options') && $product) {
    echo '<div style="position:fixed;top:32px;left:0;right:0;z-index:99999;background:#1e293b;color:#94a3b8;padding:12px 16px;font-family:monospace;font-size:13px;">';
    echo 'Slug: <strong style="color:#fff;">' . esc_html($product->get_slug()) . '</strong>';
    echo ' &nbsp;|&nbsp; Data match: <strong style="color:' . ($machine ? '#4ade80' : '#f87171') . ';">' . ($machine ? 'YES' : 'NO') . '</strong>';
    echo '</div>';
}

get_header();

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

    <?php // CTA Strip 1: Financing — catches early "can I afford this?" buyers ?>
    <?php get_template_part('templates/pages/product/cta-finance', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/machine-breakdown', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/blueprint', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/gallery', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/profile-selector', null, compact('product', 'machine')); ?>

    <?php // CTA Strip 2: Configurator — catches engaged "I want this" buyers ?>
    <?php get_template_part('templates/pages/product/cta-configurator', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/social-proof', null, compact('machine')); ?>

    <?php get_template_part('templates/pages/product/comparison', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/accessories', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/specs-accordion', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/resources', null, compact('machine')); ?>

    <?php // Combined configurator + financing deep section ?>
    <?php get_template_part('templates/pages/product/configurator-finance', null, compact('product', 'machine')); ?>

    <?php // Final CTA: "Talk to us" — catches remaining buyers ?>
    <?php get_template_part('templates/pages/product/final-cta', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/pages/product/sticky-cta', null, compact('product', 'machine')); ?>

</main>

<?php
// Preserve WooCommerce structured data (JSON-LD for Google rich snippets)
do_action('woocommerce_after_single_product');

get_footer();
