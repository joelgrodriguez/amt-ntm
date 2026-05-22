<?php
/**
 * Accessories Page — Catalog Grid
 *
 * The page's WooCommerce work. Six anchor-targeted bucket groups, each
 * rendering as a 4-col hairline grid of `card-product` accessory variants.
 * Replaces the old flat 48-item Woo shortcode.
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
use function Standard\Grid\get_card_border_classes;
use function Standard\Grid\get_overflow_border_classes;

$bucketed = get_bucketed_products();
?>

<section id="catalog" class="section bg-blue-50 border-t border-blue-200" aria-labelledby="catalog-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('The Catalog', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="catalog-title" class="section-title">
                <?php esc_html_e('Every Accessory, Grouped to Find', 'standard'); ?>
            </h2>
        </div>

        <div class="grid gap-20 lg:gap-24">
            <?php foreach ($bucketed as $bucket) :
                $products = $bucket['products'];
                $count    = count($products);
                if ($count === 0) {
                    continue;
                }

                $cols       = $count >= 4 ? 4 : $count;
                $has_overflow = ($cols === 4 && $count % 4 !== 0);
                $top_row    = $has_overflow ? array_slice($products, 0, (int) (floor($count / 4) * 4)) : $products;
                $bottom_row = $has_overflow ? array_slice($products, (int) (floor($count / 4) * 4)) : [];
            ?>
                <div id="catalog-<?php echo esc_attr($bucket['id']); ?>" class="grid gap-8" data-bucket="<?php echo esc_attr($bucket['id']); ?>">

                    <div class="flex items-center justify-between border-b border-blue-200 pb-4">
                        <h3 class="font-mono text-lg font-medium text-blue-400 uppercase tracking-wider">
                            <?php echo esc_html($bucket['label']); ?>
                        </h3>
                        <p class="font-mono text-xs uppercase tracking-wider text-blue-500">
                            <?php
                            /* translators: %d: number of products in this bucket */
                            printf(esc_html__('%d items', 'standard'), $count);
                            ?>
                        </p>
                    </div>

                    <div class="bg-white grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-<?php echo esc_attr((string) $cols); ?>">
                        <?php foreach ($top_row as $idx => $product) : ?>
                            <div class="<?php echo esc_attr(get_card_border_classes($idx, count($top_row), $cols)); ?>">
                                <?php get_template_part('templates/parts/card-product', null, ['product' => $product, 'variant' => 'stack']); ?>
                            </div>
                        <?php endforeach; ?>

                        <?php if (!empty($bottom_row)) :
                            $overflow_count = count($bottom_row);
                            $offset = (int) floor(($cols - $overflow_count) / 2);
                        ?>
                            <?php foreach ($bottom_row as $i => $product) :
                                $col_start = $offset + $i + 1;
                            ?>
                                <div class="lg:col-start-<?php echo esc_attr((string) $col_start); ?> <?php echo esc_attr(get_overflow_border_classes($i, $overflow_count)); ?>">
                                    <?php get_template_part('templates/parts/card-product', null, ['product' => $product, 'variant' => 'stack']); ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
