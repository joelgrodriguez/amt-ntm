<?php
/**
 * Accessories Page — Catalog Jump Nav
 *
 * In-page navigation for the bucketed catalog. Mirrors the blog single-post
 * TOC (sticky aside on lg+) and the filter-sidebar drawer pattern on mobile.
 *
 * Two trees, like .filter-drawer + <aside class="hidden lg:block">:
 *   - Mobile: <details> drawer using .filter-drawer-* styles for chrome.
 *   - Desktop (lg+): sticky <aside> using .toc / .toc__* styles for the rail.
 *
 * The scrollspy module targets #catalog-nav-list (desktop) for active-state
 * highlighting; the mobile drawer is for jumping, not for tracking position.
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

$render_items = static function () use ($buckets): void {
    foreach ($buckets as $bucket) : ?>
        <li class="toc__item">
            <a href="#catalog-<?php echo esc_attr($bucket['id']); ?>" class="toc__link">
                <?php echo esc_html($bucket['label']); ?>
            </a>
        </li>
    <?php endforeach;
};
?>

<!-- Mobile drawer. Hidden on lg+; the sticky aside takes over. -->
<details class="filter-drawer lg:hidden" data-accordion-group aria-label="<?php esc_attr_e('Accessory categories', 'standard'); ?>">
    <summary>
        <span><?php esc_html_e('Jump to a category', 'standard'); ?></span>
        <span class="filter-drawer-caret accordion__icon" aria-hidden="true">
            <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
        </span>
    </summary>
    <div class="filter-drawer-body px-4 py-4" data-accordion-body>
        <nav class="toc" aria-label="<?php esc_attr_e('Accessory categories', 'standard'); ?>">
            <ol class="toc__list">
                <?php $render_items(); ?>
            </ol>
        </nav>
    </div>
</details>

<!-- Desktop rail. Mirrors the single-post TOC. -->
<aside id="catalog-nav" class="hidden lg:block" aria-label="<?php esc_attr_e('Accessory categories', 'standard'); ?>">
    <nav class="toc sticky top-24">
        <p class="toc__title"><?php esc_html_e('On this page', 'standard'); ?></p>
        <ol id="catalog-nav-list" class="toc__list">
            <?php $render_items(); ?>
        </ol>
    </nav>
</aside>
