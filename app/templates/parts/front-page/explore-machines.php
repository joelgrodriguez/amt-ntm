<?php
/**
 * Explore Machines Section Template Part
 *
 * Tabbed product showcase section for the front page.
 * Displays products by category with horizontal scrolling.
 *
 * @package Standard
 */

declare(strict_types=1);

use function Standard\Products\get_product_categories;
use function Standard\Products\get_products_by_category;

$categories = get_product_categories();

if (empty($categories)) {
    return;
}

$first_category = array_key_first($categories);
?>

<section class="explore-machines pattern-dot-grid gradient-fade-bottom-sm" aria-labelledby="explore-machines-title">
    <div class="container">
        <h2 id="explore-machines-title" class="explore-machines__title">
            <?php esc_html_e('Explore All Machines', 'standard'); ?>
        </h2>

        <!-- Category Tabs -->
        <div class="explore-machines__tabs" role="tablist" aria-label="<?php esc_attr_e('Machine categories', 'standard'); ?>">
            <?php foreach ($categories as $slug => $label) : ?>
                <button
                    type="button"
                    class="explore-machines__tab <?php echo $slug === $first_category ? 'explore-machines__tab--active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $slug === $first_category ? 'true' : 'false'; ?>"
                    aria-controls="panel-<?php echo esc_attr($slug); ?>"
                    data-category="<?php echo esc_attr($slug); ?>"
                >
                    <?php echo esc_html($label); ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Product Panels -->
        <?php foreach ($categories as $slug => $label) : ?>
            <?php $products = get_products_by_category($slug); ?>
            <div
                    id="panel-<?php echo esc_attr($slug); ?>"
                    class="explore-machines__panel <?php echo $slug === $first_category ? 'explore-machines__panel--active' : ''; ?>"
                    role="tabpanel"
                    aria-labelledby="tab-<?php echo esc_attr($slug); ?>"
                    <?php echo $slug !== $first_category ? 'hidden' : ''; ?>
            >
                <div class="explore-machines__track">
                    <?php foreach ($products as $product) : ?>
                        <?php
                        get_template_part('templates/parts/card-product', null, [
                                'product' => $product,
                        ]);
                        ?>
                    <?php endforeach; ?>
                </div>

                <!-- Slider Navigation -->
                <div class="explore-machines__nav container">
                    <div class="explore-machines__nav-arrows">
                        <button
                                type="button"
                                class="explore-machines__arrow explore-machines__arrow--prev"
                                aria-label="<?php esc_attr_e('Previous products', 'standard'); ?>"
                                data-panel="<?php echo esc_attr($slug); ?>"
                        >
                            <?php icon('arrow--left', ['class' => 'w-4 h-4']); ?>
                        </button>
                        <span class="explore-machines__counter">
                        <span class="explore-machines__current">1</span>
                        <?php esc_html_e('of', 'standard'); ?>
                        <span class="explore-machines__total"><?php echo count($products); ?></span>
                    </span>
                        <button
                                type="button"
                                class="explore-machines__arrow explore-machines__arrow--next"
                                aria-label="<?php esc_attr_e('Next products', 'standard'); ?>"
                                data-panel="<?php echo esc_attr($slug); ?>"
                        >
                            <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Bottom CTAs -->
        <div class="explore-machines__footer container">
            <a href="/machines/" class="btn btn-outline-dark">
                <?php esc_html_e('Explore All Machines', 'standard'); ?>
            </a>
            <a href="/build-finance/" class="btn btn-ghost">
                <?php esc_html_e('Build & Finance', 'standard'); ?>
                <?php icon('arrow--right', ['class' => 'w-4 h-4']); ?>
            </a>
        </div>
    </div>


</section>
