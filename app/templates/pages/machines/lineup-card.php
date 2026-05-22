<?php
/**
 * Machines Page — Lineup Card
 *
 * Individual machine card for the lineup grid.
 * Shows product image, descriptor, name, highlights, and CTA.
 * If machine has price data, shows price + Explore/Build dual CTAs.
 *
 * @package Standard
 *
 * @usage Via get_template_part() from lineup-grid.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$machine = $args['machine'] ?? null;

if (!$machine) {
    return;
}

$has_price = !empty($machine['price']);
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

        <!-- Price / configured-pricing note -->
        <div>
            <?php if ($has_price) : ?>
                <p class="text-lg font-medium text-blue-900">
                    <?php echo esc_html($machine['price']); ?>
                </p>
                <p class="font-mono text-xs text-blue-500 uppercase tracking-wider">
                    <?php echo esc_html($machine['price_label']); ?>
                </p>
            <?php else : ?>
                <p class="text-lg font-medium text-blue-900">
                    <?php esc_html_e('Configured pricing', 'standard'); ?>
                </p>
                <p class="font-mono text-xs text-blue-500 uppercase tracking-wider">
                    <?php esc_html_e('Build to see your number', 'standard'); ?>
                </p>
            <?php endif; ?>
        </div>

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
