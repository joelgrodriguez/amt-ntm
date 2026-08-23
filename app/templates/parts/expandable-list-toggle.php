<?php
/**
 * Shared bottom-anchored control for expandable card lists.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$region_id      = sanitize_html_class((string) ($args['region_id'] ?? ''));
$show_label     = (string) ($args['show_label'] ?? __('See all', 'standard'));
$collapse_label = (string) ($args['collapse_label'] ?? __('Collapse', 'standard'));

if ($region_id === '') {
    return;
}
?>

<div data-expandable-list-controls class="hidden mt-8 flex justify-center">
    <button type="button"
            data-expandable-list-button
            data-show-label="<?php echo esc_attr($show_label); ?>"
            data-collapse-label="<?php echo esc_attr($collapse_label); ?>"
            class="btn btn-md btn-secondary group"
            aria-expanded="false"
            aria-controls="<?php echo esc_attr($region_id); ?>">
        <span data-expandable-list-label><?php echo esc_html($show_label); ?></span>
        <?php icon('chevron-down', [
            'class' => 'w-4 h-4 expandable-list__icon',
        ]); ?>
    </button>
</div>
