<?php
/**
 * Mobile Menu
 *
 * Slide-in two-level navigation. Renders the L1 (root) panel inline and
 * loops over panel-type items to include their L2 panels via the
 * mobile-menu-panel template part.
 *
 * Content lives in app/inc/mobile-nav.php.
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$mobile_nav = \Standard\Nav\get_mobile_nav_tree();
?>

<nav id="mobile-menu" class="mobile-menu lg:hidden" aria-hidden="true" aria-label="<?php esc_attr_e('Mobile navigation', 'standard'); ?>">
    <div class="mobile-menu__viewport">
        <div class="mobile-menu__track" data-active-panel="root">

            <!-- L1 (root) panel -->
            <section class="mobile-menu__panel" data-panel="root" aria-hidden="false">
                <?php if (!empty($mobile_nav['featured'])) : $featured = $mobile_nav['featured']; ?>
                    <a class="mobile-menu__featured" href="<?php echo esc_url($featured['url']); ?>">
                        <span class="mobile-menu__featured-image" aria-hidden="true">
                            <img src="<?php echo esc_url($featured['image']); ?>" alt="" loading="lazy" />
                        </span>
                        <span class="mobile-menu__featured-text">
                            <span class="mobile-menu__featured-label"><?php echo esc_html($featured['label']); ?></span>
                            <span class="mobile-menu__featured-subtitle">
                                <?php echo esc_html($featured['subtitle']); ?>
                                <?php icon('arrow-right', ['class' => 'w-3.5 h-3.5']); ?>
                            </span>
                        </span>
                    </a>
                <?php endif; ?>

                <ul class="mobile-menu__list mobile-menu__list--top">
                    <?php foreach ($mobile_nav['top'] as $item) : ?>
                        <li class="mobile-menu__item">
                            <?php if ($item['type'] === 'panel') : ?>
                                <button type="button" class="mobile-menu__row mobile-menu__row--panel" data-panel-target="<?php echo esc_attr($item['slug']); ?>">
                                    <span class="mobile-menu__row-label"><?php echo esc_html($item['label']); ?></span>
                                    <?php icon('chevron-right', ['class' => 'w-4 h-4 mobile-menu__row-chevron']); ?>
                                </button>
                            <?php else : ?>
                                <a class="mobile-menu__row mobile-menu__row--link" href="<?php echo esc_url($item['url']); ?>">
                                    <span class="mobile-menu__row-label"><?php echo esc_html($item['label']); ?></span>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <?php if (!empty($mobile_nav['contact'])) : $contact = $mobile_nav['contact']; ?>
                    <a class="mobile-menu__contact" href="<?php echo esc_url($contact['url']); ?>">
                        <?php if (!empty($contact['icon'])) : ?>
                            <span class="mobile-menu__contact-icon" aria-hidden="true">
                                <?php icon($contact['icon'], ['class' => 'w-5 h-5']); ?>
                            </span>
                        <?php endif; ?>
                        <span class="mobile-menu__contact-label"><?php echo esc_html($contact['label']); ?></span>
                        <span class="mobile-menu__contact-arrow" aria-hidden="true">
                            <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                        </span>
                    </a>
                <?php endif; ?>

                <ul class="mobile-menu__list mobile-menu__list--bottom">
                    <?php foreach ($mobile_nav['bottom'] as $item) : ?>
                        <li class="mobile-menu__item">
                            <a class="mobile-menu__row mobile-menu__row--link mobile-menu__row--secondary" href="<?php echo esc_url($item['url']); ?>">
                                <?php if (!empty($item['icon'])) : ?>
                                    <span class="mobile-menu__row-icon" aria-hidden="true">
                                        <?php icon($item['icon'], ['class' => 'w-4 h-4']); ?>
                                    </span>
                                <?php endif; ?>
                                <span class="mobile-menu__row-label"><?php echo esc_html($item['label']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>

            <!-- L2 panels (one per panel-type top item) -->
            <?php foreach ($mobile_nav['top'] as $item) : ?>
                <?php if ($item['type'] === 'panel') : ?>
                    <?php get_template_part('templates/parts/mobile-menu-panel', null, [
                        'slug'         => $item['slug'],
                        'label'        => $item['label'],
                        'category'     => $item['category'],
                        'view_all_url' => $item['view_all_url'],
                    ]); ?>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    </div>
</nav>
