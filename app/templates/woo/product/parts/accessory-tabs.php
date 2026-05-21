<?php
/**
 * Accessory Product — Details Accordion
 *
 * Description + WooCommerce attributes rendered in the default-specs
 * pattern: header-left, accordion group, all closed by default.
 * (Filename retained for backward compatibility; "tabs" is legacy.)
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;

if (!$product instanceof \WC_Product) {
    return;
}

$description    = $product->get_description();
$has_attributes = !empty($product->get_attributes());

if (empty($description) && !$has_attributes) {
    return;
}
?>

<section class="section bg-blue-50" aria-labelledby="accessory-details-title">
    <div class="container section-content">

        <div class="max-w-3xl">

            <div class="section-header-left mb-12">
                <p class="section-eyebrow"><?php esc_html_e('Details', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="accessory-details-title" class="section-title"><?php esc_html_e('Product Information', 'standard'); ?></h2>
            </div>

            <div data-accordion-group>
                <?php if (!empty($description)) : ?>
                    <details class="accordion">
                        <summary>
                            <?php esc_html_e('Description', 'standard'); ?>
                            <span class="accordion__icon">
                                <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
                            </span>
                        </summary>
                        <div class="accordion__body">
                            <div class="prose prose-sm text-blue-700 max-w-none">
                                <?php echo wp_kses_post(wpautop($description)); ?>
                            </div>
                        </div>
                    </details>
                <?php endif; ?>

                <?php if ($has_attributes) : ?>
                    <details class="accordion">
                        <summary>
                            <?php esc_html_e('Additional Information', 'standard'); ?>
                            <span class="accordion__icon">
                                <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
                            </span>
                        </summary>
                        <div class="accordion__body">
                            <div class="text-sm text-blue-700">
                                <?php do_action('woocommerce_product_additional_information', $product); ?>
                            </div>
                        </div>
                    </details>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>
