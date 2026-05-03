<?php
/**
 * Accessory Product — Compatible Machines
 *
 * Reverse-lookup: finds machines that share product_tag terms
 * with the current accessory and renders them as a carousel.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$product = $args['product'] ?? null;

if (!$product) {
    return;
}

// Query all machines from both categories.
// Accessory tags (e.g. ssh, ssqii) map to machine data files but machines
// themselves aren't tagged in WooCommerce — so we show all machines and
// let the tag filter kick in once machines are tagged in the future.
$machine_posts = get_posts([
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => 4,
    'orderby'        => 'menu_order title',
    'order'          => 'ASC',
    'tax_query'      => [
        [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => ['roof-wall-panel-machines', 'gutter-machines'],
            'operator' => 'IN',
        ],
    ],
]);

$machines = array_filter(array_map('wc_get_product', $machine_posts));

if (empty($machines)) {
    return;
}

$cards = [];
foreach ($machines as $machine) {
    /** @var \WC_Product $machine */
    $cards[] = [
        'url'      => $machine->get_permalink(),
        'image_id' => $machine->get_image_id(),
        'title'    => $machine->get_name(),
        'subtitle' => $machine->get_price_html() ?: null,
    ];
}

if (empty($cards)) {
    return;
}
?>

<section class="section bg-light" aria-labelledby="compatible-machines-title">
    <div class="container section-content">
        <div class="section-header-left mb-10">
            <p class="section-eyebrow"><?php esc_html_e('Compatibility', 'standard'); ?></p>
            <div class="section-divider"></div>
            <h2 id="compatible-machines-title" class="section-title"><?php esc_html_e('Works With These Machines', 'standard'); ?></h2>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php foreach (array_slice($cards, 0, 4) as $card) : ?>
                <a href="<?php echo esc_url($card['url']); ?>" class="block border border-blue-200 bg-white p-4 grid gap-3 hover:border-blue-400 transition-all group">
                    <div class="bg-blue-50 aspect-square flex items-center justify-center overflow-hidden">
                        <?php if (!empty($card['image_id'])) : ?>
                            <?php echo wp_get_attachment_image((int) $card['image_id'], 'product-card', false, [
                                'class' => 'w-full h-full object-contain p-3 transition-transform group-hover:scale-105',
                                'alt'   => $card['title'],
                            ]); ?>
                        <?php else : ?>
                            <span class="text-blue-400 text-sm font-mono"><?php echo esc_html($card['title']); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="grid gap-1">
                        <h3 class="text-sm font-medium text-blue-900 group-hover:text-blue-500 transition-colors leading-tight"><?php echo esc_html($card['title']); ?></h3>
                        <?php if (!empty($card['subtitle'])) : ?>
                            <p class="text-xs text-blue-500"><?php echo wp_kses_post($card['subtitle']); ?></p>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
