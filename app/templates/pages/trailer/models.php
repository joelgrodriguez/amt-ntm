<?php
/**
 * Trailer Page — The Two Models
 *
 * The conversion beat: the two sellable trailers, TR23 (3-axle bumper-pull)
 * and TR23G (gooseneck). Same 23,000 lb capacity and purpose; the choice is
 * hitch configuration and truck. Real prices, real product renders, real
 * descriptions from the WooCommerce products (#2857, #2856).
 *
 * Two panels split by a single full-height hairline on desktop, stacked with
 * a horizontal hairline on mobile (the blueprint "fork" pattern). Product
 * renders sit in light blue-50 wells per DESIGN.md §6.
 *
 * @package Standard
 *
 * @usage page-trailer.php
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

// Prices come live from the WooCommerce products (trailer-tr23 #2857,
// trailer-tr23g #2856). The literals are a fallback for environments
// without WooCommerce only; the store price wins whenever it resolves.
$tr23_price  = \Standard\MachinesData\get_product_price('trailer-tr23') ?? __('$32,600', 'standard');
$tr23g_price = \Standard\MachinesData\get_product_price('trailer-tr23g') ?? __('$34,200', 'standard');

$models = [
    [
        'model'      => 'TR23',
        'name'       => __('3-Axle Trailer', 'standard'),
        'hitch'      => __('Standard bumper-pull', 'standard'),
        'desc'       => __('A 23,000 lb capacity three-axle trailer for the triple overhead reel rack. Couples to a standard rear hitch, so it goes behind the truck you already run.', 'standard'),
        'price'      => $tr23_price,
        'specs'      => [
            ['label' => __('Capacity', 'standard'),    'value' => __('23,000 lb', 'standard')],
            ['label' => __('Hitch', 'standard'),       'value' => __('3-axle bumper-pull', 'standard')],
            ['label' => __('Built for', 'standard'),   'value' => __('Triple overhead reel rack', 'standard')],
            ['label' => __('License', 'standard'),     'value' => __('CDL required', 'standard')],
        ],
        'image'      => content_url('/uploads/2022/02/TR23-WAV-3-axle.png'),
        'image_alt'  => __('NTM TR23 three-axle bumper-pull trailer rendered with a WAV machine', 'standard'),
        'url'        => '/machines/accessories-add-on-equipment/trailer-tr23/',
    ],
    [
        'model'      => 'TR23G',
        'name'       => __('Gooseneck Trailer', 'standard'),
        'hitch'      => __('In-bed gooseneck', 'standard'),
        'desc'       => __('The same 23,000 lb capacity in a gooseneck configuration. Couples to an in-bed hitch for more stability and a tighter turning radius, paired with a compatible truck bed.', 'standard'),
        'price'      => $tr23g_price,
        'specs'      => [
            ['label' => __('Capacity', 'standard'),    'value' => __('23,000 lb', 'standard')],
            ['label' => __('Hitch', 'standard'),       'value' => __('Gooseneck (in-bed)', 'standard')],
            ['label' => __('Built for', 'standard'),   'value' => __('Triple overhead reel rack', 'standard')],
            ['label' => __('License', 'standard'),     'value' => __('CDL required', 'standard')],
        ],
        'image'      => content_url('/uploads/2022/02/TR23G-N31-10842-WAV-Gooseneck.png'),
        'image_alt'  => __('NTM TR23G gooseneck trailer rendered with a WAV machine', 'standard'),
        'url'        => '/machines/accessories-add-on-equipment/trailer-tr23g/',
    ],
];
?>

<section id="trailer-models" class="section bg-blue-900 scroll-mt-24" aria-labelledby="trailer-models-title">
    <div class="container section-content">

        <div class="section-header-left max-w-2xl">
            <p class="section-eyebrow"><?php esc_html_e('Two configurations', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="trailer-models-title" class="section-title text-white text-balance">
                <?php esc_html_e('Same Trailer Engineering, Your Hitch', 'standard'); ?>
            </h2>
            <p class="section-subtitle text-blue-300 text-pretty">
                <?php esc_html_e('Both carry 23,000 lb and run the triple overhead reel rack. The only real decision is how it couples to your truck.', 'standard'); ?>
            </p>
        </div>

        <div class="grid border border-blue-700 lg:grid-cols-2">
            <?php foreach ($models as $i => $model) :
                // Right/bottom panel carries the single hairline so the two
                // never double-fence (matches the blueprint fork pattern).
                $divider = $i === 1 ? 'border-t border-blue-700 lg:border-t-0 lg:border-l' : '';
            ?>
                <div class="flex flex-col gap-6 p-6 md:p-8 lg:p-10 <?php echo esc_attr($divider); ?>">

                    <div class="flex items-center gap-3">
                        <span class="font-mono font-medium text-sm uppercase tracking-mono-label text-white">
                            <?php echo esc_html($model['model']); ?>
                        </span>
                        <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                            <?php echo esc_html($model['hitch']); ?>
                        </span>
                    </div>

                    <div class="aspect-[16/10] overflow-hidden border border-blue-700 bg-blue-50" data-reveal="image">
                        <img
                            src="<?php echo esc_url($model['image']); ?>"
                            alt="<?php echo esc_attr($model['image_alt']); ?>"
                            loading="lazy"
                            decoding="async"
                            class="w-full h-full object-contain"
                        >
                    </div>

                    <h3 class="font-sans font-medium text-2xl text-white leading-tight">
                        <?php echo esc_html($model['name']); ?>
                    </h3>
                    <p class="font-sans text-[15px] leading-relaxed text-blue-200 max-w-prose">
                        <?php echo esc_html($model['desc']); ?>
                    </p>

                    <dl class="grid grid-cols-2 gap-px bg-blue-700 border border-blue-700 mt-auto">
                        <?php foreach ($model['specs'] as $spec) : ?>
                            <div class="bg-blue-900 p-4">
                                <dt class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400 mb-1">
                                    <?php echo esc_html($spec['label']); ?>
                                </dt>
                                <dd class="font-mono font-medium text-sm text-white">
                                    <?php echo esc_html($spec['value']); ?>
                                </dd>
                            </div>
                        <?php endforeach; ?>
                    </dl>

                    <div class="flex flex-wrap items-baseline justify-between gap-4 border-t border-blue-700 pt-6">
                        <div class="flex items-baseline gap-2">
                            <span class="font-mono text-[11px] uppercase tracking-mono-label text-blue-400">
                                <?php esc_html_e('Starting at', 'standard'); ?>
                            </span>
                            <span class="font-sans font-medium text-2xl text-white">
                                <?php echo esc_html($model['price']); ?>
                            </span>
                        </div>
                        <a href="<?php echo esc_url($model['url']); ?>" class="btn btn-outline-light">
                            <?php esc_html_e('View details', 'standard'); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
