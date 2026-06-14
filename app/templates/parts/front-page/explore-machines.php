<?php
/**
 * Explore Machines Section Template Part
 *
 * Tabbed product showcase section for the front page.
 * Displays products by category with horizontal scrolling.
 *
 * @package Standard
 *
 * @usage Front Page (front-page.php)
 * @styles css/explore-machines.css
 * @see js/modules/ExploreMachines.js - Tab switching and scroll navigation
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Woo\Catalog\get_product_categories;
use function Standard\Woo\Catalog\get_products_by_category;

$content = [
    'title'      => __('Explore All Machines', 'standard'),
    'tabs_label' => __('Machine categories', 'standard'),
    'prev_label' => __('Previous products', 'standard'),
    'next_label' => __('Next products', 'standard'),
    'of'         => __('of', 'standard'),
];

$categories = get_product_categories();

if (empty($categories)) {
    return;
}

$first_category = array_key_first($categories);

// Per-category landing page URLs + button labels. Catalog slugs
// don't all match the public URL (accessories sits at
// /machines/upgrades/, not at the catalog slug), so the
// mapping lives here. Button labels stay short and parallel:
// 'View All {short}'.
$landing_urls = [
    'roof-wall-panel-machines'      => '/roof-wall-panel-machines/',
    'gutter-machines'               => '/seamless-gutter-machines/',
    'profiles'                      => '/profiles/',
    'accessories-add-on-equipment'  => '/machines/upgrades/',
];
$landing_labels = [
    'roof-wall-panel-machines'      => __('View All Panel Machines', 'standard'),
    'gutter-machines'               => __('View All Gutter Machines', 'standard'),
    'profiles'                      => __('View All Profiles', 'standard'),
    'accessories-add-on-equipment'  => __('View All Accessories', 'standard'),
];
?>

<section class="explore-machines section" aria-labelledby="explore-machines-title">
    <div class="container grid gap-8 lg:gap-10">
        <h2 id="explore-machines-title" class="section-title" data-reveal="fade">
            <?php echo esc_html($content['title']); ?>
        </h2>

        <div class="explore-machines__tabs flex flex-wrap border-b border-blue-300" role="tablist" aria-label="<?php echo esc_attr($content['tabs_label']); ?>" data-reveal="fade">
            <?php foreach ($categories as $slug => $category) : ?>
                <button
                    type="button"
                    id="tab-<?php echo esc_attr($slug); ?>"
                    class="explore-machines__tab <?php echo $slug === $first_category ? 'explore-machines__tab--active' : ''; ?>"
                    role="tab"
                    aria-selected="<?php echo $slug === $first_category ? 'true' : 'false'; ?>"
                    aria-controls="panel-<?php echo esc_attr($slug); ?>"
                    aria-label="<?php echo esc_attr($category['label']); ?>"
                    tabindex="<?php echo $slug === $first_category ? '0' : '-1'; ?>"
                    data-category="<?php echo esc_attr($slug); ?>"
                ><span class="md:hidden"><?php echo esc_html($category['short']); ?></span><span class="hidden md:inline"><?php echo esc_html($category['label']); ?></span></button>
            <?php endforeach; ?>
        </div>

        <div class="explore-machines__panels">
            <?php foreach ($categories as $slug => $category) : ?>
                <?php
                $is_profiles    = $slug === 'profiles';
                $profiles       = $is_profiles ? \Standard\Woo\Catalog\get_profiles_for_explore() : [];
                $products       = $is_profiles ? [] : get_products_by_category($slug);
                $landing_url    = $landing_urls[$slug] ?? '';
                $landing_label  = $landing_labels[$slug] ?? __('View All', 'standard');
                $product_count  = $is_profiles ? count($profiles) : count($products);
                ?>
                <div
                    id="panel-<?php echo esc_attr($slug); ?>"
                    class="explore-machines__panel grid gap-8 lg:gap-10 <?php echo $slug === $first_category ? 'explore-machines__panel--active' : ''; ?>"
                    role="tabpanel"
                    aria-labelledby="tab-<?php echo esc_attr($slug); ?>"
                >
                    <!-- Browse chrome (top-right): arrows + 'N of N' counter.
                         Stays per-panel so the JS (one panel, one set of
                         arrows) keeps working with the existing
                         data-panel selector. Visually sits flush right
                         under the tabs row. -->
                    <div class="flex items-center justify-end gap-4 -mt-2">
                        <button
                            type="button"
                            class="explore-machines__arrow explore-machines__arrow--prev"
                            aria-label="<?php esc_attr_e('Previous products', 'standard'); ?>"
                            data-panel="<?php echo esc_attr($slug); ?>"
                        ><?php icon('arrow-left', ['class' => 'w-4 h-4']); ?></button>
                        <span class="font-mono text-xs uppercase tracking-wider text-blue-600 min-w-16 text-center" aria-live="polite" aria-atomic="true">
                            <span class="explore-machines__current t-text-swap">1</span>
                            <?php esc_html_e('of', 'standard'); ?>
                            <span class="explore-machines__total"><?php echo (int) $product_count; ?></span>
                        </span>
                        <button
                            type="button"
                            class="explore-machines__arrow explore-machines__arrow--next"
                            aria-label="<?php esc_attr_e('Next products', 'standard'); ?>"
                            data-panel="<?php echo esc_attr($slug); ?>"
                        ><?php icon('arrow-right', ['class' => 'w-4 h-4']); ?></button>
                    </div>

                    <div class="explore-machines__track flex gap-4 overflow-x-auto md:gap-6">
                        <?php if ($is_profiles) : ?>
                            <?php foreach ($profiles as $profile) : ?>
                                <?php get_template_part('templates/parts/card-profile', null, ['profile' => $profile, 'context' => 'carousel']); ?>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <?php foreach ($products as $product) : ?>
                                <?php get_template_part('templates/parts/card-product', null, ['product' => $product]); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <!-- Row anchor (below cards): outline button to the
                         category landing. The panel's primary outbound
                         action; lives alone so the visual weight reads
                         as 'this is what you do next.' -->
                    <?php if ($landing_url) : ?>
                        <div class="flex justify-center pt-2">
                            <a
                                href="<?php echo esc_url(\Standard\Url\internal($landing_url)); ?>"
                                class="btn btn-outline-dark"
                            >
                                <?php echo esc_html($landing_label); ?>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
