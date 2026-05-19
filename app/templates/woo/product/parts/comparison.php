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

<section class="section bg-blue-50" aria-labelledby="comparison-title">
    <div class="container section-content">

        <div class="section-header">
            <h2 id="comparison-title" class="section-title"><?php esc_html_e('Which Machine Is Right for You?', 'standard'); ?></h2>
        </div>

        <div class="grid md:grid-cols-<?php echo esc_attr((string) min(count($compare_slugs) + 1, 4)); ?> gap-6 max-w-5xl mx-auto">
            <!-- Current machine: differentiated by scale + a hairline accent, not color volume. -->
            <div class="border border-blue-500 bg-white p-6 grid gap-3 text-center relative">
                <span class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-blue-900 text-white text-xs font-mono font-medium px-3 py-1 uppercase tracking-wider">
                    <?php esc_html_e('You\'re viewing', 'standard'); ?>
                </span>
                <?php if ($product && $product->get_image_id()) : ?>
                    <div class="bg-blue-100 aspect-square flex items-center justify-center mt-4 overflow-hidden">
                        <?php echo $product->get_image('woocommerce_thumbnail', ['class' => 'w-full h-full object-contain']); ?>
                    </div>
                <?php endif; ?>
                <h3 class="text-lg font-medium text-blue-900"><?php echo esc_html($product ? $product->get_name() : ''); ?></h3>
                <?php if (!empty($best_for)) : ?>
                    <p class="text-sm text-blue-600"><?php esc_html_e('Best for:', 'standard'); ?> <?php echo esc_html($best_for); ?></p>
                <?php endif; ?>
                <?php if (!empty($price_range)) : ?>
                    <span class="text-sm font-medium text-blue-900"><?php echo esc_html($price_range); ?></span>
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
                <div class="border border-blue-200 bg-white p-6 grid gap-3 text-center">
                    <h3 class="text-lg font-medium text-blue-900"><?php echo esc_html($comp_name); ?></h3>
                    <?php if (!empty($comp_best)) : ?>
                        <p class="text-sm text-blue-600"><?php esc_html_e('Best for:', 'standard'); ?> <?php echo esc_html($comp_best); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($comp_price)) : ?>
                        <span class="text-sm font-medium text-blue-900"><?php echo esc_html($comp_price); ?></span>
                    <?php endif; ?>
                    <a href="<?php echo esc_url(\Standard\Url\internal('/machines/' . $slug . '/')); ?>" class="btn btn-sm btn-outline-dark mx-auto"><?php esc_html_e('Explore', 'standard'); ?></a>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
