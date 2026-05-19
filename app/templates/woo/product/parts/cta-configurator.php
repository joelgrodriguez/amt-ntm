<?php
/**
 * Machine Product — CTA Strip: Configurator
 *
 * Slim CTA bar after detail sections. Catches engaged buyers.
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);
if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;
$configurator_url = $product instanceof \WC_Product
    ? \Standard\Url\internal('/configurator/' . $product->get_slug() . '/')
    : \Standard\Url\internal('/configurator/');
?>

<div class="bg-blue-500 py-6">
    <div class="container flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <p class="text-white font-medium text-lg"><?php esc_html_e('Build your machine', 'standard'); ?></p>
            <p class="text-white/80 text-sm hidden md:block"><?php esc_html_e('Choose profiles, power, controls, and accessories. Get an instant quote.', 'standard'); ?></p>
        </div>
        <a href="<?php echo esc_url($configurator_url); ?>" class="btn btn-sm bg-white text-blue-500 hover:bg-blue-100 shrink-0"><?php esc_html_e('Open configurator', 'standard'); ?></a>
    </div>
</div>
