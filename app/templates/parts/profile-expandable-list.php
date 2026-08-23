<?php
/**
 * Profile carousel with an in-place expanded grid.
 *
 * Renders the compact carousel view, the expanded grid view, and the shared
 * bottom-anchored toggle control.
 *
 * Args:
 *   profiles    (array):  Profile posts or IDs.
 *   carousel_id (string): ID for the compact carousel track.
 *   grid_id     (string): ID for the expanded grid region.
 *   show_label  (string): Initial button label.
 *
 * @package Standard
 * @var array $args
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

$profiles = $args['profiles'] ?? [];

if (!is_array($profiles) || empty($profiles)) {
    return;
}

$profiles = array_values(array_filter($profiles, static function ($profile): bool {
    if ($profile instanceof \WP_Post) {
        return true;
    }

    return is_numeric($profile) && (int) $profile > 0;
}));

if (empty($profiles)) {
    return;
}

$grid_id = sanitize_html_class((string) ($args['grid_id'] ?? 'profiles-grid'));

if ($grid_id === '') {
    $grid_id = 'profiles-grid';
}

$profile_count = count($profiles);
$show_label    = (string) ($args['show_label'] ?? sprintf(
    /* translators: %d is the number of profiles available for a machine. */
    _n('See All %d Profile', 'See All %d Profiles', $profile_count, 'standard'),
    $profile_count
));
?>

<div class="grid gap-6">
    <ul id="<?php echo esc_attr($grid_id); ?>"
        data-expandable-list-content
        class="t-resize grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 list-none p-0 m-0">
        <?php foreach ($profiles as $index => $profile) : ?>
            <li<?php if ($index >= 4) : ?> data-expandable-list-expanded data-expandable-list-no-js-visible<?php endif; ?>>
                <?php get_template_part('templates/parts/card-profile', null, [
                    'profile' => $profile,
                    'context' => 'grid',
                ]); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php if ($profile_count > 4) : ?>
        <?php get_template_part('templates/parts/expandable-list-toggle', null, [
            'region_id'      => $grid_id,
            'show_label'     => $show_label,
            'collapse_label' => __('Collapse Profiles', 'standard'),
        ]); ?>
    <?php endif; ?>
</div>
