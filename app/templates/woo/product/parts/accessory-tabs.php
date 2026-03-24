<?php
/**
 * Accessory Product — Details Section
 *
 * Product description and attributes rendered as accordions.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;

if (!$product) {
    return;
}

$description    = $product->get_description();
$has_attributes = !empty($product->get_attributes());

if (empty($description) && !$has_attributes) {
    return;
}
?>

<section class="section bg-slate-50" aria-labelledby="accessory-details-title">
    <div class="container section-content">

        <div class="section-header-left mb-12">
            <p class="section-eyebrow"><?php esc_html_e('Details', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="accessory-details-title" class="section-title"><?php esc_html_e('Product Information', 'standard'); ?></h2>
        </div>

        <div class="max-w-4xl" data-accordion-group>
            <?php if (!empty($description)) : ?>
                <details class="accordion" open>
                    <summary>
                        <?php esc_html_e('Description', 'standard'); ?>
                        <span class="accordion__icon">
                            <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </summary>
                    <div class="accordion__body text-sm text-slate-600 leading-relaxed prose prose-sm max-w-none">
                        <?php echo wp_kses_post(wpautop($description)); ?>
                    </div>
                </details>
            <?php endif; ?>

            <?php if ($has_attributes) : ?>
                <details class="accordion" <?php echo empty($description) ? 'open' : ''; ?>>
                    <summary>
                        <?php esc_html_e('Additional Information', 'standard'); ?>
                        <span class="accordion__icon">
                            <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </summary>
                    <div class="accordion__body text-sm text-slate-600">
                        <?php
                        // WooCommerce attribute table
                        do_action('woocommerce_product_additional_information', $product);
                        ?>
                    </div>
                </details>
            <?php endif; ?>
        </div>

    </div>
</section>
