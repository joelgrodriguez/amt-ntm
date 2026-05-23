<?php
/**
 * Machines Page — Lineup Card
 *
 * Individual machine card for the lineup grid. Shows product image,
 * name, starting price (resolved from WooCommerce by slug), highlights,
 * and dual CTAs.
 *
 * Price falls back gracefully: hardcoded $machine['price'] wins if set,
 * otherwise we ask WC for the product's price by slug. If WC has no
 * price either, the card simply omits the price block and shows the
 * CTAs alone.
 *
 * @package Standard
 *
 * @usage Via get_template_part() from lineup-grid.php / lineup-flagship.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? null;

if (!$machine) {
    return;
}
?>

<div class="bg-white flex flex-col h-full">
    <!-- Product Image -->
    <div class="p-4 sm:p-6 flex items-center justify-center aspect-4/3">
        <?php \Standard\Images\responsive_image($machine['image'], $machine['name'], 'product-card', [
            'class' => 'max-h-full w-auto object-contain',
        ]); ?>
    </div>

    <!-- Content. One link per card: Build & Quote. The machine name is
         the on-page label; the spec page is reachable via the compare
         table's Explore button and the flagship band, so we don't need
         a redundant card-wide overlay competing with the CTA. -->
    <div class="p-4 sm:p-6 flex flex-col grow gap-4">
        <h4 class="text-2xl font-medium text-blue-900 tracking-tight">
            <?php echo esc_html($machine['name']); ?>
        </h4>

        <div class="mt-auto">
            <a href="<?php echo esc_url(\Standard\Url\with_query('/build-finance/', ['machine' => $machine['slug']])); ?>" class="btn btn-primary btn-sm">
                <?php esc_html_e('Build & Quote', 'standard'); ?>
            </a>
        </div>
    </div>
</div>
