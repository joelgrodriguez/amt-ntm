<?php
/**
 * Machines Page — Lineup Flagship Band
 *
 * Full-bleed band for the category flagship: dark background,
 * product image left (lg+), badge + name + highlights + dual CTA
 * right. Sits between the category header and the rest-of-lineup
 * grid in lineup-grid.php.
 *
 * @package Standard
 *
 * @usage Via get_template_part() with args: machine (array)
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

<div class="grid bg-blue-900 text-white overflow-hidden lg:grid-cols-[5fr_4fr] lg:items-center">

    <!-- Image -->
    <div class="relative aspect-[4/3] bg-blue-800 lg:aspect-auto lg:h-full lg:min-h-[420px]">
        <?php \Standard\Images\responsive_image($machine['image'], $machine['name'], 'product-card', [
            'class' => 'absolute inset-0 w-full h-full object-contain p-6 lg:p-10',
        ]); ?>
    </div>

    <!-- Content -->
    <div class="grid gap-6 p-8 lg:gap-8 lg:p-12 xl:p-16">

        <?php if (!empty($machine['badge']) || !empty($machine['descriptor'])) : ?>
            <div class="flex flex-wrap items-center gap-3">
                <?php if (!empty($machine['badge'])) : ?>
                    <span class="badge badge-emphasis">
                        <?php echo esc_html($machine['badge']); ?>
                    </span>
                <?php endif; ?>
                <?php if (!empty($machine['descriptor'])) : ?>
                    <span class="font-mono text-xs uppercase tracking-[0.15em] text-blue-300">
                        <?php echo esc_html($machine['descriptor']); ?>
                    </span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <h4 class="text-4xl font-medium tracking-tight text-white lg:text-5xl xl:text-6xl">
            <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="no-underline text-inherit hover:text-blue-200 transition-colors">
                <?php echo esc_html($machine['name']); ?>
            </a>
        </h4>

        <?php if ($price) : ?>
            <div class="grid gap-1">
                <p class="text-2xl font-medium text-white">
                    <?php echo esc_html($price); ?>
                </p>
                <p class="font-mono text-xs text-blue-300 uppercase tracking-wider">
                    <?php echo esc_html($price_label); ?>
                </p>
            </div>
        <?php endif; ?>

        <ul class="grid gap-3 text-blue-200">
            <?php foreach ($machine['highlights'] as $highlight) : ?>
                <li class="flex gap-3">
                    <span class="font-mono text-xs text-blue-500 mt-1.5 shrink-0">→</span>
                    <span><?php echo esc_html($highlight); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div class="flex flex-wrap gap-3 pt-2">
            <a href="<?php echo esc_url(\Standard\Url\internal($machine['url'])); ?>" class="btn btn-primary">
                <?php esc_html_e('Explore', 'standard'); ?>
                <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
            </a>
            <?php if (!empty($machine['configurator_slug'])) : ?>
                <a href="<?php echo esc_url(\Standard\Url\with_query('/build-finance/', ['machine' => $machine['slug']])); ?>" class="btn btn-outline-light">
                    <?php esc_html_e('Build & Price', 'standard'); ?>
                </a>
            <?php endif; ?>
        </div>

    </div>

</div>
