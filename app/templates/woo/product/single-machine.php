<?php
/**
 * Single Machine Product — Landing Page Template
 *
 * Custom template for machine product pages (roof/wall panel, gutter).
 * Loaded via template_include filter in inc/woo/machine-template.php.
 * Accessories use the default WooCommerce single product template.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;
use function Standard\MachineSchema\render_machine_schema;

/** @var \WC_Product|false $product */
$product = wc_get_product(get_the_ID());
$machine = $product !== false ? get_machine_product_data($product->get_slug()) : null;
$video_url = function_exists('get_field') ? get_field('video', false, false) : null;

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

    <div class="machine-product__fold">
        <?php get_template_part('templates/woo/product/parts/hero', null, compact('product', 'machine')); ?>
        <?php get_template_part('templates/woo/product/parts/stats-bar', null, compact('machine')); ?>
    </div>

    <?php get_template_part('templates/woo/product/parts/subnav', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/machine-breakdown', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/blueprint', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/gallery', null, compact('product', 'machine')); ?>

    <?php
    get_template_part('templates/parts/video-section', null, [
        'title'      => $product->get_name(),
        'video_url'  => is_string($video_url) ? $video_url : null,
        'video_type' => __('Product Video', 'standard'),
        'section_id' => 'machine-video',
    ]);
    ?>

    <?php get_template_part('templates/woo/product/parts/profile-selector', null, compact('product', 'machine')); ?>

    <?php // CTA Strip 2: Configurator — catches engaged "I want this" buyers ?>
    <?php get_template_part('templates/woo/product/parts/cta-configurator', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/case-study', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/accessories', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/specs-accordion', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/resources', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/faq', null, compact('machine')); ?>

    <?php // Combined configurator + financing deep section ?>
    <?php get_template_part('templates/woo/product/parts/configurator-finance', null, compact('product', 'machine')); ?>

    <?php // Final CTA: "Talk to us" — catches remaining buyers ?>
    <?php get_template_part('templates/woo/product/parts/final-cta', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/sticky-cta', null, compact('product', 'machine')); ?>

    <!-- Floating Quote CTA -->
    <a
        id="floating-quote-cta"
        href="<?php echo esc_url(\Standard\Url\internal('/configurator/' . $product->get_slug() . '/')); ?>"
        class="floating-quote-cta"
        aria-label="<?php esc_attr_e('Get a Quote Now', 'standard'); ?>"
    >
        <?php icon('settings', ['class' => 'w-5 h-5']); ?>
        <span class="floating-quote-cta__label"><?php esc_html_e('Get a Quote', 'standard'); ?></span>
    </a>

</main>

<?php
// Generate WooCommerce structured data (JSON-LD for Google rich snippets).
// The default template fires woocommerce_single_product_summary which triggers
// WC_Structured_Data::generate_product_data(). Since we skip that action,
// call generate_product_data() directly so the JSON-LD outputs in wp_footer.
render_machine_schema($product, $machine);
do_action('woocommerce_after_single_product');

get_footer();
