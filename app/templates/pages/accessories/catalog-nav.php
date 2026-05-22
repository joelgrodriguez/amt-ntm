<?php
/**
 * Accessories Page — Catalog Jump Nav
 *
 * Mono uppercase anchor strip below the hero. Hairline dividers between
 * links, no count badges. Visual cadence matches the chrome-bar strips
 * used elsewhere (red dot + mono labels). Pure anchors, no JS; scrolls
 * horizontally on narrow viewports.
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
        <div class="flex overflow-x-auto -mx-4 md:mx-0 divide-x divide-blue-200">
            <?php foreach ($buckets as $bucket) : ?>
                <a href="#catalog-<?php echo esc_attr($bucket['id']); ?>" class="whitespace-nowrap px-4 py-3 font-mono text-xs uppercase tracking-wider text-blue-600 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-500 transition-colors no-underline">
                    <?php echo esc_html($bucket['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</nav>
