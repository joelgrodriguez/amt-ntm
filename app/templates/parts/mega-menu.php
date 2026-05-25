<?php
/**
 * Desktop Mega Menu Panels
 *
 * Renders all mega menu panels and the overlay.
 * Hidden by default via CSS (opacity: 0, translateY(-100%)).
 * JS adds `.is-open` to reveal and `.is-closing` to animate out.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Nav\get_desktop_nav;
use function Standard\Woo\Catalog\get_products_by_category;
use function Standard\LearningCenter\get_latest_query;
use function Standard\LearningCenter\get_content_sections;

$nav    = get_desktop_nav();
// Temporary: only the Machines mega panel renders right now. Keep the
// data intact in get_desktop_nav() so other panels are one filter
// change away from coming back.
$panels = array_values(array_filter(
    $nav['items'],
    fn($i) => ($i['kind'] ?? '') === 'mega' && ($i['id'] ?? '') === 'machines'
));
?>
<div id="mega-menu-overlay" class="fixed inset-0 bg-black/40 z-40 hidden" aria-hidden="true"></div>
<div id="mega-menu-container" class="hidden lg:block">
<?php foreach ($panels as $panel) :
    $panel_id   = $panel['id'];
    $panel_type = $panel['type'];
?>

    <div
        id="mega-panel-<?php echo esc_attr($panel_id); ?>"
        class="mega-panel t-panel-slide"
        role="region"
        aria-label="<?php echo esc_attr($panel['label']); ?>"
        aria-hidden="true"
    >
        <div class="mega-panel__inner">

        <?php if ($panel_type === 'tabbed-products') : ?>

            <?php $tabs = $panel['tabs']; ?>
            <div class="mega-panel__sidebar">
                <p class="mega-sidebar__label"><?php echo esc_html($panel['label']); ?></p>
                <ul class="mega-tab-list" role="tablist" aria-label="<?php echo esc_attr($panel['label']); ?>">
                    <?php foreach ($tabs as $i => $tab) : ?>
                        <li role="none">
                            <button
                                type="button"
                                role="tab"
                                class="mega-tab"
                                data-tab="<?php echo esc_attr($tab['id']); ?>"
                                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                aria-controls="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                                id="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                            >
                                <?php echo esc_html($tab['label']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($panel['view_all_url'])) : ?>
                    <a href="<?php echo esc_url($panel['view_all_url']); ?>" class="mega-panel__view-all mt-auto px-5">
                        <?php echo esc_html($panel['view_all_label'] ?? __('View all', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="mega-panel__content">
                <?php foreach ($tabs as $i => $tab) :
                    $is_accessories = ($tab['category'] ?? '') === 'accessories-add-on-equipment';
                    $products       = get_products_by_category($tab['category']);
                ?>
                    <div
                        id="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        role="tabpanel"
                        aria-labelledby="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        class="mega-tab-panel"
                        <?php echo $i !== 0 ? 'hidden' : ''; ?>
                    >
                        <?php if (!empty($tab['heading']) || !empty($tab['view_all_url'])) : ?>
                            <div class="mega-tab-header">
                                <?php if (!empty($tab['heading'])) : ?>
                                    <h3 class="mega-tab-header__title"><?php echo esc_html($tab['heading']); ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($tab['view_all_url'])) : ?>
                                    <a href="<?php echo esc_url($tab['view_all_url']); ?>" class="mega-tab-header__link">
                                        <?php echo esc_html($tab['view_all_label'] ?? __('View all', 'standard')); ?>
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mega-product-grid">
                            <?php foreach ($products as $product) :
                                if ($is_accessories) :
                                    $card = [
                                        'url'      => $product['explore_url'] ?? '',
                                        'image_id' => isset($product['id']) ? (int) get_post_thumbnail_id((int) $product['id']) : 0,
                                        'title'    => $product['title'] ?? '',
                                        'subtitle' => $product['subtitle'] ?? null,
                                    ];
                                    get_template_part('templates/parts/card-accessory', null, ['card' => $card]);
                                else :
                                    get_template_part('templates/parts/card-product', null, [
                                        'product'          => $product,
                                        'show_description' => false,
                                    ]);
                                endif;
                            endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($panel_type === 'product-grid') :
            $products = get_products_by_category($panel['category']);
        ?>

            <div class="mega-panel__content mega-panel__content--full">
                <div class="mega-product-grid">
                    <?php foreach ($products as $product) : ?>
                        <?php get_template_part('templates/parts/card-product', null, [
                            'product'          => $product,
                            'show_description' => false,
                        ]); ?>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($panel['view_all_url'])) : ?>
                    <a href="<?php echo esc_url($panel['view_all_url']); ?>" class="mega-panel__view-all">
                        <?php echo esc_html($panel['view_all_label'] ?? __('View all', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
            </div>

        <?php elseif ($panel_type === 'tabbed-content') :
            $tabs = $panel['tabs'];
        ?>
            <div class="mega-panel__sidebar">
                <p class="mega-sidebar__label"><?php echo esc_html($panel['label']); ?></p>
                <ul class="mega-tab-list" role="tablist" aria-label="<?php echo esc_attr($panel['label']); ?>">
                    <?php foreach ($tabs as $i => $tab) : ?>
                        <li role="none">
                            <button
                                type="button"
                                role="tab"
                                class="mega-tab"
                                data-tab="<?php echo esc_attr($tab['id']); ?>"
                                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                aria-controls="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                                id="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                            >
                                <?php echo esc_html($tab['label']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($panel['view_all_url'])) : ?>
                    <a href="<?php echo esc_url($panel['view_all_url']); ?>" class="mega-panel__view-all mt-auto px-5">
                        <?php echo esc_html($panel['view_all_label'] ?? __('View all', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="mega-panel__content">
                <?php foreach ($tabs as $i => $tab) :
                    $content_query = new \WP_Query([
                        'post_type'      => $tab['post_type'],
                        'posts_per_page' => 6,
                        'post_status'    => 'publish',
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                        'no_found_rows'  => true,
                    ]);
                ?>
                    <div
                        id="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        role="tabpanel"
                        aria-labelledby="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        class="mega-tab-panel"
                        <?php echo $i !== 0 ? 'hidden' : ''; ?>
                    >
                        <?php if (!empty($tab['heading']) || !empty($tab['view_all_url'])) : ?>
                            <div class="mega-tab-header">
                                <?php if (!empty($tab['heading'])) : ?>
                                    <h3 class="mega-tab-header__title"><?php echo esc_html($tab['heading']); ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($tab['view_all_url'])) : ?>
                                    <a href="<?php echo esc_url($tab['view_all_url']); ?>" class="mega-tab-header__link">
                                        <?php echo esc_html($tab['view_all_label'] ?? __('View all', 'standard')); ?>
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($content_query->have_posts()) : ?>
                            <ul class="mega-learning-grid">
                                <?php while ($content_query->have_posts()) : $content_query->the_post(); ?>
                                    <li>
                                        <?php get_template_part('templates/parts/card-post'); ?>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-sm text-blue-400"><?php esc_html_e('No posts found.', 'standard'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php elseif ($panel_type === 'tabbed-profiles') :
            $tabs = $panel['tabs'];
        ?>
            <div class="mega-panel__sidebar">
                <p class="mega-sidebar__label"><?php echo esc_html($panel['label']); ?></p>
                <ul class="mega-tab-list" role="tablist" aria-label="<?php echo esc_attr($panel['label']); ?>">
                    <?php foreach ($tabs as $i => $tab) : ?>
                        <li role="none">
                            <button
                                type="button"
                                role="tab"
                                class="mega-tab"
                                data-tab="<?php echo esc_attr($tab['id']); ?>"
                                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                aria-controls="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                                id="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                            >
                                <?php echo esc_html($tab['label']); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($panel['view_all_url'])) : ?>
                    <a href="<?php echo esc_url($panel['view_all_url']); ?>" class="mega-panel__view-all mt-auto px-5">
                        <?php echo esc_html($panel['view_all_label'] ?? __('View all', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
            </div>
            <div class="mega-panel__content">
                <?php foreach ($tabs as $i => $tab) :
                    $profiles = new \WP_Query([
                        'post_type'      => 'profile',
                        'posts_per_page' => 8,
                        'post_status'    => 'publish',
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                        'tax_query'      => [[
                            'taxonomy' => 'category',
                            'field'    => 'slug',
                            'terms'    => $tab['category'],
                        ]],
                        'no_found_rows'  => true,
                    ]);
                ?>
                    <div
                        id="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        role="tabpanel"
                        aria-labelledby="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        class="mega-tab-panel"
                        <?php echo $i !== 0 ? 'hidden' : ''; ?>
                    >
                        <?php if (!empty($tab['heading']) || !empty($tab['view_all_url'])) : ?>
                            <div class="mega-tab-header">
                                <?php if (!empty($tab['heading'])) : ?>
                                    <h3 class="mega-tab-header__title"><?php echo esc_html($tab['heading']); ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($tab['view_all_url'])) : ?>
                                    <a href="<?php echo esc_url($tab['view_all_url']); ?>" class="mega-tab-header__link">
                                        <?php echo esc_html($tab['view_all_label'] ?? __('View all', 'standard')); ?>
                                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($profiles->have_posts()) : ?>
                            <ul class="mega-profile-grid t-avatar-group">
                                <?php while ($profiles->have_posts()) : $profiles->the_post(); ?>
                                    <li class="t-avatar">
                                        <?php get_template_part('templates/parts/card-profile', null, [
                                            'profile' => get_post(),
                                            'context' => 'mega',
                                        ]); ?>
                                    </li>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-sm text-blue-400"><?php esc_html_e('No profiles found.', 'standard'); ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        </div>
    </div>

<?php endforeach; ?>
</div>
