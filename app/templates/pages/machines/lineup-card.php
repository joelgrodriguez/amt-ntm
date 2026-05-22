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

use function Standard\MachinesData\get_product_price;

$machine = $args['machine'] ?? null;

if (!$machine) {
    return;
}

$price = !empty($machine['price'])
    ? $machine['price']
    : (!empty($machine['slug']) ? get_product_price($machine['slug']) : null);

$price_label = $machine['price_label'] ?? __('Starting at', 'standard');
?>

<div class="bg-white flex flex-col h-full relative group hover:bg-blue-50 transition-colors duration-150">
    <!-- Product Image -->
    <div class="p-4 sm:p-6 flex items-center justify-center aspect-4/3">
        <?php \Standard\Images\responsive_image($machine['image'], $machine['name'], 'product-card', [
            'class' => 'max-h-full w-auto object-contain',
        ]); ?>
    </div>

    <!-- Content -->
    <div class="p-4 sm:p-6 flex flex-col grow gap-4">
        <!-- Name -->
        <div>
            <h4 class="text-2xl font-medium text-blue-900 tracking-tight">
                <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="after:absolute after:inset-0 no-underline text-inherit">
                    <?php echo esc_html($machine['name']); ?>
                </a>
            </h4>
        </div>

        <?php if ($price) : ?>
            <!-- Starting price -->
            <div>
                <p class="text-lg font-medium text-blue-900">
                    <?php echo esc_html($price); ?>
                </p>
                <p class="font-mono text-xs text-blue-500 uppercase tracking-wider">
                    <?php echo esc_html($price_label); ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Highlights -->
        <div class="flex flex-col gap-3 text-sm text-blue-700 grow">
            <?php foreach ($machine['highlights'] as $highlight) : ?>
                <p><?php echo esc_html($highlight); ?></p>
            <?php endforeach; ?>
        </div>

        <!-- CTAs -->
        <div class="flex gap-3 mt-auto relative z-10">
            <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="btn btn-outline-dark btn-sm">
                <?php esc_html_e('Explore', 'standard'); ?>
            </a>
            <a href="<?php echo esc_url(\Standard\Url\with_query('/build-finance/', ['machine' => $machine['slug']])); ?>" class="btn btn-ghost btn-sm">
                <?php esc_html_e('Build', 'standard'); ?>
            </a>
        </div>
    </div>
</div>
