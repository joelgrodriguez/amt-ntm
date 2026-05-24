<?php
/**
 * Filter sidebar.
 *
 * One sidebar to rule them all: search, taxonomy archives, profile and
 * manual catalog landings. Driven by a normalized `groups` schema (see
 * inc/filters.php) so callers stay terse.
 *
 * Args
 * ----
 *  groups       : array<int, array{
 *                   id: string, title: string, icon: string,
 *                   mode: 'checkbox'|'radio'|'link',
 *                   name: ?string,
 *                   options: array<int, array{
 *                     value: string, label: string,
 *                     count: ?int, active: bool, url?: string,
 *                   }>,
 *                 }>
 *  form_id      : string  HTML id of the form the inputs belong to (checkbox/radio modes)
 *  apply_label  : string  label for the Apply button
 *  reset_url    : string  URL the Clear button points to (empty = hide)
 *  reset_label  : string
 *  drawer_label : string  mobile summary text, e.g. "Filters (3)"
 *  show_actions : bool    render the Apply/Clear footer (false for link-only sidebars)
 *  back_url     : string  optional "all profiles" / "all manuals" link
 *  back_label   : string
 *  aria_label   : string  aside / drawer aria-label
 *
 * @package Standard
 *
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$groups       = isset($args['groups']) && is_array($args['groups']) ? $args['groups'] : [];
$form_id      = isset($args['form_id']) ? (string) $args['form_id'] : '';
$apply_label  = isset($args['apply_label']) ? (string) $args['apply_label'] : __('Apply filters', 'standard');
$reset_url    = isset($args['reset_url']) ? (string) $args['reset_url'] : '';
$reset_label  = isset($args['reset_label']) ? (string) $args['reset_label'] : __('Clear filters', 'standard');
$drawer_label = isset($args['drawer_label']) ? (string) $args['drawer_label'] : __('Filters', 'standard');
$show_actions = array_key_exists('show_actions', $args) ? (bool) $args['show_actions'] : true;
$collapsible  = array_key_exists('collapsible', $args) ? (bool) $args['collapsible'] : true;
$back_url     = isset($args['back_url']) ? (string) $args['back_url'] : '';
$back_label   = isset($args['back_label']) ? (string) $args['back_label'] : '';
$aria_label   = isset($args['aria_label']) ? (string) $args['aria_label'] : __('Filters', 'standard');

if ($groups === []) {
    return;
}

$render_group = static function (array $group, int $index, string $scope, bool $collapsible) use ($form_id): void {
    $title = (string) ($group['title'] ?? '');
    $icon  = (string) ($group['icon'] ?? '');
    $mode  = (string) ($group['mode'] ?? 'checkbox');
    $name  = $mode === 'link' ? null : (string) ($group['name'] ?? '');
    $options = is_array($group['options'] ?? null) ? $group['options'] : [];

    if ($options === []) {
        return;
    }

    $selected_count = 0;
    foreach ($options as $option) {
        if (!empty($option['active'])) {
            $selected_count++;
        }
    }

    // Open by default if this is the first group, OR if this group has
    // an active selection (so users land on their own state, not always
    // on group #1).
    $is_open = $index === 0 || $selected_count > 0;
    $group_name = 'filter-groups-' . $scope;
    ?>
    <?php if ($collapsible) : ?>
    <details class="filter-group" name="<?php echo esc_attr($group_name); ?>" <?php echo $is_open ? 'open' : ''; ?>>
        <summary class="filter-group-summary">
            <span class="filter-group-label">
                <?php if ($icon !== '') : ?>
                    <?php icon($icon, ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                <?php endif; ?>
                <?php echo esc_html($title); ?>
            </span>
            <span class="filter-group-caret" aria-hidden="true">
                <?php icon('chevron-down', ['class' => 'w-3 h-3']); ?>
            </span>
        </summary>
    <?php else : ?>
    <section class="filter-group filter-group--static">
        <header class="filter-group-summary filter-group-summary--static">
            <span class="filter-group-label">
                <?php if ($icon !== '') : ?>
                    <?php icon($icon, ['class' => 'w-4 h-4', 'aria-hidden' => 'true']); ?>
                <?php endif; ?>
                <?php echo esc_html($title); ?>
            </span>
        </header>
    <?php endif; ?>

        <ul class="filter-options">
            <?php foreach ($options as $option) :
                $value  = (string) ($option['value'] ?? '');
                $label  = (string) ($option['label'] ?? '');
                $count  = array_key_exists('count', $option) && $option['count'] !== null
                    ? (int) $option['count']
                    : null;
                $active = !empty($option['active']);
                $url    = isset($option['url']) ? (string) $option['url'] : '';

                // Allow empty value in radio mode (acts as "All"); checkbox
                // mode still needs a real value because submit assembles an array.
                $empty_value_ok = $mode === 'radio';
                if ($label === '' || ($mode === 'link' && $url === '') || (!$empty_value_ok && $mode !== 'link' && $value === '')) {
                    continue;
                }
            ?>
                <li>
                    <?php if ($mode === 'link') : ?>
                        <a
                            class="filter-option"
                            data-active="<?php echo $active ? 'true' : 'false'; ?>"
                            <?php echo $active ? 'aria-current="true"' : ''; ?>
                            href="<?php echo esc_url($url); ?>"
                        >
                            <span class="filter-option-label"><?php echo esc_html($label); ?></span>
                            <?php if ($count !== null) : ?>
                                <span class="filter-option-count"><?php echo esc_html((string) $count); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php else : ?>
                        <label class="filter-option" data-active="<?php echo $active ? 'true' : 'false'; ?>">
                            <input
                                class="filter-option-control"
                                type="<?php echo $mode === 'radio' ? 'radio' : 'checkbox'; ?>"
                                name="<?php echo esc_attr((string) $name); ?>"
                                value="<?php echo esc_attr($value); ?>"
                                <?php if ($form_id !== '') : ?>form="<?php echo esc_attr($form_id); ?>"<?php endif; ?>
                                <?php checked($active); ?>
                            >
                            <span class="filter-option-label"><?php echo esc_html($label); ?></span>
                            <?php if ($count !== null) : ?>
                                <span class="filter-option-count"><?php echo esc_html((string) $count); ?></span>
                            <?php endif; ?>
                        </label>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php if ($collapsible) : ?>
    </details>
    <?php else : ?>
    </section>
    <?php endif; ?>
    <?php
};

$render_body = static function (string $scope) use ($groups, $render_group, $show_actions, $collapsible, $form_id, $apply_label, $reset_url, $reset_label, $back_url, $back_label): void {
    $rendered = 0;
    foreach ($groups as $group) {
        if (is_array($group)) {
            $render_group($group, $rendered, $scope, $collapsible);
            $rendered++;
        }
    }

    if ($show_actions) :
        $total_active = 0;
        foreach ($groups as $group) {
            if (!is_array($group)) {
                continue;
            }
            foreach (($group['options'] ?? []) as $option) {
                if (!empty($option['active']) && !empty($option['value'])) {
                    $total_active++;
                }
            }
        }
        ?>
        <div class="filter-sidebar-footer">
            <p class="filter-sidebar-footer-eyebrow">
                <span><?php esc_html_e('Refine results', 'standard'); ?></span>
                <?php if ($total_active > 0) : ?>
                    <span class="filter-sidebar-footer-count">
                        <?php
                        printf(
                            /* translators: %d active filter count. */
                            esc_html(_n('%d active', '%d active', $total_active, 'standard')),
                            (int) $total_active
                        );
                        ?>
                    </span>
                <?php endif; ?>
            </p>

            <button
                type="submit"
                <?php if ($form_id !== '') : ?>form="<?php echo esc_attr($form_id); ?>"<?php endif; ?>
                class="filter-apply"
            >
                <span class="filter-apply-label"><?php echo esc_html($apply_label); ?></span>
                <span class="filter-apply-icon" aria-hidden="true">
                    <?php icon('arrow-right', ['class' => 'w-4 h-4']); ?>
                </span>
            </button>

            <?php if ($reset_url !== '') : ?>
                <a href="<?php echo esc_url($reset_url); ?>" class="filter-clear">
                    <?php icon('x', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
                    <span><?php echo esc_html($reset_label); ?></span>
                </a>
            <?php endif; ?>
        </div>
    <?php endif;

    if ($back_url !== '' && $back_label !== '') : ?>
        <a href="<?php echo esc_url($back_url); ?>" class="inline-flex items-center gap-2 mx-5 font-mono uppercase tracking-wider text-blue-500 hover:text-blue-700 no-underline" style="font-size: var(--text-caption);">
            <?php icon('arrow-left', ['class' => 'w-3 h-3', 'aria-hidden' => 'true']); ?>
            <?php echo esc_html($back_label); ?>
        </a>
    <?php endif;
};
?>

<details class="filter-drawer">
    <summary>
        <span><?php echo esc_html($drawer_label); ?></span>
        <span class="filter-drawer-caret" aria-hidden="true">
            <?php icon('chevron-down', ['class' => 'w-4 h-4']); ?>
        </span>
    </summary>
    <div class="filter-drawer-body" aria-label="<?php echo esc_attr($aria_label); ?>">
        <?php $render_body('drawer'); ?>
    </div>
</details>

<aside class="hidden lg:block lg:border-r lg:border-blue-100" aria-label="<?php echo esc_attr($aria_label); ?>">
    <div class="filter-sidebar lg:sticky lg:top-24 lg:py-2">
        <?php $render_body('rail'); ?>
    </div>
</aside>
