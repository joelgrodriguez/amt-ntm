<?php
/**
 * Accessories Page — Catalog Grid
 *
 * Bucketed anchor-targeted card groups. Lives inside the `layout-with-rail`
 * grid set up in page-accessories.php; the section/container chrome is owned
 * by the parent template.
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

<div id="catalog" class="grid gap-12">

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
                    <h3 class="font-sans font-medium text-blue-900 text-xl md:text-2xl tracking-tight">
                        <?php echo esc_html($bucket['label']); ?>
                    </h3>
                    <p class="font-sans text-sm text-blue-500">
                        <?php
                        /* translators: %d: number of products in this bucket */
                        printf(esc_html__('%d items', 'standard'), count($products));
                        ?>
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
                    <?php foreach ($products as $product) : ?>
                        <?php get_template_part('templates/parts/card-accessory', null, ['card' => $product]); ?>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>

</div>
