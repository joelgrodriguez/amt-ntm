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
if (empty($video_url) && is_array($machine)) {
    $video_url = $machine['hero']['video'] ?? null;
}

get_header();
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

    <?php
    get_template_part('templates/parts/video-section', null, [
        'title'      => $product->get_name(),
        'video_url'  => is_string($video_url) ? $video_url : null,
        'video_type' => __('Product Video', 'standard'),
        'section_id' => 'machine-video',
    ]);
    ?>

    <?php get_template_part('templates/woo/product/parts/machine-breakdown', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/machine-fit', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/profile-selector', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/accessories', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/blueprint', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/specs-accordion', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/resources', null, compact('machine')); ?>

    <?php get_template_part('templates/woo/product/parts/faq', null, compact('machine')); ?>

    <?php
    $case_study = $machine['case_study'] ?? null;
    if (is_array($case_study) && !empty($case_study['content'])) {
        get_template_part('templates/parts/customer-story', null, [
            'anchor'         => 'machine-case-study',
            'section_id'     => 'machine-case-study-title',
            'image_position' => $case_study['image_position'] ?? 'right',
            'background'     => $case_study['background'] ?? 'bg-blue-50',
            'content'        => $case_study['content'],
            'stats'          => $case_study['stats'] ?? [],
        ]);
    }
    ?>

    <?php get_template_part('templates/woo/product/parts/final-cta', null, compact('product', 'machine')); ?>

    <?php get_template_part('templates/woo/product/parts/floating-quote-cta', null, compact('product')); ?>

</main>

<?php
render_machine_schema($product, $machine);
do_action('woocommerce_after_single_product');

get_footer();
