<?php
/**
 * Mobile Menu L2 Panel Template Part
 *
 * Renders one slide-in panel for the 4-action IA: a section sub-header,
 * the intro block, and the section's three groups stacked.
 *
 * Args (passed via get_template_part(..., null, [...])):
 *   - slug   (string)               panel slug, used in data-panel and aria-* hooks
 *   - label  (string)               panel title shown in the sub-header
 *   - intro  (array<string, mixed>) title, body, secondary_label, secondary_url
 *   - groups (array<int, array>)    each: label, items[] where items have label, url, optional anchor
 *
 * @package Standard
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$slug   = $args['slug']   ?? '';
$label  = $args['label']  ?? '';
$intro  = $args['intro']  ?? [];
$groups = $args['groups'] ?? [];

if ($slug === '' || $groups === []) {
    return;
}
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
        <?php if (!empty($intro['body'])) : ?>
            <p class="px-5 py-4 font-sans text-base leading-relaxed text-blue-600 border-b border-blue-100">
                <?php echo esc_html($intro['body']); ?>
            </p>
        <?php endif; ?>

        <?php foreach ($groups as $group) : ?>
            <div class="px-5 pt-6 pb-2">
                <?php if (!empty($group['label'])) : ?>
                    <p class="mb-3 font-mono text-caption font-medium uppercase tracking-widest text-blue-400">
                        <?php echo esc_html($group['label']); ?>
                    </p>
                <?php endif; ?>
                <ul class="list-none m-0 p-0 divide-y divide-blue-100 border-y border-blue-100">
                    <?php foreach (($group['items'] ?? []) as $item) :
                        $is_anchor = !empty($item['anchor']);
                        $size      = $is_anchor ? ' text-lg font-semibold text-blue-900' : ' text-body text-blue-700';
                    ?>
                        <li>
                            <a class="flex items-center justify-between min-h-12 py-3 font-sans no-underline transition-colors duration-150 ease-linear hover:bg-blue-50<?php echo esc_attr($size); ?>" href="<?php echo esc_url($item['url'] ?? '#'); ?>">
                                <span class="flex items-center gap-2">
                                    <?php echo esc_html($item['label'] ?? ''); ?>
                                    <?php if (!empty($item['badge'])) : ?>
                                        <span class="badge badge-emphasis"><?php echo esc_html($item['badge']); ?></span>
                                    <?php endif; ?>
                                </span>
                                <?php icon('arrow-right', ['class' => 'w-4 h-4 flex-none text-blue-400']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>

        <?php if (!empty($intro['secondary_url'])) : ?>
            <a class="mobile-menu__view-all" href="<?php echo esc_url($intro['secondary_url']); ?>">
                <?php echo esc_html($intro['secondary_label'] ?? __('Learn more', 'standard')); ?>
                <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
            </a>
        <?php endif; ?>
        <?php foreach (($intro['secondary_links'] ?? []) as $link) : ?>
            <?php if (!empty($link['url'])) : ?>
                <a class="mobile-menu__view-all" href="<?php echo esc_url($link['url']); ?>">
                    <span class="inline-flex items-center gap-2">
                        <?php echo esc_html($link['label'] ?? __('Learn more', 'standard')); ?>
                        <?php if (!empty($link['badge'])) : ?>
                            <span class="badge badge-emphasis"><?php echo esc_html($link['badge']); ?></span>
                        <?php endif; ?>
                    </span>
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
