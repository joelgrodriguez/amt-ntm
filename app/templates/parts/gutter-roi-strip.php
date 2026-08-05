<?php
/**
 * Gutter Machine ROI Calculator Strip
 *
 * A compact secondary CTA for gutter machine product pages. The category
 * guard keeps the strip off roof and wall panel machines when callers include
 * the part from a shared machine template.
 *
 * @package Standard
 *
 * @var array{product?: \WC_Product} $args
 *
 * @usage single-machine.php, single-machine-default.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product_id = isset($args['product']) && $args['product'] instanceof \WC_Product
    ? $args['product']->get_id()
    : get_queried_object_id();

if ($product_id <= 0 || !has_term('gutter-machines', 'product_cat', $product_id)) {
    return;
}
?>

<aside class="bg-blue-900" aria-labelledby="gutter-roi-strip-title">
    <div class="container flex flex-col gap-5 py-6 md:flex-row md:items-center md:justify-between md:gap-10">
        <div class="max-w-3xl">
            <p class="font-mono text-xs font-medium uppercase tracking-mono-label text-blue-300">
                <?php esc_html_e('Know Your Numbers', 'standard'); ?>
            </p>
            <h2 id="gutter-roi-strip-title" class="mt-2 font-sans text-2xl font-semibold tracking-tight text-white text-balance md:text-3xl">
                <?php esc_html_e('See how fast a gutter machine can pay for itself.', 'standard'); ?>
            </h2>
            <p class="mt-2 text-base leading-relaxed text-blue-200 text-pretty">
                <?php esc_html_e('Estimate production, revenue, costs, and payback with the Gutter Machine ROI Calculator.', 'standard'); ?>
            </p>
        </div>

        <a href="<?php echo esc_url(\Standard\Url\internal('/gutter-machine-roi-calculator/')); ?>" class="btn btn-outline-light shrink-0 self-start md:self-auto">
            <?php esc_html_e('Calculate Your ROI', 'standard'); ?>
            <?php icon('trending-up', ['class' => 'w-5 h-5']); ?>
        </a>
    </div>
</aside>
