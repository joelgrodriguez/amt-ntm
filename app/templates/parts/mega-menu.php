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
$panels = array_values(array_filter($nav['items'], fn($i) => ($i['kind'] ?? '') === 'mega'));
?>

<!-- Mega menu overlay -->
<div id="mega-menu-overlay" class="fixed inset-0 bg-black/40 z-40 hidden" aria-hidden="true"></div>

<!-- Mega menu panels -->
<div id="mega-menu-container" class="hidden lg:block">
<?php foreach ($panels as $panel) :
    $panel_id   = $panel['id'];
    $panel_type = $panel['type'];
?>

    <div
        id="mega-panel-<?php echo esc_attr($panel_id); ?>"
        class="mega-panel"
        role="region"
        aria-label="<?php echo esc_attr($panel['label']); ?>"
        aria-hidden="true"
    >
        <div class="mega-panel__inner">

        <?php if ($panel_type === 'tabbed-products') : ?>

            <?php $tabs = $panel['tabs']; ?>

            <!-- Tab rail -->
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

            <!-- Tab panels -->
            <div class="mega-panel__content">
                <?php foreach ($tabs as $i => $tab) :
                    $products = get_products_by_category($tab['category']);
                ?>
                    <div
                        id="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        role="tabpanel"
                        aria-labelledby="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        class="mega-tab-panel"
                        <?php echo $i !== 0 ? 'hidden' : ''; ?>
                    >
                        <div class="mega-product-grid">
                            <?php foreach ($products as $product) : ?>
                                <?php get_template_part('templates/parts/card-product', null, ['product' => $product]); ?>
                            <?php endforeach; ?>
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
                        <?php get_template_part('templates/parts/card-product', null, ['product' => $product]); ?>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($panel['view_all_url'])) : ?>
                    <a href="<?php echo esc_url($panel['view_all_url']); ?>" class="mega-panel__view-all">
                        <?php echo esc_html($panel['view_all_label'] ?? __('View all', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
            </div>

        <?php elseif ($panel_type === 'learning-center') :
            $sections = get_content_sections();
            $recent   = get_latest_query(3);
        ?>

            <!-- Left rail: content type links -->
            <div class="mega-panel__sidebar">
                <p class="mega-sidebar__label"><?php esc_html_e('Learning Center', 'standard'); ?></p>
                <ul class="mega-lc__type-list">
                    <?php foreach ($sections as $section) : ?>
                        <li>
                            <a href="<?php echo esc_url($section['link'] ?: \Standard\Url\internal('/learning-center/')); ?>" class="mega-lc__type-link">
                                <?php icon($section['icon'], ['class' => 'w-4 h-4 flex-shrink-0 text-blue-400']); ?>
                                <span><?php echo esc_html($section['title']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo esc_url(\Standard\Url\internal('/learning-center/')); ?>" class="mega-panel__view-all mt-auto px-5">
                    <?php esc_html_e('Visit Learning Center', 'standard'); ?>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            </div>

            <!-- Right: 3 recent posts -->
            <div class="mega-panel__content">
                <?php if ($recent->have_posts()) : ?>
                    <div class="mega-lc__posts">
                        <?php while ($recent->have_posts()) : $recent->the_post(); ?>
                            <?php get_template_part('templates/parts/mega-menu-card-post', null, ['post' => get_post()]); ?>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php elseif ($panel_type === 'tabbed-profiles') :
            $tabs = $panel['tabs'];
        ?>

            <!-- Tab rail -->
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

            <!-- Tab panels (profiles by category) -->
            <div class="mega-panel__content">
                <?php foreach ($tabs as $i => $tab) :
                    // ASSUMPTION: CPT slug is `profile`, taxonomy is `profile_category` — verify in WP admin if profiles don't appear
                    $profiles = new \WP_Query([
                        'post_type'      => 'profile',
                        'posts_per_page' => 8,
                        'post_status'    => 'publish',
                        'orderby'        => 'menu_order',
                        'order'          => 'ASC',
                        'tax_query'      => [[
                            'taxonomy' => 'profile_category',
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
                        <?php if ($profiles->have_posts()) : ?>
                            <div class="mega-profile-grid">
                                <?php while ($profiles->have_posts()) : $profiles->the_post(); ?>
                                    <?php get_template_part('templates/parts/mega-menu-card-profile', null, ['profile' => get_post()]); ?>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

        <?php endif; ?>

        </div><!-- /.mega-panel__inner -->
    </div><!-- /.mega-panel -->

<?php endforeach; ?>
</div><!-- /#mega-menu-container -->
