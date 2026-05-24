<?php
/**
 * Machines Page — Lineup Card
 *
 * Individual machine card for the lineup grid. Image, name, starting
 * price, and highlights. The whole card is one tap target for the
 * machine's spec page via the name-overlay link — no secondary CTA, so
 * keyboard users get one stop per card and the hover affordance reads
 * as a real link.
 *
 * Price falls back gracefully: hardcoded $machine['price'] wins if
 * set, otherwise we ask WC for the product's price by slug. If WC has
 * no price either, the price block is omitted.
 *
 * @package Standard
 *
 * @usage Via get_template_part() from lineup-grid.php
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

    <!-- Content. Single card-wide link via the name overlay. -->
    <div class="p-4 sm:p-6 flex flex-col grow gap-4">
        <h4 class="text-2xl font-medium text-blue-900 tracking-tight group-hover:text-blue-500 transition-colors duration-150">
            <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="after:absolute after:inset-0 no-underline text-inherit focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                <?php echo esc_html($machine['name']); ?>
            </a>
        </h4>

        <?php if ($price) : ?>
            <div>
                <p class="text-lg font-medium text-blue-900">
                    <?php echo esc_html($price); ?>
                </p>
                <p class="font-mono text-xs text-blue-500 uppercase tracking-wider">
                    <?php echo esc_html($price_label); ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($machine['highlights'])) : ?>
            <div class="flex flex-col gap-3 text-sm text-blue-700 grow">
                <?php foreach ($machine['highlights'] as $highlight) : ?>
                    <p><?php echo esc_html($highlight); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- CTA. relative z-10 lifts it above the card-wide name
             overlay so the button stays clickable on its own target. -->
        <div class="mt-auto relative z-10">
            <a href="<?php echo esc_url(\Standard\Url\with_query('/build-finance/', ['machine' => $machine['slug']])); ?>" class="btn btn-outline-dark btn-sm">
                <?php esc_html_e('Build & Configure', 'standard'); ?>
            </a>
        </div>
    </div>
</div>
