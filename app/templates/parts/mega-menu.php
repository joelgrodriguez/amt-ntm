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

$nav    = get_desktop_nav();
$panels = array_values(array_filter(
    $nav['items'],
    fn($i) => ($i['kind'] ?? '') === 'mega'
));
?>
<div id="mega-menu-overlay" class="mega-overlay" aria-hidden="true"></div>
<div id="mega-menu-container" class="hidden lg:block">
<?php foreach ($panels as $panel) :
    $panel_id = $panel['id'];
    $intro    = $panel['intro']  ?? [];
    $groups   = $panel['groups'] ?? [];
?>

    <div
        id="mega-panel-<?php echo esc_attr($panel_id); ?>"
        class="mega-panel t-panel-slide"
        role="group"
        aria-label="<?php echo esc_attr($panel['label']); ?>"
        aria-hidden="true"
    >
        <div class="mega-panel__inner">
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
                    <div class="mega-menu__cards mt-8 grid grid-cols-2 gap-3 md:grid-cols-4">
                        <?php foreach ($cards as $card) : ?>
                            <a href="<?php echo esc_url($card['url'] ?? '#'); ?>" class="mega-menu__card">
                                <span class="mega-menu__card-label"><?php echo esc_html($card['label'] ?? ''); ?></span>
                                <?php if (!empty($card['badge'])) : ?>
                                    <span class="badge badge-emphasis mega-menu__card-badge"><?php echo esc_html($card['badge']); ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php endforeach; ?>
</div>
