<?php
/**
 * Mobile Menu L2 Panel Template Part
 *
 * Renders one slide-in panel with sub-header, machine cards, and a view-all link.
 *
 * Args (passed via get_template_part(..., null, [...])):
 *   - slug         (string) panel slug, used in data-panel and aria-* hooks
 *   - label        (string) panel title shown in the sub-header
 *   - category     (string) catalog slug passed to get_products_by_category()
 *   - view_all_url (string) URL for the bottom "View all <Label>" link
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Woo\Catalog\get_products_by_category;

$slug         = $args['slug']         ?? '';
$label        = $args['label']        ?? '';
$category     = $args['category']     ?? '';
$view_all_url = $args['view_all_url'] ?? '#';

if ($slug === '' || $category === '') {
    return;
}

$products = get_products_by_category($category);
?>

<section class="mobile-menu__panel" data-panel="<?php echo esc_attr($slug); ?>" aria-hidden="true" aria-labelledby="mobile-menu-title-<?php echo esc_attr($slug); ?>">
    <header class="mobile-menu__panel-header">
        <button type="button" class="mobile-menu__back" data-action="back" aria-label="<?php esc_attr_e('Back', 'standard'); ?>">
            <?php icon('arrow-left', ['class' => 'w-5 h-5']); ?>
        </button>
        <h2 id="mobile-menu-title-<?php echo esc_attr($slug); ?>" class="mobile-menu__panel-title">
            <?php echo esc_html($label); ?>
        </h2>
        <span class="mobile-menu__panel-spacer" aria-hidden="true"></span>
    </header>

    <div class="mobile-menu__panel-body">
        <?php if (!empty($products)) : ?>
            <div class="mobile-menu__cards">
                <?php foreach ($products as $product) : ?>
                    <?php get_template_part('templates/parts/card-product', null, ['product' => $product, 'variant' => 'stack']); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <a class="mobile-menu__view-all" href="<?php echo esc_url($view_all_url); ?>">
            <?php
            /* translators: %s: category label, e.g. "Roof & Wall Panel Machines" */
            printf(esc_html__('View all %s', 'standard'), esc_html($label));
            ?>
            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
        </a>
    </div>
</section>
