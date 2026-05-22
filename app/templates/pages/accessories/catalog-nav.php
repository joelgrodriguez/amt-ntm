<?php
/**
 * Accessories Page — Catalog Jump Nav
 *
 * Horizontal strip of anchor links jumping to catalog sections below.
 * Replaces the bucket-card map: same purpose (orient and skip), less
 * visual weight, same visual language as the front-page explore-machines
 * tabs (bottom-border underline, mono labels).
 *
 * Pure anchor links; no JS. Scrolls horizontally on narrow viewports.
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
$buckets  = array_values(array_filter($bucketed, static fn(array $b): bool => count($b['products']) > 0));

if (empty($buckets)) {
    return;
}
?>

<nav class="border-y border-blue-200 bg-white" aria-label="<?php esc_attr_e('Accessory categories', 'standard'); ?>">
    <div class="container">
        <div class="flex gap-2 md:gap-1 overflow-x-auto -mx-4 px-4 md:mx-0 md:px-0">
            <?php foreach ($buckets as $bucket) : ?>
                <a href="#catalog-<?php echo esc_attr($bucket['id']); ?>" class="catalog-nav__link whitespace-nowrap px-4 py-4 md:py-5 font-mono text-sm font-medium text-blue-600 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-500 transition-colors no-underline">
                    <?php echo esc_html($bucket['label']); ?>
                    <span class="ml-1 text-blue-400">
                        <?php echo esc_html((string) count($bucket['products'])); ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
