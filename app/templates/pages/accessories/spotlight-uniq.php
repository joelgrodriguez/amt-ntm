<?php
/**
 * Accessories Page — UNIQ Spotlight
 *
 * Asymmetric two-column dark band. Photo on the left, editorial block +
 * CTAs on the right. UNIQ is the single highest-leverage upgrade in the
 * catalog, called out separately from the bucket grid.
 *
 * No spec quad: half-invented specs read worse than no specs on a brand
 * that lives on engineering credibility. The product page is one click
 * away for the real numbers.
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
                <p class="font-mono text-xs uppercase tracking-wider text-blue-500 flex items-center gap-2">
                    <span class="inline-block w-1 h-1 bg-blue-500"></span>
                    <?php esc_html_e('Spotlight', 'standard'); ?>
                </p>
                <h2 id="uniq-spotlight-title" class="font-sans font-medium tracking-tight text-white text-3xl md:text-4xl lg:text-5xl leading-tight">
                    <?php esc_html_e('UNIQ Automatic Control System', 'standard'); ?>
                </h2>
                <p class="text-blue-200 text-base md:text-lg max-w-prose">
                    <?php esc_html_e('Type the length, hit run. The controller drives the shear, counts the panels, and remembers the job. The difference between a machine and a production line.', 'standard'); ?>
                </p>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-blue-700">
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
