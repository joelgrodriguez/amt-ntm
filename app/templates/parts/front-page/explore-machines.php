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

<section class="explore-machines py-16 bg-slate-50 pattern-dot-grid gradient-fade-bottom-sm md:py-20" aria-labelledby="explore-machines-title">
    <div class="container">
        <h2 id="explore-machines-title" class="text-3xl font-bold text-center text-slate-900 m-0 mb-8 md:text-4xl lg:text-5xl lg:mb-10">
            <?php esc_html_e('Explore All Machines', 'standard'); ?>
        </h2>

        <div class="explore-machines__tabs flex justify-center flex-wrap border-b border-slate-300 mb-8 lg:mb-10" role="tablist" aria-label="<?php esc_attr_e('Machine categories', 'standard'); ?>">
            <?php foreach ($categories as $slug => $label) : ?>
                <button
                    type="button"
                    class="explore-machines__tab px-4 py-2 text-sm font-medium text-slate-600 bg-transparent border-b border-transparent -mb-px cursor-pointer whitespace-nowrap transition-all duration-200 hover:text-slate-900 focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2 lg:text-base lg:px-6 <?php echo $slug === $first_category ? 'explore-machines__tab--active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $slug === $first_category ? 'true' : 'false'; ?>"
                    aria-controls="panel-<?php echo esc_attr($slug); ?>"
                    data-category="<?php echo esc_attr($slug); ?>"
                ><?php echo esc_html($label); ?></button>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="container mx-auto">
        <?php foreach ($categories as $slug => $label) : ?>
            <?php $products = get_products_by_category($slug); ?>
            <div
                    id="panel-<?php echo esc_attr($slug); ?>"
                    class="explore-machines__panel hidden <?php echo $slug === $first_category ? 'explore-machines__panel--active' : ''; ?>"
                    role="tabpanel"
                    aria-labelledby="tab-<?php echo esc_attr($slug); ?>"
                    <?php echo $slug !== $first_category ? 'hidden' : ''; ?>
            >
                <div class="explore-machines__track flex gap-4 overflow-x-auto md:gap-6">
                    <?php foreach ($products as $product) : ?>
                        <?php get_template_part('templates/parts/card-product', null, ['product' => $product]); ?>
                    <?php endforeach; ?>
                    <div class="shrink-0 w-px" aria-hidden="true"></div>
                </div>

                <div class="flex justify-center mt-8 lg:mt-10">
                    <div class="flex items-center gap-4">
                        <button
                                type="button"
                                class="explore-machines__arrow explore-machines__arrow--prev flex items-center justify-center w-8 h-8 bg-slate-200 text-slate-600 border-none cursor-pointer transition-all duration-200 hover:bg-slate-900 hover:text-white disabled:opacity-40 disabled:cursor-not-allowed focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
                                aria-label="<?php esc_attr_e('Previous products', 'standard'); ?>"
                                data-panel="<?php echo esc_attr($slug); ?>"
                        ><?php icon('arrow--left', ['class' => 'w-4 h-4']); ?></button>
                        <span class="text-sm text-slate-600 min-w-16 text-center">
                        <span class="explore-machines__current">1</span>
                        <?php esc_html_e('of', 'standard'); ?>
                        <span class="explore-machines__total"><?php echo count($products); ?></span>
                    </span>
                        <button
                                type="button"
                                class="explore-machines__arrow explore-machines__arrow--next flex items-center justify-center w-8 h-8 bg-slate-200 text-slate-600 border-none cursor-pointer transition-all duration-200 hover:bg-slate-900 hover:text-white disabled:opacity-40 disabled:cursor-not-allowed focus-visible:outline-2 focus-visible:outline-primary focus-visible:outline-offset-2"
                                aria-label="<?php esc_attr_e('Next products', 'standard'); ?>"
                                data-panel="<?php echo esc_attr($slug); ?>"
                        ><?php icon('arrow--right', ['class' => 'w-4 h-4']); ?></button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="container">
        <div class="flex justify-center gap-4 flex-wrap mt-8 lg:mt-10">
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
