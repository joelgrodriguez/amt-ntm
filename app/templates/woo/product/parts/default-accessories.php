<?php
/**
 * Default Machine — Compatible Accessories
 *
 * Tag-driven query: looks up the accessory tag from accessory-tag-map.php
 * by product slug, then queries all products with that tag. Renders via
 * the existing product-card-link partial for visual parity with other
 * machine surfaces.
 *
 * @package Standard
 * @var array{product: \WC_Product} $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Woo\AccessoryTagMap\tag_for_slug;
use function Standard\Woo\Accessories\product_cards;

$product = $args['product'] ?? null;
if (!$product instanceof \WC_Product) {
    return;
}

$tag = tag_for_slug($product->get_slug());
if ($tag === null) {
    return;
}

$accessory_ids = get_posts([
    'post_type'              => 'product',
    'post_status'            => 'publish',
    'posts_per_page'         => -1,
    'fields'                 => 'ids',
    'no_found_rows'          => true,
    'update_post_term_cache' => false,
    'tax_query'              => [
        [
            'taxonomy' => 'product_tag',
            'field'    => 'slug',
            'terms'    => $tag,
        ],
    ],
]);

if (empty($accessory_ids)) {
    return;
}

$accessory_products = array_filter(array_map('wc_get_product', $accessory_ids));
if (empty($accessory_products)) {
    return;
}

$cards = product_cards($accessory_products);
?>

<?php
$accessory_count = count($cards);
$carousel_id     = 'default-accessories-' . $product->get_id();
$title_id        = 'default-accessories-title';
$section_title   = sprintf(
    /* translators: %s is the machine name. */
    __('Built for the %s', 'standard'),
    $product->get_name()
);
$show_all_label = sprintf(
    /* translators: %d is the number of compatible accessories. */
    __('See All %d Accessories', 'standard'),
    $accessory_count
);
?>

<section id="machine-accessories" class="section" aria-labelledby="<?php echo esc_attr($title_id); ?>">
    <div class="container section-content" data-expandable-list>

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6 mb-10">
            <div>
                <p class="section-eyebrow mb-2"><?php esc_html_e('Compatible Accessories', 'standard'); ?></p>
                <h2 id="<?php echo esc_attr($title_id); ?>" class="section-title">
                    <?php echo esc_html($section_title); ?>
                </h2>
            </div>
        </div>

        <div id="<?php echo esc_attr($carousel_id); ?>"
             data-expandable-list-content
             class="t-resize grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <?php foreach ($cards as $index => $card) : ?>
                    <div<?php if ($index >= 4) : ?> data-expandable-list-expanded data-expandable-list-no-js-visible<?php endif; ?>>
                    <?php get_template_part('templates/parts/card-accessory', null, [
                        'card'    => $card,
                        'context' => 'grid',
                    ]); ?>
                    </div>
                <?php endforeach; ?>
        </div>

        <?php if ($accessory_count > 4) : ?>
            <?php get_template_part('templates/parts/expandable-list-toggle', null, [
                'region_id'      => $carousel_id,
                'show_label'     => $show_all_label,
                'collapse_label' => __('Collapse Accessories', 'standard'),
            ]); ?>
        <?php endif; ?>

    </div>
</section>
