<?php
/**
 * Accessories Page — Category Map
 *
 * The page's organizing spine. A hairline grid of bucket cards, each
 * anchor-linked to its matching section in the catalog grid below.
 * Mono label + count + one-line description, no icons.
 *
 * Card layout follows the same hairline approach used in the machines
 * lineup grid: dividers between cards, no individual card boxes.
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
$buckets  = array_values(array_filter($bucketed, static fn(array $b): bool => $b['id'] !== 'more' || count($b['products']) > 0));
$total    = count($buckets);
$cols     = $total >= 3 ? 3 : $total;
?>

<section class="section" aria-labelledby="category-map-title">
    <div class="container section-content">

        <div class="section-header">
            <p class="section-eyebrow"><?php esc_html_e('Build Your Setup', 'standard'); ?></p>
            <div class="section-divider-center"></div>
            <h2 id="category-map-title" class="section-title">
                <?php esc_html_e('Six Ways a Machine Gets Better', 'standard'); ?>
            </h2>
        </div>

        <div class="border-t border-blue-200 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($buckets as $idx => $bucket) :
                $count    = count($bucket['products']);
                $disabled = $count === 0;
                $is_last_col_sm = ($idx % 2 === 1);
                $is_last_col_lg = (($idx + 1) % $cols === 0);
                $border_classes = 'border-b border-blue-200';
                $border_classes .= $is_last_col_sm ? '' : ' sm:border-r';
                $border_classes .= $is_last_col_lg ? ' lg:border-r-0' : ' lg:border-r';
            ?>
                <?php if ($disabled) : ?>
                    <div class="<?php echo esc_attr($border_classes); ?> group p-8 md:p-10 grid gap-3 content-start">
                        <p class="font-mono text-xs uppercase tracking-wider text-blue-400">
                            <?php esc_html_e('Coming Soon', 'standard'); ?>
                        </p>
                        <h3 class="font-sans font-medium text-blue-700 text-xl md:text-2xl tracking-tight">
                            <?php echo esc_html($bucket['label']); ?>
                        </h3>
                        <p class="text-blue-600 text-sm md:text-base max-w-prose">
                            <?php echo esc_html($bucket['description']); ?>
                        </p>
                    </div>
                <?php else : ?>
                    <a href="#catalog-<?php echo esc_attr($bucket['id']); ?>" class="<?php echo esc_attr($border_classes); ?> group p-8 md:p-10 grid gap-3 content-start no-underline hover:bg-blue-50 transition-colors duration-200">
                        <p class="font-mono text-xs uppercase tracking-wider text-blue-500">
                            <?php
                            /* translators: %d: number of products in this bucket */
                            printf(esc_html__('%d in catalog', 'standard'), $count);
                            ?>
                        </p>
                        <h3 class="font-sans font-medium text-blue-900 text-xl md:text-2xl tracking-tight">
                            <?php echo esc_html($bucket['label']); ?>
                        </h3>
                        <p class="text-blue-600 text-sm md:text-base max-w-prose">
                            <?php echo esc_html($bucket['description']); ?>
                        </p>
                        <span class="inline-flex items-center gap-1 text-sm font-mono font-medium text-blue-500 mt-2">
                            <?php esc_html_e('Jump', 'standard'); ?>
                            <?php icon('arrow-down', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>

    </div>
</section>
