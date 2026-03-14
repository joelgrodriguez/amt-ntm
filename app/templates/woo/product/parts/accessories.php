<?php
/**
 * Machine Product — Accessories Carousel
 *
 * Horizontal scrollable carousel with prev/next navigation.
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
    'limit'  => 12,
    'status' => 'publish',
]);

if (empty($accessories)) {
    return;
}

$carousel_id = 'accessories-carousel';
?>

<section class="section" aria-labelledby="accessories-title">
    <div class="container section-content">

        <div class="flex items-end justify-between gap-4 mb-10">
            <div class="section-header-left mb-0">
                <p class="section-eyebrow"><?php esc_html_e('Accessories', 'standard'); ?></p>
                <div class="section-divider"></div>
                <h2 id="accessories-title" class="section-title"><?php esc_html_e('Complete Your Setup', 'standard'); ?></h2>
            </div>
            <div class="flex gap-2 shrink-0">
                <button type="button"
                        data-carousel-prev="<?php echo esc_attr($carousel_id); ?>"
                        class="w-10 h-10 border border-slate-300 flex items-center justify-center hover:bg-slate-100 transition-colors"
                        aria-label="<?php esc_attr_e('Previous accessories', 'standard'); ?>">
                    <span class="text-slate-600">&larr;</span>
                </button>
                <button type="button"
                        data-carousel-next="<?php echo esc_attr($carousel_id); ?>"
                        class="w-10 h-10 border border-slate-300 flex items-center justify-center hover:bg-slate-100 transition-colors"
                        aria-label="<?php esc_attr_e('Next accessories', 'standard'); ?>">
                    <span class="text-slate-600">&rarr;</span>
                </button>
            </div>
        </div>

        <div id="<?php echo esc_attr($carousel_id); ?>"
             class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4"
             style="scrollbar-width: none; -ms-overflow-style: none;">
            <?php foreach ($accessories as $accessory) :
                /** @var \WC_Product $accessory */
                $desc = $accessory->get_short_description();
                $desc = wp_trim_words(wp_strip_all_tags($desc), 12, '&hellip;');
            ?>
                <a href="<?php echo esc_url($accessory->get_permalink()); ?>"
                   class="snap-start shrink-0 w-[280px] border border-slate-200 bg-white p-6 grid gap-3 hover:border-slate-400 hover:shadow-md transition-all">
                    <div class="bg-slate-50 aspect-square flex items-center justify-center overflow-hidden rounded">
                        <?php if ($accessory->get_image_id()) : ?>
                            <?php echo wp_get_attachment_image($accessory->get_image_id(), 'product-card', false, ['class' => 'w-full h-full object-contain p-4']); ?>
                        <?php else : ?>
                            <span class="text-slate-400 text-sm font-mono"><?php esc_html_e('Photo', 'standard'); ?></span>
                        <?php endif; ?>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900 leading-tight"><?php echo esc_html($accessory->get_name()); ?></h3>
                    <?php if (!empty($desc)) : ?>
                        <p class="text-xs text-slate-500 leading-relaxed"><?php echo wp_kses_post($desc); ?></p>
                    <?php endif; ?>
                    <?php if ($accessory->get_price_html()) : ?>
                        <span class="text-sm font-semibold text-slate-700"><?php echo wp_kses_post($accessory->get_price_html()); ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<script>
(function() {
    const id = <?php echo wp_json_encode($carousel_id); ?>;
    const track = document.getElementById(id);
    if (!track) return;

    const scrollAmount = 300;

    document.querySelectorAll(`[data-carousel-prev="${id}"]`).forEach(btn => {
        btn.addEventListener('click', () => track.scrollBy({ left: -scrollAmount, behavior: 'smooth' }));
    });
    document.querySelectorAll(`[data-carousel-next="${id}"]`).forEach(btn => {
        btn.addEventListener('click', () => track.scrollBy({ left: scrollAmount, behavior: 'smooth' }));
    });
})();
</script>
