<?php
/**
 * Profile carousel with an in-place expanded grid.
 *
 * Renders the compact carousel view, the hidden expanded grid view, and the
 * toggle control. The nearest ancestor with `data-profile-expand` owns the
 * show/collapse labels so header controls can participate in the same state.
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

$carousel_id = sanitize_html_class((string) ($args['carousel_id'] ?? 'profiles-carousel'));
$grid_id     = sanitize_html_class((string) ($args['grid_id'] ?? $carousel_id . '-grid'));

if ($carousel_id === '') {
    $carousel_id = 'profiles-carousel';
}

if ($grid_id === '') {
    $grid_id = $carousel_id . '-grid';
}

$profile_count = count($profiles);
$show_label    = (string) ($args['show_label'] ?? sprintf(
    /* translators: %d is the number of profiles available for a machine. */
    _n('See All %d Profile', 'See All %d Profiles', $profile_count, 'standard'),
    $profile_count
));
?>

<div class="grid gap-6">
    <ul id="<?php echo esc_attr($carousel_id); ?>"
        data-profile-expand-compact
        class="carousel__track list-none p-0 m-0">
        <?php foreach ($profiles as $profile) : ?>
            <li class="contents">
                <?php get_template_part('templates/parts/card-profile', null, [
                    'profile' => $profile,
                    'context' => 'carousel',
                ]); ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div data-profile-expand-controls class="hidden flex justify-center">
        <button type="button"
                data-profile-expand-button
                class="btn btn-md btn-secondary group"
                aria-expanded="false"
                aria-controls="<?php echo esc_attr($grid_id); ?>">
            <span data-profile-expand-label><?php echo esc_html($show_label); ?></span>
            <?php icon('chevron-down', ['class' => 'w-4 h-4 transition-transform duration-200']); ?>
        </button>
    </div>

    <div id="<?php echo esc_attr($grid_id); ?>"
         data-profile-expand-grid
         class="hidden"
         hidden>
        <ul class="grid gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 list-none p-0 m-0">
            <?php foreach ($profiles as $profile) : ?>
                <li>
                    <?php get_template_part('templates/parts/card-profile', null, [
                        'profile' => $profile,
                        'context' => 'grid',
                    ]); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
