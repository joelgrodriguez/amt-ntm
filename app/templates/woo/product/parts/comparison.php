<?php
/**
 * Machine Product — Machine Comparison
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\MachineProductData\get_machine_product_data;

$product    = $args['product'] ?? null;
$machine    = $args['machine'] ?? [];
$comparison = $machine['comparison'] ?? [];

if (empty($comparison['compare_with'])) {
    return;
}

$best_for    = $comparison['best_for'] ?? '';
$price_range = $machine['finance']['price_range'] ?? '';
$compare_slugs = $comparison['compare_with'];
?>

<section class="section bg-slate-50" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">Compare</p>
            <div class="section-divider-center"></div>
            <h2 id="comparison-title" class="section-title">Which Machine Is Right for You?</h2>
        </div>

        <div class="grid md:grid-cols-<?php echo esc_attr((string) min(count($compare_slugs) + 1, 4)); ?> gap-6 max-w-5xl mx-auto">
            <!-- Current machine -->
            <div class="border-2 border-secondary bg-white p-6 grid gap-3 text-center relative">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-secondary text-white text-xs font-semibold px-3 py-1 uppercase tracking-wider">You're Viewing</span>
                <div class="bg-slate-100 aspect-square flex items-center justify-center mt-4 overflow-hidden">
                    <?php if ($product && $product->get_image_id()) : ?>
                        <?php echo $product->get_image('woocommerce_thumbnail', ['class' => 'w-full h-full object-contain']); ?>
                    <?php else : ?>
                        <span class="text-slate-400 text-xs font-mono">Machine image</span>
                    <?php endif; ?>
                </div>
                <h3 class="text-lg font-bold text-slate-900"><?php echo esc_html($product ? $product->get_name() : ''); ?></h3>
                <?php if (!empty($best_for)) : ?>
                    <p class="text-sm text-slate-500">Best for: <?php echo esc_html($best_for); ?></p>
                <?php endif; ?>
                <?php if (!empty($price_range)) : ?>
                    <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($price_range); ?></span>
                <?php endif; ?>
            </div>

            <!-- Comparison machines -->
            <?php foreach ($compare_slugs as $slug) :
                $comp = get_machine_product_data($slug);
                if (!$comp) {
                    continue;
                }
                $comp_name  = $comp['hero']['headline'] ?? ucwords(str_replace('-', ' ', $slug));
                $comp_price = $comp['finance']['price_range'] ?? '';
                $comp_best  = $comp['comparison']['best_for'] ?? '';
            ?>
                <div class="border border-slate-200 bg-white p-6 grid gap-3 text-center">
                    <div class="bg-slate-100 aspect-square flex items-center justify-center">
                        <span class="text-slate-400 text-xs font-mono">Machine image</span>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900"><?php echo esc_html($comp_name); ?></h3>
                    <?php if (!empty($comp_best)) : ?>
                        <p class="text-sm text-slate-500">Best for: <?php echo esc_html($comp_best); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($comp_price)) : ?>
                        <span class="text-sm font-semibold text-slate-900"><?php echo esc_html($comp_price); ?></span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url('/machines/' . $slug . '/'); ?>" class="btn btn-sm btn-outline-dark mx-auto">Explore</a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
