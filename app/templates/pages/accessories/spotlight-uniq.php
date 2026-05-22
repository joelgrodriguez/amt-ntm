<?php
/**
 * Accessories Page — UNIQ Spotlight
 *
 * Asymmetric two-column: photo on the left, editorial block + spec rows
 * + CTA on the right. The single highest-leverage upgrade, called out
 * separately from the catalog.
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$uniq_image = '/wp-content/uploads/uniq-control-system.jpg';
$uniq_url   = '/product/uniq-automatic-control-system/';
$uniq_id    = function_exists('get_page_by_path') ? null : null;

if (function_exists('wc_get_product') && function_exists('get_page_by_path')) {
    $maybe = get_page_by_path('uniq-automatic-control-system', OBJECT, 'product');
    if ($maybe) {
        $product = wc_get_product($maybe->ID);
        if ($product instanceof \WC_Product) {
            $img = wp_get_attachment_url($product->get_image_id());
            if ($img) {
                $uniq_image = $img;
            }
            $uniq_url = $product->get_permalink();
        }
    }
}

$specs = [
    ['label' => __('Tolerance', 'standard'),    'value' => __('±1/16"', 'standard')],
    ['label' => __('Memory', 'standard'),       'value' => __('100 jobs', 'standard')],
    ['label' => __('Interface', 'standard'),    'value' => __('Touch + Remote', 'standard')],
    ['label' => __('Integration', 'standard'),  'value' => __('Hot-Melt Ready', 'standard')],
];
?>

<section class="bg-blue-900 border-t border-blue-800" aria-labelledby="uniq-spotlight-title">
    <div class="grid lg:grid-cols-2 lg:items-stretch">

        <div class="relative bg-blue-800 aspect-[4/3] lg:aspect-auto lg:min-h-[520px]">
            <?php if ($uniq_image) : ?>
                <img
                    src="<?php echo esc_url($uniq_image); ?>"
                    alt=""
                    class="absolute inset-0 w-full h-full object-cover"
                    loading="lazy"
                />
            <?php endif; ?>
        </div>

        <div class="px-6 py-16 md:px-12 md:py-20 lg:px-16 lg:py-24 grid content-center gap-8 max-w-2xl">

            <div class="grid gap-4">
                <p class="font-mono text-xs uppercase tracking-wider text-red flex items-center gap-2">
                    <span class="inline-block w-1 h-1 bg-red"></span>
                    <?php esc_html_e('Spotlight · The Upgrade Everyone Benchmarks', 'standard'); ?>
                </p>
                <h2 id="uniq-spotlight-title" class="font-sans font-medium tracking-tight text-white text-3xl md:text-4xl lg:text-5xl leading-tight">
                    <?php esc_html_e('UNIQ Automatic Control System', 'standard'); ?>
                </h2>
                <p class="text-blue-200 text-base md:text-lg max-w-prose">
                    <?php esc_html_e('Type the length, hit run. The controller drives the shear, counts the panels, and remembers the job. The difference between a machine and a production line.', 'standard'); ?>
                </p>
            </div>

            <dl class="grid grid-cols-2 gap-y-6 gap-x-8 border-t border-blue-800 pt-8">
                <?php foreach ($specs as $spec) : ?>
                    <div class="grid gap-1">
                        <dt class="font-mono text-xs uppercase tracking-wider text-blue-400">
                            <?php echo esc_html($spec['label']); ?>
                        </dt>
                        <dd class="font-mono font-medium text-white text-lg md:text-xl">
                            <?php echo esc_html($spec['value']); ?>
                        </dd>
                    </div>
                <?php endforeach; ?>
            </dl>

            <div class="flex flex-col sm:flex-row gap-4 pt-2">
                <a href="<?php echo esc_url(\Standard\Url\internal($uniq_url)); ?>" class="btn btn-primary">
                    <?php esc_html_e('See the Controller', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-5 h-5']); ?>
                </a>
                <a href="#catalog-power-controls" class="btn btn-outline-light">
                    <?php esc_html_e('All Controllers & Power', 'standard'); ?>
                </a>
            </div>

        </div>

    </div>
</section>
