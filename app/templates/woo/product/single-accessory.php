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

    <?php
    // Related accessories from the same category.
    // Note: 'orderby' => 'rand' is intentionally excluded from cache reuse
    // by including the current product ID in the args fingerprint.
    $related = \Standard\Woo\Cache\get_products([
        'category' => ['accessories-add-on-equipment'],
        'exclude'  => [$product->get_id()],
        'limit'    => 4,
        'status'   => 'publish',
        'orderby'  => 'rand',
    ]);

    if (!empty($related)) :
    ?>
    <section class="section" aria-labelledby="related-accessories-title">
        <div class="container section-content">
            <div class="section-header-left mb-10">
                <p class="section-eyebrow"><?php esc_html_e('Related', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="related-accessories-title" class="section-title"><?php esc_html_e('More Accessories', 'standard'); ?></h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach ($related as $rel) :
                    $image_url = $rel->get_image_id()
                        ? wp_get_attachment_image_url($rel->get_image_id(), 'product-card')
                        : '';
                ?>
                    <a href="<?php echo esc_url($rel->get_permalink()); ?>" class="block border border-slate-200 bg-white p-4 grid gap-3 hover:border-slate-400 hover:shadow-md transition-all group">
                        <div class="bg-slate-50 aspect-square flex items-center justify-center overflow-hidden rounded">
                            <?php if (!empty($image_url)) : ?>
                                <img src="<?php echo esc_url($image_url); ?>"
                                     alt="<?php echo esc_attr($rel->get_name()); ?>"
                                     class="w-full h-full object-contain p-3 transition-transform group-hover:scale-105"
                                     loading="lazy">
                            <?php else : ?>
                                <span class="text-slate-400 text-sm font-mono"><?php echo esc_html($rel->get_name()); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="grid gap-1">
                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-primary transition-colors leading-tight"><?php echo esc_html($rel->get_name()); ?></h3>
                            <?php if ($rel->get_price_html()) : ?>
                                <p class="text-xs text-slate-500"><?php echo wp_kses_post($rel->get_price_html()); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="bg-slate-950 text-white py-12 lg:py-16">
        <div class="container text-center grid gap-4">
            <p class="text-sm font-mono uppercase tracking-wider text-slate-400"><?php esc_html_e('Need help choosing the right accessories?', 'standard'); ?></p>
            <h2 class="text-2xl font-bold font-mono md:text-3xl"><?php esc_html_e('Talk to a Specialist', 'standard'); ?></h2>
            <p class="text-slate-400 max-w-xl mx-auto"><?php esc_html_e('Our team can help you find the right setup for your machine and jobsite.', 'standard'); ?></p>
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
