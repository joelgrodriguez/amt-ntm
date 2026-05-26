<?php
/**
 * Machine Product — Floating "Get a Quote" CTA
 *
 * Always-visible quote shortcut for machine product pages. Routes to
 * the machine's /configurator/<slug>/ page using the catalog mapping.
 * Renders nothing for machines that have no configurator page.
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

$configurator_url = \Standard\Woo\Catalog\get_configurator_url($product->get_slug());

if ($configurator_url === '') {
    return;
}
?>

<a
    id="floating-quote-cta"
    href="<?php echo esc_url($configurator_url); ?>"
    class="floating-quote-cta"
    aria-label="<?php esc_attr_e('Get a Quote Now', 'standard'); ?>"
>
    <?php icon('settings', ['class' => 'w-5 h-5']); ?>
    <span class="floating-quote-cta__label"><?php esc_html_e('Get a Quote', 'standard'); ?></span>
</a>
