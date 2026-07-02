<?php
/**
 * Desktop Mega Menu Panels
 *
 * Renders all mega menu panels and the overlay.
 * Hidden by default via CSS (opacity: 0, translateY(-100%)).
 * JS adds `.is-open` to reveal and `.is-closing` to animate out.
 *
 * Two panel shapes, selected per item via $panel['type']:
 *   - 'tabbed-machines': sidebar category tab-list | content grid that
 *     swaps per tab. Machine/accessory tabs render a live WooCommerce
 *     product grid; the Profiles tab renders three stacked sub-category
 *     sections (heading + cards + per-section View All). Tab switching is
 *     wired by MegaMenu.js (initMegaTabs).
 *   - anything else ('flyout-groups'): sidebar intro blurb | columns of
 *     text links, with an optional card row beneath.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

use function Standard\Nav\get_desktop_nav;
use function Standard\Woo\Catalog\get_products_by_category;

$nav    = get_desktop_nav();
$panels = array_values(array_filter(
    $nav['items'],
    fn($i) => ($i['kind'] ?? '') === 'mega'
));
?>
<div id="mega-menu-overlay" class="mega-overlay" aria-hidden="true"></div>
<div id="mega-menu-container" class="hidden lg:block">
<?php foreach ($panels as $panel) :
    $panel_id   = $panel['id'];
    $panel_type = $panel['type'] ?? 'flyout-groups';
?>

    <div
        id="mega-panel-<?php echo esc_attr($panel_id); ?>"
        class="mega-panel t-panel-slide"
        role="group"
        aria-label="<?php echo esc_attr($panel['label']); ?>"
        aria-hidden="true"
    >
        <div class="mega-panel__inner">

        <?php if ($panel_type === 'tabbed-machines') :
            $tabs = $panel['tabs'] ?? [];
        ?>

            <div class="mega-panel__sidebar">
                <?php if (!empty($panel['sidebar_label'])) : ?>
                    <p class="mega-sidebar__label"><?php echo esc_html($panel['sidebar_label']); ?></p>
                <?php endif; ?>
                <ul class="mega-tab-list" role="tablist" aria-label="<?php echo esc_attr($panel['label']); ?>">
                    <?php foreach ($tabs as $i => $tab) : ?>
                        <li role="none">
                            <button
                                type="button"
                                role="tab"
                                class="mega-tab"
                                data-tab="<?php echo esc_attr($tab['id']); ?>"
                                aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                                tabindex="<?php echo $i === 0 ? '0' : '-1'; ?>"
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

            <div class="mega-panel__content mega-tab-content" data-dir="forward">
                <?php foreach ($tabs as $i => $tab) :
                    $tab_kind = $tab['kind'] ?? 'products';
                ?>
                    <div
                        id="mega-tabpanel-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        role="tabpanel"
                        aria-labelledby="mega-tab-<?php echo esc_attr($panel_id); ?>-<?php echo esc_attr($tab['id']); ?>"
                        class="mega-tab-panel<?php echo $i === 0 ? ' is-active' : ''; ?>"
                        <?php echo $i !== 0 ? 'aria-hidden="true"' : ''; ?>
                    >

                    <?php if ($tab_kind === 'profile-groups') :
                        $sections = $tab['sections'] ?? [];
                    ?>
                        <?php foreach ($sections as $section) :
                            $profiles = new \WP_Query([
                                'post_type'      => 'profile',
                                'posts_per_page' => 4,
                                'post_status'    => 'publish',
                                'orderby'        => 'title',
                                'order'          => 'ASC',
                                'tax_query'      => [[
                                    'taxonomy' => 'category',
                                    'field'    => 'slug',
                                    'terms'    => $section['category'],
                                ]],
                                'no_found_rows'  => true,
                            ]);
                        ?>
                            <div class="mega-profile-section">
                                <div class="mega-tab-header">
                                    <h3 class="mega-tab-header__title"><?php echo esc_html($section['heading']); ?></h3>
                                    <?php if (!empty($section['view_all_url'])) : ?>
                                        <a href="<?php echo esc_url($section['view_all_url']); ?>" class="mega-tab-header__link">
                                            <?php echo esc_html($section['view_all_label'] ?? __('View all', 'standard')); ?>
                                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>

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

                    <?php else :
                        // products + accessories: live WooCommerce grid.
                        $is_accessories = $tab_kind === 'accessories';
                        $products       = get_products_by_category($tab['category']);
                        if ($is_accessories) {
                            $products = array_slice($products, 0, 6);
                        }
                    ?>
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

                        <?php if (!empty($products)) : ?>
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
                        <?php else : ?>
                            <p class="text-sm text-blue-400"><?php esc_html_e('No products found.', 'standard'); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>

                    </div>
                <?php endforeach; ?>
            </div>

        <?php else :
            // ── Flat-link panels (flyout-groups): intro blurb | link columns ──
            $intro  = $panel['intro']  ?? [];
            $groups = $panel['groups'] ?? [];
        ?>

            <div class="mega-panel__sidebar">
                <?php if (!empty($intro['title'])) : ?>
                    <h2 class="px-5 mb-3 font-sans font-medium text-heading-sm text-blue-900"><?php echo esc_html($intro['title']); ?></h2>
                <?php endif; ?>
                <?php if (!empty($intro['body'])) : ?>
                    <p class="px-5 mb-6 font-sans text-sm leading-relaxed text-blue-600"><?php echo esc_html($intro['body']); ?></p>
                <?php endif; ?>
                <?php if (!empty($intro['secondary_url'])) : ?>
                    <a href="<?php echo esc_url($intro['secondary_url']); ?>" class="mega-panel__view-all mt-auto px-5">
                        <?php echo esc_html($intro['secondary_label'] ?? __('Learn more', 'standard')); ?>
                        <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                    </a>
                <?php endif; ?>
                <?php foreach (($intro['secondary_links'] ?? []) as $link) : ?>
                    <?php if (!empty($link['url'])) : ?>
                        <a href="<?php echo esc_url($link['url']); ?>" class="mega-panel__view-all px-5">
                            <?php echo esc_html($link['label'] ?? __('Learn more', 'standard')); ?>
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
            <div class="mega-panel__content">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <?php foreach ($groups as $group) : ?>
                        <div>
                            <?php if (!empty($group['label'])) : ?>
                                <p class="mb-3 pb-2 font-mono text-caption font-medium uppercase tracking-widest text-blue-400 border-b border-blue-100">
                                    <?php echo esc_html($group['label']); ?>
                                </p>
                            <?php endif; ?>
                            <ul class="mega-link-list">
                                <?php foreach (($group['items'] ?? []) as $item) : ?>
                                    <li>
                                        <a href="<?php echo esc_url($item['url'] ?? '#'); ?>" class="mega-link">
                                            <?php echo esc_html($item['label'] ?? ''); ?>
                                            <?php if (!empty($item['badge'])) : ?>
                                                <span class="badge badge-emphasis mega-link__badge"><?php echo esc_html($item['badge']); ?></span>
                                            <?php endif; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php $cards = $panel['cards'] ?? []; ?>
                <?php if (!empty($cards)) : ?>
                    <div class="mega-panel__cards mt-8 grid grid-cols-2 gap-3 md:grid-cols-4">
                        <?php foreach ($cards as $card) : ?>
                            <a href="<?php echo esc_url($card['url'] ?? '#'); ?>" class="mega-panel__card">
                                <span><?php echo esc_html($card['label'] ?? ''); ?></span>
                                <?php if (!empty($card['badge'])) : ?>
                                    <span class="badge badge-emphasis mega-panel__card-badge"><?php echo esc_html($card['badge']); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        </div>
    </div>

<?php endforeach; ?>
</div>
