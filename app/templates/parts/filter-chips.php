<?php
/**
 * Active-filter chip strip.
 *
 * Rendered above a result grid when the user has filters on. Each chip
 * removes itself by linking to a URL with that filter dropped.
 *
 * Args
 * ----
 *  chips     : array<int, array{label: string, remove_url: string}>
 *  clear_url : string  optional "clear all" URL
 *  label     : string  visually-hidden label for the strip
 *
 * @package Standard
 *
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$chips     = isset($args['chips']) && is_array($args['chips']) ? $args['chips'] : [];
$clear_url = isset($args['clear_url']) ? (string) $args['clear_url'] : '';
$label     = isset($args['label']) ? (string) $args['label'] : __('Active filters', 'standard');

if ($chips === []) {
    return;
}
?>

<div class="grid gap-3" role="group" aria-label="<?php echo esc_attr($label); ?>">
    <ul class="filter-chips">
        <?php foreach ($chips as $chip) :
            $chip_label = (string) ($chip['label'] ?? '');
            $remove_url = (string) ($chip['remove_url'] ?? '');

            if ($chip_label === '' || $remove_url === '') {
                continue;
            }
        ?>
            <li>
                <a class="filter-chip" href="<?php echo esc_url($remove_url); ?>">
                    <span><?php echo esc_html($chip_label); ?></span>
                    <span class="filter-chip-x" aria-hidden="true">
                        <?php icon('x', ['class' => 'w-3 h-3']); ?>
                    </span>
                    <span class="sr-only"><?php esc_html_e('Remove filter', 'standard'); ?></span>
                </a>
            </li>
        <?php endforeach; ?>

        <?php if ($clear_url !== '') : ?>
            <li>
                <a class="font-mono uppercase tracking-wider text-blue-500 hover:text-blue-700 no-underline px-2 py-2" style="font-size: var(--text-caption);" href="<?php echo esc_url($clear_url); ?>">
                    <?php esc_html_e('Clear all', 'standard'); ?>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>
