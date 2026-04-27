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

<div class="bg-white flex flex-col h-full relative group hover:bg-slate-50 transition-colors duration-150">
    <!-- Product Image -->
    <div class="p-4 sm:p-6 flex items-center justify-center aspect-4/3">
        <img
            src="<?php echo esc_url($machine['image']); ?>"
            alt="<?php echo esc_attr($machine['name']); ?>"
            class="max-h-full w-auto object-contain"
            loading="lazy"
        >
    </div>

    <!-- Content -->
    <div class="p-4 sm:p-6 flex flex-col grow gap-4">
        <!-- Name -->
        <div>
            <h4 class="text-2xl font-bold font-mono text-slate-900">
                <a href="<?php echo esc_url($machine['url']); ?>" class="after:absolute after:inset-0 no-underline text-inherit">
                    <?php echo esc_html($machine['name']); ?>
                </a>
            </h4>
        </div>

        <?php if ($has_price) : ?>
            <!-- Price -->
            <div>
                <p class="text-lg font-semibold text-slate-900">
                    <?php echo esc_html($machine['price']); ?>
                </p>
                <p class="text-xs text-slate-500 uppercase tracking-wide">
                    <?php echo esc_html($machine['price_label']); ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Highlights -->
        <div class="flex flex-col gap-3 text-sm text-slate-700 grow">
            <?php foreach ($machine['highlights'] as $highlight) : ?>
                <p><?php echo esc_html($highlight); ?></p>
            <?php endforeach; ?>
        </div>

        <!-- CTAs -->
        <?php if ($has_price) : ?>
            <div class="flex gap-3 mt-auto relative z-10">
                <a href="<?php echo esc_url($machine['url']); ?>" class="btn btn-outline-dark btn-sm">
                    <?php esc_html_e('Explore', 'standard'); ?>
                </a>
                <a href="<?php echo esc_url('/build-finance/?machine=' . $machine['slug']); ?>" class="btn btn-ghost btn-sm">
                    <?php esc_html_e('Build', 'standard'); ?>
                </a>
            </div>
        <?php else : ?>
            <a href="<?php echo esc_url($machine['url']); ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-900 hover:text-primary transition-colors no-underline mt-auto relative z-10">
                <?php esc_html_e('Explore More', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
            </a>
        <?php endif; ?>
    </div>
</div>
