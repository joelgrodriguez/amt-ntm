<?php
/**
 * Machine Product — Accessories & Equipment
 *
 * @package Standard
 * @var array{product: \WC_Product, machine: array} $args
 */

declare(strict_types=1);

$machine     = $args['machine'] ?? [];
$product_tag = $machine['accessories']['product_tag'] ?? '';

if (empty($product_tag)) {
    return;
}

$accessories = wc_get_products([
    'tag'    => [$product_tag],
    'limit'  => 8,
    'status' => 'publish',
]);

if (empty($accessories)) {
    return;
}
?>

<section class="section" aria-labelledby="accessories-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow">Accessories</p>
            <div class="section-divider-center"></div>
            <h2 id="accessories-title" class="section-title">Complete Your Setup</h2>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($accessories as $accessory) :
                /** @var \WC_Product $accessory */
                $desc = $accessory->get_short_description();
                $desc = wp_trim_words(wp_strip_all_tags($desc), 10, '&hellip;');
            ?>
                <a href="<?php echo esc_url($accessory->get_permalink()); ?>" class="border border-slate-200 bg-white p-6 text-center grid gap-2 hover:border-slate-400 transition-colors">
                    <div class="bg-slate-100 aspect-square mb-2 flex items-center justify-center overflow-hidden">
                        <?php if ($accessory->get_image_id()) : ?>
                            <?php echo $accessory->get_image('woocommerce_thumbnail', ['class' => 'w-full h-full object-contain']); ?>
                        <?php else : ?>
                            <span class="text-slate-400 text-xs font-mono">Photo</span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900"><?php echo esc_html($accessory->get_name()); ?></h3>
                    <?php if (!empty($desc)) : ?>
                        <p class="text-xs text-slate-500"><?php echo wp_kses_post($desc); ?></p>
                    <?php endif; ?>
                    <span class="text-sm font-semibold text-slate-700"><?php echo wp_kses_post($accessory->get_price_html()); ?></span>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
