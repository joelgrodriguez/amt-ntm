<?php
/**
 * Accessories Page — Catalog Grid
 *
 * The page's WooCommerce work. Six anchor-targeted bucket groups, each
 * rendering as a responsive card grid using the canonical `card-product`
 * partial and the system gap (gap-8 / lg:gap-10).
 *
 * No bespoke hairline-divider grid: that pattern is reserved for the
 * machine lineup pages where there are ~6 hero products. For 60+ product
 * accessory listings, the canonical pattern is a standard gapped grid
 * (matches front-page Explore strip and Woo catalog elsewhere).
 *
 * @package Standard
 *
 * @usage Accessories Page (page-accessories.php)
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\AccessoriesData\get_bucketed_products;

$bucketed = get_bucketed_products();
?>

<section id="catalog" class="section" aria-labelledby="catalog-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('The Catalog', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="catalog-title" class="section-title">
                <?php esc_html_e('More for Every Machine', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-16 lg:gap-20">
            <?php foreach ($bucketed as $bucket) :
                $products = $bucket['products'];
                if (empty($products)) {
                    continue;
                }
            ?>
                <div id="catalog-<?php echo esc_attr($bucket['id']); ?>" class="grid gap-8 scroll-mt-24">

                    <div class="flex items-center justify-between border-b border-blue-200 pb-4">
                        <h3 class="font-mono text-lg font-medium text-blue-400 uppercase tracking-wider">
                            <?php echo esc_html($bucket['label']); ?>
                        </h3>
                        <p class="font-mono text-xs uppercase tracking-wider text-blue-500">
                            <?php
                            /* translators: %d: number of products in this bucket */
                            printf(esc_html__('%d items', 'standard'), count($products));
                            ?>
                        </p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 lg:gap-10">
                        <?php foreach ($products as $product) : ?>
                            <?php get_template_part('templates/parts/card-product', null, ['product' => $product]); ?>
                        <?php endforeach; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
