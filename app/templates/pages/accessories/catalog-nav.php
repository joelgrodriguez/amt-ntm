<?php
/**
 * Accessories Page — Catalog Jump Nav
 *
 * Below-hero anchor strip. Mono uppercase links to each bucket section.
 *
 * Mobile: collapsed <details> dropdown. The summary shows the section
 * title; tapping reveals a vertical list of links. Pure HTML/CSS, no JS.
 *
 * Desktop (md+): full-width horizontal row, justify-between so links
 * span the container with even spacing, with vertical hairline dividers
 * between items.
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
        <details class="catalog-nav-dropdown md:hidden group">
            <summary class="flex items-center justify-between py-4 font-mono text-xs uppercase tracking-wider text-blue-900 cursor-pointer list-none [&::-webkit-details-marker]:hidden">
                <span><?php esc_html_e('Jump to a Category', 'standard'); ?></span>
                <?php icon('chevron-down', ['class' => 'w-4 h-4 transition-transform group-open:rotate-180']); ?>
            </summary>
            <ul class="grid border-t border-blue-200 divide-y divide-blue-200">
                <?php foreach ($buckets as $bucket) : ?>
                    <li>
                        <a href="#catalog-<?php echo esc_attr($bucket['id']); ?>" class="block py-3 font-mono text-xs uppercase tracking-wider text-blue-700 hover:text-blue-900 no-underline">
                            <?php echo esc_html($bucket['label']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </details>
        <div class="hidden md:flex md:items-stretch md:justify-between">
            <?php foreach ($buckets as $i => $bucket) : ?>
                <?php if ($i > 0) : ?>
                    <span aria-hidden="true" class="w-px bg-blue-200"></span>
                <?php endif; ?>
                <a href="#catalog-<?php echo esc_attr($bucket['id']); ?>" class="flex-1 flex items-center justify-center text-center py-4 font-mono text-xs uppercase tracking-wider text-blue-700 hover:text-blue-900 border-b-2 border-transparent hover:border-blue-500 transition-colors no-underline">
                    <?php echo esc_html($bucket['label']); ?>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</nav>
